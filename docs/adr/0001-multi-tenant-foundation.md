# ADR 0001: Multi-Tenant Foundation

**Status:** Accepted  
**Date:** 2025-10-08

## Context
We want Fan Feed Calendar to serve both individual fans and organisations (e.g., clubs, schools, partners). Multi-user access and potential white-labelling require clean tenant isolation.

## Decision
- Introduce a `tenants` domain and make all user-specific data tenant-scoped.
- Solo users get a dedicated tenant by default (transparent to them).
- Add `tenant_memberships` for roles (`owner|admin|member`).
- All writeable domains (Subscriptions, Sync, Output, Identity) include `tenant_id` in their tables.
- Catalogue (leagues/seasons/teams) remains global reference data.

## Consequences
- Clear data boundaries avoid future rewrites when we support org workspaces.
- Query patterns always include `tenant_id` for isolation.
- White-label branding and shared calendars become straightforward upgrades later.

## Alternatives considered
- Single-tenant with per-user scoping only — rejected (limits org use cases, makes future split painful).
- Postpone multi-tenant — rejected (high risk of schema churn later).

## Related
- Continuity Map: Identity & Tenant sections.
- Future ADR: Role model expansion (Viewer), Custom branding.
