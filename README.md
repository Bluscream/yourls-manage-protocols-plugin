# Manage Protocols Plugin for YOURLS

A simple, powerful YOURLS plugin that allows you to manage whitelisted redirect URL protocols (schemes) directly from your administration dashboard.

## Features

- **Toggle Built-in Protocols**: Easily disable standard schemes like `mailto:`, `ftp://`, or `sms:` if you do not want to allow them on your server.
- **Add Custom Schemes**: Add custom protocols such as `vrchat://`, `steam://`, or `magnet:` so they can be shortened.
- **Type Badges**: View clear labels showing whether a protocol is `Built-in` or `Custom`.
- **Dynamic Bypass Option**: Optionally allow *all* protocols dynamically by extracting and appending any incoming URL scheme on the fly.
- **One-Click Reset**: Restores all protocols back to the factory defaults instantly.

## Screenshots

<details>
<summary>Screenshot(s)</summary>

| Manage Allowed Protocols |
| :---: |
| ![Manage Allowed Protocols Settings](https://i.imgur.com/2jlUEUO.png) |

</details>

## Installation

1. Copy or move the `manage-protocols` directory into your YOURLS `user/plugins/` directory.
2. Go to your YOURLS Administration Panel and navigate to **Plugins**.
3. Locate **Manage Protocols** and click **Activate**.

## Settings & Configuration

Navigate to **Manage Protocols** in the admin sidebar navigation menu to configure the following settings:

| Setting Option | Type | Default Value | Description |
| :--- | :--- | :--- | :--- |
| `Allow Any Protocol (Bypass)` | Boolean | `false` | When enabled, dynamically detects and whitelists any incoming URL scheme on the fly, bypassing restrictions. |
| `Add New Protocol` | String | `""` | Enter a custom protocol (e.g. `steam://`, `ts3server://`) to add it to the managed whitelist. |
| `Toggle Protocol Status` | Action | `Active` | Enable or disable individual protocols to allow/disallow them. |
| `Delete Custom Protocol` | Action | `N/A` | Permanently remove custom added protocols from the whitelist. |
| `Reset to Defaults` | Button | `N/A` | Resets all protocols back to the standard YOURLS default scheme whitelist. |

## Authors

- **Bluscream**
- **Antigravity.AI**

## Other Plugins

Check out our other YOURLS plugins:
- [Prune Inactive Links](../prune-inactive-links): Automatically deletes old links that receive no clicks.
- [Public Shortener Front Page](../public-shortener): A premium, Turnstile-secured public URL shortener.
- [Webhook Notification](../webhook): Sends a JSON POST webhook notification on new link creation.
- [Modern Clicks Log Viewer](../modern-log-viewer): Responsive table of click logs with GeoLite2 geolocation.

## AI Disclaimer

This plugin was created and is maintained with the assistance of Antigravity, an agentic AI coding assistant by Google DeepMind.
