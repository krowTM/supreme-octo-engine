<!DOCTYPE html>
<html>
    <head>
        <title>CSV Importer</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">CSV Importer</div>
                <div class="setup-button"><a href="#" onclick="App.import(event);">Import</a></div>
                <div class="form-button"><a href="#" onclick="App.charts(event);">Charts</a></div>
                <div class="clear"></div>
                <div class="result"></div>
                <div class="clear"></div>
            </div>
        </div>
    </body>
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script type="text/javascript" src="{{ asset('js/jquery-3.0.0.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/scripts.js') }}"></script>
</html>
