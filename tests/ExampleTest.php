<?php

declare(strict_types=1);
use MrJin\FilamentFormsTinymce\FilamentFormsTinymceServiceProvider;
use MrJin\FilamentFormsTinymce\TinyMceEditor;

it('loads the service provider', function () {
    expect(class_exists(FilamentFormsTinymceServiceProvider::class))->toBeTrue();
    expect(class_exists(TinyMceEditor::class))->toBeTrue();
});
