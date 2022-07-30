let mix = require('laravel-mix');
require('laravel-mix-purgecss');
var path = require('path');

mix.sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/admin.scss', 'public/css').options({
        processCssUrls: false,
        uglify: {
            parallel: 4, // Use multithreading for the processing
            uglifyOptions: {
                mangle: true,
                compress: false, // The slow bit
            }
        }
    });

if (mix.inProduction()) {
    mix.purgeCss({
        extend: {
        content: [path.join(__dirname, './node_modules/summernote/dist/*.js')],
        },
    }); // remove unused css rules
}

mix.scripts([
        './node_modules/jquery/dist/jquery.js',
        './node_modules/jquery-ui-dist/jquery-ui.min.js',
        './node_modules/popper.js/dist/umd/popper.min.js',
        './node_modules/bootstrap/dist/js/bootstrap.js',
        './node_modules/summernote/dist/summernote-bs4.js',
    ], 'public/js/vendor.js')
    .copy('./node_modules/jquery-ui-dist/jquery-ui.min.css', 'public/css')
    .copyDirectory('./node_modules/jquery-ui-dist/images', 'public/css/images')
    .copyDirectory('./node_modules/summernote/dist/font', 'public/css/font')
    .copyDirectory('./node_modules/@fortawesome/fontawesome-free/webfonts', 'public/css/font');

mix.minify('public/js/vendor.js');