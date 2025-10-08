# Design System Overview

## Design Tokens

### Colour Palette
- **Light Theme**
  - Primary: Sky Blue 500 `#3BA7F0`
  - Success: Emerald 500 `#1EB980`
  - Warning: Amber 500 `#F7B500`
  - Danger: Crimson 500 `#E23D4C`
  - Surface: Porcelain `#F5F7FA`
  - Surface Elevated: White `#FFFFFF`
  - Text Primary: Charcoal `#1F2933`
  - Text Secondary: Slate `#52606D`
  - Border: Cloud `#CBD2D9`
  - Neutrals: `#FFFFFF`, `#F5F7FA`, `#E4E7EB`, `#CBD2D9`, `#9AA5B1`, `#7B8794`, `#52606D`, `#3E4C59`, `#1F2933`

- **Dark Theme**
  - Primary: Sky Blue 400 `#59B7F2`
  - Success: Emerald 400 `#2ECFA0`
  - Warning: Amber 400 `#FFC94A`
  - Danger: Crimson 400 `#F06272`
  - Surface: Midnight `#101820`
  - Surface Elevated: Slate `#1D2A36`
  - Text Primary: Mist `#F5F7FA`
  - Text Secondary: Steel `#9AA5B1`
  - Border: Storm `#3E4C59`
  - Neutrals: `#0B121A`, `#101820`, `#16212B`, `#1D2A36`, `#27323D`, `#35424F`, `#52606D`, `#9AA5B1`, `#F5F7FA`

- **Semantic Tokens**
  - Primary: High-contrast accent colour for interactive elements.
  - Success: Affirmative actions and confirmations.
  - Warning: Cautionary messaging and pending states.
  - Danger: Errors, destructive actions, and critical alerts.
  - Surface: Page backgrounds and base containers.
  - Surface Elevated: Cards, sheets, and overlays with subtle elevation.
  - Text Primary: Core content, headings, and high-emphasis copy.
  - Text Secondary: Supportive copy, metadata, and labels.
  - Border: Dividers, input outlines, focus rings.

### Type Scale
- **H1**: 40px / 48px line-height, weight 700.
- **H2**: 32px / 40px line-height, weight 600.
- **H3**: 24px / 32px line-height, weight 600.
- **Body**: 16px / 24px line-height, weight 400.
- **Small**: 14px / 20px line-height, weight 400.

### Spacing Scale
Use a 4px baseline grid with the following key increments: 4, 8, 12, 16, 20, 24, 32, 40, 48, 56, 64px.

### Radii & Elevation
- **Corner Radii**: 8px (interactive controls), 12px (cards), 16px (large surfaces).
- **Shadows**:
  - Elevation 1: `0 1px 2px rgba(0, 0, 0, 0.1)`
  - Elevation 2: `0 2px 6px rgba(0, 0, 0, 0.12)`
  - Elevation 3: `0 8px 16px rgba(0, 0, 0, 0.16)`

### Motion
- Standard interaction animations range between 100–200ms easing with `ease-out`.
- Provide reduced-motion alternatives: prefer fades or instant state changes, and respect system `prefers-reduced-motion`.

## Theming Rules
- Ensure light and dark themes maintain parity in layout, hierarchy, and interaction affordances.
- Maintain WCAG AA contrast ratios (4.5:1 for text, 3:1 for large text and UI components).
- Focus states must be visible with a 2px outline using the theme's primary colour on surfaces.
- Disabled styles use decreased opacity (40%) and remove drop shadows and hover states.

## Component Specifications
- **Buttons**: Primary, secondary, tertiary. States: default, hover, active, focus, loading, disabled.
- **Inputs**: Email, select, multi-select. States: default, hover, focus, filled, error, disabled. Include helper/error text.
- **Chips**: Filter and input chips with removable icons; states: default, selected, disabled.
- **Badges**: Status indicators (success, warning, danger, info) with solid and subtle variants.
- **Cards**: Use surface-elevated backgrounds, 12px radius, elevation tier 2 shadow.
- **Banners/Toasts**: Full-width (banner) vs. floating (toast) with semantic colour support and dismiss controls.
- **Calendar Event Pill**: Rounded 12px, colour-coded by calendar, includes time and title, truncates gracefully.
- **Modal/Drawer**: Modal center aligned, drawer slides from right; both use elevation 3 shadow and overlay fade.
- **Tabs**: Underline indicator for active tab, focus ring on tablist, supports horizontal scroll on mobile.
- **Pagination**: Numbered items with previous/next controls; compact and expanded variants.
- **Skeleton Loaders**: Rounded rectangles with 12px radius and animated shimmer respecting reduced motion.

## Responsive Layout
- **Breakpoints**:
  - XS: 0–479px — single-column layouts, full-width controls.
  - SM: 480–767px — two-column capability, stacked navigation.
  - MD: 768–1023px — multi-column layouts, persistent sidebar optional.
  - LG: 1024px+ — full grid layouts, expanded navigation.
- Components adapt fluidly between breakpoints; maintain comfortable touch targets (44px minimum) on XS/SM.

## Iconography & Imagery
- **Iconography**: 24px artboard, 2px stroke weight, rounded stroke caps, consistent visual center. Filled variants reserved for active states only.
- **Imagery**: Use photography that reinforces community and event energy; apply subtle colour grading to align with primary palette. Maintain accessible overlays for text legibility.
