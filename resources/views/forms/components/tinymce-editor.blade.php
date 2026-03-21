<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        ax-load
        ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('tinymce-field', 'haosheng0211/filament-forms-tinymce') }}"
        x-data="tinymceField({
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$getStatePath()}')") }},
            statePath: @js($getStatePath()),
            config: @js($getTinymceConfig()),
            fileBrowserEnabled: @js($isFileBrowserEnabled()),
            cdnUrl: @js($getCdnUrl()),
            integrity: @js(config('filament-forms-tinymce.integrity')),
            crossorigin: @js(config('filament-forms-tinymce.crossorigin', 'anonymous')),
            mediaDisk: @js($getMediaDisk()),
            mediaDirectory: @js($getMediaDirectory()),
            mergetags: @js($getMergetags()),
            mergetagPrefix: @js($getMergetagPrefix()),
            mergetagSuffix: @js($getMergetagSuffix()),
            readonly: @js($isDisabled() || $isReadOnly()),
        })"
        wire:ignore
    >
        <textarea x-ref="editor"></textarea>
    </div>
</x-dynamic-component>
