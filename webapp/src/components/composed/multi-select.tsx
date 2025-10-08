"use client";

import * as React from "react";
import { ChevronsUpDown, Filter, X } from "lucide-react";

import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover";
import { cn } from "@/lib/utils";

type Option = {
  label: string;
  value: string;
  group?: string;
};

export interface MultiSelectProps {
  options: Option[];
  value: string[];
  onChange: (value: string[]) => void;
  placeholder?: string;
  label?: string;
  className?: string;
}

export function MultiSelect({
  options,
  value,
  onChange,
  placeholder = "Select teams",
  label,
  className
}: MultiSelectProps) {
  const [open, setOpen] = React.useState(false);
  const selected = React.useMemo(
    () => options.filter((option) => value.includes(option.value)),
    [options, value]
  );

  const toggleValue = React.useCallback(
    (newValue: string) => {
      onChange(
        value.includes(newValue)
          ? value.filter((v) => v !== newValue)
          : [...value, newValue]
      );
    },
    [onChange, value]
  );

  const clearAll = React.useCallback(() => onChange([]), [onChange]);
  const labelId = label
    ? `${label.toLowerCase().replace(/[^a-z0-9]+/g, "-")}-label`
    : undefined;

  return (
    <div className={cn("flex w-full flex-col gap-1", className)}>
      {label ? (
        <span className="text-sm font-medium text-muted-foreground" id={labelId}>
          {label}
        </span>
      ) : null}
      <Popover open={open} onOpenChange={setOpen}>
        <PopoverTrigger asChild>
          <Button
            type="button"
            variant="secondary"
            className="h-10 justify-between"
            aria-haspopup="listbox"
            aria-expanded={open}
            aria-labelledby={labelId}
          >
            <span className="flex items-center gap-2 truncate">
              <Filter className="h-4 w-4 opacity-80" aria-hidden="true" />
              {selected.length > 0
                ? `${selected.length} team${selected.length === 1 ? "" : "s"} selected`
                : placeholder}
            </span>
            <ChevronsUpDown className="h-4 w-4 opacity-60" aria-hidden="true" />
          </Button>
        </PopoverTrigger>
        <PopoverContent className="w-72 p-0" align="start">
          <div className="flex items-center justify-between border-b border-border px-3 py-2 text-xs uppercase tracking-wide text-muted-foreground">
            <span>Teams</span>
            <Button
              type="button"
              variant="ghost"
              size="sm"
              className="h-8 px-2 text-xs"
              onClick={clearAll}
              disabled={selected.length === 0}
            >
              Clear
            </Button>
          </div>
          <ul className="max-h-64 overflow-y-auto p-2" role="listbox" aria-multiselectable>
            {options.map((option) => {
              const isSelected = value.includes(option.value);
              return (
                <li key={option.value}>
                  <div
                    role="option"
                    aria-selected={isSelected}
                    tabIndex={0}
                    onClick={() => toggleValue(option.value)}
                    onKeyDown={(event) => {
                      if (event.key === "Enter" || event.key === " ") {
                        event.preventDefault();
                        toggleValue(option.value);
                      }
                    }}
                    className={cn(
                      "flex w-full cursor-pointer items-center justify-between gap-3 rounded-md px-2 py-2 text-sm transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-ring",
                      isSelected ? "bg-primary/10 text-primary" : "hover:bg-muted/50"
                    )}
                  >
                    <span className="flex flex-col items-start">
                      <span>{option.label}</span>
                      {option.group ? (
                        <span className="text-xs text-muted-foreground">{option.group}</span>
                      ) : null}
                    </span>
                    <Checkbox
                      checked={isSelected}
                      className="pointer-events-none"
                      aria-hidden="true"
                    />
                  </div>
                </li>
              );
            })}
            {options.length === 0 ? (
              <li className="p-3 text-sm text-muted-foreground">No teams found.</li>
            ) : null}
          </ul>
        </PopoverContent>
      </Popover>
      {selected.length > 0 ? (
        <div className="flex flex-wrap gap-2 pt-1">
          {selected.map((option) => (
            <span
              key={option.value}
              className="inline-flex items-center gap-2 rounded-full bg-primary/10 px-3 py-1 text-xs font-medium text-primary"
            >
              {option.label}
              <button
                type="button"
                onClick={() => toggleValue(option.value)}
                className="rounded-full p-0.5 hover:bg-primary/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                aria-label={`Remove ${option.label}`}
              >
                <X className="h-3.5 w-3.5" aria-hidden="true" />
              </button>
            </span>
          ))}
        </div>
      ) : null}
    </div>
  );
}
