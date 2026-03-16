<?php

declare(strict_types=1);

namespace MrJin\FilamentFormsTinymce;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentFormsTinymceServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-forms-tinymce';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            AlpineComponent::make('tinymce-field', __DIR__.'/../resources/js/tinymce-field.js'),
        ], package: 'haosheng0211/filament-forms-tinymce');
    }
}
