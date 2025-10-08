# Contributing

Thanks for helping build Fan Feed Calendar. This guide keeps the repo consistent and avoids drift.

## Guardrails
- **No tech stack changes** (frameworks, build tools) without an ADR.
- **No schema changes** without: (a) migration, (b) Continuity Map update, (c) ADR.
- **No UI layout changes** unless the ticket explicitly calls for it. Copy updates are fine when requested.
- **One ticket → one branch → one PR.** Keep PRs small and reversible.
- **Follow the Continuity Map.** Each concept has a single owning table.

## Branching and commits
- Branch names: `area/short-description` (e.g., `docs/ux-storyboard`, `copy/branding-pass`).
- Conventional commits: `feat:`, `fix:`, `docs:`, `chore:`, `refactor:`, etc.

## Pull requests
All PRs must:
- Use the PR template.
- State **scope** and **out of scope**.
- List **acceptance criteria** and how you tested.
- Include screenshots for any user-facing change.

## Migrations
- One migration per PR, clearly named with intent and date.
- Start the SQL with a comment block describing **owner domain**, **why**, **rollback**.
- Update `/docs/schema.sql` and `/docs/continuity-map.md`.

## ADRs
- Place new ADRs in `/docs/adr/NNNN-title.md`.
- Keep them short: Context → Decision → Consequences → Alternatives → Related.

## Local development
- Prefer `.env` for environment flags (e.g., `APP_ENV=dev`).
- Dev-only routes or diagnostics must be guarded behind `APP_ENV=dev`.

## Review checklist
- [ ] Scope matches the ticket  
- [ ] No unrelated changes  
- [ ] Continuity Map respected  
- [ ] Tests / manual checks done  
- [ ] Screenshots (if applicable)  
- [ ] Docs and ADRs updated (if schema changed)
