var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {

    // Generate application styles
    mix.less('app.less', 'public/css/styles.css');

    // Combine libraries and save to libraries.js file
    mix.scripts([
        'libraries/jquery-1.11.0.min.js',
        'libraries/jquery.pjax.js',
        'libraries/bootstrap.js',
        'libraries/moment.min.js',
        'libraries/bootstrap-datetimepicker.js',
        'libraries/nprogress.js',
        'libraries/select2.js',
        'libraries/bootbox.js',
        'libraries/d3.v3.js',
        'libraries/c3.js',
        'libraries/toastr.js',
        'libraries/underscore.min.js',
        'libraries/angular.min.js',

    ], 'public/js/libraries.js');

    // Generate application.js file
    mix.browserify([
        'app.js'
    ], 'public/js/application.js');

    // Now merge them both together into one file
    mix.scripts([
        'libraries.js',
        'application.js'
    ], 'public/js/scripts.js', 'public/js');

    // Enable versioning
    mix.version(["css/styles.css", "js/scripts.js"]);

    // And copy fonts and images
    mix.copy('resources/assets/fonts', 'public/build/fonts');
    mix.copy('resources/assets/images', 'public/build/images');

    // Copy html partials
    mix.copy('resources/assets/**/*.html', 'public/html');
});
