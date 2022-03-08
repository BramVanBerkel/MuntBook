const mix = require('laravel-mix');
mix.disableNotifications();

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/priceChart.js', 'public/js')
    .js('resources/js/nonceDistribution.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ]);

if (process.env.MIX_TESTNET === 'true') {
    console.log('Building for testnet...');

    mix.copy('resources/images/icon/testnet/*', 'public/images/icon')
        .copy('resources/images/logos/testnet/*', 'public/images/logos')
        .copy('resources/images/splash/testnet/*', 'public/images/splash')
        .copy('resources/webmanifest/testnet.webmanifest', 'public/site.webmanifest');
} else {
    console.log('Building for mainnet...');

    mix.copy('resources/images/icon/mainnet/*', 'public/images/icon')
        .copy('resources/images/logos/mainnet/*', 'public/images/logos')
        .copy('resources/images/splash/mainnet/*', 'public/images/splash')
        .copy('resources/webmanifest/mainnet.webmanifest', 'public/site.webmanifest');
}
mix.copy('resources/images/favicon.ico', 'public/images')
    .copy('resources/images/og_image.png', 'public/images')
