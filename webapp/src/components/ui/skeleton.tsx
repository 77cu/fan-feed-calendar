import * as React from "react";

import { cn } from "@/lib/utils";

export interface SkeletonProps extends React.HTMLAttributes<HTMLDivElement> {}

export function Skeleton({ className, ...props }: SkeletonProps) {
  return (
    <div
      className={cn(
        "animate-pulse rounded-md bg-muted/60 motion-reduce:animate-none motion-reduce:bg-muted",
        className
      )}
      {...props}
    />
  );
}
