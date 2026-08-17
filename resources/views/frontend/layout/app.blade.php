@include('frontend.include.head')

<body>
    <div class="loader-area" id="loader_area">
        <div class="loader" id="loader"></div>
    </div>
    @include('frontend.include.navigation')


    <!-- ======= Hero Section ======= -->

    <main id="main" class="set-margin">
        @yield('content')
    </main>
    <!-- End #main -->

    @include('frontend.include.footer')
