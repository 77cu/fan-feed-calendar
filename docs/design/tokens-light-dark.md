# Token Reference: Light & Dark Themes

This document enumerates the design token names standardised across the Fan Feed Calendar design system. Values are provided for both light and dark themes and should be implemented in code via CSS variables or equivalent platform tokens.

## Colour Tokens

| Token | Description | Light Value | Dark Value |
| --- | --- | --- | --- |
| `--color-bg` | Base page background | `#F5F7FA` | `#101820` |
| `--color-bg-elevated` | Surfaces with elevation | `#FFFFFF` | `#1D2A36` |
| `--color-bg-inverse` | Inverse surface | `#1F2933` | `#F5F7FA` |
| `--color-border` | Standard border/divider | `#CBD2D9` | `#3E4C59` |
| `--color-border-strong` | High emphasis border | `#7B8794` | `#52606D` |
| `--color-text-primary` | Primary text | `#1F2933` | `#F5F7FA` |
| `--color-text-secondary` | Secondary text | `#52606D` | `#9AA5B1` |
| `--color-text-inverse` | Text on dark surfaces | `#FFFFFF` | `#0B121A` |
| `--color-accent` | Primary brand accent | `#3BA7F0` | `#59B7F2` |
| `--color-accent-strong` | Hover/active accent | `#1F8FE0` | `#3BA7F0` |
| `--color-success` | Positive feedback | `#1EB980` | `#2ECFA0` |
| `--color-warning` | Cautionary feedback | `#F7B500` | `#FFC94A` |
| `--color-danger` | Destructive/error | `#E23D4C` | `#F06272` |
| `--color-info` | Informational states | `#2680EB` | `#4AA3FF` |
| `--color-overlay` | Scrim overlay for modals | `rgba(31, 41, 51, 0.6)` | `rgba(10, 18, 26, 0.7)` |

## Typography Tokens

| Token | Description | Value |
| --- | --- | --- |
| `--font-family-base` | Primary typeface stack | `"Inter", "Helvetica Neue", Arial, sans-serif` |
| `--font-size-h1` | H1 size | `40px` |
| `--font-size-h2` | H2 size | `32px` |
| `--font-size-h3` | H3 size | `24px` |
| `--font-size-body` | Body text | `16px` |
| `--font-size-small` | Small text | `14px` |
| `--line-height-tight` | Compact headings | `1.2` |
| `--line-height-base` | Standard paragraphs | `1.5` |
| `--font-weight-regular` | Body weight | `400` |
| `--font-weight-medium` | Emphasis | `500` |
| `--font-weight-semibold` | Headings | `600` |
| `--font-weight-bold` | Highlight | `700` |

## Spacing Tokens

| Token | Value |
| --- | --- |
| `--space-1` | `4px` |
| `--space-2` | `8px` |
| `--space-3` | `12px` |
| `--space-4` | `16px` |
| `--space-5` | `20px` |
| `--space-6` | `24px` |
| `--space-7` | `32px` |
| `--space-8` | `40px` |
| `--space-9` | `48px` |
| `--space-10` | `56px` |
| `--space-11` | `64px` |

## Radius & Elevation Tokens

| Token | Value |
| --- | --- |
| `--radius-sm` | `8px` |
| `--radius-md` | `12px` |
| `--radius-lg` | `16px` |
| `--shadow-1` | `0 1px 2px rgba(0, 0, 0, 0.10)` |
| `--shadow-2` | `0 2px 6px rgba(0, 0, 0, 0.12)` |
| `--shadow-3` | `0 8px 16px rgba(0, 0, 0, 0.16)` |

## Motion Tokens

| Token | Description | Value |
| --- | --- | --- |
| `--motion-duration-fast` | Quick interactions | `100ms` |
| `--motion-duration-medium` | Standard transitions | `160ms` |
| `--motion-duration-slow` | Complex animations | `200ms` |
| `--motion-easing-standard` | Default easing | `cubic-bezier(0.4, 0, 0.2, 1)` |
| `--motion-easing-emphasized` | Emphasized exit/entry | `cubic-bezier(0.2, 0, 0, 1)` |

## Breakpoint Tokens

| Token | Description | Value |
| --- | --- | --- |
| `--breakpoint-xs` | Extra small screens | `0px` |
| `--breakpoint-sm` | Small screens | `480px` |
| `--breakpoint-md` | Medium screens | `768px` |
| `--breakpoint-lg` | Large screens | `1024px` |

## Interaction State Tokens

| Token | Description | Light Value | Dark Value |
| --- | --- | --- | --- |
| `--state-focus-ring` | Focus outline colour | `#3BA7F0` | `#59B7F2` |
| `--state-disabled-opacity` | Disabled element opacity | `0.4` | `0.4` |
| `--state-hover-overlay` | Hover overlay tint | `rgba(59, 167, 240, 0.08)` | `rgba(89, 183, 242, 0.12)` |
| `--state-active-overlay` | Active press tint | `rgba(59, 167, 240, 0.16)` | `rgba(89, 183, 242, 0.24)` |

## Iconography Tokens

| Token | Description | Value |
| --- | --- | --- |
| `--icon-size-md` | Standard icon size | `24px` |
| `--icon-stroke` | Stroke weight | `2px` |

## Imagery Tokens

| Token | Description | Value |
| --- | --- | --- |
| `--image-overlay` | Gradient overlay for text legibility | `linear-gradient(180deg, rgba(16, 24, 32, 0) 0%, rgba(16, 24, 32, 0.72) 100%)` |

## Implementation Notes
- Always map these tokens to platform equivalents (CSS variables, design tool styles) to maintain consistency.
- Respect user `prefers-reduced-motion` by shortening durations and removing shimmers when enabled.
- Tokens may be extended for future needs, but base names should remain unchanged for compatibility.
