let mix = require('laravel-mix');

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
    }).scripts([
        './node_modules/jquery/dist/jquery.js',
        './node_modules/bootstrap/dist/js/bootstrap.js',
        './node_modules/summernote/dist/summernote-bs4.js',
    ], 'public/js/vendor.js').copyDirectory('./node_modules/summernote/dist/font','public/css/font');