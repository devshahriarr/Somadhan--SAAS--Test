<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Log In - Eclipse POS || Eclipse Intellitech LTD POS Software">
    <meta name="author" content="eclipse intellitech">
    <meta name="keywords" content="eclipse, pos, eclipse-pos, software, intelligence, technology, intellitech, ltd, ">

    <title>Log In - Eclipse POS || Eclipse Intellitech LTD POS Software</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->

    <!-- core:css -->
    <link rel="stylesheet" href="../../../assets/vendors/core/core.css">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="../../../assets/fonts/feather-font/css/iconfont.css">
    <link rel="stylesheet" href="../../../assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="../../../assets/css/demo1/style.css">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="../../../assets/images/favicon.svg" />
</head>
<style>
    .full-page {
        background-image: url('../../../assets/images/log_in_bg.png') !important;
    }

    .input_design {
        border-radius: 25px;
        padding-right: 40px;
        /* background-color: #f26539 !important; */
        /* background-image: linear-gradient(to bottom right, #f26539, #f6921e); */
        /* color: #fff; */

        border: 1px solid #408dff;
    }

    .input_design::placeholder {
        color: #ccc;
        /* Optional: change placeholder color */
    }

    .input_design:focus {
        color: #408dff;
        /* Ensure text remains white on focus */
        outline: none;
        /* Remove the default outline */
        /* background-color: #444; */
        border-color: #408dff;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        color: #a6a5a8;
        cursor: pointer;
        z-index: 2;
    }

    .check_design {
        border-radius: 50% !important;
    }

    .login_header {
        text-align: center;
        /* color: #fff; */
        background-image: linear-gradient(to bottom right, #408dff, #0664f0);
    }

    .wd_160 {
        width: 160px;
        /* height: 160px;
        border-radius: 50%;
        background-image: url('{{ 'assets/logo.png' }}');
        background-size: cover;
        background-position: center;
        margin-bottom: 20px; */
    }

    .text_custom_header {
        color: #408dff;
        font-weight: 600 !important;
    }

    .text_custom {
        color: #408dff;
    }

    .custom_btn {
        /* background-color: #ff541e; */
        background-image: linear-gradient(to bottom right, #408dff, #0664f0) color: #fff;
        border: none;
        border-radius: 10px;
        padding: 7px 25px;
        cursor: pointer;
    }
</style>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">

                <div class="row w-100 mx-0 auth-page">
                    <div class="col-md-6 col-xl-4 mx-auto">
                        <div class="card">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="auth-form-wrapper ">
                                        <div class="py-5 login_header">
                                            <a href="#" class="noble-ui-logo d-block mb-2">
                                                <img class="wd_160" src="{{ 'assets/logo.png' }}" alt="">
                                            </a>
                                        </div>
                                        <form class="forms-sample px-5 py-5" method="POST"
                                            action="{{ route('login') }}">
                                            @csrf
                                            <h5 class="text_custom_header text-center fw-normal mb-4">Welcome back! Log
                                                in to your account.</h5>
                                            <div class="mb-3 position-relative">
                                                {{-- <label for="userEmail" class="form-label">Email address</label> --}}
                                                <div class="input-wrapper">
                                                    <input type="email" class="form-control input_design"
                                                        name="email" id="userEmail"
                                                        placeholder="Please Enter Your Email Here" value="">

                                                </div>
                                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                                            </div>

                                            <div class="mb-3 position-relative">
                                                {{-- <label for="userPassword" class="form-label">Password</label> --}}
                                                <div class="input-wrapper">
                                                    <input type="password" class="form-control input_design"
                                                        id="userPassword" autocomplete="current-password"
                                                        placeholder="Please Enter Your Password" name="password"
                                                        value="">
                                                    <div id="togglePassword" style="cursor: pointer;"><i
                                                            data-feather="eye" class="input-icon"></i></div>
                                                </div>
                                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                                            </div>

                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input check_design"
                                                    id="authCheck">
                                                <label class="form-check-label text_custom" for="authCheck">
                                                    Remember me
                                                </label>
                                                <a href="#"
                                                    class="float-end text_custom text-decoration-none">Forgot
                                                    Password?</a>
                                            </div>
                                            <div class=" justify-content-center align-items-center d-flex w-full">
                                                {{-- <a class="btn btn-primary me-2 mb-2 mb-md-0 text-white custom_btn "
                                                    id="admin-btn">Admin</a> --}}
                                                <button
                                                    class="btn btn-primary me-2  mb-2 mb-md-0 text-white custom_btn">Login</button>
                                                {{-- <a class="btn btn-primary  mb-2 mb-md-0 text-white custom_btn"
                                                    id="salesman-btn">Slaes Man</a> --}}
                                            </div>
                                            {{-- <a href="{{ route('register') }}" class="d-block mt-3 text-muted">Not a
                                                user? Sign
                                                up</a> --}}
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
    <!-- Custom JS for this page -->
    <script>
        const passwordInput = document.getElementById('userPassword');
        const togglePassword = document.getElementById('togglePassword');
        const icon = togglePassword.querySelector('i'); // Select the icon element inside the div

        togglePassword.addEventListener('click', function() {
            const isPasswordVisible = passwordInput.getAttribute('type') === 'text';

            // Toggle password field visibility
            passwordInput.setAttribute('type', isPasswordVisible ? 'password' : 'text');
            console.log(passwordInput);
            // Toggle the icon's data-feather attribute
            icon.setAttribute('data-feather', isPasswordVisible ? 'eye' : 'eye-off');
            console.log(icon);
            // Re-render Feather icons to update the UI
            feather.replace();
            console.log(icon);
        });
        // document.getElementById('admin-btn').addEventListener('click', function() {
        //     document.getElementById('userEmail').value = 'admin@gmail.com';
        //     document.getElementById('userPassword').value = 123;
        // });
        // document.getElementById('salesman-btn').addEventListener('click', function() {
        //     document.getElementById('userEmail').value = 'salesman@gmail.com';
        //     document.getElementById('userPassword').value = 12345678
        // });
    </script>

    <!-- core:js -->
    <script src="../../../assets/vendors/core/core.js"></script>
    <!-- endinject -->


    <!-- inject:js -->
    <script src="../../../assets/vendors/feather-icons/feather.min.js"></script>
    <script src="../../../assets/js/template.js"></script>
    <!-- endinject -->

    <script>
        window.onload = function() {
            feather.replace(); // Initial render of Feather icons
        };
    </script>
</body>

</html>
