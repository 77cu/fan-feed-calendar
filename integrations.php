<?php
declare(strict_types=1);
$placeholderUrl = 'https://fanfeed.app/integrations/your-personal-feed.ics';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Fan Feed Calendar – Integrations</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
:root {
  color-scheme: light dark;
  --bg: #f5f5f7;
  --surface: #ffffff;
  --surface-muted: #f8fafc;
  --border: #d0d5dd;
  --text-primary: #111827;
  --text-muted: #667085;
  --accent: #2563eb;
  --accent-text: #ffffff;
  --warn-bg: #fff7e6;
  --warn-border: #ffd591;
  --note-bg: #eff8ff;
  --note-border: #b2ddff;
}

@media (prefers-color-scheme: dark) {
  :root {
    --bg: #0f172a;
    --surface: #1e293b;
    --surface-muted: #111827;
    --border: #334155;
    --text-primary: #f8fafc;
    --text-muted: #cbd5f5;
    --accent: #60a5fa;
    --accent-text: #0f172a;
    --warn-bg: rgba(249, 115, 22, 0.18);
    --warn-border: rgba(249, 115, 22, 0.6);
    --note-bg: rgba(96, 165, 250, 0.2);
    --note-border: rgba(96, 165, 250, 0.6);
  }
}

* { box-sizing: border-box; }
a {
  color: var(--accent);
}

a:hover,
a:focus {
  text-decoration: underline;
}
body {
  margin: 0;
  font-family: system-ui, -apple-system, Segoe UI, Arial, sans-serif;
  background: var(--bg);
  color: var(--text-primary);
  line-height: 1.5;
}

.site-header {
  background: var(--surface);
  border-bottom: 1px solid var(--border);
}

.site-header-inner {
  max-width: 960px;
  margin: 0 auto;
  padding: 20px;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
}

.site-title {
  font-weight: 600;
  font-size: 1.1rem;
}

.site-nav {
  display: flex;
  gap: 12px;
}

.site-nav a {
  color: var(--text-muted);
  text-decoration: none;
  font-weight: 500;
  padding: 6px 10px;
  border-radius: 8px;
}

.site-nav a:hover,
.site-nav a:focus {
  color: var(--accent);
  background: var(--surface-muted);
  outline: none;
  text-decoration: none;
}

.site-nav a[aria-current="page"] {
  color: var(--accent);
  background: var(--surface-muted);
}

main {
  max-width: 960px;
  margin: 0 auto;
  padding: 32px 20px 64px;
}

h1 {
  margin: 0 0 12px;
  font-size: 2rem;
}

p {
  margin: 0 0 12px;
}

.integration-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 20px;
  margin-top: 24px;
}

.integration-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 18px;
  padding: 24px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  min-height: 280px;
}

.integration-card h2 {
  margin: 0;
  font-size: 1.2rem;
}

.status-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
}

.status-pill {
  padding: 4px 12px;
  border-radius: 999px;
  background: var(--surface-muted);
  color: var(--text-muted);
  font-size: 0.85rem;
  font-weight: 600;
}

.help-text {
  color: var(--text-muted);
  font-size: 0.95rem;
}

.integration-card .help-text {
  margin-top: auto;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: var(--accent);
  color: var(--accent-text);
  border-radius: 10px;
  padding: 10px 16px;
  text-decoration: none;
  border: 0;
  cursor: pointer;
  font-weight: 600;
  transition: transform 0.12s ease, opacity 0.12s ease;
}

.btn:hover,
.btn:focus {
  opacity: 0.9;
  transform: translateY(-1px);
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.btn-secondary {
  background: transparent;
  color: var(--accent);
  border: 1px solid var(--accent);
}

.url-row {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.url-row input {
  flex: 1 1 200px;
  padding: 10px 12px;
  border: 1px solid var(--border);
  border-radius: 10px;
  background: var(--surface-muted);
  color: inherit;
}

.url-row input:focus {
  outline: 2px solid var(--accent);
  outline-offset: 2px;
}

footer {
  margin-top: 48px;
  color: var(--text-muted);
  font-size: 0.9rem;
}
</style>
</head>
<body>
<header class="site-header">
  <div class="site-header-inner">
    <div class="site-title">Fan Feed Calendar</div>
    <nav class="site-nav">
      <a href="index.php">Home</a>
      <a href="integrations.php" aria-current="page">Integrations</a>
    </nav>
  </div>
</header>
<main>
  <h1>Integrations</h1>
  <p class="help-text">Connect Fan Feed to your favorite calendar apps. Automatic sync is coming soon—connect now to be ready when it launches.</p>

  <div class="integration-grid">
    <section class="integration-card">
      <div class="status-row">
        <h2>Google Calendar</h2>
        <span class="status-pill">Disconnected</span>
      </div>
      <p>Bring fixtures into your Google Calendar automatically.</p>
      <button type="button" class="btn" data-provider="google">Connect Google</button>
      <p class="help-text">We’ll walk you through the Google sign-in flow once integrations are live.</p>
    </section>

    <section class="integration-card">
      <div class="status-row">
        <h2>Microsoft 365</h2>
        <span class="status-pill">Disconnected</span>
      </div>
      <p>Sync match schedules with Outlook and Microsoft 365 calendars.</p>
      <button type="button" class="btn" data-provider="microsoft-365">Connect Microsoft 365</button>
      <p class="help-text">Connection opens Microsoft secure login. We’ll add automation soon.</p>
    </section>

    <section class="integration-card">
      <div class="status-row">
        <h2>Personal ICS Feed</h2>
        <span class="status-pill">Disconnected</span>
      </div>
      <p>Subscribe to a live-updating ICS feed in any calendar app.</p>
      <div class="url-row">
        <input id="ics-url" type="text" readonly value="<?= htmlspecialchars($placeholderUrl) ?>">
        <button id="ics-copy" type="button" class="btn btn-secondary">Copy</button>
      </div>
      <p class="help-text">Paste this URL into Google Calendar, Apple Calendar, or any app that supports ICS feeds.</p>
    </section>
  </div>

  <footer>
    Need another integration? <a href="mailto:hello@fanfeed.app">Let us know</a> which calendar you use most.
  </footer>
</main>
<script>
window.dataLayer = window.dataLayer || [];

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('[data-provider]').forEach(function (button) {
    button.addEventListener('click', function () {
      var provider = button.getAttribute('data-provider');
      window.dataLayer.push({ event: 'integration_connect_clicked', provider: provider });
    });
  });

  var copyButton = document.getElementById('ics-copy');
  var urlField = document.getElementById('ics-url');
  if (copyButton && urlField) {
    var originalLabel = copyButton.textContent;
    copyButton.addEventListener('click', function () {
      window.dataLayer.push({ event: 'ics_link_copied' });
      var setFeedback = function (label, disable) {
        copyButton.textContent = label;
        if (disable) {
          copyButton.disabled = true;
        }
        setTimeout(function () {
          copyButton.textContent = originalLabel;
          copyButton.disabled = false;
        }, 2000);
      };

      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(urlField.value).then(function () {
          setFeedback('Copied!', true);
        }).catch(function () {
          setFeedback('Link copied', false);
        });
      } else {
        urlField.focus();
        urlField.select();
        try {
          document.execCommand('copy');
          setFeedback('Copied!', true);
        } catch (err) {
          setFeedback('Link ready', false);
        }
        urlField.setSelectionRange(urlField.value.length, urlField.value.length);
      }
    });
  }
});
</script>
</body>
</html>
