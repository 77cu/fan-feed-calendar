import { type ClassValue, clsx } from "clsx";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]): string {
  return twMerge(clsx(inputs));
}

export function focusRing({
  offset = 2,
  width = 2
}: {
  offset?: number;
  width?: number;
} = {}): string {
  return `focus-visible:outline-none focus-visible:ring-${width} focus-visible:ring-ring focus-visible:ring-offset-${offset} focus-visible:ring-offset-background`;
}
