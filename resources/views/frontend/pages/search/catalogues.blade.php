@foreach($catalogues as $catalogue)
    <div class="card">
		<a href="{{ route('view.catalogue', $catalogue->slug) }}">
			<img src="{{ asset('uploads/catalogue-images/'.$catalogue->thumbnail) }}" alt="">
		</a>
        <p class="text-uppercase catelogues__product--title" title="{{ $catalogue->title }}">{{ Str::of($catalogue->title)->limit(15) }}</p>
        <p class="catelogues__product--info">{{$catalogue->short_description}}</p>
        <div class="catelogues__product--footer">
            {{-- <a href="{{ route('view.catalogue', $catalogue->slug) }}" class="btn btn-sm catelogues__product--read">Book View</a> --}}
            <a href="@if ($catalogue->getTranslation(Session::get('language'), 'file') != null) {{ asset('uploads/catalogue-files/'.$catalogue->getTranslation(Session::get('language'), 'file')) }} @else {{ asset('uploads/catalogue-files/'.$catalogue->file) }} @endif" target="_blank" class="btn btn-sm catelogues__product--read">New Page</a>
        </div>
		<div class="d-flex justify-content-around w-100 mt-1">
            <a href="{{ route('download.catalogue', ['lang' => 'en', 'catelogue_id' => $catalogue->id]) }}" class="text-decoration-underline">EN <i class="fa-solid fa-download"></i></a>
            
            @if ($catalogue->getTranslation('fr', 'file') != null)
                <a href="{{ route('download.catalogue',  ['lang' => 'fr', 'catelogue_id' => $catalogue->id]) }}" class="text-decoration-underline">FR <i class="fa-solid fa-download"></i></a>
            @endif
           
            @if ($catalogue->getTranslation('es', 'file') != null)
                <a href="{{ route('download.catalogue',  ['lang' => 'es', 'catelogue_id' => $catalogue->id]) }}" class="text-decoration-underline">ES <i class="fa-solid fa-download"></i></a>
            @endif
            
            @if ($catalogue->getTranslation('pt', 'file') != null)
                <a href="{{ route('download.catalogue',  ['lang' => 'pt', 'catelogue_id' => $catalogue->id]) }}" class="text-decoration-underline">PT <i class="fa-solid fa-download"></i></a>
            @endif
            
			{{-- <a href="{{ asset('uploads/catalogue-files/'.$catalogue->file) }}" target="_blank" class="btn btn-sm catelogues__product--download mt-1 w-100">New Page</a> --}}
		</div>
    </div>
@endforeach