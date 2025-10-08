# Analytics Tracking Plan

## page_view

| Field | Type | Description |
| --- | --- | --- |
| `event` | string | Always `"page_view"`. |
| `page_name` | string | Product-friendly identifier for the page or experience. |
| `page_title` | string | Document title rendered in the browser tab. |
| `page_path` | string | Path portion of the URL (no domain or query string). |
| `page_url` | string | Full canonical URL for the page. |
| `page_type` | string | Template or experience category (e.g., `app`, `marketing`). |
| `page_category` | string | Product area or navigation grouping. |
| `site_language` | string | ISO language code resolved from the document tag. |
| `site_theme` | string | UI theme applied at render (`light`/`dark`). |
| `user_id` | string \| null | Known identifier for the signed-in fan (if available). |
| `ff_account_status` | string \| null | Account tier (`free`, `premium`, etc.) when available. |

For any values we cannot determine at render time, send `null` so that downstream tooling can perform accurate null-handling.

## theme_toggle

| Field | Type | Description |
| --- | --- | --- |
| `event` | string | Always `"theme_toggle"`. |
| `theme_before` | string | Theme prior to the toggle action. |
| `theme_after` | string | Theme applied after the toggle completes. |
| `page_path` | string \| null | Path of the page where the toggle happened. |
| `site_theme` | string | Theme value after the toggle. |

All analytics events should be pushed via `ffPush(event, payload)` so that dev logging and dataLayer management stay consistent.
