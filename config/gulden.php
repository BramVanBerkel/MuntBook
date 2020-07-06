<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Gulden RPC settings
    |--------------------------------------------------------------------------
    |
    | These RPC settings are automatically configured for the Gulden service
    | and used to connect via RPC.
    |
    */
    'rpc_user' => env('GULDEN_RPC_USER', 'gulden'),
    'rpc_password' => env('GULDEN_RPC_PASSWORD', 'secret'),
    'rpc_host' => env('GULDEN_RPC_HOST', '127.0.0.1'),
    'rpc_port' => env('GULDEN_RPC_PORT', '9232'),

    /*
    |--------------------------------------------------------------------------
    | Blocknotify
    |--------------------------------------------------------------------------
    |
    | The command to execute when a new block is found
    |
    */
    'blocknotify' => env('GULDEN_BLOCKNOTIFY')
];
