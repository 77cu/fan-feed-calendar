import { Calendar, Clock, MapPin, Tv } from "lucide-react";
import * as React from "react";

import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger
} from "@/components/ui/dialog";
import { Chip } from "@/components/ui/chip";

export type Match = {
  id: string;
  league: string;
  homeTeam: string;
  awayTeam: string;
  venue: string;
  kickoff: string;
  status: "Scheduled" | "Live" | "Final";
  broadcast?: string;
};

export interface MatchCardProps {
  match: Match;
}

export function MatchCard({ match }: MatchCardProps) {
  const kickoffDate = new Date(match.kickoff);
  const kickoffTime = kickoffDate.toLocaleTimeString([], {
    hour: "2-digit",
    minute: "2-digit"
  });
  const kickoffDay = kickoffDate.toLocaleDateString([], {
    weekday: "short",
    month: "short",
    day: "numeric"
  });

  return (
    <Dialog>
      <DialogTrigger asChild>
        <Card className="cursor-pointer transition-transform hover:-translate-y-0.5 hover:shadow-lg">
          <CardHeader className="flex flex-row items-center justify-between pb-0">
            <Chip variant="league">{match.league}</Chip>
            <Badge
              variant={
                match.status === "Live"
                  ? "warning"
                  : match.status === "Final"
                    ? "success"
                    : "neutral"
              }
            >
              {match.status}
            </Badge>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <CardTitle className="text-2xl font-semibold">
                {match.homeTeam}
                <span className="mx-2 text-sm font-medium text-muted-foreground">vs</span>
                {match.awayTeam}
              </CardTitle>
              <CardDescription className="flex flex-wrap items-center gap-3 text-sm">
                <span className="inline-flex items-center gap-1">
                  <Calendar className="h-4 w-4" aria-hidden="true" />
                  {kickoffDay}
                </span>
                <span className="inline-flex items-center gap-1">
                  <Clock className="h-4 w-4" aria-hidden="true" />
                  {kickoffTime}
                </span>
                <span className="inline-flex items-center gap-1">
                  <MapPin className="h-4 w-4" aria-hidden="true" />
                  {match.venue}
                </span>
                {match.broadcast ? (
                  <span className="inline-flex items-center gap-1">
                    <Tv className="h-4 w-4" aria-hidden="true" />
                    {match.broadcast}
                  </span>
                ) : null}
              </CardDescription>
            </div>
            <div className="flex items-center justify-between text-sm text-muted-foreground">
              <span>Tap for more match details</span>
              <span aria-hidden="true">↗</span>
            </div>
          </CardContent>
        </Card>
      </DialogTrigger>
      <DialogContent
        variant="drawer"
        className="sm:left-1/2 sm:top-1/2 sm:bottom-auto sm:right-auto sm:inset-auto sm:max-w-xl sm:-translate-x-1/2 sm:-translate-y-1/2 sm:rounded-2xl"
      >
        <DialogHeader>
          <DialogTitle>
            {match.homeTeam} vs {match.awayTeam}
          </DialogTitle>
          <DialogDescription>
            {match.league} • {kickoffDay} at {kickoffTime}
          </DialogDescription>
        </DialogHeader>
        <div className="space-y-4 text-sm">
          <div className="flex items-center gap-3 rounded-lg bg-muted/40 p-3">
            <Calendar className="h-4 w-4" aria-hidden="true" />
            <div className="flex flex-col">
              <span className="font-medium">Date</span>
              <span className="text-muted-foreground">{kickoffDay}</span>
            </div>
          </div>
          <div className="flex items-center gap-3 rounded-lg bg-muted/40 p-3">
            <Clock className="h-4 w-4" aria-hidden="true" />
            <div className="flex flex-col">
              <span className="font-medium">Kick-off</span>
              <span className="text-muted-foreground">{kickoffTime}</span>
            </div>
          </div>
          <div className="flex items-center gap-3 rounded-lg bg-muted/40 p-3">
            <MapPin className="h-4 w-4" aria-hidden="true" />
            <div className="flex flex-col">
              <span className="font-medium">Venue</span>
              <span className="text-muted-foreground">{match.venue}</span>
            </div>
          </div>
          {match.broadcast ? (
            <div className="flex items-center gap-3 rounded-lg bg-muted/40 p-3">
              <Tv className="h-4 w-4" aria-hidden="true" />
              <div className="flex flex-col">
                <span className="font-medium">Watch on</span>
                <span className="text-muted-foreground">{match.broadcast}</span>
              </div>
            </div>
          ) : null}
        </div>
        <DialogFooter>
          <Button variant="primary">Add to calendar</Button>
          <Button variant="secondary">Share match</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
