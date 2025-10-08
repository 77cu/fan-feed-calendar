# Fan Feed Calendar IA & Key Journeys

## Information Architecture
- **Onboarding**
  - Welcome & account creation
  - Competition/team selection
  - Calendar connection (OAuth providers, manual ICS upload)
  - Review & confirmation
- **Dashboard**
  - Upcoming highlights
  - Recent updates summary
  - Quick actions (manage teams, sync status, billing shortcut)
- **Calendar**
  - Embedded view of synced events
  - Filters by competition, team, sport, and time period
  - Sync status indicator and refresh controls
- **Match Card**
  - Fixture details (date, time, venue, broadcast)
  - Team line-ups & news links
  - Add-to-calendar quick actions & sharing
  - Related content (articles, stats, merchandising)
- **Settings**
  - Notification preferences
  - Calendar sync options & default view
  - Localization (language, time zone)
- **Account**
  - Profile details
  - Authentication & security (password, MFA, connected logins)
- **Integrations**
  - Calendar providers (Google, Outlook, Apple, ICS)
  - Third-party content feeds & data providers
  - Webhooks/API keys for partners
- **Billing / Plans**
  - Current plan overview
  - Upgrade/downgrade options
  - Payment history & invoices
- **Help / Support**
  - Knowledge base & FAQs
  - Contact support (email, chat)
  - System status page
- **Legal (Privacy / Cookies)**
  - Privacy policy
  - Cookie policy & preferences manager
  - Terms of service

## Primary User Journeys

### New User Setup
1. Sign up or authenticate via Onboarding welcome screen.
2. Pick preferred competitions and teams (multi-select with search/filter).
3. Connect calendar provider (OAuth) or choose manual ICS download.
4. Review generated calendar preview, ensuring time zone and reminders are correct.
5. Confirm sync to finalize and land on Dashboard with success messaging.

### Existing User Management
1. From Dashboard, open Manage Teams quick action.
2. Add or remove teams/competitions using filters and suggestions.
3. Review changes in a diff-style summary, including affected fixtures.
4. Approve updates to push modifications to the Calendar and connected providers.
5. Receive confirmation toast and optional email notification.

### Subscriber Plan Changes (with GTM events)
1. Visit Billing / Plans section (`gtm_event: billing_view`).
2. Choose upgrade, downgrade, or cancel flow (`gtm_event: plan_select`).
3. Review plan details and pricing confirmation (`gtm_event: plan_review`).
4. Authorize payment or confirm cancellation (`gtm_event: plan_confirm`).
5. Display success/failure state with next steps (`gtm_event: plan_result`).

## State Considerations

### Onboarding
- **Empty**: Default prompts with hero illustrations and recommended competitions.
- **Loading**: Skeleton selectors while fetching competitions and calendar providers.
- **Error**: Inline alerts for failed competition fetch or calendar authorization; retry actions.

### Dashboard
- **Empty**: Encouraging message to add teams with CTA to Onboarding/Manage Teams.
- **Loading**: Skeleton cards for upcoming matches and sync status.
- **Error**: Banner indicating data fetch issues with refresh option.

### Calendar
- **Empty**: Placeholder calendar grid with instructions to add teams or adjust filters.
- **Loading**: Spinner overlay while syncing events.
- **Error**: Modal or inline alert if sync fails; provide retry and contact support links.

### Match Card
- **Empty**: Placeholder card with note that details become available closer to match time.
- **Loading**: Skeleton for key data (time, teams, content).
- **Error**: Alert within card if data feed unavailable; fallback to minimal fixture info.

### Settings & Account
- **Empty**: Default states show current defaults with prompts to configure.
- **Loading**: Field-level spinners when saving preferences.
- **Error**: Inline validation or toast messages on save failure.

### Integrations
- **Empty**: List of available integrations with connect buttons.
- **Loading**: Integration tiles show progress indicators during connection.
- **Error**: Inline error under specific integration with support link.

### Billing / Plans
- **Empty**: Plan comparison table with highlighted recommended plan.
- **Loading**: Spinner while retrieving billing data.
- **Error**: Blocking message if payment provider unreachable; include retry and support.

### Help / Support
- **Empty**: Search prompt with popular articles.
- **Loading**: Skeleton list while fetching help topics.
- **Error**: Message guiding user to contact support directly.

### Legal
- **Empty**: Default policy content with last updated date.
- **Loading**: Progress indicator while loading documents.
- **Error**: Fallback link to downloadable PDF.

## Sports Expansion Notes
- Structure navigation to group by sport first, then competition/team, enabling scalable filters across regions.
- Introduce sport-specific dashboards and calendar filters (e.g., soccer, basketball, cricket) with localized terminology.
- Support regional calendars and time zones, allowing users to toggle between home region and competition locale.
- Maintain consistent IA sections, but parameterize content (e.g., Match Card fields for different sports stats).
- Integrate localization for legal, support, and onboarding content tailored to regional requirements.
- Allow multi-sport bundles within Billing / Plans to surface relevant upgrades as inventory expands.
