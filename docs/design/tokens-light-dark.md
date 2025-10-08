# Fan Feed UI – Light & Dark Theme Tokens

These design tokens are implemented as CSS custom properties. They provide the
theming foundation for both light and dark modes across the prototype screens.

| Token | Description |
| --- | --- |
| `--ff-color-bg` | Page background color. |
| `--ff-color-surface` | Card and surface background color. |
| `--ff-color-border` | Standard border and divider color. |
| `--ff-color-text` | Primary text color. |
| `--ff-color-text-muted` | Secondary / muted text color. |
| `--ff-color-accent` | Interactive and primary action background color. |
| `--ff-color-accent-contrast` | Text color used on top of accent backgrounds. |
| `--ff-color-input-bg` | Input background fill. |
| `--ff-color-input-border` | Input border color. |
| `--ff-color-note-bg` | Informational note background. |
| `--ff-color-note-border` | Informational note border. |
| `--ff-color-warn-bg` | Warning background. |
| `--ff-color-warn-border` | Warning border. |
| `--ff-radius-card` | Corner radius for cards and alerts. |
| `--ff-radius-input` | Corner radius for inputs. |
| `--ff-radius-pill` | Full-pill radius (toggle pills). |
| `--ff-shadow-card` | Elevation for cards. |

`--ff-color-*` tokens have values defined for light (root) and dark (`.theme-dark`)
modes. Radius and shadow tokens remain constant across themes.
