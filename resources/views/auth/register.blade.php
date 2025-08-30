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

    <title>Sign In - EIL - POS || Eclipse Intellitech LTD POS Software</title>

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

                <div class="row w-100 mx-0 auth-page">
                    <div class="col-md-8 col-xl-6 mx-auto">
                        <div class="card">
                            <div class="row">

                                <div class="col-md-12 px-5">
                                    <div class="auth-form-wrapper px-4 py-5">
                                        <a href="#" class="noble-ui-logo d-block mb-2">EIL<span>POS</span></a>
                                        <h5 class="text-muted fw-normal mb-4">Sign Up EIL POS</h5>
                                        <form class="forms-sample" method="POST" action="{{ route('register') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="exampleInputUsername1" class="form-label">Name</label>
                                                <input type="text" class="form-control" name="name"
                                                    id="exampleInputUsername1" autocomplete="Username"
                                                    placeholder="Username">
                                                <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                                            </div>
                                            <div class="mb-3">
                                                <label for="userEmail" class="form-label">Email address</label>
                                                <input type="email" class="form-control" id="userEmail"
                                                    placeholder="Email" name="email">
                                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="userPassword" class="form-label">Password</label>
                                                    <input type="password" class="form-control" id="userPassword"
                                                        autocomplete="current-password" name="password"
                                                        placeholder="Password">
                                                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="userPassword" class="form-label">Confirm
                                                        Password</label>
                                                    <input type="password" class="form-control" id="userPassword"
                                                        autocomplete="current-password" name="password_confirmation"
                                                        placeholder="Password">
                                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                                                </div>
                                            </div>
                                            {{-- <div class="mb-3">
                                               
                                            </div> --}}
                                            {{-- <div class="mb-3">
                                              
                                            </div> --}}
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" id="authCheck">
                                                <label class="form-check-label" for="authCheck">
                                                    Remember me
                                                </label>
                                            </div>
                                            <div>
                                                <button class="btn btn-primary text-white me-2 mb-2 mb-md-0">Sign
                                                    up</button>
                                            </div>
                                            <a href="{{ route('login') }}" class="d-block mt-3 text-muted">Already a
                                                user? Sign
                                                in</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
