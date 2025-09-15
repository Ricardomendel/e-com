<?php

return [
    // Ensure Livewire routes are registered on the web middleware group
    'middleware_group' => 'web',

    // Leave asset_url empty locally so package serves current hashes
    'asset_url' => env('LIVEWIRE_ASSET_URL'),
    
    // Explicitly enable auto-discovery
    'auto_discovery' => true,
    
    // Ensure the manifest path is correct
    'manifest_path' => null,
];


