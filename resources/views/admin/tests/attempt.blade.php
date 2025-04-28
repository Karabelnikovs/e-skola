@extends('layouts.admin')

@section('content')
    @php

        $lang = Session::get('lang', 'lv');
    @endphp

    <div class="card-body">
        <a href="/test/attempts/{{ $attempt->test_id }}/{{ $attempt->user_id }}"
            class="btn btn-label-info btn-round me-2 mb-3">
            <i class="fas fa-arrow-circle-left"></i> Atpakaļ
        </a>
        <div>
            <h1 class="fw-bold mb-3">{{ $title }}</h1>
            <h6 class="op-7 mb-2">{!! $attempt->passed
                ? '<h8 class="fw-bold mb-3"><span class="badge badge-success"><i class="fas fa-check-circle"></i> Nokārtots </span></h8>'
                : '<h8 class="fw-bold mb-3"><span class="badge badge-danger"><i class="fas fa-times-circle"></i> Nenokārtots </span></h8>' !!}
                <i class="far fa-arrow-alt-circle-right"></i>
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
                        <h3 class="mb-2">{{ $item->question_lv }}</h3>
                        <p class="mb-1">
                            <em>Izvēlētā atbilde:</em> <span
                                class="fw-bold">{{ $item->options_lv[$item->answer_given] }}</span>
                        </p>
                        <p class="mb-0">
                            {!! $item->is_correct
                                ? '<h8 class=" mb-3"><span class="badge badge-success"><i class="fas fa-check-circle"></i> Pareizi </span></h8>'
                                : '<h8 class=" mb-3"><span class="badge badge-danger"><i class="fas fa-times-circle"></i> Nepareizi </span> <em> Pareizā atbilde:</em> ' .
                                    $item->options_lv[$item->correct_answer] .
                                    '</h8>' !!}

                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
