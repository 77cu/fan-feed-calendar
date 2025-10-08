# Fan Feed Calendar — Product Design Brief

## Phase 1 Goals & Non-goals
### Goals
- Deliver a dependable calendar experience for football supporters with live synchronisation across Google and Microsoft calendars, plus an always-visible ICS fallback.
- Provide transparent communication around fixture changes, postponements, and rescheduled competitions with concise, jargon-free language.
- Instrument the entire subscription funnel with Google Tag Manager so product performance is measurable end-to-end.
- Ensure the interface works responsively from small mobile screens through to polished desktop layouts, with equal support for light and dark modes.
- Meet WCAG 2.2 AA accessibility standards, including robust keyboard navigation and honouring reduced motion preferences.

### Non-goals
- Building bespoke features for a single club, league, or sport — keep structures and copy sport-agnostic using "Competition", "Team", and "Fixture" terminology.
- Implementing advanced analytics platforms beyond GTM instrumentation in this phase.
- Delivering native mobile applications — focus on mobile-first responsive web only.

## Audience & Personas
### Casual Fan
- Wants a quick, reliable way to track favourite teams and fixtures without heavy setup.
- Relies on calendar alerts to avoid missing kick-off times or last-minute changes.
- Values clear, friendly explanations when matches move or are cancelled.

### Power User
- Follows multiple competitions, teams, and possibly international fixtures.
- Expects granular controls for filtering feeds and managing notifications across devices.
- Appreciates data accuracy, rapid updates, and the ability to compare fixtures at a glance.

### Team / Organisation Admin
- Manages fixture calendars for clubs, leagues, or supporter groups.
- Needs tools to publish official schedules quickly and communicate changes confidently.
- Looks for analytics to understand subscription uptake and supporter engagement.

## Brand Voice & Copy Principles
- Modern, warm, and confident tone expressed through short, human copy in UK English.
- Prioritise clarity and brevity; avoid jargon and explain fixture changes plainly.
- Use inclusive language that resonates with global sports audiences without favouring a single sport.

## Platforms & Modes
- Design mobile-first layouts that scale smoothly from small screens to large desktops, adding desktop polish where space allows.
- Treat light and dark themes as first-class citizens with equal consideration for colour, contrast, and brand expression.

## Accessibility & Interaction
- Adhere to WCAG 2.2 AA criteria, including colour contrast, focus states, and touch targets.
- Guarantee full keyboard navigation and visible focus throughout flows.
- Respect reduced motion preferences with subtle transitions and alternative states.

## Calendar Integrations
- Provide native push integrations for Google Calendar and Microsoft Outlook with clear setup flows.
- Keep an ICS download link visible at all times as a universal fallback.
- Communicate sync status, delays, or errors plainly so users understand what to expect.

## Sports Expansion Considerations
- Build adaptable navigation and information architecture using "Competition", "Team", and "Fixture" labels to support new sports quickly.
- Use iconography, colour, and terminology that remain neutral across global sports.
- Plan for scalable content modules that handle differing season structures and time zones.

## Analytics & Measurement
- Implement GTM-first tracking with defined events for every key user action: subscription start, calendar selection, feed confirmation, sharing, and cancellation.
- Ensure analytics tie the entire funnel together, from landing to successful calendar sync.
- Provide reporting hooks for admins to assess performance without introducing new analytics suites in Phase 1.

