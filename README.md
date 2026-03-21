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

> **Tip:** Install [`haosheng0211/filament-media-browser`](https://github.com/haosheng0211/filament-media-browser) for a ready-made media browser that works out of the box.

```bash
composer require haosheng0211/filament-media-browser
```

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

#### URL format

TinyMCE always requests full URLs from the media browser (`storeAsUrl: true`), regardless of the media browser's global `store_as_url` setting. The final URL format stored in HTML is controlled by TinyMCE's built-in URL conversion options:

| Desired output | Configuration |
|---|---|
| `/storage/media/photo.jpg` (default) | `relative_urls: false`, `remove_script_host: true` |
| `http://domain.com/storage/media/photo.jpg` | `relative_urls: false`, `remove_script_host: false` |
| Keep original URL | `convert_urls: false` |

The default profile already includes `relative_urls: false` and `remove_script_host: true`, which produces portable absolute paths without the domain.

For **email templates** that require full URLs (email clients cannot resolve relative paths), use a dedicated profile or override via `options()`:

```php
// Option 1: Use a profile
TinyMceEditor::make('email_body')
    ->profile('email')

// Option 2: Override inline
TinyMceEditor::make('email_body')
    ->options(['remove_script_host' => false])
```

### Merge tags

Insert predefined variables into the editor via a toolbar dropdown — useful for email templates. No TinyMCE Premium required.

#### Via profile (recommended)

Define merge tags in a profile so all fields using that profile share the same variables:

```php
// config/filament-forms-tinymce.php
'profiles' => [
    'email' => [
        'plugins' => 'lists link image media table code wordcount',
        'toolbar' => 'undo redo | blocks | bold italic | mergetags | link image | removeformat',
        'menubar' => false,
        'height' => 480,
        'mergetags' => [
            ['value' => 'user.name', 'title' => '使用者名稱'],
            ['value' => 'user.email', 'title' => '使用者信箱'],
            ['title' => '網站', 'menu' => [
                ['value' => 'site.name', 'title' => '網站名稱'],
                ['value' => 'site.url', 'title' => '網站網址'],
            ]],
        ],
        'mergetag_prefix' => '{{',  // optional, defaults to '{{'
        'mergetag_suffix' => '}}',  // optional, defaults to '}}'
        'custom_configs' => [
            'relative_urls' => false,
            'remove_script_host' => false,
        ],
    ],
],
```

```php
TinyMceEditor::make('email_body')
    ->profile('email')
```

Selecting "使用者名稱" inserts `{{user.name}}` into the editor.

#### Via instance methods

Instance methods take priority over profile settings:

```php
TinyMceEditor::make('email_body')
    ->toolbar('undo redo | blocks | bold italic | mergetags | removeformat')
    ->mergetags([
        ['value' => 'user.name', 'title' => 'User Name'],
    ])
    ->mergetagPrefix('${')
    ->mergetagSuffix('}')
// Inserts: ${user.name}
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
            'custom_configs' => [
                'relative_urls' => false,
                'remove_script_host' => true,
            ],
        ],
        'simple' => [ /* ... */ ],
        'full'   => [ /* ... */ ],
        // 'email' => [
        //     'custom_configs' => [
        //         'relative_urls' => false,
        //         'remove_script_host' => false,
        //     ],
        // ],
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
