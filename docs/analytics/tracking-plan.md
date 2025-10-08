# Fan Feed Calendar Analytics Tracking Plan

## Google Tag Manager (GTM) Container Approach
- Maintain a single web GTM container dedicated to Fan Feed Calendar web properties.
- Publish tagged versions per environment (`production`, `staging`, etc.) and use workspaces for feature development.
- Load GTM asynchronously on every page and initialize an empty `dataLayer` array before the GTM snippet.
- Use GTM to manage Google Analytics 4 (GA4), consent tooling, advertising pixels, and downstream integrations. Non-marketing tags should be documented and approved by the analytics team.
- Configure GTM to listen for `dataLayer` events documented below. Each event must be validated in preview mode before release.

## Data Layer Conventions
- All events are pushed as objects to the global `window.dataLayer`.
- Every event payload includes the common `context` block (see below), an ISO-8601 timestamp (`ts`), and a unique `request_id` (UUID or ULID generated per event).
- Keys are `snake_case`; enumerated values are lowercase.
- Do not mutate or reuse previous event objects; push a new object per event.

### Global `page_view` Event
- Fired on every virtual or hard page load after key context is available.
- Payload fields:
  - `event`: `"page_view"`
  - `ts`: ISO-8601 timestamp
  - `request_id`: UUID/ULID used for tracing
  - `context` (object):
    - `env`: environment identifier (`production`, `staging`, etc.)
    - `tenant_id`: UUID representing the customer tenant
    - `user_id_hash`: SHA-256 hash of the authenticated user's canonical identifier; omit when user is anonymous
    - `ui_theme`: `light`, `dark`, or `auto`
    - `sport`: canonical sport slug for the current view (e.g., `soccer`)
    - `competition_id`: identifier of the selected competition (string or numeric)
    - `team_ids`: array of selected team identifiers
    - `plan_tier`: customer's subscription plan (e.g., `free`, `pro`, `enterprise`)
    - `integration_provider`: `google`, `microsoft`, `ics`, or `none`
- Additional contextual fields specific to the page may be nested under `context.page`.

## Core UI Events
Each UI event pushes an object with the following structure unless otherwise noted:

```json
{
  "event": "<event_name>",
  "ts": "2024-05-01T12:00:00.000Z",
  "request_id": "uuid",
  "context": { ...global context... },
  "payload": { ...event-specific attributes... }
}
```

### Onboarding
- **`onboarding_started`**
  - Payload schema: `{ "entry_point": "modal|landing|invite" }`
  - Example: `payload.entry_point = "invite"`
- **`onboarding_completed`**
  - Payload schema: `{ "duration_ms": number, "steps_completed": number }`
  - Example: `payload = { "duration_ms": 45000, "steps_completed": 5 }`

### Competition & Team Selection
- **`competition_selected`**
  - Payload: `{ "competition_id": string, "sport": string }`
  - Example: `{ "competition_id": "premier-league", "sport": "soccer" }`
- **`team_selected`**
  - Payload: `{ "team_id": string, "competition_id": string }`
  - Example: `{ "team_id": "arsenal", "competition_id": "premier-league" }`
- **`team_deselected`**
  - Payload: `{ "team_id": string, "competition_id": string }`
  - Example: `{ "team_id": "tottenham", "competition_id": "premier-league" }`

### Calendar Integrations
- **`integration_connect_clicked`**
  - Payload: `{ "provider": "google|microsoft|ics", "surface": string }`
  - Example: `{ "provider": "google", "surface": "settings" }`
- **`integration_connected`**
  - Payload: `{ "provider": "google|microsoft|ics", "duration_ms": number }`
  - Example: `{ "provider": "google", "duration_ms": 3200 }`
- **`integration_disconnected`**
  - Payload: `{ "provider": "google|microsoft|ics" }`
  - Example: `{ "provider": "microsoft" }`

### ICS Interactions
- **`ics_link_copied`**
  - Payload: `{ "surface": string }`
  - Example: `{ "surface": "settings" }`
- **`ics_downloaded`**
  - Payload: `{ "surface": string }`
  - Example: `{ "surface": "email" }`

### Calendar Push
- **`calendar_push_requested`**
  - Payload: `{ "provider": "google|microsoft", "fixtures": number }`
  - Example: `{ "provider": "google", "fixtures": 48 }`
- **`calendar_push_succeeded`**
  - Payload: `{ "provider": "google|microsoft", "fixtures": number, "duration_ms": number }`
  - Example: `{ "provider": "google", "fixtures": 48, "duration_ms": 1200 }`
- **`calendar_push_failed`**
  - Payload: `{ "provider": "google|microsoft", "fixtures": number, "error_code": string }`
  - Example: `{ "provider": "google", "fixtures": 48, "error_code": "timeout" }`

### Fixture Inclusion
- **`fixture_included`**
  - Payload: `{ "fixture_id": string, "source": "manual|bulk" }`
  - Example: `{ "fixture_id": "fixture-123", "source": "manual" }`
- **`fixture_excluded`**
  - Payload: `{ "fixture_id": string, "source": "manual|bulk" }`
  - Example: `{ "fixture_id": "fixture-456", "source": "bulk" }`

### Odds
- **`odds_toggled`**
  - Payload: `{ "state": "on|off", "surface": string }`
  - Example: `{ "state": "on", "surface": "fixture-card" }`
- **`odds_viewed`**
  - Payload: `{ "surface": string, "odds_provider": string }`
  - Example: `{ "surface": "fixture-modal", "odds_provider": "bet365" }`

### Navigation, Filters, and Views
- **`view_changed`**
  - Payload: `{ "view": "month|week|list" }`
  - Example: `{ "view": "week" }`
- **`filter_changed`**
  - Payload: `{ "filter": string, "value": string|boolean|number }`
  - Example: `{ "filter": "sport", "value": "basketball" }`

### Subscription Funnel
- **`subscription_viewed`**
  - Payload: `{ "plan_tier": string, "surface": string }`
  - Example: `{ "plan_tier": "pro", "surface": "pricing" }`
- **`checkout_started`**
  - Payload: `{ "plan_tier": string, "payment_provider": string }`
  - Example: `{ "plan_tier": "pro", "payment_provider": "stripe" }`
- **`checkout_step`**
  - Payload: `{ "step": number, "name": string }`
  - Example: `{ "step": 2, "name": "payment" }`
- **`checkout_completed`**
  - Payload: `{ "plan_tier": string, "transaction_id": string, "value": number, "currency": string }`
  - Example: `{ "plan_tier": "pro", "transaction_id": "txn_123", "value": 99.0, "currency": "USD" }`
- **`subscription_activated`**
  - Payload: `{ "plan_tier": string, "activation_source": string }`
  - Example: `{ "plan_tier": "enterprise", "activation_source": "admin" }`
- **`subscription_cancelled`**
  - Payload: `{ "plan_tier": string, "cancellation_reason": string }`
  - Example: `{ "plan_tier": "pro", "cancellation_reason": "no-longer-needed" }`

### Consent Management
- **`consent_banner_shown`**
  - Payload: `{ "surface": string }`
  - Example: `{ "surface": "footer" }`
- **`consent_accepted`**
  - Payload: `{ "categories": string[] }`
  - Example: `{ "categories": ["analytics", "functional"] }`
- **`consent_rejected`**
  - Payload: `{ "categories": string[] }`
  - Example: `{ "categories": ["advertising"] }`

### Error Handling
- **`ui_error_shown`**
  - Payload: `{ "code": string, "message": string, "surface": string }`
  - Example: `{ "code": "integration-failed", "message": "Failed to connect Google Calendar", "surface": "integration-modal" }`

## GA4 E-commerce Mapping
- GA4 recommended events:
  - `purchase` ↔ `checkout_completed`
  - `begin_checkout` ↔ `checkout_started`
  - `add_payment_info` ↔ `checkout_step` when `step` corresponds to payment entry
  - `view_cart`/`view_promotion` may be leveraged for `subscription_viewed`
- Use GTM to map dataLayer payloads into GA4 event parameters:
  - `items`: array with one entry per plan tier `{ "item_id": plan_tier, "item_name": plan_tier, "item_category": "subscription", "price": value }`
  - `currency`: ISO 4217 currency code from payload
  - `value`: numeric transaction value
  - `coupon`, `payment_type`, and other GA4 parameters can be populated when available.
- Maintain consistency between dataLayer payloads and GA4 events for downstream reporting.

## PII Policy
- Never send raw personally identifiable information (PII) such as email addresses or names to GTM, GA4, or other vendors.
- Hash unique user identifiers client-side with SHA-256 before including them in `user_id_hash`.
- Exclude free-text user inputs unless they are classified as non-PII.
- Review all new events for PII compliance before deployment.

## Event Quality Standards
- Populate `ts` using UTC ISO-8601 (`new Date().toISOString()`).
- Generate a unique `request_id` per event to aid in debugging and deduplication.
- Validate events via automated tests or manual QA prior to release.
- Monitor GTM/GA4 debugging consoles to ensure events fire once per user action and include the required context.
- Log client-side errors when `ui_error_shown` events fire to facilitate root-cause analysis.
