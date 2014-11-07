<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>
			@section('title')
                {{ site_title() }}
			@show
		</title>
        
        <script>
            // Set project base url to use in scripts when needed
            var baseUrl = "{{ url('/') }}";
        </script>
        
        <!-- BEGIN THEME STYLES -->
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="{{ asset(theme_path('css/styles.min.css')) }}" rel="stylesheet" type="text/css"/>
        <!-- END THEME STYLES -->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        @section('styles')
            
        @show
        
    </head>

	<body>

        <!-- Loader for PJAX pagination -->
        <div id="loading" class="btn btn-warning" style="position: fixed; top: 10px; left: 50%; z-index: 9999; display: none;">Loading...</div>

        <!-- Header -->
        @include(theme_view('includes/header'))

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <!-- Side bar -->
                    @include(theme_view('includes/sidebar'))
                </div>

                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                    <div id="pjax-container">

                        <!-- Notifications -->
                        @include(theme_view('includes/notifications', array('alertClass' => 'col-md-5 col-lg-4')))

                        <!-- Content -->
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        @include(theme_view('includes/footer'))

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="{{ asset(theme_path("js/jquery-1.11.0.min.js")) }}"><\/script>')</script>
        <script src="{{ asset(theme_path('js/jquery.pjax.min.js')) }}" type="text/javascript"></script>
        <script src="{{ asset(theme_path('js/bootstrap.min.js')) }}" type="text/javascript"></script>
        <script src="{{ asset(theme_path('js/moment.min.js')) }}" type="text/javascript"></script>
        <script src="{{ asset(theme_path('js/bootstrap-datetimepicker.min.js')) }}" type="text/javascript"></script>
        <script src="{{ asset(theme_path('js/nprogress.min.js')) }}" type="text/javascript"></script>
        <script src="{{ asset(theme_path('js/select2.min.js')) }}" type="text/javascript"></script>
        <script src="{{ asset(theme_path('js/bootbox.min.js')) }}" type="text/javascript"></script>

        <!-- Custom project scripts -->
        <script src="{{ asset(theme_path('js/main.min.js')) }}" type="text/javascript"></script>

        @section('scripts')

        @show

	</body>
</html>
