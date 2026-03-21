<?php

// config for MrJin/FilamentFormsTinymce
return [

    /*
    |--------------------------------------------------------------------------
    | TinyMCE CDN
    |--------------------------------------------------------------------------
    |
    | Loaded from jsDelivr CDN. No API key required.
    | Use {version} as placeholder in cdn_url.
    |
    */
    'cdn_url' => 'https://cdn.jsdelivr.net/npm/tinymce@{version}/tinymce.min.js',

    'version' => '8.3.2',

    // SRI (Subresource Integrity) hash for CDN script (sha384)
    // This hash matches TinyMCE 8.3.2 from jsDelivr. Update when changing version.
    // Generate via: curl -s <cdn_url> | openssl dgst -sha384 -binary | openssl base64 -A
    // Set to null to disable SRI check
    'integrity' => 'sha384-J2fmZ01GJZtp6FUtKlTwhRClCU9DBXUIihwVeJ4Naa90dximDfIb9sHZJo91hJEh',

    'crossorigin' => 'anonymous',

    /*
    |--------------------------------------------------------------------------
    | Profiles
    |--------------------------------------------------------------------------
    |
    | Pre-defined editor configurations. Use ->profile('name') on the field
    | to apply a profile. Defaults to 'default' when no profile is specified.
    | Per-instance method calls (e.g. ->toolbar()) still take highest priority.
    |
    | Supported keys: toolbar, plugins, menubar, height, skin, content_css,
    | language, custom_configs, mergetags, mergetag_prefix, mergetag_suffix.
    |
    | 'custom_configs' accepts an associative array of arbitrary TinyMCE
    | init options that will be merged into the final config. This is useful
    | for profile-specific settings not covered by the named keys above.
    | Priority: instance ->options() > profile custom_configs.
    |
    */
    'profiles' => [

        'default' => [
            'plugins' => 'lists link image media table code wordcount',
            'toolbar' => 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright | bullist numlist | link image media | table code | removeformat',
            'menubar' => false,
            'height' => 480,
            'custom_configs' => [
                'relative_urls' => false,
                'remove_script_host' => true,
            ],
        ],

        'simple' => [
            'plugins' => 'lists link',
            'toolbar' => 'bold italic underline | bullist numlist | link | removeformat',
            'menubar' => false,
            'height' => 300,
        ],

        'full' => [
            'plugins' => 'lists link image media table code wordcount fullscreen searchreplace visualblocks',
            'toolbar' => 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code fullscreen | removeformat',
            'menubar' => 'file edit view insert format tools table',
            'height' => 600,
        ],

        // Email templates: keep full URL (with domain) for email clients
        // 'email' => [
        //     'plugins' => 'lists link image media table code wordcount',
        //     'toolbar' => 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright | bullist numlist | link image media | table code | removeformat',
        //     'menubar' => false,
        //     'height' => 480,
        //     'custom_configs' => [
        //         'relative_urls' => false,
        //         'remove_script_host' => false,
        //     ],
        // ],

    ],

];
