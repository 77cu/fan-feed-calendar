# Design Tokens — Light & Dark Themes

The following tables define the foundational design tokens used across the Fan Feed Calendar web experience. Values are provided for both the default (light) theme and the optional dark theme. All values are expressed in CSS-friendly units.

## Color Tokens

| Token | Description | Light | Dark |
| --- | --- | --- | --- |
| `--color-bg` | Application background | `#f8fafc` | `#0f172a` |
| `--color-surface` | Elevated surface background | `#ffffff` | `#1e293b` |
| `--color-surface-subtle` | Muted/elevated backgrounds | `#eef2ff` | `#111c33` |
| `--color-border` | Default border color | `#cbd5f5` | `#334155` |
| `--color-border-strong` | Stronger border/divider color | `#94a3d3` | `#1f2a3f` |
| `--color-primary` | Primary action background | `#2563eb` | `#3b82f6` |
| `--color-primary-hover` | Primary action hover background | `#1d4ed8` | `#2563eb` |
| `--color-primary-soft` | Subtle/soft primary tint | `#dbeafe` | `#1e3a8a` |
| `--color-on-primary` | Text/icon color on primary buttons | `#f8fafc` | `#0b1120` |
| `--color-text` | Primary body text | `#0f172a` | `#f8fafc` |
| `--color-text-muted` | Secondary text | `#475569` | `#cbd5f5` |
| `--color-text-inverse` | Inverse text for dark surfaces | `#f8fafc` | `#0f172a` |
| `--color-success` | Success state | `#15803d` | `#22c55e` |
| `--color-warning` | Warning state | `#b45309` | `#f59e0b` |
| `--color-danger` | Destructive state | `#dc2626` | `#f87171` |

## Typography Tokens

| Token | Value |
| --- | --- |
| `--font-family-sans` | `"Inter", "SF Pro Text", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif` |
| `--font-size-xs` | `0.75rem` |
| `--font-size-sm` | `0.875rem` |
| `--font-size-base` | `1rem` |
| `--font-size-lg` | `1.125rem` |
| `--font-size-xl` | `1.25rem` |
| `--font-size-2xl` | `1.5rem` |
| `--line-height-tight` | `1.2` |
| `--line-height-snug` | `1.35` |
| `--line-height-normal` | `1.5` |
| `--line-height-relaxed` | `1.65` |
| `--font-weight-regular` | `400` |
| `--font-weight-medium` | `500` |
| `--font-weight-semibold` | `600` |

## Radius Tokens

| Token | Value |
| --- | --- |
| `--radius-xs` | `0.125rem` |
| `--radius-sm` | `0.25rem` |
| `--radius-md` | `0.5rem` |
| `--radius-lg` | `0.75rem` |
| `--radius-full` | `9999px` |

## Shadow Tokens

| Token | Value |
| --- | --- |
| `--shadow-sm` | `0 1px 2px rgba(15, 23, 42, 0.08)` |
| `--shadow-md` | `0 4px 12px rgba(15, 23, 42, 0.12)` |
| `--shadow-lg` | `0 12px 24px rgba(15, 23, 42, 0.16)` |

These tokens are the single source of truth for the visual language of the application. CSS variables should reference these names, with TailwindCSS pointing to the same variables for consistency across utility classes and authored styles.
