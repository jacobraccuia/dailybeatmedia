var gulp = require('gulp');
var notify = require('gulp-notify');
var gutil = require('gulp-util');
var ftp = require('vinyl-ftp');

var runSequence = require('run-sequence');

// CSS
var postcss = require('gulp-postcss');

var autoprefixer = require('autoprefixer');
var precss = require('precss');
//var minifyCSS = require('gulp-minify-css');

// JS
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');

// NOTE re work gulp watches for each stage.

// for generic theme work
gulp.task('default', ['deploy', 'css', 'js', 'db_js'], function() {
    gulp.watch('dailybeat_v3.0/js/*.js', {debounceDelay: 2000}, ['db_js']);
    gulp.watch('dailybeat_v3.0/src/js/*.js', {debounceDelay: 2000}, ['js']);
    gulp.watch('dailybeat_v3.0/src/css/*.css', {debounceDelay: 2000}, ['css']);
    gulp.watch('**',  {debounceDelay: 2000}, ['deploy']);

});


// css
gulp.task('css', function() {
    var processors = [
        autoprefixer({ browsers: ['> 5%'] }),
        precss
    ];

    return gulp.src('dailybeat_v3.0/src/css/*.css')
    .pipe(postcss(processors))
    .pipe(gulp.dest('dailybeat_v3.0'));
});

// js
gulp.task('js', function() {
    return gulp.src('dailybeat_v3.0/src/js/*.js')
        .pipe(concat('concat.js'))
        .pipe(rename('uglify_db_scripts.js'))
        .pipe(uglify())
        .pipe(gulp.dest('dailybeat_v3.0/js'));
});


// js
gulp.task('db_js', function() {
    return gulp.src(['dailybeat_v3.0/js/scripts.js', 'dailybeat_v3.0/js/single_scripts.js', 'dailybeat_v3.0/js/homepage_scripts.js', 'dailybeat_v3.0/js/ajax_page_load.js'])
        .pipe(concat('concat.js'))
        .pipe(rename('uglify.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('dailybeat_v3.0/js'));
});





// FTP
// removes dist, than deploys completely new site.

conn = ftp.create({
    host:     'vps12192.inmotionhosting.com',
    user:     'jacobnew@dailybeatmedia.com',
    password: 'test123',
    parallel: 10,
    maxConnections: 10,
    log:      gutil.log
});



gulp.task('reset', function(cb) { 
    runSequence('clean', 'deploy', cb);
});

gulp.task('deploy', function() {

    // using base = '.' will transfer everything to /public_html correctly 
    // turn off buffering in gulp.src for best performance     

    var globs = [
    'dailybeat_v3.0/**',
    'dailybeat_v3.0',
    '!gulpfile.js',
    '!node_modules/**',
    '!node_modules',
    '!package.json',
    '!README.md',
    '!dbm.sublime-project',
    '!.gitignore'
    ];


    return gulp.src(globs, { base: '.', buffer: false })
        .pipe(conn.newer('/dev/wp-content/themes/')) // only upload newer files 
        .pipe(conn.dest('/dev/wp-content/themes/'))
        .pipe(conn.mode('/dev/wp-content/themes/', '0664'))
        .pipe(notify('deployed'));

});

gulp.task('plugin', function() {

    // using base = '.' will transfer everything to /public_html correctly 
    // turn off buffering in gulp.src for best performance     

    var globs = [
    'db-network/**',
    'db-network',
    '!dailybeat_v3.0/**',
    '!dailybeat_v3.0',
    '!gulpfile.js',
    '!node_modules/**',
    '!node_modules',
    '!package.json',
    '!README.md',
    '!dbm.sublime-project',
    '!.gitignore'
    ];


    return gulp.src(globs, { base: '.', buffer: false })
        .pipe(conn.newer('/dev/wp-content/plugins/')) // only upload newer files 
        .pipe(conn.dest('/dev/wp-content/plugins/'))
        .pipe(conn.mode('/dev/wp-content/plugins/', '0664'));

});


gulp.task('deploy_artists', function() {

    // using base = '.' will transfer everything to /public_html correctly 
    // turn off buffering in gulp.src for best performance     

    var globs = [
    'artists/**',
    'artists',
    '!dailybeat_v3.0/**',
    '!dailybeat_v3.0',
    '!gulpfile.js',
    '!node_modules/**',
    '!node_modules',
    '!package.json',
    '!README.md',
    '!dbm.sublime-project',
    '!.gitignore'
    ];


    return gulp.src(globs, { base: '.', buffer: false })
        .pipe(conn.newer('/dev/wp-content/themes/')) // only upload newer files 
        .pipe(conn.dest('/dev/wp-content/themes/'))
        .pipe(conn.mode('/dev/wp-content/themes/', '0664'));

});


gulp.task('deploy_fnt', function() {

    // using base = '.' will transfer everything to /public_html correctly 
    // turn off buffering in gulp.src for best performance     

    var globs = [
    'freshnewtracks/**',
    'freshnewtracks',
    '!dailybeat_v3.0/**',
    '!dailybeat_v3.0',
    '!gulpfile.js',
    '!node_modules/**',
    '!node_modules',
    '!package.json',
    '!README.md',
    '!dbm.sublime-project',
    '!.gitignore'
    ];


    return gulp.src(globs, { base: '.', buffer: false })
        .pipe(conn.newer('/dev/wp-content/themes/')) // only upload newer files 
        .pipe(conn.dest('/dev/wp-content/themes/'))
        .pipe(conn.mode('/dev/wp-content/themes/', '0664'));

});



gulp.task('clean', function(cb) {
    conn.rmdir('/dev/wp-content/themes/', cb);
});


