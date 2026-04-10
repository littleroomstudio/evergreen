# Evergreen

A WordPress plugin that automatically enables auto-updates for plugins when they're installed or activated. Decisions, not options.

## The Problem

Every time you install a plugin, WordPress makes you manually flip the auto-update switch. One plugin? Fine. Ten plugins across a dozen client sites? That's tedious busywork.

Evergreen fixes this by doing it for you. Install a plugin, it gets auto-updates. Activate a plugin, it gets auto-updates. One less thing to think about.

## How It Works

Evergreen hooks into two WordPress actions:

1. **`upgrader_process_complete`** — fires when a plugin is installed
2. **`activate_plugin`** — fires when a plugin is activated

When either event happens, the plugin gets added to WordPress's `auto_update_plugins` option. Evergreen also tracks which plugins it enrolled in a separate option for clean uninstall.

## Features

- **Automatic enrollment** — any plugin you install or activate gets auto-updates enabled
- **Works everywhere** — WordPress admin, WP-CLI, programmatic installs, whatever
- **Clean uninstall** — only removes the auto-update settings it added, nothing else
- **Zero configuration** — no settings page, no options, no decisions to make

## Automatic Updates

This plugin supports [Git Updater](https://git-updater.com/). Install it and you'll get update notifications when new versions are released.

## Requirements

- WordPress 6.0+
- PHP 8.3+

## Installation

1. Upload the `evergreen` directory to `/wp-content/plugins/`
2. Activate through the Plugins menu

That's it. From now on, any plugin you install or activate will have auto-updates enabled.

## Uninstalling

If you uninstall Evergreen, it will:

- Remove auto-update settings **only** for plugins it enrolled
- Delete its tracking data
- Leave plugins you manually enabled for auto-updates untouched

## License

GPL-3.0-or-later

## Credits

**Author:** [Jason Cosper](https://github.com/boogah)

**Inspired by:** Jos Velasco ([Trac #58389](https://core.trac.wordpress.org/ticket/58389)) and Andy Fragen
