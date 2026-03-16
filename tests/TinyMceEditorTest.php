<?php

declare(strict_types=1);

use MrJin\FilamentFormsTinymce\TinyMceEditor;

it('builds tinymce config with only non-null values', function () {
    config()->set('filament-forms-tinymce.profiles', []);

    $field = TinyMceEditor::make('content')
        ->toolbar('bold italic')
        ->height(400);

    $config = $field->getTinymceConfig();

    expect($config)
        ->toHaveKey('toolbar', 'bold italic')
        ->toHaveKey('height', 400)
        ->not->toHaveKey('plugins')
        ->not->toHaveKey('menubar')
        ->not->toHaveKey('skin');
});

it('instance options override config options', function () {
    config()->set('filament-forms-tinymce.profiles.default', [
        'custom_configs' => ['branding' => true],
    ]);

    $field = TinyMceEditor::make('content')
        ->options(['branding' => false]);

    $config = $field->getTinymceConfig();

    expect($config)->toHaveKey('branding', false);
});

it('generates correct CDN URL with version substitution', function () {
    config()->set('filament-forms-tinymce.version', '8.3.2');
    config()->set('filament-forms-tinymce.cdn_url', 'https://cdn.jsdelivr.net/npm/tinymce@{version}/tinymce.min.js');

    $field = TinyMceEditor::make('content');

    expect($field->getCdnUrl())
        ->toBe('https://cdn.jsdelivr.net/npm/tinymce@8.3.2/tinymce.min.js');
});

it('file browser can be disabled', function () {
    $field = TinyMceEditor::make('content')
        ->fileBrowser(false);

    expect($field->isFileBrowserEnabled())->toBeFalse();
});

it('instance mediaDisk overrides config', function () {
    config()->set('filament-forms-tinymce.media.disk', 'public');

    $field = TinyMceEditor::make('content')
        ->mediaDisk('s3');

    expect($field->getMediaDisk())->toBe('s3');
});

it('profile applies toolbar, plugins, menubar, height', function () {
    config()->set('filament-forms-tinymce.profiles.simple', [
        'toolbar' => 'bold italic',
        'plugins' => 'lists link',
        'menubar' => false,
        'height' => 300,
    ]);

    $field = TinyMceEditor::make('content')
        ->profile('simple');

    $config = $field->getTinymceConfig();

    expect($config)
        ->toHaveKey('toolbar', 'bold italic')
        ->toHaveKey('plugins', 'lists link')
        ->toHaveKey('menubar', false)
        ->toHaveKey('height', 300);
});

it('instance method overrides profile value', function () {
    config()->set('filament-forms-tinymce.profiles.simple', [
        'toolbar' => 'bold italic',
        'height' => 300,
    ]);

    $field = TinyMceEditor::make('content')
        ->profile('simple')
        ->toolbar('bold italic underline strikethrough');

    expect($field->getToolbar())->toBe('bold italic underline strikethrough');
    expect($field->getHeight())->toBe(300);
});

it('profile overrides global config default', function () {
    config()->set('filament-forms-tinymce.profiles.default', ['height' => 400]);
    config()->set('filament-forms-tinymce.profiles.compact', [
        'height' => 200,
    ]);

    $field = TinyMceEditor::make('content')
        ->profile('compact');

    expect($field->getHeight())->toBe(200);
});

it('profile custom_configs merge with instance options', function () {
    config()->set('filament-forms-tinymce.profiles.custom', [
        'custom_configs' => ['resize' => true],
    ]);

    $field = TinyMceEditor::make('content')
        ->profile('custom')
        ->options(['paste_as_text' => true]);

    $config = $field->getTinymceConfig();

    expect($config)
        ->toHaveKey('resize', true)
        ->toHaveKey('paste_as_text', true);
});

it('profile custom_configs merge into final config', function () {
    config()->set('filament-forms-tinymce.profiles.blog', [
        'toolbar' => 'bold italic',
        'custom_configs' => [
            'relative_urls' => false,
            'remove_script_host' => false,
        ],
    ]);

    $field = TinyMceEditor::make('content')
        ->profile('blog');

    $config = $field->getTinymceConfig();

    expect($config)
        ->toHaveKey('toolbar', 'bold italic')
        ->toHaveKey('relative_urls', false)
        ->toHaveKey('remove_script_host', false);
});

it('instance options override profile custom_configs', function () {
    config()->set('filament-forms-tinymce.profiles.blog', [
        'custom_configs' => [
            'relative_urls' => false,
            'branding' => true,
        ],
    ]);

    $field = TinyMceEditor::make('content')
        ->profile('blog')
        ->options(['branding' => false]);

    $config = $field->getTinymceConfig();

    expect($config)
        ->toHaveKey('relative_urls', false)
        ->toHaveKey('branding', false);
});

it('profile custom_configs work without other profile keys', function () {
    config()->set('filament-forms-tinymce.profiles.minimal', [
        'custom_configs' => ['branding' => false, 'statusbar' => false],
    ]);

    $field = TinyMceEditor::make('content')
        ->profile('minimal');

    $config = $field->getTinymceConfig();

    expect($config)
        ->toHaveKey('branding', false)
        ->toHaveKey('statusbar', false);
});
