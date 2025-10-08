# Privacy, Consent, and Tag Management Guidance

This document summarises how Fan Feed Calendar manages user consent, data tracking, and Google Tag Manager (GTM) configuration.

## Cookie Categories

We classify cookies and other client storage into the following categories. The consent banner must surface these groups clearly and allow users to grant or refuse each optional category.

### Strictly Necessary
- Required for site functionality such as session continuity, authentication, and security controls.
- Always active and cannot be disabled, but should still be documented in the banner for transparency.

### Analytics
- Measures site performance, page views, and aggregated audience insights.
- Examples: Google Analytics, GA4, or similar measurement tags.
- Must only activate after the user accepts Analytics cookies.

### Marketing
- Supports advertising, attribution, and personalised offers.
- Examples: remarketing pixels, affiliate tracking, or odds partner scripts.
- Must only activate after the user accepts Marketing cookies.

## Consent Banner Behaviour

- Present the consent banner on first visit and whenever consent preferences need to be refreshed (e.g., after policy updates).
- Provide granular toggles for the Analytics and Marketing categories alongside an "Accept All" and "Reject All" option.
- Record the user’s choices in a consent management store (e.g., cookie/localStorage) with timestamp and versioning.
- Display a persistent control (such as a footer link) that lets users revisit and change their preferences at any time.
- **Region awareness (future enhancement):** integrate geo-detection so the banner auto-enforces local regulations (e.g., default opt-out for EU/UK visitors).

### dataLayer Events

Surface consent decisions via the `dataLayer` so downstream tools can react consistently. The following events are recommended (see Issue 6):

| Event Name | Trigger | Payload Example |
|------------|---------|-----------------|
| `consentInitialised` | Consent banner loads with default settings. | `{ consentVersion: "2024-06", analytics: false, marketing: false }` |
| `consentUpdated` | User saves preferences via the banner or control centre. | `{ analytics: true, marketing: false, timestamp: "2024-06-18T12:34:56Z" }` |
| `consentRejectedAll` | User explicitly rejects all optional categories. | `{ analytics: false, marketing: false }` |
| `consentAcceptedAll` | User explicitly accepts all categories. | `{ analytics: true, marketing: true }` |

Ensure every event includes a consent version identifier so downstream systems can audit policy changes.

## Google Tag Manager Integration

- Initialise GTM after firing the `consentInitialised` event so that GTM starts in a known state.
- Configure GTM consent overview (or Consent Mode) to map the Analytics and Marketing states to built-in consent types (e.g., `analytics_storage`, `ad_storage`).
- Gate all Analytics tags behind the Analytics consent state; the tag should fire **only** if Analytics consent is `true`.
- Gate all Marketing tags behind the Marketing consent state; these tags should fire **only** after Marketing consent is `true`.
- Default optional tags to paused/blocked status until the relevant consent event updates their triggers.

## Data Minimisation

- Hash any user identifiers before pushing them into the `dataLayer` (e.g., SHA-256 with salt) and avoid storing raw IDs client-side when not necessary.
- Never include email addresses or other directly identifiable information in the `dataLayer` or consent payloads.
- Limit payloads to the minimum metadata required for measurement and auditing (e.g., consent booleans, timestamps, hashed IDs).

## Responsible Gambling Links

For odds or betting-related pages targeting UK markets, include a prominent link to [BeGambleAware](https://www.begambleaware.org/). This link should be visible near odds content and, where practical, in the consent banner messaging shown to UK users.

