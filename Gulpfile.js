// Get modules
var gulp 		= require('gulp');
var less 		= require('gulp-less');
var uglify 		= require('gulp-uglify');
var minify 		= require('gulp-minify-css');
var rename 		= require("gulp-rename");
var imagemin 	= require('gulp-imagemin');
var prefix      = require('gulp-autoprefixer');


var assetFolder = 'app/App/assets/themes/';
var outputFolder = 'public/assets/themes/';

// Task sass
gulp.task('styles', function () {
    gulp.src(assetFolder + '*/less/styles.less')
        .pipe(less())
        .pipe(minify())
        .pipe(rename(function(path){
            path.dirname = path.dirname.replace('/less', '/css');
            path.extname = '.min.css';
        }))
        .pipe(prefix())
        .pipe(gulp.dest(outputFolder));
});

// Task scripts
gulp.task('scripts', function() {
 	gulp.src(assetFolder + '*/js/*.js')
    	.pipe(uglify())
    	.pipe(rename(function(path){
            var suffix = '.min';
            if(path.basename.indexOf(suffix, path.basename.length - suffix.length) === -1)
                path.extname = '.min' + path.extname;
        }))
    	.pipe(gulp.dest(outputFolder));
});

// Task images
gulp.task('images', function () {
    gulp.src(assetFolder + '*/images/*.{png,gif,jpg}')
        .pipe(imagemin())
        .pipe(gulp.dest(outputFolder));
});

// Task watch
gulp.task('watch', function () {

  	gulp.watch(assetFolder + '*/css/**', ['styles']);
  	gulp.watch(assetFolder + '*/less/**', ['styles']);
  	gulp.watch(assetFolder + '*/js/**', ['scripts']);
  	gulp.watch(assetFolder + '*/images/**', ['images']);

});

// The default task (called when you run `gulp` from cli)
gulp.task('default', ['styles', 'scripts', 'images', 'watch']);