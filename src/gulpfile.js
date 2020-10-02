var mix = require('laravel-mixin');
mix.sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/admin.scss', 'public/css/admin');