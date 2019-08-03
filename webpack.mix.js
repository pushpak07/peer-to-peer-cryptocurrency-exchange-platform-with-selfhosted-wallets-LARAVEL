let mix = require('laravel-mix');

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

/**
 * JavaScript Assets: This is where both global assets as well as each pages's
 * scope javascript assets are compiled from the resource folder into the public
 * folder.
 */

/* Dashboard - Global */
mix.js('resources/assets/js/app.js', 'public/js');

/**
 * StyleSheet Assets: This is where both global assets as well as each pages's
 * scope stylesheets assets are compiled from the resource folder into the public
 * folder.
 */

/* Dashboard - Global */
mix.sass('resources/assets/sass/vendors.scss', 'public/css')
   .sass('resources/assets/sass/app.scss', 'public/css')

   // Pages
   .sass('resources/assets/sass/pages/chat.scss', 'public/css/pages')
   .sass('resources/assets/sass/pages/auth.scss', 'public/css/pages')
   .sass('resources/assets/sass/pages/profile.scss', 'public/css/pages')
   .sass('resources/assets/sass/pages/error.scss', 'public/css/pages')

   // Menu Types
   .sass('resources/assets/sass/core/menu/menu-types/horizontal-menu.scss', 'public/css/core/menu/menu-types')
   .sass('resources/assets/sass/core/menu/menu-types/vertical-compact-menu.scss', 'public/css/core/menu/menu-types')
   .sass('resources/assets/sass/core/menu/menu-types/vertical-content-menu.scss', 'public/css/core/menu/menu-types')
   .sass('resources/assets/sass/core/menu/menu-types/vertical-overlay-menu.scss', 'public/css/core/menu/menu-types')
   .sass('resources/assets/sass/core/menu/menu-types/vertical-menu-modern.scss', 'public/css/core/menu/menu-types')
   .sass('resources/assets/sass/core/menu/menu-types/vertical-menu.scss', 'public/css/core/menu/menu-types')


   // Colors
   .sass('resources/assets/sass/core/colors/palette-loader.scss', 'public/css/core/colors')
   .sass('resources/assets/sass/core/colors/palette-switch.scss', 'public/css/core/colors')
   .sass('resources/assets/sass/core/colors/palette-climacon.scss', 'public/css/core/colors')
   .sass('resources/assets/sass/core/colors/palette-gradient.scss', 'public/css/core/colors')
   .sass('resources/assets/sass/core/colors/palette-callout.scss', 'public/css/core/colors')
   .sass('resources/assets/sass/core/colors/palette-tooltip.scss', 'public/css/core/colors')
   .sass('resources/assets/sass/core/colors/palette-noui.scss', 'public/css/core/colors');


if (mix.inProduction()) {
	mix.version();
}