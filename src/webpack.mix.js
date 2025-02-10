const mix = require('laravel-mix');

// change theme path according to your need.    
// const themePath = 'resources/assets/'; 

// mix.options({
//   publicPath: 'public/css/'
// })

// change script name and path according to your need.
// mix.js(themePath + '/assets/src/main.js', 'main.js')
//    .sass(themePath + '/assets/src/main.scss', 'main.css');

mix.sass('resources/assets/app.scss', 'public/css/app.css');
mix.sass('resources/assets/admin.scss', 'public/css/admin.css');