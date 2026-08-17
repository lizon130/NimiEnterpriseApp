@extends('frontend.layout.app')
@push('header')
	<style>
		.card-title{
			margin-bottom: 1rem;
		}
		.card i{
			font-size: 4rem;
		}
		.review-page-container{
			background-color: #eceeef;
		}
		
		.card-great:hover{
			color: white !important;
			background-color: #198754;
		}
		.card-great:hover .text-success{
			color: white !important;
			background-color: #198754;
		}
		
		.card-neutral:hover{
			color: white !important;
			background-color: gray ;
		}
		.card-neutral:hover .card-title{
			color: white !important;
			background-color: gray ;
		}
		.card-neutral:hover i{
			color: white !important;
			background-color: gray ;
		}
		
		.card-not-happy:hover{
			color: white !important;
			background-color: #DC3545;
		}
		.card-not-happy:hover .text-danger{
			color: white !important;
			background-color: #DC3545;
		}
		
		.google-icon{
			font-size: 5em;
		}
		
		.fb-icon{
			font-size: 5em;
		}
		
	</style>
@endpush
@section('content')
	<div class="container-fluid review-page-container pb-5" >
		<div class="row  justify-content-center">
			<div class="col-lg-12">
				<h1 class="page_title py-5">How would you rate your experience?<h1>
			</div>
			<div class="col-lg-2">
				<a href="" data-bs-toggle="modal" data-bs-target="#exampleModal">
					<div class="card card-great">
						<div class="card-body text-center">
							<h5 class="card-title text-success">Great</h5>
							<i class="fa-regular fa-face-smile text-success"></i>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-2">
				<a href="" data-bs-toggle="modal" data-bs-target="#exampleModal">
					<div class="card card-neutral">
						<div class="card-body text-center">
							<h5 class="card-title">Neutral</h5>
							<i class="fa-regular fa-face-meh"></i>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-2">
				<a href="" data-bs-toggle="modal" data-bs-target="#exampleModal">
					<div class="card card-not-happy">
						<div class="card-body text-center">
							<h5 class="card-title text-danger">Not Happy</h5>
							<i class="fa-regular fa-face-frown text-danger"></i>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Select your social media platform</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="d-flex justify-content-center">
						<a target="_blank" href="https://www.google.com/maps/place/Machine+Tool+Solutions+Ltd./@43.7555665,-79.7127096,17z/data=!4m8!3m7!1s0x882b3d102112c0cb:0xb974a2fdb36870dc!8m2!3d43.755527!4d-79.7099143!9m1!1b1!16s%2Fg%2F1tm1r2rq?entry=ttu" class="text-danger google-icon"><i class="fa-brands fa-google"></i></a>
						<a target="_blank" href="https://www.facebook.com/machinetoolsolutionsltd/reviews" class="text-info fb-icon ms-4"><i class="fa-brands fa-facebook"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
