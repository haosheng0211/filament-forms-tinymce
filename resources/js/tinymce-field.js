let _loadPromise = null;

export default function tinymceField({
    state,
    statePath,
    config,
    fileBrowserEnabled,
    cdnUrl,
    integrity,
    crossorigin,
    mediaDisk,
    mediaDirectory,
    mergetags,
    mergetagPrefix,
    mergetagSuffix,
    readonly,
}) {
    return {
        editor: null,
        state: state,
        _filePickerCallback: null,
        _livewireCleanup: null,

        async init() {
            await this.loadTinymce();

            const dark = document.documentElement.classList.contains('dark');

            const initConfig = {
                ...config,
                target: this.$refs.editor,
                license_key: 'gpl',
                promotion: false,
                skin: config.skin ?? (dark ? 'oxide-dark' : 'oxide'),
                content_css: config.content_css ?? (dark ? 'dark' : 'default'),
                setup: (editor) => {
                    this.editor = editor;

                    if (mergetags && mergetags.length > 0) {
                        this._registerMergetags(editor, mergetags, mergetagPrefix, mergetagSuffix);
                    }

                    editor.on('init', () => {
                        if (this.state) {
                            editor.setContent(this.state);
                        }

                        if (readonly) {
                            editor.mode.set('readonly');
                        }
                    });

                    editor.on('change input undo redo', () => {
                        this.state = editor.getContent();
                    });

                    editor.on('blur', () => {
                        this.state = editor.getContent();
                    });
                },
            };

            if (fileBrowserEnabled && !readonly) {
                initConfig.file_picker_types = 'image media';
                initConfig.file_picker_callback = (callback, value, meta) => {
                    this._filePickerCallback = callback;

                    Livewire.dispatch('open-media-browser', {
                        statePath: statePath,
                        mediaType: meta.filetype,
                        disk: mediaDisk,
                        directory: mediaDirectory,
                        storeAsUrl: true,
                    });
                };

                this._livewireCleanup = Livewire.on('media-selected', (params) => {
                    if (params.statePath !== statePath) return;
                    if (this._filePickerCallback) {
                        this._filePickerCallback(params.url, {
                            alt: params.alt || '',
                            title: params.title || '',
                        });
                        this._filePickerCallback = null;
                    }
                });
            }

            tinymce.init(initConfig);

            this.$watch('state', (newValue) => {
                if (this.editor && this.editor.getContent() !== newValue) {
                    this.editor.setContent(newValue || '');
                }
            });
        },

        _registerMergetags(editor, tags, prefix, suffix) {
            const buildMenuItems = (items) => {
                return items.map((item) => {
                    if (item.menu) {
                        return {
                            type: 'nestedmenuitem',
                            text: item.title,
                            getSubmenuItems: () => buildMenuItems(item.menu),
                        };
                    }

                    return {
                        type: 'menuitem',
                        text: item.title || item.value,
                        onAction: () => {
                            editor.insertContent(prefix + item.value + suffix);
                        },
                    };
                });
            };

            editor.ui.registry.addMenuButton('mergetags', {
                icon: 'addtag',
                tooltip: 'Merge Tags',
                fetch: (callback) => {
                    callback(buildMenuItems(tags));
                },
            });
        },

        async loadTinymce() {
            if (window.tinymce) return;

            if (_loadPromise) return _loadPromise;

            _loadPromise = new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = cdnUrl;

                if (integrity) {
                    script.integrity = integrity;
                }
                if (crossorigin) {
                    script.crossOrigin = crossorigin;
                }

                script.referrerPolicy = 'no-referrer';
                script.onload = resolve;
                script.onerror = () => {
                    _loadPromise = null;
                    script.remove();
                    reject(new Error('Failed to load TinyMCE'));
                };
                document.head.appendChild(script);
            });

            return _loadPromise;
        },

        destroy() {
            if (this._livewireCleanup) {
                this._livewireCleanup();
                this._livewireCleanup = null;
            }
            if (this.editor) {
                this.editor.remove();
                this.editor = null;
            }
        },
    };
}
