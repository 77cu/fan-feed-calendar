"use client";

import * as React from "react";
import { ThemeProvider as NextThemesProvider } from "next-themes";

export type ThemeMode = "light" | "dark" | "system";

type ThemeProviderProps = {
  children: React.ReactNode;
  attribute?: string;
  defaultTheme?: ThemeMode;
  enableSystem?: boolean;
};

export function ThemeProvider({
  children,
  attribute = "data-theme",
  defaultTheme = "system",
  enableSystem = true
}: ThemeProviderProps) {
  return (
    <NextThemesProvider
      attribute={attribute}
      defaultTheme={defaultTheme}
      enableSystem={enableSystem}
      disableTransitionOnChange
    >
      {children}
    </NextThemesProvider>
  );
}
