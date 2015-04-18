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
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css"/>
        <!-- END THEME STYLES -->

        <style>

            body{
                background: url(http://mymaplist.com/img/parallax/back.png);
                background-color: #444;
                background: url(http://mymaplist.com/img/parallax/pinlayer2.png),url(http://mymaplist.com/img/parallax/pinlayer1.png),url(http://mymaplist.com/img/parallax/back.png);
            }

            .vertical-offset-100 {
                padding-top:100px;
            }

            @media screen and (max-device-width: 640px){
                .vertical-offset-100 {
                    padding-top:0px;
                }
            }

        </style>

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

        <!-- Content -->
        @yield('content')

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="{{ asset("js/jquery-1.11.0.min.js") }}"><\/script>')</script>
        <script src="{{ asset('js/jquery.pjax.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/nprogress.min.js') }}" type="text/javascript"></script>

        <!-- Custom project scripts -->
        <script src="{{ asset('js/main.min.js') }}" type="text/javascript"></script>

        <!-- This is a very simple parallax effect achieved by simple CSS 3 multiple backgrounds, made by http://twitter.com/msurguy -->
        <script src="http://mymaplist.com/js/vendor/TweenLite.min.js"></script>

        <script>

            $(document).ready(function() {
                $(document).mousemove(function(e) {
                    TweenLite.to($('body'), .5, {
                        css: {
                            backgroundPosition: "" + parseInt(event.pageX / 8) + "px " + parseInt(event.pageY / '12') + "px, " + parseInt(event.pageX / '15') + "px " + parseInt(event.pageY / '15') + "px, " + parseInt(event.pageX / '30') + "px " + parseInt(event.pageY / '30') + "px"
                        }
                    });
                });
            });

        </script>

        @section('scripts')

        @show

	</body>
</html>
