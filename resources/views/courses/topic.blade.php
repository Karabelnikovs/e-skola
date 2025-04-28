@extends('layouts.app')

@section('content')
    @php
        $translations = [
            'lv' => [
                'title' => 'Teorija',
            ],
            'en' => [
                'title' => 'Theory',
            ],
            'ru' => [
                'title' => 'Теория',
            ],
            'ua' => [
                'title' => 'Теорія',
            ],
        ];

        $lang = Session::get('lang', 'lv');
    @endphp
    <div class="card-body">
        <a href="{{ route('module.show', $course_id) }}" class="btn btn-label-info btn-round me-2 mb-3 "><i
                class="fas fa-arrow-circle-left "></i>
            Atpakaļ</a>
        <h1>{{ $title }} | {{ $translations[$lang]['title'] ?? 'Teorija' }}</h1>
        <div class="row px-4">

            {!! $topic->{'content_' . $lang} !!}


        </div>
    </div>
@endsection
