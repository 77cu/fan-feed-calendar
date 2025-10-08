# Fan Feed Calendar — Continuity Map

## 1) Principles
- **One canonical home per concept.** Every entity has a single owning table. Anything derived is a view/materialised view, not another owner.
- **Tenant-first.** Every row belongs to exactly one `tenant_id` unless it’s truly global reference data.
- **Stable identifiers.** Use UUIDs for our IDs; use provider IDs (Google/Microsoft/API-Football) only as foreign keys or unique constraints.
- **Hash what we sync.** For mirrored external objects (fixtures, odds), store a deterministic `content_hash`.
- **Small, named boundaries.** Each domain (Identity, Catalogue, Ingest, Subscriptions, Sync, Output) owns its tables; cross-domain writes go through services, not direct SQL.

## 2) Domains & Table Ownership

### A) Identity (tenants, users, memberships, connections) — *Owner: Identity service*
- **tenants**: `id`, `name`, `slug`, `created_at`.  
  *What:* organisation/workspace. Solo fans = their own tenant.
- **users**: `id`, `email (unique)`, `password_hash`, `full_name`, `timezone`, `created_at`.
- **tenant_memberships**: `tenant_id → tenants`, `user_id → users`, `role (owner|admin|member)`. *Unique:* `(tenant_id, user_id)`.
- **connections**: `tenant_id`, `user_id`, `provider (google|microsoft|ics)`, OAuth tokens OR `ics_token`, `feed_name`, `provider_account_id`. *Unique:* `(tenant_id, user_id, provider)` and `ics_token`.

### B) Catalogue (reference data: leagues, seasons, teams) — *Owner: Catalogue*
- **leagues**: `provider_league_id (unique)`, `name`, `country`, `coverage_json`.
- **seasons**: `league_id → leagues`, `year`, `coverage_json`. *Unique:* `(league_id, year)`.
- **teams**: `provider_team_id (unique)`, `name`, `country`, `logo_url`.

### C) Ingest (mirrors from providers) — *Owner: Ingest*
- **fixtures**: `provider_fixture_id (unique)`, `league_id`, `season_id`, `home_team_id`, `away_team_id`, `start_time_utc`, `venue_name`, `round`, `status_short`, `raw_json`, `content_hash`, `last_seen_at`.
- **fixture_odds**: `fixture_id`, `market (1x2)`, `bookmaker`, `home_price`, `draw_price`, `away_price`, `last_updated_at`, `raw_json`. *Unique:* `(fixture_id, market, bookmaker)`.

### D) Subscriptions (user intent & selections) — *Owner: Subscriptions*
- **subscriptions**: `tenant_id`, `user_id`, `name`, `window_days`, `auto_add`, `reminder_minutes`, `include_venues`, `show_odds`, timestamps.
- **subscription_leagues**: `subscription_id`, `league_id`, `season_id`. *PK:* `(subscription_id, league_id, season_id)`.
- **subscription_teams**: `subscription_id`, `team_id`. *PK:* `(subscription_id, team_id)`.

### E) Sync (diff engine & provider mapping) — *Owner: Sync*
- **sync_runs**: audit per run: `tenant_id`, `user_id`, `started_at`, `finished_at`, `result_json`.
- **sync_queue**: pending actions: `tenant_id`, `user_id`, `connection_id`, `fixture_id`, `action (create|update|delete)`, `target_at`, `attempts`.
- **user_events**: maps a fixture to a provider event: `tenant_id`, `user_id`, `connection_id`, `fixture_id`, `provider_event_id`, `last_pushed_hash`, `last_synced_at`. *Unique:* `(connection_id, fixture_id)`.

### F) Output (feeds) — *Owner: Output*
- **ics_feeds**: `tenant_id`, `user_id`, `token (unique)`, `filter_json`, `last_generated_at`.

## 3) Relationships (at a glance)
- `tenants 1—* tenant_memberships *—1 users`
- `users 1—* connections`
- `leagues 1—* seasons`
- `leagues 1—* fixtures`, `seasons 1—* fixtures`
- `teams 1—* fixtures (home)`, `teams 1—* fixtures (away)`
- `subscriptions 1—* subscription_leagues`, `subscriptions 1—* subscription_teams`
- `fixtures 1—* user_events`
- `fixtures 1—* fixture_odds`

## 4) Data flow (write ownership)
- **Ingest** writes: `fixtures`, `fixture_odds`.
- **Subscriptions** writes: `subscriptions`, `subscription_*`.
- **Sync** reads subscriptions+fixtures → computes diff → writes `sync_queue`, `user_events` → calls providers (Google/Microsoft).
- **Output** reads filters+fixtures → renders ICS → updates `ics_feeds.last_generated_at`.

**Anti-dup rule:** If you need a new fixture field, add it to `fixtures.raw_json` and a projection column if actively used; don’t spawn parallel detail tables without an ADR.

## 5) Naming & constraints
- Tables/columns: `snake_case`.  
- All tenant-owned tables include `tenant_id` (and `user_id` if applicable).  
- PKs are UUIDs unless explicitly provider-keyed.  
- Store times as UTC `timestamptz`.  
- Enforce uniques at DB level (see above).

## 6) Migrations & change control
- One migration per PR; name with intent (e.g., `2025-10-08_add_user_events.sql`).  
- Each migration starts with a comment block: Domain owner / Why / Deprecates.  
- Keep `/docs/schema.sql` updated (DDL only).  
- For any schema change: update this map and add an ADR.

### Pre-merge checklist for schema changes
1) Which domain owns it?  
2) Could it live in an existing table? Why not?  
3) What is the unique key?  
4) Which service writes it?  
5) Rollback strategy?  
6) Updated: Continuity Map, schema.sql, ADR.

## 7) Initial implementation order
1) Identity: tenants, users, memberships, connections  
2) Catalogue: leagues, seasons, teams  
3) Ingest: fixtures (+ `content_hash`), fixture_odds (optional in Phase 1)  
4) Subscriptions: subscriptions + links  
5) Sync: user_events, sync_runs, sync_queue  
6) Output: ics_feeds
