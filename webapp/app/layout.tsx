import "./globals.css";
import type { Metadata } from "next";
import { Inter } from "next/font/google";
import * as React from "react";

import { ThemeProvider } from "@/components/theme/theme-provider";

const inter = Inter({ subsets: ["latin"] });

export const metadata: Metadata = {
  title: "Fan Feed Calendar Components",
  description: "UI component catalog for the Fan Feed Calendar web app"
};

export default function RootLayout({
  children
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en" suppressHydrationWarning>
      <body className={`${inter.className} min-h-screen bg-background text-foreground`}>
        <ThemeProvider attribute="data-theme" defaultTheme="system" enableSystem>
          {children}
        </ThemeProvider>
      </body>
    </html>
  );
}
