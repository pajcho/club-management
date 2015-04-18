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
    mix.less('app.less');

    mix.scripts([
        'jquery-1.11.0.min.js',
        'jquery.pjax.js',
        'bootstrap.js',
        'moment.min.js',
        'bootstrap-datetimepicker.js',
        'nprogress.js',
        'select2.js',
        'bootbox.js',
        'd3.v3.js',
        'c3.js',
        'toastr.js',
        'main.js'
    ]);

    mix.version(["css/app.css", "js/all.js"]);

    mix.copy('resources/assets/fonts', 'public/build/fonts');
    mix.copy('resources/assets/images', 'public/build/images');
});