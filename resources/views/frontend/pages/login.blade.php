@extends('frontend.layout.app')
@section('content')
    <div id="login">
        <div class="img-container">
            <img class="w-100 h-100" src="{{ asset('assets/img/login.png') }}" alt="">
            <div>
                <h1>Machine Tools</h1>
                <p>Premium Grade work-holding equipment and components, optimizing your machine tools for peak performance.</p>
            </div>
        </div>
        <div class="form-container p-5">
            {{-- <img src="{{ asset('assets/img/Logo.png') }}" alt=""> --}}
            <p class="login__welcome__text">
                Welcome Back <br>
                To <br>
                Machine Tool Solutions
            </p>
            <form class="w-100">
                <h1>Log In</h1>
                <p>Login using your credentials</p>
                <div class="form-floating mb-3 w-100">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Email address</label>
                </div>
                <div class="form-floating w-100">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                </div>
                <div class="d-flex justify-content-between w-100">
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Remember Me</label>
                    </div>
                    <a href="">Forget Password</a>
                </div>
                <button type="submit" class="btn btn__login_submit mb-3">Submit</button>
                <span>Don't have an account? <a href="{{ url('registration') }}">Register Now</a></span>
            </form>
        </div>
    </div>
@endsection
