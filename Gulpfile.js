/*
 * Load required plugins.
 */
var gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-minify-css'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    livereload = require('gulp-livereload'),
    sourcemaps = require('gulp-sourcemaps'),
    sassPath = './src/sass',
    jsPath = 'src/js',
    imgPath = 'src/images';


/*
 * Define tasks
 */

// Process sass
gulp.task('styles', function () {
    gulp.src(sassPath + '/**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer('last 2 version'))
        .pipe(minifycss())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('.'))
});

// Process JS files
gulp.task('scripts', function () {
    return gulp.src(jsPath + '/**/*.js')
        .pipe(sourcemaps.init())
        .pipe(concat('main.js'))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('./js'))
        .pipe(livereload())
});

// Compress images
gulp.task('images', function () {
    return gulp.src(imgPath + '/**/*')
        .pipe(imagemin({optimizationLevel: 7, progressive: true, interlaced: true}))
        .pipe(gulp.dest('./images'))
        .pipe(livereload())
});


// Create the watcher task
gulp.task('watch', function () {
    gulp.watch(sassPath + '/**/*', ['styles']);
    gulp.watch(jsPath + '/**/*', ['scripts']);
    gulp.watch(imgPath + '/**/*', ['images']);
    livereload.listen();
    gulp.watch(['./js/**/*', './images/*', './**/*.php', 'style.css']).on('change', livereload.changed);
});


//  Create a default task
gulp.task('default', ['styles', 'scripts', 'images', 'watch'], null);
