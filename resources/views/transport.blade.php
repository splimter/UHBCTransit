@extends('layouts.app')
@section('title')
    Transport
@endsection
@section('content')
    <nav>
        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab"
               href="#nav-bus" role="tab">Bus</a>
            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab"
               href="#nav-driver" role="tab">Drivers</a>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-bus" role="tabpanel">
            @include('pages.bus')
        </div>
        <div class="tab-pane fade" id="nav-driver" role="tabpanel">
            @include('pages.driver')
        </div>
    </div>
@endsection
