@extends('layouts.admin')

@section('content')
    @php

        $lang = Session::get('lang', 'lv');
    @endphp

    <div class="card-body">
        <a href="/tests/history/{{ $userID }}" class="btn btn-label-info btn-round me-2 mb-3">
            <i class="fas fa-arrow-circle-left"></i> Atpakaļ
        </a>

        <h1>{{ $title }}</h1>

        <div>
            @if ($attempts->isEmpty())
                <div class="alert alert-info" role="alert">
                    Lietotājs nav mēģinājis iziet nevienu testu...
                </div>
            @else
                @foreach ($attempts as $item)
                    <div
                        class="card card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                        <div>
                            <h3 class="mb-2">{{ $item->title_lv }}</h3>
                            <p class="mb-1">
                                Rezultāts: {{ $item->score }} / {{ $question_count }}
                            </p>
                            <p class="mb-0">
                                {!! $item->passed
                                    ? '<h8 class="fw-bold mb-3"><span class="badge badge-success"><i class="fas fa-check-circle"></i> Nokārtots </span></h8>'
                                    : '<h8 class="fw-bold mb-3"><span class="badge badge-danger"><i class="fas fa-times-circle"></i> Nenokārtots </span></h8>' !!}

                            </p>
                        </div>

                        <div class="ms-md-auto py-2 py-md-0">
                            <a href="{{ route('test.attempt', ['id' => $item->id]) }}"
                                class="btn btn-label-info btn-round me-2">
                                Apskatīt mēģinājumu <i class="fas fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
