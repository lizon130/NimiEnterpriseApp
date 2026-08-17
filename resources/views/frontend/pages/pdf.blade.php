@extends('frontend.layout.app')
@section('content')
<div id="pdf">
    <iframe src="{{ asset('assets/pdf.pdf') }}" frameborder="0"></iframe>
    <a href="{{ url('calculator') }}" class="calculator-btn" target="myWindow" onclick="window.open(this.href, 'myWindow', 'width=500,height=400'); return false;"><i class="fa-solid fa-calculator"></i></a>
</div>
@endsection
