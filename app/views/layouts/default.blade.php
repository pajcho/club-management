<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>
			@section('title')
                Club Members
			@show
		</title>
        
        <script>
            // Set project base url to use in scripts when needed
            var baseUrl = "{{ url('/') }}";
        </script>
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <meta name="MobileOptimized" content="320">
        
        <!-- Css styles -->
        @section('styles')
            
        @show
        
    </head>

	<body>
        
        <!-- Header -->
        @include('includes/header')
        
        <!-- Notifications -->
        @include('includes/notifications')

        <!-- Content -->
        @yield('content')
        
        <!-- Header -->
        @include('includes/footer')
        
        <!-- Java script -->
        @section('scripts')
            
        @show
	</body>
</html>
