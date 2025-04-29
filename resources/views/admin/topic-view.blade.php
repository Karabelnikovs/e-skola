@extends('layouts.admin')

@section('content')
    @php

    @endphp
    <div class="card-body">
        <a href="{{ route('topics') }}" class="btn btn-label-info btn-round me-2 mb-3 "><i
                class="fas fa-arrow-circle-left "></i>
            Atpakaļ</a>
        <h1>{{ $title }}</h1>
        <div class="row px-4">

            {!! $topic->content_lv !!}


        </div>
    </div>
@endsection
