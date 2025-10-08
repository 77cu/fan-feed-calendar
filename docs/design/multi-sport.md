# Multi-Sport Design Guidance

## Terminology
- **Competition**: Neutral label covering leagues, cups, tournaments, and championships.
- **Season**: Standard reference for a competition's temporal grouping (e.g., 2024 Regular Season).
- **Fixture / Game / Match**: Default terms for scheduled play; sport-specific labels may be added as secondary context.
- **Team / Side**: Neutral terms for competitors; use "Team" in UI copy, allow "Side" for editorial contexts.
- **Venue**: Applies to stadiums, arenas, courses, or rinks regardless of sport.
- **Optional sport-specific fields**: Innings, quarters, periods, sets, legs, heats, etc., surfaced only when data coverage supports them.

## Time & Timezone Display
- Display fixture times in the viewer's local timezone with a clear timezone cue (e.g., "7:30 PM ET").
- Persist canonical timestamps in UTC for storage, feeds, and API integration.
- When local time differs from venue time, offer a tooltip or secondary line showing the venue's local time.
- Use ISO 8601 formatting for machine-readable exports.

## Calendar Event Naming Templates
- Default naming pattern: `Home Team vs Away Team` (e.g., "Red Sox vs Yankees").
- Baseball: `Home Team vs Away Team – Series Game X` when series data exists.
- Basketball (NBA/WNBA/FIBA): `Away Team at Home Team` including round when in playoffs.
- Soccer/Football (UEFA, MLS, etc.): `Home Team vs Away Team` with leg indicator if applicable (e.g., "Leg 1").
- American Football (NFL, NCAA): `Away Team at Home Team` with week identifier (e.g., "Week 5").
- Hockey: `Away Team at Home Team` with season stage when known.
- Cricket: `Team A vs Team B` with match format abbreviation (e.g., "ODI", "T20").
- Tennis: `Player A vs Player B` with round (e.g., "Quarterfinal").
- Motorsports: `Event Name – Session Type` (e.g., "Monaco GP – Qualifying").
- Fallback to the default template when sport-specific context is unavailable.

## Colour Coding
- Assign colours based on competitions, not sports, to avoid exhausting the palette in multi-league views.
- Reserve neutral colours for cross-competition events and shared calendars.
- Ensure sufficient contrast for accessibility (WCAG AA minimum).

## Odds & Betting Information
- Display odds only for competitions where licensed coverage exists.
- Collapse odds modules by default; allow users to expand to view lines and markets.
- Present regional disclaimers and age restrictions aligned with the viewer's locale.
- Provide clear links to responsible gambling resources.

## Mobile Considerations
- Truncate or marquee long competition names; provide tooltips or tap-to-expand for full names.
- Allow badges/logos to scale down while maintaining legibility (minimum 24px height).
- Stack meta information (venue, odds, broadcast) vertically when horizontal space is limited.

## Future Enhancements
- Support bracket and playoff visualisations for competitions with series structures (NBA/NFL style).
- Model multi-leg fixtures with aggregate scoring (e.g., UEFA home/away legs) and display aggregate totals.
- Accommodate reseeding rules and conditional matchups in future scheduling.

## Internationalisation & RTL
- Prepare UI components for right-to-left layouts (mirrored alignment, reversed iconography).
- Externalise copy for translation and support locale-specific date/time formats.
- Plan to revisit layout testing with RTL languages once multi-sport foundations are stable.
