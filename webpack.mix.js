const mix = require('laravel-mix');

//require('laravel-mix-polyfill');

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
   .sass('resources/sass/app.scss', 'public/css')
   .options({
    postCss: [
        require("autoprefixer")({
            browsers: ["last 30 versions"]
        })
    ]
   })
   .version();

if (mix.config.production) {
    mix.babel(['public/js/app.js'], 'public/js/app.js');

    mix.setResourceRoot("/");
} else {
    mix.setResourceRoot("/adria/public/");

    mix.browserSync({
        proxy: "localhost/adria/public",
        open: true
    });
}