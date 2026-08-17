@extends('frontend.layout.app')
@push('header')
@endpush
@section('content')
    <div id="catelogues">
        <div class="breadcrumb__nk">
            <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / <a href="{{ route('forms') }}" class="text-light">{{ trans('language.form') }}</a> / {{ $catalogue->title }} </div>
        </div>
        <div class="container pb-5">
            <h1 class="page_title text-start">{{ $catalogue->title }}</h1>
			<p class="text-center"><b>Note:</b> Upload your customized form <a href="" data-bs-toggle="modal" data-bs-target="#uploadFormModel" class="text-primary"><b>here</b></a> </p>
            <div class="row position-relative">
                <div id="container" class="col-lg-12 h-100">
					<form id="pdfForm" action="{{ route('forms.submit') }}" method="post" enctype="multipart/form-data"> 
						@csrf
						<input type="hidden" name="file_content" id="fileContent">
						<iframe id="pdfIframe" src="{{ asset('uploads/catalogue-files/'.$catalogue->file)}}" height="1000" width="100%" title="{{ $catalogue->title }}"></iframe>
						<!--<button type="button" onclick="" class="btn btn-primary">Submit</button>-->
					<form>
				</div>
            </div>
        </div>
    </div>
	<div class="modal fade" id="uploadFormModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Upload your form</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form method="post" action="{{ route('forms.submit') }}" enctype="multipart/form-data"> 
						@csrf 
						<div class="row">
							<div class="col-lg-6 form-group">
								<label>Your Name<span class="text-danger">*</span></label>
								<input type="text" name="name" class="form-control" required="" />
							</div>
							
							<div class="col-lg-6 form-group">
								<label>Select Form<span class="text-danger">*</span></label>
								<input type="file" name="file" class="form-control" accept="pdf/*" required="" />
							</div>
							
							<div class="col-lg-12 form-group">
								<label>Your Email<span class="text-danger">*</span></label>
								<input type="email" name="email" class="form-control" required="" />
							</div>
							
							<div class="col-lg-12 form-group">
								<label>Note</label>
								<textarea class="form-control" rows="5" name="note" ></textarea>
							</div>
							
							<div class="col-lg-12 form-group mt-2 text-end">
								<button type="submit" class="btn btn-success">Submit</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
    @push('footer')
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

		<!-- Include html2canvas library -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
		<script type="text/javascript">
			window.jsPDF = window.jspdf.jsPDF;

			$("button").click(function () {
				// Create a new jsPDF instance
				var pdf = new window.jsPDF();

				// Assuming "pdfIframe" is the ID of the iframe
				var iframe = document.getElementById("pdfIframe");

				// Get the content of the iframe
				var content = iframe.contentDocument.body;

				// Use the html2canvas method to capture content as an image
				html2canvas(content, {
					scale: 2,
					logging: true,
					useCORS: true,
				}).then(function (canvas) {
					// Add the canvas as an image to the PDF
					pdf.addImage(canvas.toDataURL("image/png"), "PNG", 15, 15);

					// Get the binary data of the generated PDF
					var pdfData = pdf.output('arraybuffer');

					const csrfToken = $('meta[name="csrf-token"]').attr('content');

					// Convert the binary data to base64
					var binaryString = String.fromCharCode.apply(null, new Uint8Array(pdfData));
					var base64Data = btoa(binaryString);

					fetch('{{ route('forms.submit') }}', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': csrfToken,
						},
						body: JSON.stringify({ pdfData: base64Data }),
					})
						.then(response => {
							if (!response.ok) {
								throw new Error(`HTTP error! Status: ${response.status}`);
							}
							return response.json();
						})
						.then(data => console.log(data))
						.catch(error => {
							console.error('Error:', error);
							// Check if the error is a Response with text() method
							if (error instanceof Response && typeof error.text === 'function') {
								error.text().then(errorMessage => console.error('Response Text:', errorMessage));
							} else {
								console.error('Unable to retrieve response text.');
							}
						});
				});
			});

		</script>
    @endpush
@endsection
