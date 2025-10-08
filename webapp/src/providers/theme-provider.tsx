import React, { createContext, useCallback, useContext, useEffect, useMemo, useState } from 'react';

type Theme = 'light' | 'dark';
type ThemeSetting = Theme | 'system';

interface ThemeContextValue {
  theme: ThemeSetting;
  resolvedTheme: Theme;
  toggleTheme: () => void;
  setTheme: (theme: ThemeSetting) => void;
}

const STORAGE_KEY = 'fan-feed-calendar-theme';

const ThemeContext = createContext<ThemeContextValue | undefined>(undefined);

const prefersDark = () =>
  typeof window !== 'undefined' &&
  window.matchMedia &&
  window.matchMedia('(prefers-color-scheme: dark)').matches;

const getInitialTheme = (): ThemeSetting => {
  if (typeof window === 'undefined') {
    return 'system';
  }

  const stored = window.localStorage.getItem(STORAGE_KEY) as ThemeSetting | null;
  if (stored === 'light' || stored === 'dark') {
    return stored;
  }

  return 'system';
};

export const ThemeProvider: React.FC<React.PropsWithChildren> = ({ children }) => {
  const [theme, setThemeState] = useState<ThemeSetting>(getInitialTheme);
  const [systemTheme, setSystemTheme] = useState<Theme>(prefersDark() ? 'dark' : 'light');

  useEffect(() => {
    if (typeof window !== 'undefined' && window.matchMedia) {
      const media = window.matchMedia('(prefers-color-scheme: dark)');
      const listener = (event: MediaQueryListEvent) => {
        setSystemTheme(event.matches ? 'dark' : 'light');
      };
      media.addEventListener('change', listener);
      return () => media.removeEventListener('change', listener);
    }
  }, []);

  const resolvedTheme: Theme = theme === 'system' ? systemTheme : theme;

  useEffect(() => {
    if (typeof document === 'undefined') {
      return;
    }

    const root = document.documentElement;
    root.setAttribute('data-theme', resolvedTheme);
    root.style.colorScheme = resolvedTheme;

    if (typeof window !== 'undefined') {
      if (theme === 'system') {
        window.localStorage.removeItem(STORAGE_KEY);
      } else {
        window.localStorage.setItem(STORAGE_KEY, theme);
      }
    }
  }, [resolvedTheme, theme]);

  const setTheme = useCallback((value: ThemeSetting) => {
    setThemeState(value);
  }, []);

  const toggleTheme = useCallback(() => {
    setThemeState((current) => {
      const next = current === 'system' ? (prefersDark() ? 'dark' : 'light') : current;
      return next === 'dark' ? 'light' : 'dark';
    });
  }, []);

  const contextValue = useMemo<ThemeContextValue>(
    () => ({
      theme,
      resolvedTheme,
      toggleTheme,
      setTheme,
    }),
    [theme, resolvedTheme, toggleTheme, setTheme]
  );

  return <ThemeContext.Provider value={contextValue}>{children}</ThemeContext.Provider>;
};

export const useTheme = (): ThemeContextValue => {
  const context = useContext(ThemeContext);
  if (!context) {
    throw new Error('useTheme must be used within a ThemeProvider');
  }
  return context;
};
