<!DOCTYPE html>
<html lang="en" ng-app="angularApp">
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
        <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
        <link href="{{ asset(elixir('css/styles.css')) }}" rel="stylesheet" type="text/css"/>
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
        @include('includes/header')

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <!-- Side bar -->
                    @include('includes/sidebar')
                </div>

                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                    <div id="pjax-container">

                        <!-- Notifications -->
                        @include('includes/notifications', array('alertClass' => 'col-md-5 col-lg-4'))

                        <!-- Content -->
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        @include('includes/footer')

        <script src="{{ asset(elixir('js/scripts.js')) }}" type="text/javascript"></script>

        @section('scripts')

        @show

	</body>
</html>
