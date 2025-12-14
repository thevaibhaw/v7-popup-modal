# V7 Popup Modal

V7 Popup Modal displays a lightweight, accessible, and responsive modal on your site's homepage. It is fully configurable from the WordPress admin and supports a timed display window. It is a small, production-ready WordPress plugin that displays a responsive, accessible popup modal on your site's homepage. It provides admin-configurable content, an optional timed display window, scoped custom CSS, and an integrity check to detect file tampering.

## Installation (end users)

1. Download the repository ZIP from GitHub or use the WordPress plugin installer.
2. Upload the `v7-popup-modal` folder into `/wp-content/plugins/` or install via **Plugins → Add New → Upload Plugin**.
3. Activate the plugin under **Plugins → Installed Plugins**.
4. Configure under **Settings → V7 Popup Modal**.

## Settings (Admin)

- **Icon**: Upload a small image to show in the modal.
- **Popup Title**: Title text (plain text).
- **Popup Content**: HTML-enabled content (sanitized) shown inside the modal.
- **Word Limit**: Maximum words allowed in content; overly long content will be trimmed.
- **Enable Timer**: Toggle timed display.
- **Timer Duration (hours)**: Number of hours the modal remains active after saving (server time basis).
- **Background Color**: Background color for the modal (color picker).
- **Custom Modal CSS**: Scoped CSS to style only the modal. Rules are automatically prefixed with `#v7-popup-modal` to avoid affecting other site elements.

Notes:

- When you enable the timer and save, the plugin records a server-side start timestamp. The modal will appear for the configured duration from that save time.
- If you disable the timer, the modal will display normally (no auto-close).

## Frontend behavior

- Modal will appear on the site's front page (home) only.
- If timer is enabled, the modal will only be rendered while the server-calculated remaining time is positive; otherwise it won't appear.
- The client-side countdown uses server-provided remaining seconds to avoid clock skew issues.

## Security & Best Practices

- Inputs are sanitized (URLs, color hex, text). Content is sanitized using `wp_kses_post()` where appropriate.
- Custom CSS is stripped of HTML tags and scoped to avoid site-wide CSS leakage — however, do not paste untrusted CSS.
- The plugin stores SHA1 hashes on activation and will mark itself as tampered and deactivate if those hashes change.

## Developer Notes

- To change how time is formatted in admin previews or frontend, the plugin uses `wp_date()` (respects WordPress timezone). If `wp_date()` is not available, it falls back to PHP `date()`.
- Assets are enqueued only on the front page (public) and on plugin settings pages (admin) to minimize overhead.

## Troubleshooting

- If the modal shows briefly and disappears: check **Settings → V7 Popup Modal** whether the timer is enabled and the `Starts/Expires` values correctly reflect your desired window.
- If custom CSS doesn't appear: ensure it's valid CSS and saved; the admin preview applies changes live via a small preview script.

## License

GPL v2 or later.

## Author

Author Name:- Vaibhaw Kumar <br/>
Website:- https://vaibhawkumarparashar.in <br/>
Email:- imvaibhaw@gmail.com
