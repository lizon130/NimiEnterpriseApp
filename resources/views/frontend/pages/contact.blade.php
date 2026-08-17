@extends('frontend.layout.app')
@section('content')
	<div class="breadcrumb__nk">
		<div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / {{ trans('language.contact') }} </div>
	</div>
	<div id="contact" class="container">
		<h1 class="contact__title mb-0">{{ trans('language.get_in_touch') }}!</h1>
		<p class="text-center mb-5">{{ trans('language.contact_us_for_inquery') }} </p>
		<nav class="tab__options--container">
			<div class="nav nav-tabs mx-auto w-100 d-flex justify-content-center gap-3 border border-0" id="nav-tab" role="tablist">
				@foreach($addresses as $address)
					<button class="nav-link @if($address->is_default == 1) active @endif" id="nav-{{$address->id}}-tab" data-bs-toggle="tab" data-bs-target="#nav-{{$address->id}}" type="button" role="tab" aria-controls="nav-{{$address->id}}" aria-selected="true">{{$address->title}}</button>
				@endforeach

			</div>
		  </nav>
		  <div class="tab-content" id="nav-tabContent">
			@foreach($addresses as $address)
				<div class="tab-pane fade @if($address->is_default == 1) show active @endif" id="nav-{{$address->id}}" role="tabpanel" aria-labelledby="nav-{{$address->id}}-tab" tabindex="0">
					<div class="row mb-4">
						<div class="col-lg-4">
							<div class="card bg-theme h-100">
								<div class="card-body text-center text-light">
									<i class="fa-solid fa-location-dot"></i>
									<p>{{$address->address}}</p>
								</div>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="card bg-theme h-100">
								<div class="card-body text-center text-light">
									<i class="fa fa-phone"></i>
									@if($address->phone)
										<p class="mb-0">{{ trans('language.label_phone') }}:{{ $address->phone }} </p>
									@endif
									@if($address->toll_free)
										<p>Toll-Free:{{ $address->toll_free }} </p>
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="card bg-theme h-100">
								<div class="card-body text-center text-light">
									<i class="fa-solid fa-envelope"></i>
									@if($address->email)
										<p class="mb-0">{{ trans('language.label_e_mail') }}:{{ $address->email }}</p>
									@endif    
									@if($address->fax)
										<p>{{ trans('language.label_fax') }}:{{ $address->fax }}</p>
									@endif
								</div>
							</div>
						</div>
					</div>
					<div class="contact__content--container">
						<div>
							@if ($errors->any())
								<div class="alert alert-danger alert-dismissible">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									@foreach ($errors->all() as $error)
										<p class="mb-0">{{ $error }}</p>
									@endforeach
								</div>
							@endif
							@if(Session::has('message'))
								<div class="alert alert-success alert-dismissible">
									<p class="mb-0">{{ Session::get('message') }}</p>
								</div>
							@endif
							@if ($errors->has('g-recaptcha-response'))
								<div class="alert alert-success alert-dismissible">
									<p class="mb-0">{{ $errors->first('g-recaptcha-response') }}</p>
								</div>
							@endif
							<form action="{{ url('contact/submit') }}" method="post" >
								@csrf
								<div class="row">
									<div class="col-lg-6">
										<div class="form-floating mb-3 w-100">
											<input type="text" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_name') }}" name="name" required>
											<label for="floatingInput">{{ trans('language.label_name') }}</label>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-floating mb-3 w-100">
											<input type="email" class="form-control" id="floatingInput" placeholder="{{ trans('language.email') }}" name="email" required>
											<label for="floatingInput">{{ trans('language.email') }}</label>
										</div>
									</div>
								</div>

								<div class="form-floating mb-3 w-100">
									<input type="text" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_phone') }}" name="Phone" required>
									<label for="floatingInput">{{ trans('language.label_phone') }}</label>
								</div>
								<div class="form-floating mb-3 w-100">
									<input type="text" class="form-control" id="floatingInput" placeholder="{{ trans('language.subject') }}" name="subject" required>
									<label for="floatingInput">{{ trans('language.subject') }}</label>
								</div>
								<div class="form-floating mb-3 w-100">
									<textarea type="text" class="form-control" style="height: auto !important;" rows="10" cols="10" id="floatingInput" placeholder="{{ trans('language.message') }}" name="message"></textarea>
									<label for="floatingInput">{{ trans('language.message') }}</label>
								</div >
								<div class="form-floating mb-3 w-100">
									{!! NoCaptcha::renderJs() !!}
									{!! NoCaptcha::display() !!}
								</div>
								
								<div class="text-end">
									<input type="hidden" name="sender_email" value="{{$address->email}}">
									<button type="submit" class="btn w-20">{{ trans('language.btn_send') }}</button>
								</div>
							</form>
						</div>
						<div class="map-container">
							{!! $address->google_map !!}
						</div>
					</div>
				</div>
			@endforeach

		</div>
	</div>
	@push('footer')
		
    @endpush
@endsection
