"use client";

import * as React from "react";
import { Mail, Plus, X as CloseIcon } from "lucide-react";

import { Banner } from "@/components/composed/banner";
import { EmptyState } from "@/components/composed/empty-state";
import { MatchCard } from "@/components/composed/match-card";
import { MultiSelect } from "@/components/composed/multi-select";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from "@/components/ui/card";
import { Checkbox } from "@/components/ui/checkbox";
import { Input } from "@/components/ui/input";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Skeleton } from "@/components/ui/skeleton";
import { Switch } from "@/components/ui/switch";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import {
  Toast,
  ToastClose,
  ToastDescription,
  ToastProvider,
  ToastTitle,
  ToastViewport
} from "@/components/ui/toast";
import { Chip } from "@/components/ui/chip";
import { ThemeToggle } from "@/components/theme/theme-toggle";
import { teams } from "@/lib/teams";

const demoMatch = {
  id: "match-123",
  league: "NWSL",
  homeTeam: "OL Reign",
  awayTeam: "San Diego Wave",
  venue: "Lumen Field",
  kickoff: new Date().toISOString(),
  status: "Scheduled" as const,
  broadcast: "Paramount+"
};

export function ComponentsCatalog() {
  const [selectedTeams, setSelectedTeams] = React.useState<string[]>(["sea", "olr"]);
  const [email, setEmail] = React.useState("");
  const [league, setLeague] = React.useState("nws1");
  const [updatesEnabled, setUpdatesEnabled] = React.useState(true);
  const [toastOpen, setToastOpen] = React.useState(false);

  return (
    <ToastProvider swipeDirection="right">
      <div className="mx-auto flex w-full max-w-6xl flex-col gap-12 px-6 py-12">
        <header className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div className="space-y-2">
            <h1 className="text-3xl font-semibold">Component Catalog</h1>
            <p className="max-w-2xl text-muted-foreground">
              Interactive preview of the shared Fan Feed Calendar component kit.
              Use this page during development to perform quick visual QA across
              light and dark themes.
            </p>
          </div>
          <ThemeToggle />
        </header>

        <section className="space-y-6">
          <h2 className="text-xl font-semibold">Buttons</h2>
          <div className="flex flex-wrap gap-3">
            <Button>Primary</Button>
            <Button variant="secondary">Secondary</Button>
            <Button variant="ghost">Ghost</Button>
            <Button variant="destructive">Destructive</Button>
            <Button size="icon" aria-label="Add">
              <Plus className="h-4 w-4" />
            </Button>
          </div>
        </section>

        <section className="space-y-6">
          <h2 className="text-xl font-semibold">Inputs</h2>
          <div className="grid gap-6 md:grid-cols-2">
            <label className="flex flex-col gap-2 text-sm font-medium">
              Email address
              <Input
                type="email"
                placeholder="you@example.com"
                value={email}
                onChange={(event) => setEmail(event.target.value)}
                aria-describedby="email-help"
              />
              <span id="email-help" className="text-xs text-muted-foreground">
                We use this to send you match reminders.
              </span>
            </label>

            <div className="flex flex-col gap-2 text-sm font-medium">
              League
              <Select value={league} onValueChange={setLeague}>
                <SelectTrigger>
                  <SelectValue placeholder="Choose league" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="nws1">NWSL</SelectItem>
                  <SelectItem value="mls">MLS</SelectItem>
                  <SelectItem value="usoc">US Open Cup</SelectItem>
                </SelectContent>
              </Select>
            </div>

            <MultiSelect
              label="Teams"
              options={teams.map((team) => ({
                label: team.label,
                value: team.value,
                group: team.group
              }))}
              value={selectedTeams}
              onChange={setSelectedTeams}
            />

            <div className="flex items-center gap-3">
              <Checkbox id="marketing" defaultChecked />
              <label htmlFor="marketing" className="text-sm leading-none">
                Receive marketing updates
              </label>
            </div>

            <div className="flex items-center gap-3">
              <Switch
                id="notifications"
                checked={updatesEnabled}
                onCheckedChange={(checked) => setUpdatesEnabled(!!checked)}
              />
              <label htmlFor="notifications" className="text-sm leading-none">
                Real-time match alerts
              </label>
            </div>
          </div>
        </section>

        <section className="space-y-6">
          <h2 className="text-xl font-semibold">Chips & Badges</h2>
          <div className="flex flex-wrap items-center gap-3">
            <Chip variant="league">NWSL</Chip>
            <Chip variant="team" leadingIcon={<Mail className="h-3.5 w-3.5" />}>
              OL Reign
            </Chip>
            <Badge>Status: Upcoming</Badge>
            <Badge variant="warning">Live</Badge>
            <Badge variant="success">Final</Badge>
          </div>
        </section>

        <section className="space-y-6">
          <h2 className="text-xl font-semibold">Cards</h2>
          <div className="grid gap-4 md:grid-cols-2">
            <Card>
              <CardHeader>
                <CardTitle>Base Card</CardTitle>
                <CardDescription>
                  Lightweight container for simple information groupings.
                </CardDescription>
              </CardHeader>
              <CardContent className="space-y-3 text-sm text-muted-foreground">
                <p>
                  Use base cards for neutral presentation of schedules,
                  matchups, or saved filters.
                </p>
                <p>
                  They respect theming tokens and provide subtle border
                  treatment for clarity.
                </p>
              </CardContent>
            </Card>
            <Card elevated>
              <CardHeader>
                <CardTitle>Elevated Card</CardTitle>
                <CardDescription>
                  Adds atmospheric depth for featured information.
                </CardDescription>
              </CardHeader>
              <CardContent className="space-y-3 text-sm text-muted-foreground">
                <p>
                  Elevated cards include a soft drop shadow to call attention
                  to promoted content like spotlighted matches.
                </p>
              </CardContent>
              <CardFooter>
                <Button variant="primary" className="ml-auto">
                  Explore feature
                </Button>
              </CardFooter>
            </Card>
          </div>
        </section>

        <section className="space-y-6">
          <h2 className="text-xl font-semibold">Banner & Toast</h2>
          <div className="space-y-4">
            <Banner
              title="Season Pass Upgrade"
              description="Unlock condensed replays and additional match insights when you upgrade to the Fan+ tier."
              variant="upgrade"
              action={<Button size="sm">Upgrade</Button>}
            />
            <div className="flex items-center gap-3">
              <Button onClick={() => setToastOpen(true)}>Trigger toast</Button>
              <span className="text-sm text-muted-foreground">
                Toast respects reduced motion and auto-dismisses after 4 seconds.
              </span>
            </div>
          </div>
        </section>

        <section className="space-y-6">
          <h2 className="text-xl font-semibold">Modal & Drawer</h2>
          <p className="text-sm text-muted-foreground">
            Match cards open into a responsive overlay: drawer on mobile and
            centered modal on larger screens.
          </p>
          <MatchCard match={demoMatch} />
        </section>

        <section className="space-y-6">
          <h2 className="text-xl font-semibold">Tabs</h2>
          <Tabs defaultValue="schedule" className="w-full">
            <TabsList>
              <TabsTrigger value="schedule">Schedule</TabsTrigger>
              <TabsTrigger value="news">News</TabsTrigger>
              <TabsTrigger value="stats">Stats</TabsTrigger>
            </TabsList>
            <TabsContent value="schedule">
              <p>
                Aggregated fixtures from your followed leagues appear here with
                filters to refine by competition.
              </p>
            </TabsContent>
            <TabsContent value="news">
              <p>
                Club headlines, match previews, and podcast episodes curated by
                your interests.
              </p>
            </TabsContent>
            <TabsContent value="stats">
              <p>
                Team standings and head-to-head stats tailored to your
                subscriptions.
              </p>
            </TabsContent>
          </Tabs>
        </section>

        <section className="space-y-6">
          <h2 className="text-xl font-semibold">Skeletons & Empty States</h2>
          <div className="grid gap-6 md:grid-cols-2">
            <div className="space-y-3">
              <Skeleton className="h-10 w-3/4" />
              <Skeleton className="h-4 w-full" />
              <Skeleton className="h-4 w-5/6" />
              <Skeleton className="h-40 w-full rounded-xl" />
            </div>
            <EmptyState
              title="No saved matches yet"
              description="Follow teams and competitions to build your personalized calendar."
              action={{ label: "Browse teams" }}
            />
          </div>
        </section>
      </div>

      <Toast open={toastOpen} onOpenChange={setToastOpen} variant="success" duration={4000}>
        <div className="flex flex-1 flex-col gap-1">
          <ToastTitle>Subscribed!</ToastTitle>
          <ToastDescription>
            You will receive alerts for OL Reign match updates.
          </ToastDescription>
        </div>
        <ToastClose aria-label="Dismiss notification">
          <CloseIcon className="h-4 w-4" aria-hidden="true" />
        </ToastClose>
      </Toast>
      <ToastViewport />
    </ToastProvider>
  );
}
