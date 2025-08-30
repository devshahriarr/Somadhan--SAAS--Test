<!DOCTYPE html>

<html lang="en">

<head>
    <!DOCTYPE html>

    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
        <meta name="author" content="NobleUI">
        <meta name="keywords"
            content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

        <title>Electro POS Error Page</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets') }}/vendors/core/core.css">
        <link rel="stylesheet" href="{{ asset('assets') }}/fonts/feather-font/css/iconfont.css">
        <link rel="stylesheet" href="{{ asset('assets') }}/vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="{{ asset('assets') }}/css/demo1/style.css">
        <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.png" />
    </head>

<body>

    <div class="main-wrapper">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">
                @yield('message')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets') }}/vendors/core/core.js"></script>
    <script src="{{ asset('assets') }}/vendors/feather-icons/feather.min.js"></script>
    <script src="{{ asset('assets') }}/js/template.js"></script>
</body>

</html>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
<meta name="author" content="NobleUI">
<meta name="keywords"
    content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

<title>EIL Electro POS</title>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
<!-- End fonts -->

<!-- core:css -->
<link rel="stylesheet" href="{{ asset('assets') }}/vendors/core/core.css">
<!-- endinject -->

<!-- Plugin css for this page -->
<!-- End plugin css for this page -->

<!-- inject:css -->
<link rel="stylesheet" href="{{ asset('assets') }}/fonts/feather-font/css/iconfont.css">
<link rel="stylesheet" href="{{ asset('assets') }}/vendors/flag-icon-css/css/flag-icon.min.css">
<!-- endinject -->

<!-- Layout styles -->
<link rel="stylesheet" href="{{ asset('assets') }}/css/demo1/style.css">
<!-- End layout styles -->

<link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.png" />
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">
                @yield('message')
            </div>
        </div>
    </div>

    <!-- core:js -->
    <script src="{{ asset('assets') }}/vendors/core/core.js"></script>
    <!-- endinject -->

    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="{{ asset('assets') }}/vendors/feather-icons/feather.min.js"></script>
    <script src="{{ asset('assets') }}/js/template.js"></script>
    <!-- endinject -->

    <!-- Custom js for this page -->
    <!-- End custom js for this page -->

</body>

</html>
