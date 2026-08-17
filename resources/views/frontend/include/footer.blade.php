<section class="become-a-partner">
    <div class="{{ URL::current() === url('/registration') ? 'd-none' : 'footer-top' }}">
        <div>
            <h3>{{ trans('language.become_a_partner_today') }}!</h3>
            <p>{{ trans('language.grow_business_together') }}</p>
        </div>
        <a id="register-button" class="jiggle" href="{{ url('registration') }}">{{ trans('language.register') }}</a>
    </div>
</section>
<footer id="footer">
    {{-- <div class="{{ URL::current() === url('/registration') ? 'd-none' : 'footer-top' }}">
        <div>
            <h3>Become a Partner Today!</h3>
            <p>Let’s grow our business together</p>
        </div>
        <a href="{{ url('registration') }}">Register</a>
    </div> --}}
    <div class="container footer-links-container">
        <div class="footer-links">
            <h4>{{ trans('language.company') }}</h4>
            <ul>
                <li><i class="bx bx-chevron-right"></i> <a href="{{ route('news') }}">{{ trans('language.news') }}</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="{{ route('about-us') }}">{{ trans('language.about_us') }}</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="{{ route('contact-us') }}">{{ trans('language.contact') }}</a></li>
            </ul>
        </div>
        <div class="footer-links">
            <h4>{{ trans('language.brands') }}</h4>
			@php
				$footerBrands = Cache::remember('footer_brands', now()->addHours(1), function () {
					return App\Models\Brand::where('status', 1)->get();
				});
			@endphp

            <ul>
                @foreach($footerBrands as $row)
                    <li><i class="bx bx-chevron-right"></i> <a href="{{ route('brand.products', $row->id) }}">{{ $row->title }}</a></li>
                @endforeach
            </ul>
        </div>
        {{-- <div class="footer-links">
            <h4>Site</h4>
            <ul>
                <li><i class="bx bx-chevron-right"></i> <a href="#">Home</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="#">Categories</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="#">Catalogues</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="#">Services</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="#">Media</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="#">News</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="#">Cart</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="#">Wishlist</a></li>
            </ul>
        </div> --}}
        <div class="footer-links">
            <h4>{{ trans('language.legals') }}</h4>
            <ul>
                <li><i class="bx bx-chevron-right"></i> <a href="{{ route('page', 'terms-and-conditions') }}">{{ trans('language.terms_condition') }}</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="{{ route('page', 'privacy-policy') }}">{{ trans('language.privacy_policy') }}</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="{{ route('page', 'return-policy') }}">{{ trans('language.return_policy') }}</a></li>
            </ul>
        </div>
        <div class="footer-links">
            <h4>{{ trans('language.get_in_touch') }}</h4>
            <div class="contact-area">
                <p class="mb-3">{{ trans('language.head_office') }}</p>
                <ul>
                    <li><i class="fa fa-phone"></i> Local (Dhaka): <a href="tel:{{ Helper::getSettings('application_phone') }}"> {{ Helper::getSettings('application_phone') }}</a></li>
                    <li><i class="fa-solid fa-envelope"></i> <a href="mailto:{{ Helper::getSettings('application_email') }}">{{ Helper::getSettings('application_email') }}</a></li>
                    <li class="align-items-baseline"><i class="fa-solid fa-location-dot"></i><a target="_blank" href="https://maps.app.goo.gl/QyitijrSLcREscZx8">{{ Helper::getSettings('application_address') }}</a></li>
                </ul>
            </div>
            <div class="social-links mt-3">
                <a href="{{ Helper::getSettings('facebook_link') }}" target="_blank" class="facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="{{ Helper::getSettings('twitter_link') }}" target="_blank" class="twitter"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="{{ Helper::getSettings('instagram_link') }}" target="_blank" class="instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="{{ Helper::getSettings('linkedin_link') }}" target="_blank" class="linkedin-in"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="{{ Helper::getSettings('youtube_link') }}" target="_blank" class="youtube"><i class="fa-brands fa-youtube"></i></a>
                <a href="{{ route('review.us') }}" target="_blank" class="youtube"><i class="fa-regular fa-star"></i></a>
            </div>
        </div>
    </div>
</footer>
<section class="copy-right">
    <div class="d-flex flex-column justify-content-center align-items-center py-2">
        <p class="m-1 text-light text-center">© {{ date('Y') }} <span class="fw-bold">{{ Helper::getSettings('application_name') }}</span>. {{ trans('language.copy_right') }}</p>
        <p class="m-1 text-light text-center">Developed by <a href="https://lasdigitalsolution.netlify.app/" class="text-light fw-bold" title="Best software company.">LAS Digital Solution</a></p>
    </div>
</section>

{{-- <div id="preloader"></div> --}}
<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="fa fa-arrow-up"></i>
</a>

<script src="{{ asset('assets/js/aos.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" >
</script>

<script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>

<script src="{{ asset('assets/js/main.js') }}"></script>
<script>
    $(document).on('click', '.flag-select', function(e) {
        e.preventDefault();
        console.log('5')
        let language = $(this).attr('data-language');
        $.ajax({
            url: "{{ url('change-language') }}",
            type: "Get",
            data: {
                language: language,
            },
            success: function (response) {
                window.location.reload();
            }
        })
    });
</script>


<script>

    $(document).ready(function() {

    toastr.options = {
      closeButton: true,
      positionClass: "toast-custom-position",
      timeOut: 3000,
    };


    $(document).ready(function() {
  // Toastr initialization and AJAX setup

  $(".add-to-wishlist").click(function(event) {
    event.preventDefault();

    var link = $(this);

    $.ajax({
      url: link.attr('href'),
      method: "GET",
      dataType: "json",
      success: function(response) {
        if (response.status === 'success') {
          toastr.success(response.message);
          link.addClass("added-to-wishlist");

          // Update wishlist counter instantly
          updateWishlistCounter();

          // Show the wishlist badge
          $("#wishlist-counter").show();
        } else {
          toastr.error(response.message);
		  link.removeClass("added-to-wishlist");
		  updateWishlistCounter();

          // Show the wishlist badge
          $("#wishlist-counter").show();
        }
      },
      error: function(xhr, status, error) {
        toastr.error("An error occurred while processing your request.");
      }
    });
  });

  function updateWishlistCounter() {
    // Fetch the updated wishlist count from the server
    $.get('{{ route("get.wishlist.count") }}', function(count) {
      var counter = $("#wishlist-counter");
      counter.text(count); // Update badge counter
      counter.toggle(count > 0); // Show/hide badge based on count
    });
  }
});




$(document).ready(function() {
  $(".add-to-cart").click(function(event) {
    event.preventDefault();

    var link = $(this);

    $.ajax({
      url: link.attr('href'),
      method: "GET",
      dataType: "json",
      success: function(response) {
        if (response.status === 'success') {
          toastr.success(response.message);
          // Other success actions as needed

          // Update cart counter instantly
          updateCartCounter();
        } else {
          toastr.error(response.message);
        }
      },
      error: function(xhr, status, error) {
        toastr.error("An error occurred while processing your request.");
      }
    });
  });

  function updateCartCounter() {
    // Fetch the updated cart count from the server
    $.get('{{ route("get.cart.count") }}', function(count) {
      var counter = $("#cart-counter");
      counter.text(count); // Update badge counter
      counter.toggle(count > 0); // Show/hide badge based on count
    });
  }
});


    });
</script>

@stack('footer')
</body>

</html>
