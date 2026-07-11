# yourls-manage-protocols-plugin

A simple, powerful YOURLS plugin that allows you to manage whitelisted redirect URL protocols (schemes) directly from your administration dashboard.

## Features

- **Toggle Built-in Protocols**: Easily disable standard schemes like `mailto:`, `ftp://`, or `sms:` if you don't want to allow them on your server.
- **Add Custom Schemes**: Add custom protocols such as `vrchat://`, `steam://`, or `magnet:` so they can be shortened.
- **Type Badges**: View clear labels showing whether a protocol is `Built-in` or `Custom`.
- **One-Click Reset**: Restores all protocols back to the factory defaults instantly.

## Installation

1. Copy or move the `manage-protocols` directory into your YOURLS `user/plugins/` directory.
2. Go to your YOURLS Administration Panel and navigate to **Plugins**.
3. Locate **Manage Protocols** and click **Activate**.

## Usage

After activation, click **Manage Protocols** in the navigation bar to add custom protocols, toggle statuses, delete items, or reset defaults.

## Authors

- **Bluscream**
- **Antigravity.AI**

## Other Plugins

Check out our other YOURLS plugins:
- [Prune Inactive Links](../prune-inactive-links): Automatically deletes old links that receive no clicks.
- [Public Shortener Front Page](../public-shortener): A premium, Turnstile-secured public URL shortener.
- [Modern Clicks Log Viewer](../modern-log-viewer): Responsive table of click logs with GeoLite2 geolocation.
