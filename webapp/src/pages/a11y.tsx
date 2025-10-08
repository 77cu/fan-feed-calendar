import type { ReactNode } from "react";

const Section = ({
  title,
  description,
  children,
}: {
  title: string;
  description: string;
  children: ReactNode;
}) => (
  <section className="space-y-4">
    <div className="space-y-2">
      <h2 className="text-xl font-semibold text-text">{title}</h2>
      <p className="text-sm text-text-muted max-w-3xl">{description}</p>
    </div>
    {children}
  </section>
);

const SampleCard = () => (
  <article className="rounded-lg border border-border bg-surface p-6 shadow-md space-y-4">
    <div className="space-y-1">
      <h3 className="text-lg font-semibold text-text">Matchday reminder</h3>
      <p className="text-sm text-text-muted">
        Stay on top of your favourite fixtures with timely notifications and calendar updates.
      </p>
    </div>
    <button className="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow-sm transition-colors hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
      Enable alerts
    </button>
  </article>
);

const SampleList = () => (
  <ul className="divide-y divide-border-strong overflow-hidden rounded-lg border border-border">
    {[
      {
        title: "Premier League",
        detail: "Next match: 23 Aug, 19:30",
      },
      {
        title: "Women's Super League",
        detail: "Next match: 26 Aug, 14:00",
      },
      {
        title: "Championship",
        detail: "Next match: 28 Aug, 16:00",
      },
    ].map((item) => (
      <li key={item.title} className="flex items-center justify-between bg-surface px-4 py-3">
        <div>
          <p className="text-sm font-medium text-text">{item.title}</p>
          <p className="text-xs text-text-muted">{item.detail}</p>
        </div>
        <a
          href="#"
          className="rounded-full bg-primary px-3 py-1 text-xs font-medium text-primary-foreground shadow-sm transition-colors hover:bg-primary-hover"
        >
          View
        </a>
      </li>
    ))}
  </ul>
);

const ThemePreview = ({
  label,
  className = "",
}: {
  label: string;
  className?: string;
}) => (
  <div className={`rounded-2xl border border-border bg-background p-6 shadow-lg ${className}`}>
    <div className="space-y-6">
      <div className="space-y-2">
        <h3 className="text-lg font-semibold text-text">{label}</h3>
        <p className="text-sm text-text-muted">
          Tokens cascade via CSS variables. Utilities read from Tailwind semantic tokens.
        </p>
      </div>
      <SampleCard />
      <SampleList />
    </div>
  </div>
);

export default function AccessibilityPreviewPage() {
  return (
    <div className="min-h-screen bg-background px-6 py-10 text-text">
      <div className="mx-auto flex w-full max-w-6xl flex-col gap-12">
        <header className="space-y-4">
          <p className="text-sm font-semibold uppercase tracking-wide text-primary">
            Accessibility Toolkit
          </p>
          <h1 className="text-2xl font-semibold text-text">
            Theme tokens preview
          </h1>
          <p className="max-w-3xl text-base text-text-muted">
            The following sections demonstrate how light and dark theme variables map to Tailwind utilities. Primary
            actions and body copy meet WCAG AA contrast targets in both themes.
          </p>
        </header>

        <Section
          title="Foundational colors"
          description="Swatches sourced from CSS variables ensure consistent theming between Tailwind utilities and authored CSS."
        >
          <div className="grid gap-4 sm:grid-cols-3">
            {[
              { name: "Background", token: "bg-background", text: "text-text" },
              { name: "Surface", token: "bg-surface", text: "text-text" },
              { name: "Primary", token: "bg-primary text-primary-foreground", text: "text-primary-foreground" },
            ].map((swatch) => (
              <div
                key={swatch.name}
                className={`flex h-24 flex-col justify-between rounded-lg border border-border p-4 shadow-sm ${swatch.token}`}
              >
                <span className={`text-sm font-medium ${swatch.text}`}>{swatch.name}</span>
                <span className={`text-xs ${swatch.text}`}>.{swatch.token.replace(/\s+/g, " .")}</span>
              </div>
            ))}
          </div>
        </Section>

        <Section
          title="Theme previews"
          description="Compare light and dark experiences side-by-side. Each preview lives within its own scope so the correct token set is applied."
        >
          <div className="grid gap-8 lg:grid-cols-2">
            <ThemePreview label="Light theme" />
            <ThemePreview label="Dark theme" className="theme-dark" />
          </div>
        </Section>
      </div>
    </div>
  );
}
