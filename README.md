# Filament Forms TinyMCE

[![Latest Version on Packagist](https://img.shields.io/packagist/v/haosheng0211/filament-forms-tinymce.svg?style=flat-square)](https://packagist.org/packages/haosheng0211/filament-forms-tinymce)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/haosheng0211/filament-forms-tinymce/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/haosheng0211/filament-forms-tinymce/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/haosheng0211/filament-forms-tinymce.svg?style=flat-square)](https://packagist.org/packages/haosheng0211/filament-forms-tinymce)

A [Filament v3](https://filamentphp.com) form field that integrates [TinyMCE 8](https://www.tiny.cloud/) rich text editor.

## Requirements

- PHP ^8.2
- Laravel ^10.0 / ^11.0 / ^12.0
- Filament ^3.0

## Installation

```bash
composer require haosheng0211/filament-forms-tinymce
```

Publish the config file:

```bash
php artisan vendor:publish --tag="filament-forms-tinymce-config"
```

## Usage

### Basic

```php
use MrJin\FilamentFormsTinymce\TinyMceEditor;

TinyMceEditor::make('content')
```

### Profiles

Profiles let you define reusable editor presets in the config file. Three built-in profiles are provided: `default`, `simple`, and `full`.

```php
TinyMceEditor::make('content')
    ->profile('simple')
```

Define your own profiles in the config:

```php
// config/filament-forms-tinymce.php

'profiles' => [
    'blog' => [
        'plugins' => 'lists link image media code',
        'toolbar' => 'blocks | bold italic | link image media | code',
        'menubar' => false,
        'height' => 500,
    ],
],
```

**Priority order:** instance method > profile > TinyMCE built-in default

```php
// Profile sets height to 500, but this instance overrides it to 800
TinyMceEditor::make('content')
    ->profile('blog')
    ->height(800)
```

### Editor options

All TinyMCE options can also be set directly via fluent methods without using profiles:

```php
TinyMceEditor::make('content')
    ->toolbar('bold italic underline | bullist numlist | link image media')
    ->plugins('lists link image media table code')
    ->menubar('file edit view insert format')
    ->height(500)
    ->language('zh_TW')
    ->skin('oxide-dark')
    ->contentCss('dark')
```

For any TinyMCE init option not covered by a dedicated method, use `options()`:

```php
TinyMceEditor::make('content')
    ->options([
        'branding' => false,
        'resize' => true,
        'paste_as_text' => true,
    ])
```

### File browser

The file browser integration is **enabled by default**. When enabled, TinyMCE's `file_picker_callback` dispatches Livewire events (`open-media-browser` / `media-selected`) so you can connect your own media browser component.

> **Tip:** You can use the suggested package [`haosheng0211/filament-media-browser`](https://github.com/haosheng0211/filament-media-browser) which provides a ready-made Livewire media browser that listens for these events.

#### Disable file browser

```php
TinyMceEditor::make('content')
    ->fileBrowser(false)
```

#### Media disk & directory

You can specify the disk and directory to pass to the media browser:

```php
TinyMceEditor::make('content')
    ->mediaDisk('s3')
    ->mediaDirectory('uploads/articles')
```

### TinyMCE source

TinyMCE is loaded from jsDelivr CDN with SRI integrity check. No API key required.

You can customise the CDN URL and version in the config:

```php
'cdn_url' => 'https://cdn.jsdelivr.net/npm/tinymce@{version}/tinymce.min.js',
'version' => '8.3.2',
```

## Config reference

```php
return [
    'cdn_url' => 'https://cdn.jsdelivr.net/npm/tinymce@{version}/tinymce.min.js',
    'version' => '8.3.2',

    // SRI hash for CDN (set to null to disable)
    'integrity' => 'sha384-...',
    'crossorigin' => 'anonymous',

    // Profiles (pre-defined editor configurations)
    'profiles' => [
        'default' => [
            'plugins' => 'lists link image media table code wordcount',
            'toolbar' => 'undo redo | blocks | bold italic underline strikethrough | ...',
            'menubar' => false,
            'height' => 480,
        ],
        'simple' => [
            'plugins' => 'lists link',
            'toolbar' => 'bold italic underline | bullist numlist | link | removeformat',
            'menubar' => false,
            'height' => 300,
        ],
        'full' => [
            'plugins' => 'lists link image media table code wordcount fullscreen searchreplace visualblocks',
            'toolbar' => 'undo redo | blocks fontfamily fontsize | ...',
            'menubar' => 'file edit view insert format tools table',
            'height' => 600,
            'custom_configs' => [],
        ],
    ],
];
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Mr.Jin](https://github.com/haosheng0211)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
