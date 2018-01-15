const gulp = require('gulp'),
    react = require('gulp-react'),
    babel = require('gulp-babel'),
    open = require('gulp-open'),
    concat = require('gulp-concat'),
    watch = require('gulp-watch'),
    sourcemaps = require('gulp-sourcemaps'),
    browserify = require('browserify'),
    babelify = require('babelify'),
    uglify = require('gulp-uglify'),
    buffer = require('gulp-buffer'),
    source = require('vinyl-source-stream'),
    sass = require('gulp-sass'),
    contactcss = require('gulp-concat-css');

const Config = {
    OUTPUT_ROOT: '../public/',
    OUTPUT_JS: '../public/js/',
    OUTPUT_CSS: '../public/css',
    PRODUCTION: false
};

if(Config.PRODUCTION){
   process.env.NODE_ENV = 'production';
}

gulp.task('transform', function() {

    var bundle = browserify({
            basedir: 'js',
            entries: 'index.jsx',
            extensions: ['.jsx'],
            debug: !Config.PRODUCTION,
            cache: {},
            packageCache: {},
        })
        .transform(babelify)
        .bundle().on("error", function(err) {
            console.log("Error : " + err.message);
        })
        .pipe(source('bundle.js'));
    if (Config.PRODUCTION) {
        bundle = bundle.pipe(buffer()).pipe(uglify());
    }

    bundle.pipe(gulp.dest(Config.OUTPUT_JS));
});
/*
gulp.task('6to5', ['transform'], function(){
    return gulp.src('js/*.js')
    .pipe(babel())
    .pipe(gulp.dest(config.OUTPUT_JS));
});*/

gulp.task('copystatic', function() {
    return gulp.src('static/**.*')
        .pipe(gulp.dest(Config.OUTPUT_ROOT));
});

gulp.task('sass', function() {
    var scss = gulp.src('./sass/main.scss');

    if (Config.PRODUCTION) {
        scss = scss.pipe(sass({
            outputStyle: 'compressed'
        }).on('error', sass.logError));
    } else {
        scss = scss.pipe(sourcemaps.init())
            .pipe(sass().on('error', sass.logError))
            .pipe(sourcemaps.write());
    }

    scss.pipe(gulp.dest(Config.OUTPUT_CSS));
    return scss;
});

gulp.task('default', ['transform', 'sass'], function() {

});

gulp.task('watch', ['default'], function() {
    gulp.watch('js/**/*.jsx', ['transform']);
    gulp.watch('./sass/**/*.scss', ['sass']);
});
