<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">

        <!-- BEGIN THEME STYLES -->
        <link href="{{ asset(theme_path('css/bootstrap.min.css')) }}" rel="stylesheet" type="text/css" id="style_color"/>
        <link href="{{ asset(theme_path('css/main.css')) }}" rel="stylesheet" type="text/css"/>
        <!-- END THEME STYLES -->

        @section('styles')
            
        @show
        
    </head>

	<body style="width: 900px; padding-top: 10px;">
        
        <!-- Content -->
        @yield('content')

	</body>
</html>
