# Changelog

All notable changes to `filament-forms-tinymce` will be documented in this file.

## 1.2.0 - 2026-03-22

- Add merge tags support for email templates — insert predefined variables (e.g. `{{user.name}}`) via toolbar dropdown
- Support nested menu groups for merge tags
- Merge tags configurable via profile (`mergetags`, `mergetag_prefix`, `mergetag_suffix`) or instance methods
- Priority: instance method > profile setting
- No TinyMCE Premium required — implemented as a custom toolbar button

## 1.1.0 - 2026-03-21

- Integrate with [filament-media-browser](https://github.com/haosheng0211/filament-media-browser) — always request full URL (`storeAsUrl: true`) from media browser to ensure valid HTML output
- Add `relative_urls: false` and `remove_script_host: true` to default profile for portable absolute paths (`/storage/media/photo.jpg`)
- Add commented `email` profile example for email templates that need full URLs with domain
- URL format is now controlled by TinyMCE built-in options (`relative_urls`, `remove_script_host`, `convert_urls`) instead of a custom `storeAsUrl` property

## 1.0.0 - 2026-03-16

- Initial release
- TinyMCE 8.3.2 integration for Filament v3 forms
- Profile system (default, simple, full) with custom profile support
- Dark mode auto-detection
- File browser integration via Livewire events
- SRI integrity check for CDN loading
- Fluent API for all TinyMCE options
