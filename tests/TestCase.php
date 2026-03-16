<?php

declare(strict_types=1);

namespace MrJin\FilamentFormsTinymce\Tests;

use Filament\FilamentServiceProvider;
use Filament\Support\SupportServiceProvider;
use Livewire\LivewireServiceProvider;
use MrJin\FilamentFormsTinymce\FilamentFormsTinymceServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            FilamentServiceProvider::class,
            FilamentFormsTinymceServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Run Spatie Media Library migration
        $app['db']->connection()->getSchemaBuilder()->create('media', function ($table) {
            $table->bigIncrements('id');
            $table->morphs('model');
            $table->string('uuid')->nullable()->unique();
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            $table->unsignedInteger('order_column')->nullable()->index();
            $table->nullableTimestamps();
        });

        // Run GlobalMedia migration
        $app['db']->connection()->getSchemaBuilder()->create('tinymce_global_media', function ($table) {
            $table->id();
            $table->timestamps();
        });

        // Users table for auth tests
        $app['db']->connection()->getSchemaBuilder()->create('users', function ($table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Test fixture tables
        $app['db']->connection()->getSchemaBuilder()->create('test_models', function ($table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('disallowed_models', function ($table) {
            $table->id();
            $table->timestamps();
        });
    }
}
