<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" href="{{ asset('images/favicon_1.ico') }}">

        <title>OP Admin - OrderPang Admin WebApp.</title>

        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/icons.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/myStyle.css') }}" rel="stylesheet">
    </head>


    <body class="fixed-left">
        <div id="app">
            <div id="wrapper">
                <topbar-component></topbar-component>
                <sidebar-component></sidebar-component>
            </div>
            <div class="content-page" style="background-color: #f5f5f5;">
                <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>

<script src="{{ mix('/js/app.js') }}"></script>