const mix = require('laravel-mix');

mix.sass('resources/assets/app.scss', 'public/css/app.css');
mix.sass('resources/assets/admin.scss', 'public/css/admin.css');