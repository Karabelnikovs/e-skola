@extends('layouts.app')

@section('content')
    @php
        $translations = [
            'en' => [
                'title' => 'Tests',
                'button' => 'View Attempts',
                'empty' => 'There are no tests yet...',
                'passed' => 'Passed',
            ],
            'lv' => [
                'title' => 'Testi',
                'button' => 'Apskatīt mēģinājumus',
                'empty' => 'Pagaidām nav neviena testa...',
                'passed' => 'Izpildīts',
            ],
            'ru' => [
                'title' => 'Тесты',
                'button' => 'Посмотреть попытки',
                'empty' => 'Пока нет ни одного теста...',
                'passed' => 'Пройдено',
            ],
            'ua' => [
                'title' => 'Тести',
                'button' => 'Переглянути спроби',
                'empty' => 'Поки немає жодного тесту...',
                'passed' => 'Пройдено',
            ],
        ];

        $lang = Session::get('lang', 'lv');
    @endphp
    <div class="card-body">
        <a href="/" class="btn btn-label-info btn-round me-2 mb-3 "><i class="fas fa-arrow-circle-left "></i>
            Atpakaļ</a>
        <h1>{{ $title }}</h1>
        <div>
            @if (count($tests) == 0)
                <div class="alert alert-info" role="alert">
                    {{ $translations[$lang]['empty'] }}
                </div>
            @endif
            @foreach ($tests as $item)
                <div class="card card-body d-flex flex-row justify-content-between align-items-center mb-3">
                    <div>
                        <h3 class="fw-bold mb-3">{{ $item->{'title_' . $lang} }}</h3>
                        @if ($item->passed == 1)
                            <h8 class="fw-bold mb-3">
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i>
                                    {{ $translations[$lang]['passed'] }}</span>
                            </h8>
                        @endif
                    </div>
                    <div class="ms-md-auto py-2 py-md-0">
                        <a href="{{ route($lang . '.attempts.view', [$item->id]) }}"
                            class="btn btn-label-info btn-round me-2">{{ $translations[$lang]['button'] }} <i
                                class="fas fa-angle-right"></i></a>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection
