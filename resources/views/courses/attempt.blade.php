@extends('layouts.app')

@section('content')
    @php
        $translations = [
            'en' => [
                'title' => 'Attempt results',
                'score' => 'Score',
                'passed' => 'Passed',
                'not_passed' => 'Not Passed',
                'correct' => 'Correct',
                'incorrect' => 'Incorrect',
                'correct_answer' => 'Correct answer',
                'answer_given' => 'Selected answer',
                'back' => 'Back',
            ],
            'lv' => [
                'title' => 'Mēģinājuma rezultāti',
                'score' => 'Rezultāts',
                'passed' => 'Nokārtots',
                'not_passed' => 'Nenokārtots',
                'correct' => 'Pareizi',
                'incorrect' => 'Nepareizi',
                'correct_answer' => 'Pareizā atbilde',
                'answer_given' => 'Izvēlētā atbilde',
                'back' => 'Atpakaļ',
            ],
            'ru' => [
                'title' => 'Результаты попытки',
                'score' => 'Результат',
                'passed' => 'Пройдено',
                'not_passed' => 'Не пройдено',
                'correct' => 'Правильно',
                'incorrect' => 'Неправильно',
                'correct_answer' => 'Правильный ответ',
                'answer_given' => 'Выбранный ответ',
                'back' => 'Назад',
            ],
            'ua' => [
                'title' => 'Результати спроби',
                'score' => 'Результат',
                'passed' => 'Складено',
                'not_passed' => 'Не складено',
                'correct' => 'Правильно',
                'incorrect' => 'Неправильно',
                'correct_answer' => 'Правильна відповідь',
                'answer_given' => 'Вибрана відповідь',
                'back' => 'Назад',
            ],
        ];

        $lang = Session::get('lang', 'lv');
    @endphp

    <div class="card-body">
        <a href="/{{ $lang }}/attempts/{{ $test_id }}" class="btn btn-label-info btn-round me-2 mb-3">
            <i class="fas fa-arrow-circle-left"></i> {{ $translations[$lang]['back'] }}
        </a>
        <div>
            <h1 class="fw-bold mb-3">{{ $translations[$lang]['title'] }}</h1>
            <h6 class="op-7 mb-2">{!! $attempt->passed
                ? '<h8 class="fw-bold mb-3"><span class="badge badge-success"><i class="fas fa-check-circle"></i> ' .
                    $translations[$lang]['passed'] .
                    '</span></h8>'
                : '<h8 class="fw-bold mb-3"><span class="badge badge-danger"><i class="fas fa-times-circle"></i> ' .
                    $translations[$lang]['not_passed'] .
                    '</span></h8>' !!} <i class="far fa-arrow-alt-circle-right"></i>
                {{ $attempt->score }} / {{ $question_count }}
                ({{ number_format(($attempt->score / $question_count) * 100, 0) }}%)
            </h6>
        </div>
        {{-- @dd($answers) --}}
        <div>

            @foreach ($answers as $item)
                <div
                    class="card card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                    <div>
                        <h3 class="mb-2">{{ $item->{'question_' . $lang} }}</h3>
                        <p class="mb-1">
                            <em>{{ $translations[$lang]['answer_given'] }}:</em> <span
                                class="fw-bold">{{ $item->{'options_' . $lang}[$item->answer_given] }}</span>
                        </p>
                        <p class="mb-0">
                            {!! $item->is_correct
                                ? '<h8 class=" mb-3"><span class="badge badge-success"><i class="fas fa-check-circle"></i> ' .
                                    $translations[$lang]['correct'] .
                                    '</span></h8>'
                                : '<h8 class=" mb-3"><span class="badge badge-danger"><i class="fas fa-times-circle"></i> ' .
                                    $translations[$lang]['incorrect'] .
                                    '</span> <em>' .
                                    $translations[$lang]['correct_answer'] .
                                    ':</em> ' .
                                    $item->{'options_' . $lang}[$item->correct_answer] .
                                    '</h8>' !!}

                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
