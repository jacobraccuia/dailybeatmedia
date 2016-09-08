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

// for dbv3 theme work
gulp.task('default', ['deploy', 'css', 'db_js'], function() {
    gulp.watch('dailybeat_v3.0/src/js/*.js', {debounceDelay: 2000}, ['db_js']);
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
gulp.task('db_js', function() {
    return gulp.src(['dailybeat_v3.0/src/js/*.js'])
        .pipe(concat('concat.js'))
        .pipe(rename('scripts.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('dailybeat_v3.0/js'));
});





// for channel theme work
gulp.task('channel', ['deploy_channel', 'ch_css', 'ch_js'], function() {
    gulp.watch('channel/src/js/*.js', {debounceDelay: 2000}, ['ch_js']);
    gulp.watch('channel/src/css/*.css', {debounceDelay: 2000}, ['ch_css']);
    gulp.watch('**',  {debounceDelay: 2000}, ['deploy_channel']);

});

// css
gulp.task('ch_css', function() {
    var processors = [
        autoprefixer({ browsers: ['> 5%'] }),
        precss
    ];

    return gulp.src('channel/src/css/*.css')
    .pipe(postcss(processors))
    .pipe(gulp.dest('channel'));
});

// js
gulp.task('ch_js', function() {
    return gulp.src(['channel/src/js/scripts.js'])
        .pipe(concat('concat.js'))
        .pipe(rename('scripts.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('channel/js'));
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


gulp.task('plugin', ['deploy_plugin', 'dbn_css', 'dbn_js', 'dbn_uglify_js'], function() {
    gulp.watch('db-network/src/js/*.js', {debounceDelay: 2000}, ['dbn_js']);
    gulp.watch('db-network/src/js/uglify/*.js', {debounceDelay: 2000}, ['dbn_uglify_js']);
    gulp.watch('db-network/src/css/*.css', {debounceDelay: 2000}, ['dbn_css']);
    gulp.watch('**',  {debounceDelay: 2000}, ['deploy_plugin']);

});

// css
gulp.task('dbn_css', function() {
    var processors = [
        autoprefixer({ browsers: ['> 5%'] }),
        precss
    ];

    return gulp.src('db-network/src/css/*.css')
    .pipe(postcss(processors))
    .pipe(gulp.dest('db-network'));
});


// plugin js
gulp.task('dbn_js', function() {
    return gulp.src(['db-network/src/js/*.js'])
        .pipe(concat('concat.js'))
        .pipe(rename('db-network.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('db-network/js'));
});

// plugin uglify js
gulp.task('dbn_uglify_js', function() {
    return gulp.src(['db-network/src/js/uglify/*.js'])
        .pipe(concat('concat.js'))
        .pipe(rename('db-network-uglify.js'))
        .pipe(uglify())
        .pipe(gulp.dest('db-network/js'));
});

gulp.task('deploy_plugin', function() {

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


gulp.task('deploy_channel', function() {

    // using base = '.' will transfer everything to /public_html correctly 
    // turn off buffering in gulp.src for best performance     

    var globs = [
    'channel/**',
    'channel',
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


