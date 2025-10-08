import { cva, type VariantProps } from "class-variance-authority";
import * as React from "react";

import { cn } from "@/lib/utils";

const chipVariants = cva(
  "inline-flex items-center gap-1 rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide",
  {
    variants: {
      variant: {
        league: "border-primary/30 bg-primary/10 text-primary",
        team: "border-secondary/40 bg-secondary text-secondary-foreground"
      }
    },
    defaultVariants: {
      variant: "league"
    }
  }
);

export interface ChipProps
  extends React.HTMLAttributes<HTMLSpanElement>,
    VariantProps<typeof chipVariants> {
  leadingIcon?: React.ReactNode;
}

export const Chip = React.forwardRef<HTMLSpanElement, ChipProps>(
  ({ className, variant, leadingIcon, children, ...props }, ref) => {
    return (
      <span
        ref={ref}
        className={cn(chipVariants({ variant }), className)}
        {...props}
      >
        {leadingIcon}
        {children}
      </span>
    );
  }
);
Chip.displayName = "Chip";
