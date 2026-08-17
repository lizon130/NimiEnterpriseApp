@foreach($news as $row)
    <div class="col-lg-4 mb-2">
		<div class="news__card">
			<div class="news__image-section">
				<a href="{{ route('news.details', $row->slug) }}" class="">
					<img src="{{ asset('uploads/news-images/'.$row->media) }}" alt="" class="img-fluid">
				</a>
			</div>
			<div class="news__details-section">
				<a href="{{ route('news.details', $row->slug) }}" class="">
					<h5 class="news__card--title">{{ strtolower($row->getTranslation(Session::get('language') ?? 'en', 'title') ?? $row->title) }}</h5>
				</a>
				<span class="">{{ date('d F, Y', strtotime($row->publish_date)) }}</span>
				<a href="{{ route('news.details', $row->slug) }}" class="d-block">{{ trans('language.btn_read_more') }}</a>
			</div>
		</div>
    </div>
@endforeach