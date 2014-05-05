<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">

        <!-- BEGIN THEME STYLES -->
        <link href="{{ asset(theme_path('css/styles.min.css')) }}" rel="stylesheet" type="text/css"/>
        <!-- END THEME STYLES -->

        @section('styles')
            <style>
                .pdf-body {
                    width: 900px;
                    padding-top: 10px;
                }
                table thead th, table tbody tr {
                    text-align: center;
                }
                table thead tr th.no-border {
                    border: none !important;
                }
                .current {
                    background-color: #f1f1f1;
                }
                .table-bordered,
                .table-bordered > thead > tr > th,
                .table-bordered > tbody > tr > th,
                .table-bordered > tfoot > tr > th,
                .table-bordered > thead > tr > td,
                .table-bordered > tbody > tr > td,
                .table-bordered > tfoot > tr > td {
                    border-color: #000;
                }
            </style>
        @show
        
    </head>

	<body class="pdf-body">
        
        <!-- Content -->
        @yield('content')

	</body>
</html>
