import { Inbox } from "lucide-react";
import * as React from "react";

import { Button } from "@/components/ui/button";
import { cn } from "@/lib/utils";

export interface EmptyStateProps extends React.HTMLAttributes<HTMLDivElement> {
  icon?: React.ReactNode;
  title: string;
  description?: string;
  action?: {
    label: string;
    onClick?: () => void;
  };
}

export function EmptyState({
  icon,
  title,
  description,
  action,
  className,
  ...props
}: EmptyStateProps) {
  const Icon = icon ? null : Inbox;
  return (
    <div
      className={cn(
        "flex w-full flex-col items-center justify-center gap-3 rounded-xl border border-dashed border-border bg-muted/10 p-10 text-center",
        className
      )}
      {...props}
    >
      {icon ? icon : <Icon className="h-10 w-10 text-muted-foreground" aria-hidden="true" />}
      <div className="space-y-1">
        <h3 className="text-lg font-semibold">{title}</h3>
        {description ? (
          <p className="text-sm text-muted-foreground">{description}</p>
        ) : null}
      </div>
      {action ? (
        <Button onClick={action.onClick} variant="secondary">
          {action.label}
        </Button>
      ) : null}
    </div>
  );
}
