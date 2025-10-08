import { cva, type VariantProps } from "class-variance-authority";
import { Info, ShieldAlert, ShieldCheck, Sparkles } from "lucide-react";
import * as React from "react";

import { cn } from "@/lib/utils";

const iconMap = {
  info: Info,
  success: ShieldCheck,
  warning: ShieldAlert,
  upgrade: Sparkles
} as const;

type BannerVariant = keyof typeof iconMap;

const bannerVariants = cva(
  "relative flex w-full gap-3 rounded-lg border px-4 py-3 text-sm",
  {
    variants: {
      variant: {
        info: "border-info/30 bg-info/10 text-info",
        success: "border-success/30 bg-success/10 text-success",
        warning: "border-warning/30 bg-warning/10 text-warning",
        upgrade: "border-primary/30 bg-primary/10 text-primary"
      }
    },
    defaultVariants: {
      variant: "info"
    }
  }
);

export interface BannerProps
  extends React.HTMLAttributes<HTMLDivElement>,
    VariantProps<typeof bannerVariants> {
  title: string;
  description?: string;
  action?: React.ReactNode;
}

export const Banner = React.forwardRef<HTMLDivElement, BannerProps>(
  ({ title, description, action, className, variant, ...props }, ref) => {
    const Icon = iconMap[(variant as BannerVariant) ?? "info"];
    return (
      <div
        ref={ref}
        className={cn(bannerVariants({ variant }), className)}
        role="status"
        {...props}
      >
        <Icon className="mt-0.5 h-5 w-5 flex-none" aria-hidden="true" />
        <div className="flex flex-1 flex-col gap-1">
          <span className="font-semibold">{title}</span>
          {description ? (
            <span className="text-sm text-muted-foreground">
              {description}
            </span>
          ) : null}
        </div>
        {action ? <div className="flex items-center gap-2">{action}</div> : null}
      </div>
    );
  }
);
Banner.displayName = "Banner";
