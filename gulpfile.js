const { src, dest, watch, series, parallel } = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const webpack = require('webpack-stream');
const browsersync = require("browser-sync").create();
const prefix = require('gulp-autoprefixer');
const sourcemaps  = require('gulp-sourcemaps');
const plumber = require('gulp-plumber');
const merge = require('merge-stream');
const concat = require('gulp-concat');

function onError(err) {
    console.log(err);
}

// BrowserSync
function browserSync(done) {
    browsersync.init({
        proxy: 'usi.local',
        open: false,
        notify: false,
        ghostMode: false,
        ui: {
            port: 8001
        }
    });
    done();
}

// BrowserSync Reload
function browserSyncReload(done) {
    browsersync.reload();
    done();
}

function buildScripts() {
    return src('./src/js/entry.js')
        .pipe(webpack({
            mode: 'development'
        }))
        .pipe(dest('./'))
        .pipe(browsersync.stream());
}

function buildStyles() {
    let sassStream, cssStream;

    // compile sass
    sassStream = src('./src/scss/style.scss')
        .pipe(sass({includePaths: ['node_modules']}))
        .pipe(prefix('last 2 versions'))

    // select additional css files
    cssStream = src('./node_modules/glightbox/dist/css/glightbox.css')

    // merge streams and concat into one file
    return merge(sassStream, cssStream)
        .pipe(concat('style.css'))
        .pipe(dest('./'))
        .pipe(plumber({errorHandler: onError}))
        .pipe(browsersync.stream());
}

function watchFiles() {
    watch("./src/scss/**/*.scss", series(buildStyles, browserSyncReload));
    watch("./src/js/**/*.js", series(buildScripts, browserSyncReload));
}
const defaultWatch = parallel(buildStyles, buildScripts, browserSync, watchFiles);

exports.buildScripts = buildScripts;
exports.buildStyles = buildStyles;
exports.watch = defaultWatch