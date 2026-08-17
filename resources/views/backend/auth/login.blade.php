<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Login - Nimi Enterprise</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="{{ asset('assets/css/backend/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">

    <style>
        :root {
            --primary: #f85606;
            --primary-dark: #d94a04;
            --primary-soft: #fff1e8;
            --dark: #111827;
            --muted: #6b7280;
            --border: #e5e7eb;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
            overflow-x: hidden;
            font-family: "Poppins", "Open Sans", sans-serif;
            background: #fff;
        }

        body {
            margin: 0;
        }

        #layoutAuthentication {
            min-height: 100vh;
            padding-top: 92px;
            background:
                radial-gradient(circle at top left, rgba(248, 86, 6, .14), transparent 32%),
                linear-gradient(135deg, #fff7f2 0%, #ffffff 48%, #f8fafc 100%);
        }

        #layoutAuthentication_content {
            min-height: calc(100vh - 92px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 35px 15px;
        }

        .content_middle {
            width: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        .layoutAuthentication_content-wrapper {
            width: 100%;
            max-width: 460px;
            padding: 0;
        }

        .login-card {
            position: relative;
            background: rgba(255, 255, 255, .92);
            border: 1px solid rgba(248, 86, 6, .12);
            border-radius: 28px;
            padding: 34px;
            box-shadow: 0 24px 70px rgba(17, 24, 39, .13);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            overflow: hidden;
        }

        .login-card::before {
            content: "";
            position: absolute;
            top: -80px;
            right: -80px;
            width: 180px;
            height: 180px;
            background: rgba(248, 86, 6, .14);
            border-radius: 50%;
        }

        .login-card::after {
            content: "";
            position: absolute;
            bottom: -90px;
            left: -90px;
            width: 190px;
            height: 190px;
            background: rgba(248, 86, 6, .08);
            border-radius: 50%;
        }

        .login-card > * {
            position: relative;
            z-index: 2;
        }

        .login-title {
            text-align: center;
            margin-bottom: 26px;
        }

        .login-title h3 {
            font-size: 30px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .login-title p {
            color: var(--muted);
            font-size: 14px;
            margin: 0;
        }

        .alert {
            border-radius: 16px;
            font-size: 14px;
            padding: 12px 14px;
        }

        .alert ul {
            margin-bottom: 0;
            padding-left: 18px;
        }

        .form-floating {
            margin-bottom: 16px !important;
        }

        .form-floating > .form-control {
            height: 58px;
            border-radius: 16px;
            border: 1px solid var(--border);
            font-size: 14px;
            color: var(--dark);
            box-shadow: none;
            background: #fff;
        }

        .form-floating > label {
            color: var(--muted);
            font-size: 14px;
            padding-left: 16px;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(248, 86, 6, .13);
        }

        .form-check {
            gap: 8px;
            align-items: center;
        }

        .form-check-input {
            cursor: pointer;
            border-color: #cbd5e1;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            color: var(--dark);
            font-size: 14px;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .login-btn {
            min-height: 54px;
            border-radius: 16px;
            background: var(--primary);
            border: none;
            color: #fff;
            font-weight: 800;
            font-size: 15px;
            box-shadow: 0 14px 30px rgba(248, 86, 6, .32);
            transition: .25s ease;
        }

        .login-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 18px 34px rgba(248, 86, 6, .42);
        }

        .register-text {
            text-align: center;
            margin: 26px 0 0;
            color: var(--muted);
            font-size: 14px;
        }

        .register-text a {
            color: var(--primary);
            font-weight: 800;
            text-decoration: none;
        }

        .register-text a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            #layoutAuthentication {
                padding-top: 74px;
            }

            #layoutAuthentication_content {
                min-height: calc(100vh - 74px);
                padding: 22px 14px;
                align-items: flex-start;
            }

            .login-card {
                padding: 26px 20px;
                border-radius: 22px;
                margin-top: 20px;
            }

            .login-title h3 {
                font-size: 25px;
            }

            .login-title p {
                font-size: 13px;
            }

            .form-floating > .form-control {
                height: 54px;
                border-radius: 14px;
            }

            .form-check.d-flex {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 10px;
            }

            .login-btn {
                min-height: 52px;
                border-radius: 14px;
            }
        }

        @media (max-width: 420px) {
            #layoutAuthentication_content {
                padding: 16px 10px;
            }

            .login-card {
                padding: 22px 16px;
                border-radius: 20px;
                margin-top: 12px;
            }

            .login-title h3 {
                font-size: 23px;
            }

            .form-check-label,
            .forgot-link,
            .register-text {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    @include('frontend.include.navigation')

    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <div class="row content_middle">
                <div class="col-lg-4 col-md-7 col-sm-10 layoutAuthentication_content-wrapper">
                    <div class="login-card">

                        <div class="login-title">
                            <h3>{{ trans('language.login') }}</h3>
                            <p>Welcome back! Please login to continue.</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(Session::has('message'))
                            <div class="alert alert-success alert-dismissible">
                                <p class="mb-0">{{ Session::get('message') }}</p>
                            </div>
                        @endif

                        <form action="{{ url('login-post') }}" method="post">
                            @csrf

                            <div class="form-floating">
                                <input class="form-control" id="inputEmail" type="email" name="email"
                                    placeholder="name@example.com" required />
                                <label for="inputEmail">
                                    {{ trans('language.label_email') }} <span class="text-danger">*</span>
                                </label>
                            </div>

                            <div class="form-floating">
                                <input class="form-control" id="inputPassword" name="password" type="password"
                                    placeholder="{{ trans('language.label_password') }}" required />
                                <label for="inputPassword">
                                    {{ trans('language.label_password') }} <span class="text-danger">*</span>
                                </label>
                            </div>

                            <div class="form-check d-flex justify-content-between mb-3">
                                <div>
                                    <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                    <label class="form-check-label" for="inputRememberPassword">
                                        {{ trans('language.label_remember') }}
                                    </label>
                                </div>

                                <div>
                                    <a class="forgot-link" href="{{ url('reset-password') }}">
                                        {{ trans('language.label_forgot_password') }}?
                                    </a>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                <button type="submit" class="btn login-btn w-100">
                                    {{ trans('language.login') }}
                                </button>
                            </div>
                        </form>

                        <p class="register-text">
                            {{ trans('language.label_have_account') }}?
                            <a href="{{ url('registration') }}">
                                {{ trans('language.label_register_now') }}
                            </a>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/backend/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/7e596160a4.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/backend/scripts.js') }}"></script>
</body>

</html>