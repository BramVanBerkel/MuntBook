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
    'transaction_length' => 64,
    'address_length' => 34,
    'witness_length' => 62,
];
