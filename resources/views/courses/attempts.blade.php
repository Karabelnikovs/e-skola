@extends('layouts.app')

@section('content')
    @php
        $translations = [
            'en' => [
                'title' => 'Tests results',
                'button' => 'View Attempt',
                'empty' => 'You have not attempted any tests yet...',
                'score' => 'Score',
                'passed' => 'Passed',
                'not_passed' => 'Not Passed',
                'back' => 'Back',
            ],
            'lv' => [
                'title' => 'Testu rezultāti',
                'button' => 'Apskatīt mēģinājumu',
                'empty' => 'Jūs vēl neesat mēģinājis iziet nevienu testu...',
                'score' => 'Rezultāts',
                'passed' => 'Nokārtots',
                'not_passed' => 'Nenokārtots',
                'back' => 'Atpakaļ',
            ],
            'ru' => [
                'title' => 'Результаты тестов',
                'button' => 'Посмотреть попытку',
                'empty' => 'Вы еще не пытались пройти ни один тест...',
                'score' => 'Результат',
                'passed' => 'Пройдено',
                'not_passed' => 'Не пройдено',
                'back' => 'Назад',
            ],
            'ua' => [
                'title' => 'Результати тестів',
                'button' => 'Переглянути спробу',
                'empty' => 'Ви ще не намагалися пройти жоден тест...',
                'score' => 'Результат',
                'passed' => 'Складено',
                'not_passed' => 'Не складено',
                'back' => 'Назад',
            ],
        ];

        $lang = Session::get('lang', 'lv');
    @endphp

    <div class="card-body">
        <a href="/{{ $lang }}/tests" class="btn btn-label-info btn-round me-2 mb-3">
            <i class="fas fa-arrow-circle-left"></i> {{ $translations[$lang]['back'] }}
        </a>

        <h1>{{ $translations[$lang]['title'] }}</h1>

        <div>
            @if ($attempts->isEmpty())
                <div class="alert alert-info" role="alert">
                    {{ $translations[$lang]['empty'] }}
                </div>
            @else
                @foreach ($attempts as $item)
                    <div
                        class="card card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                        <div>
                            <h3 class="mb-2">{{ $item->{'title_' . $lang} }}</h3>
                            <p class="mb-1">
                                {{ $translations[$lang]['score'] }}: {{ $item->score }} / {{ $question_count }}
                            </p>
                            <p class="mb-0">
                                {!! $item->passed
                                    ? '<h8 class="fw-bold mb-3"><span class="badge badge-success"><i class="fas fa-check-circle"></i> ' .
                                        $translations[$lang]['passed'] .
                                        '</span></h8>'
                                    : '<h8 class="fw-bold mb-3"><span class="badge badge-danger"><i class="fas fa-times-circle"></i> ' .
                                        $translations[$lang]['not_passed'] .
                                        '</span></h8>' !!}

                            </p>
                        </div>

                        <div class="ms-md-auto py-2 py-md-0">
                            <a href="{{ route($lang . '.attempt.view', ['id' => $item->id]) }}"
                                class="btn btn-label-info btn-round me-2">
                                {{ $translations[$lang]['button'] }} <i class="fas fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
