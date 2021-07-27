const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/prices.js', 'public/js')
    .js('resources/js/nonce_distribution.js', 'public/js')
    .js('resources/js/average_blocktime.js', 'public/js')
    .copy('resources/js/particleSettings.json', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

if(process.env.MIX_TESTNET === 'true') {
    console.log('Building for testnet...');

    mix.copy('resources/images/icon/testnet/*', 'public/images/icon');
    mix.copy('resources/images/logos/testnet/*', 'public/images/logos');
    mix.copy('resources/images/splash/testnet/*', 'public/images/splash');
    mix.copy('resources/webmanifest/testnet.webmanifest', 'public/site.webmanifest');
} else {
    console.log('Building for mainnet...');

    mix.copy('resources/images/icon/mainnet/*', 'public/images/icon');
    mix.copy('resources/images/logos/mainnet/*', 'public/images/logos');
    mix.copy('resources/images/splash/mainnet/*', 'public/images/splash');
    mix.copy('resources/webmanifest/mainnet.webmanifest', 'public/site.webmanifest');
}
mix.copy('resources/images/favicon.ico', 'public/images')
