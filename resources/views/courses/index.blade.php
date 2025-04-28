@extends('layouts.app')

@section('content')
    @php
        $translations = [
            'lv' => [
                'title' => 'Pieejamie Moduļi',
                'button' => 'Apskatīt moduli',
            ],
            'en' => [
                'title' => 'Available Modules',
                'button' => 'View Module',
            ],
            'ru' => [
                'title' => 'Доступные модули',
                'button' => 'Посмотреть модуль',
            ],
            'ua' => [
                'title' => 'Доступні модулі',
                'button' => 'Переглянути модуль',
            ],
        ];

        $lang = Session::get('lang', 'lv');
        // dd($lang);
    @endphp
    <div class="card-body">
        <h1>{{ $translations[$lang]['title'] ?? 'Pieejamie Moduļi' }}</h1>
        <div class="row px-4">
            @foreach ($courses as $course)
                <div class="card me-4" style="width: 24rem;">
                    <div class="card-img-top-container overflow-hidden rounded m-2" style="height: 14rem;">
                        <img src="{{ $course->img }}" alt="card-image" class="w-100 h-100 object-fit-cover">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-dark mb-2 fw-semibold">
                            {{ $course->{'title_' . $lang} }}
                        </h5>
                        <p class="card-text text-secondary font-weight-light">

                            {{ $course->{'description_' . $lang} }}


                        </p>
                        <a href="{{ route('module.show', $course->id) }}" class="btn btn-primary btn-round">
                            {{ $translations[$lang]['button'] ?? 'Apskatīt moduli' }}
                        </a>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection
