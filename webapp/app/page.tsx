import Link from "next/link";

import { Button } from "@/components/ui/button";
import { ThemeToggle } from "@/components/theme/theme-toggle";

export default function HomePage() {
  return (
    <main className="flex min-h-screen flex-col items-center justify-center gap-8 p-8 text-center">
      <div className="flex items-center gap-4">
        <h1 className="text-3xl font-semibold">Fan Feed Calendar UI</h1>
        <ThemeToggle />
      </div>
      <p className="max-w-xl text-muted-foreground">
        Explore the shared component library that powers the Fan Feed Calendar
        experience. Visit the demo catalog to see the full set of reusable
        building blocks.
      </p>
      <Button asChild variant="primary">
        <Link href="/components">View components catalog</Link>
      </Button>
    </main>
  );
}
