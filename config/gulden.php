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
    | Port
    |--------------------------------------------------------------------------
    |
    | Listen for connections on <port> (default: 9231 or testnet: 9923)
    |
    */
    'port' => env('GULDEN_PORT', '9231'),

    /*
    |--------------------------------------------------------------------------
    | Testnet
    |--------------------------------------------------------------------------
    |
    | Optionally configure gulden to run on a testnet
    |
    */
    'testnet' => env('GULDEN_TESTNET'),

    /*
    |--------------------------------------------------------------------------
    | Addnode
    |--------------------------------------------------------------------------
    |
    | Add nodes on Gulden startup
    |
    */
    'addnode' => env('GULDEN_ADDNODE'),

    /*
    |--------------------------------------------------------------------------
    | Sync delay
    |--------------------------------------------------------------------------
    |
    | How long to wait before syncing a new block
    |
    */
    'sync_delay' => env('GULDEN_SYNC_DELAY'),

    /*
    |--------------------------------------------------------------------------
    | Blocknotify
    |--------------------------------------------------------------------------
    |
    | The command to execute when a new block is found
    |
    */
    'blocknotify' => env('GULDEN_BLOCKNOTIFY'),

    /*
    |--------------------------------------------------------------------------
    | Lengths
    |--------------------------------------------------------------------------
    |
    | Save the lengths of certain strings in Gulden to determine what they are
    |
    */
    'transaction_or_block_hash_length' => 64,
    'address_length' => 34,
    'witness_address_length' => 62,

    /*
    |--------------------------------------------------------------------------
    | Blocktime
    |--------------------------------------------------------------------------
    |
    | The desired amount of seconds between blocks
    |
    */
    'blocktime' => 150,
];
