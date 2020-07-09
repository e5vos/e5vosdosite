<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.Laravel = {csrfToken: '{{csrf_token()}}'}</script>

    <title> E5N Codes </title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer> </script>
    <script src="{{ asset('js/qr.js') }}" defer> </script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <style>
    @page {
        size: A4;
        margin: 0;
    }

    .studentcard{
        break-before:page;
        break-inside: avoid-page;
        height: auto;
        text-align: center;
        padding-bottom:20px;
        border: 1px dashed black;
    }

    h3 {
        font: bold 12pt Georgia, "Times New Roman", Times, serif;
        padding-top:2px;
        padding-bottom:2px;
    }
    body {
        font: 10pt Georgia, "Times New Roman", Times, serif;
        line-height: 1.3;
        margin: 0;
    }

    .studentcardgrid {
        display: grid;
        page-break-inside: avoid;
        grid-template-columns: repeat(4, 25%);
        grid-template-rows: repeat(4, 70mm);
        grid-gap: 0;
    }

    img {
        width:100%;
        max-width: 5cm;
        padding-top:3px;
        padding-left: 4px;
        padding-right: 2px;
    }
    </style>
</head>
<body>
<div id="app">
    <qrcodes></qrcodes>
</div>
</body>
</html>
