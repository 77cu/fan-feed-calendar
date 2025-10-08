# Fan Feed Calendar Wireframes

These annotated wireframes capture the primary application flows. Each section lists the core layout, content, user actions, and states (normal, empty, error, loading). ASCII diagrams are provided where useful to convey spatial relationships.

---

## 1. Onboarding Flow

### Overview
* Purpose: guide users from country selection to competitions and finally to teams they follow.
* Pattern: sequential multi-step wizard with progress indicator.
* Primary action: Continue to next step; Secondary: Back/Skip (where applicable).

### Layout
```
+-----------------------------------------------------------+
| Fan Feed Calendar logo           Progress: Step X of 3    |
+-----------------------------------------------------------+
| Headline / helper copy                                      |
|                                                             |
| [Step content area]                                         |
|  ├─ Filter search input                                     |
|  ├─ Multi-select chips (responsive grid)                    |
|  └─ Supporting imagery / illustration (optional)            |
|                                                             |
| Sticky footer:                                              |
|  [Back]                                [Continue / Skip]   |
+-----------------------------------------------------------+
```

### Step Details
1. **Country Selection**
   * **Content**: Search bar, list of country chips (flag + country name), "Select all" chip, tooltip describing regional coverage.
   * **Actions**: Select/deselect chips (multi-select), Continue, Back (disabled on first step).
   * **States**:
     * Normal: Chips grouped alphabetically.
     * Empty: No countries returned (e.g., search filter) → show illustration + "No countries match your search" with Clear filter button.
     * Loading: Skeleton chips displayed while fetching.
     * Error: Inline banner "We couldn't load countries" with Retry.

2. **Competition Selection**
   * **Content**: Selected countries summary pills at top, competition chips (league icon + name), filter tabs (All / Domestic / International).
   * **Actions**: Multi-select chips, Continue, Back, Skip.
   * **States**:
     * Normal: Chips grouped by country with collapsible accordions.
     * Empty: "No competitions found" message + link to adjust countries.
     * Loading: Spinner overlay on chip grid.
     * Error: Banner with Retry and Contact Support link.

3. **Team Selection**
   * **Content**: Selected competitions summary bar, team chips (crest + name), favorite badge icon for prioritized teams.
   * **Actions**: Multi-select chips, Finish setup, Back.
   * **States**:
     * Normal: Chips sorted by league standing.
     * Empty: "No teams available" with suggestion to add more competitions.
     * Loading: Skeleton team rows.
     * Error: Toast notification + Retry button.

### Additional Notes
* Progress indicator updates after each Continue.
* Selected chips appear in sticky tray at bottom with quick removal.

---

## 2. Dashboard

### Layout
```
+--------------------------------------------------------------------------------+
| Top Nav: Logo | Search | Notifications | User avatar                           |
+--------------------------------------------------------------------------------+
| Status Bar: "Upcoming 7 days" | Sync status | Last updated | Add calendar CTA  |
+--------------------------------------------------------------------------------+
| Filters row: [Time range] [Competition] [Team] [Status] [Reset]                 |
+---------------------------+----------------------------------------------------+
| Calendar Pane             | Changes Panel                                      |
| (list or grid of events)  | - Feed of recent updates                           |
|                           | - Grouped by date                                  |
|                           | - Each item: team badge, change summary, actions   |
+---------------------------+----------------------------------------------------+
```

### Content
* Status bar displaying overview metrics, sync health, and quick actions.
* Filter chips/dropdowns controlling calendar view.
* Calendar pane supporting month/week/list toggles; event cards show teams, time, venue, status badges.
* Changes panel summarizing additions, removals, reschedules.

### Actions
* Apply/clear filters, toggle calendar view, click events to open match drawer, refresh feed, acknowledge change items.

### States
* **Normal**: Calendar populated with matches; Changes panel showing latest activity.
* **Empty**: No matches → empty state illustration + "No fixtures in this range" + adjust filters button; Changes panel shows "All caught up".
* **Loading**: Skeleton calendar rows and shimmering cards; spinner in Changes panel.
* **Error**: Inline alert across status bar with Retry; specific modules show toast with failure details.

---

## 3. Match Card Drawer / Modal

### Layout
```
+-----------------------------------------------------------+
| Header: Team A vs Team B              Close (X)           |
+-----------------------------------------------------------+
| Match details: date, kick-off time, venue, competition    |
| Status badge (Scheduled/Live/Final/Postponed)             |
|                                                           |
| Odds section (collapsed accordion by default)             |
|  └─ Expand to show book probabilities / lines             |
|                                                           |
| Include in calendar toggle [Include | Exclude]            |
|                                                           |
| Action buttons: [Add reminder] [Share]                    |
+-----------------------------------------------------------+
```

### Content
* Team logos, names, record summary.
* Time, venue, broadcast info.
* Live status indicator if in-progress.
* Optional odds block (collapsed) showing betting partners when expanded.

### Actions
* Toggle Include/Exclude (updates calendar sync).
* Expand/collapse odds.
* Add reminder, Share link, Close drawer.

### States
* **Normal**: All details loaded with interactive controls active.
* **Empty**: Rare; if data missing show placeholder text "Details coming soon" and disable odds.
* **Loading**: Skeleton for header and details while fetching; spinner overlay.
* **Error**: Inline error message "Unable to load match" with Retry; Include toggle disabled until resolved.

---

## 4. Settings

### Layout
```
+-----------------------------------------------------------+
| Settings sidebar:                                          |
|  - Account                                                 |
|  - Integrations                                            |
|  - Preferences                                             |
|  - Theme                                                   |
+---------------------------+--------------------------------+
| Content panel (section)   | Contextual help / tips         |
+---------------------------+--------------------------------+
```

### Sections
1. **Account**
   * **Content**: Profile info (name, email, avatar), password reset, session management.
   * **Actions**: Edit profile, Change password, Logout other sessions.
   * **States**:
     * Normal: Forms populated with current data.
     * Empty: For new users missing fields, show prompts to complete profile.
     * Loading: Save button shows spinner on submit.
     * Error: Inline form validation and global error banner on failure.

2. **Integrations (Google / Microsoft 365 / ICS)**
   * **Content**: Cards for each integration with connection status, last sync time, connect/disconnect buttons.
   * **Actions**: Connect, Disconnect, Sync now, View logs (link).
   * **States**:
     * Normal: Status badges (Connected/Not connected).
     * Empty: No integrations connected → highlight available options.
     * Loading: Spinner when initiating OAuth or sync.
     * Error: Inline error messages, banner for auth issues, Retry option.

3. **Preferences**
   * **Content**: Toggles for reminders, auto-add new fixtures, odds display; dropdowns for default time zone and notification cadence.
   * **Actions**: Toggle switches, Save preferences.
   * **States**: Similar to Account (normal, empty when defaults unset, loading on save, error on failure).

4. **Theme**
   * **Content**: Radio buttons for Light, Dark, Auto (system), preview thumbnails.
   * **Actions**: Select theme, Apply immediately.
   * **States**: Normal preview; error toast if theme save fails.

---

## 5. Billing

### Layout
```
+-----------------------------------------------------------+
| Billing header: Plan overview, current usage               |
+-----------------------------------------------------------+
| Plan Cards (grid):                                         |
|  - Free | Pro | Enterprise                                 |
|  - Feature list, price, CTA                                |
+-----------------------------------------------------------+
| Checkout Flow (modal/stepper):                             |
|  Step 1: Plan confirmation                                 |
|  Step 2: Payment details                                   |
|  Step 3: Review & analytics consent                        |
|  Step 4: Confirmation                                      |
+-----------------------------------------------------------+
```

### Content
* Plan cards with highlights, best value tag, compare button.
* Checkout steps include analytics tracking points: plan selection, payment submitted, confirmation.
* Billing history table with invoices, download links.

### Actions
* Select plan, Start trial/Upgrade, Enter payment, Agree to terms, Download invoice, Manage billing info.

### States
* **Normal**: Current plan highlighted, checkout stepper active.
* **Empty**: No billing history → show illustration and "Invoices will appear here".
* **Loading**: Skeleton plan cards during fetch; spinner when processing payment.
* **Error**: Payment failure banner with retry; plan load error toast.

---

These wireframes serve as a foundational reference for UI/UX design and engineering alignment.
