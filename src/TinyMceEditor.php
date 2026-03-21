<?php

declare(strict_types=1);

namespace MrJin\FilamentFormsTinymce;

use Closure;
use Filament\Forms\Components\Concerns\CanBeReadOnly;
use Filament\Forms\Components\Field;

class TinyMceEditor extends Field
{
    use CanBeReadOnly;

    protected string $view = 'filament-forms-tinymce::forms.components.tinymce-editor';

    // TinyMCE options
    protected string|Closure|null $toolbar = null;

    protected string|array|Closure|null $plugins = null;

    protected string|bool|Closure|null $menubar = null;

    protected int|Closure|null $height = null;

    protected string|Closure|null $skin = null;

    protected string|Closure|null $contentCss = null;

    protected string|Closure|null $language = null;

    protected array|Closure $extraOptions = [];

    // Profile
    protected string|Closure|null $profile = null;

    // File browser options
    protected bool|Closure $fileBrowserEnabled = true;

    protected string|Closure|null $mediaDisk = null;

    protected string|Closure|null $mediaDirectory = null;

    // Merge tags
    protected array|Closure $mergetags = [];

    protected string|Closure $mergetagPrefix = '{{';

    protected string|Closure $mergetagSuffix = '}}';

    // --- TinyMCE Option Setters ---

    public function toolbar(string|Closure|null $toolbar): static
    {
        $this->toolbar = $toolbar;

        return $this;
    }

    public function plugins(string|array|Closure|null $plugins): static
    {
        $this->plugins = $plugins;

        return $this;
    }

    public function menubar(string|bool|Closure|null $menubar): static
    {
        $this->menubar = $menubar;

        return $this;
    }

    public function height(int|Closure|null $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function skin(string|Closure|null $skin): static
    {
        $this->skin = $skin;

        return $this;
    }

    public function contentCss(string|Closure|null $contentCss): static
    {
        $this->contentCss = $contentCss;

        return $this;
    }

    public function language(string|Closure|null $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function options(array|Closure $options): static
    {
        $this->extraOptions = $options;

        return $this;
    }

    // --- Profile ---

    public function profile(string|Closure|null $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    public function getProfile(): ?string
    {
        return $this->evaluate($this->profile);
    }

    /**
     * Resolve the profile config array, or empty array if no profile is set.
     *
     * @return array<string, mixed>
     */
    protected function getProfileConfig(): array
    {
        $name = $this->getProfile() ?? 'default';

        return config("filament-forms-tinymce.profiles.{$name}", []);
    }

    // --- File Browser Setters ---

    public function fileBrowser(bool|Closure $enabled = true): static
    {
        $this->fileBrowserEnabled = $enabled;

        return $this;
    }

    public function mediaDisk(string|Closure|null $disk): static
    {
        $this->mediaDisk = $disk;

        return $this;
    }

    public function mediaDirectory(string|Closure|null $directory): static
    {
        $this->mediaDirectory = $directory;

        return $this;
    }

    // --- Merge Tags Setters ---

    public function mergetags(array|Closure $tags): static
    {
        $this->mergetags = $tags;

        return $this;
    }

    public function mergetagPrefix(string|Closure $prefix): static
    {
        $this->mergetagPrefix = $prefix;

        return $this;
    }

    public function mergetagSuffix(string|Closure $suffix): static
    {
        $this->mergetagSuffix = $suffix;

        return $this;
    }

    // --- Getters ---

    public function getToolbar(): ?string
    {
        return $this->evaluate($this->toolbar)
            ?? $this->getProfileConfig()['toolbar']
            ?? null;
    }

    public function getPlugins(): string|array|null
    {
        return $this->evaluate($this->plugins)
            ?? $this->getProfileConfig()['plugins']
            ?? null;
    }

    public function getMenubar(): string|bool|null
    {
        return $this->evaluate($this->menubar)
            ?? $this->getProfileConfig()['menubar']
            ?? null;
    }

    public function getHeight(): ?int
    {
        return $this->evaluate($this->height)
            ?? $this->getProfileConfig()['height']
            ?? null;
    }

    public function getSkin(): ?string
    {
        return $this->evaluate($this->skin)
            ?? $this->getProfileConfig()['skin']
            ?? null;
    }

    public function getContentCss(): ?string
    {
        return $this->evaluate($this->contentCss)
            ?? $this->getProfileConfig()['content_css']
            ?? null;
    }

    public function getLanguage(): ?string
    {
        $language = $this->evaluate($this->language)
            ?? $this->getProfileConfig()['language']
            ?? null;

        if ($language === null) {
            return null;
        }

        // TinyMCE 8 uses RFC 5646 hyphenated locale codes (zh_TW → zh-TW)
        return str_replace('_', '-', $language);
    }

    public function getLanguageUrl(): ?string
    {
        $language = $this->getLanguage();

        if ($language === null || $language === 'en') {
            return null;
        }

        return "https://cdn.jsdelivr.net/npm/tinymce-i18n/langs8/{$language}.js";
    }

    public function getExtraOptions(): array
    {
        return array_merge(
            $this->getProfileConfig()['custom_configs'] ?? [],
            $this->evaluate($this->extraOptions),
        );
    }

    public function getCdnUrl(): string
    {
        $template = config('filament-forms-tinymce.cdn_url', 'https://cdn.jsdelivr.net/npm/tinymce@{version}/tinymce.min.js');
        $version = config('filament-forms-tinymce.version', '8.3.2');

        return str_replace('{version}', $version, $template);
    }

    public function isFileBrowserEnabled(): bool
    {
        return $this->evaluate($this->fileBrowserEnabled);
    }

    public function getMediaDisk(): ?string
    {
        return $this->evaluate($this->mediaDisk);
    }

    public function getMediaDirectory(): ?string
    {
        return $this->evaluate($this->mediaDirectory);
    }

    public function getMergetags(): array
    {
        return $this->evaluate($this->mergetags);
    }

    public function getMergetagPrefix(): string
    {
        return $this->evaluate($this->mergetagPrefix);
    }

    public function getMergetagSuffix(): string
    {
        return $this->evaluate($this->mergetagSuffix);
    }

    // --- Config Builder ---

    public function getTinymceConfig(): array
    {
        $config = $this->getExtraOptions();

        $mappings = [
            'toolbar' => $this->getToolbar(),
            'plugins' => $this->getPlugins(),
            'menubar' => $this->getMenubar(),
            'height' => $this->getHeight(),
            'skin' => $this->getSkin(),
            'content_css' => $this->getContentCss(),
            'language' => $this->getLanguage(),
            'language_url' => $this->getLanguageUrl(),
        ];

        foreach ($mappings as $key => $value) {
            if ($value !== null) {
                $config[$key] = $value;
            }
        }

        return $config;
    }
}
