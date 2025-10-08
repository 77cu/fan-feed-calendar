(function () {
  const THEME_KEY = 'fanfeed_theme';
  const root = document.documentElement;
  const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

  function resolveStorage() {
    try {
      const testKey = '__fanfeed-theme__test__';
      window.localStorage.setItem(testKey, '1');
      window.localStorage.removeItem(testKey);
      return window.localStorage;
    } catch (error) {
      return null;
    }
  }

  const storage = resolveStorage();

  function readPreference() {
    const stored = storage ? storage.getItem(THEME_KEY) : null;
    return stored === 'light' || stored === 'dark' || stored === 'auto' ? stored : 'auto';
  }

  function resolveTheme(preference) {
    if (preference === 'light' || preference === 'dark') {
      return preference;
    }
    return mediaQuery.matches ? 'dark' : 'light';
  }

  function applyPreference(preference) {
    const effectiveTheme = resolveTheme(preference);
    root.setAttribute('data-theme', effectiveTheme);
    root.style.colorScheme = effectiveTheme === 'dark' ? 'dark' : 'light';
    return effectiveTheme;
  }

  function dispatchThemeChange(preference, effectiveTheme) {
    if (typeof window.CustomEvent === 'function') {
      window.dispatchEvent(
        new CustomEvent('fanfeed-theme-change', {
          detail: { preference, effectiveTheme }
        })
      );
    }
  }

  function setPreference(nextPreference) {
    if (storage) {
      storage.setItem(THEME_KEY, nextPreference);
    }
    const effective = applyPreference(nextPreference);
    dispatchThemeChange(nextPreference, effective);
    return effective;
  }

  function onMediaChange() {
    const currentPreference = readPreference();
    if (currentPreference === 'auto') {
      const effective = applyPreference(currentPreference);
      dispatchThemeChange(currentPreference, effective);
    }
  }

  const initialPreference = readPreference();
  applyPreference(initialPreference);

  if (typeof mediaQuery.addEventListener === 'function') {
    mediaQuery.addEventListener('change', onMediaChange);
  } else if (typeof mediaQuery.addListener === 'function') {
    mediaQuery.addListener(onMediaChange);
  }

  window.fanfeedTheme = {
    getPreference: readPreference,
    getEffectiveTheme: function () {
      return resolveTheme(readPreference());
    },
    setPreference: setPreference,
    applyPreference: applyPreference
  };
})();
