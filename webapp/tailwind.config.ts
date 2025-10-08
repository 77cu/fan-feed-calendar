import type { Config } from "tailwindcss";
import colors from "tailwindcss/colors";

const config: Config = {
  darkMode: ["class"],
  content: [
    "./app/**/*.{ts,tsx}",
    "./components/**/*.{ts,tsx}",
    "./src/**/*.{ts,tsx}"
  ],
  theme: {
    extend: {
      colors: {
        border: "hsl(var(--border))",
        input: "hsl(var(--input))",
        ring: "hsl(var(--ring))",
        background: "hsl(var(--background))",
        foreground: "hsl(var(--foreground))",
        primary: {
          DEFAULT: "hsl(var(--primary))",
          foreground: "hsl(var(--primary-foreground))"
        },
        secondary: {
          DEFAULT: "hsl(var(--secondary))",
          foreground: "hsl(var(--secondary-foreground))"
        },
        destructive: {
          DEFAULT: "hsl(var(--destructive))",
          foreground: "hsl(var(--destructive-foreground))"
        },
        muted: {
          DEFAULT: "hsl(var(--muted))",
          foreground: "hsl(var(--muted-foreground))"
        },
        accent: {
          DEFAULT: "hsl(var(--accent))",
          foreground: "hsl(var(--accent-foreground))"
        },
        popover: {
          DEFAULT: "hsl(var(--popover))",
          foreground: "hsl(var(--popover-foreground))"
        },
        card: {
          DEFAULT: "hsl(var(--card))",
          foreground: "hsl(var(--card-foreground))"
        },
        success: colors.emerald[500],
        info: colors.sky[500],
        warning: colors.amber[500]
      },
      borderRadius: {
        lg: "var(--radius-lg)",
        md: "var(--radius-md)",
        sm: "var(--radius-sm)"
      },
      fontFamily: {
        sans: ["var(--font-sans)", "system-ui", "sans-serif"]
      },
      keyframes: {
        "toast-slide-in": {
          from: { transform: "translateX(calc(100% + 1rem))" },
          to: { transform: "translateX(0)" }
        },
        "toast-hide": {
          from: { opacity: "1" },
          to: { opacity: "0" }
        }
      },
      animation: {
        "toast-in": "toast-slide-in 150ms ease-out",
        "toast-out": "toast-hide 100ms ease-in forwards"
      }
    }
  },
  plugins: [require("tailwindcss-animate")]
};

export default config;
