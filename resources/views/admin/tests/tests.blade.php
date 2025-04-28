@extends('layouts.admin')

@section('content')
    @php

        $lang = Session::get('lang', 'lv');
    @endphp
    <div class="card-body">
        <a href="/tests/users-history" class="btn btn-label-info btn-round me-2 mb-3 "><i
                class="fas fa-arrow-circle-left "></i>
            Atpakaļ</a>
        <h1>{{ $title }}</h1>
        <div>
            @if (count($tests) == 0)
                <div class="alert alert-info" role="alert">
                    Pagaidām nav neviena testa...
                </div>
            @endif
            @foreach ($tests as $item)
                <div class="card card-body d-flex flex-row justify-content-between align-items-center mb-3">
                    <div>
                        <h3 class="fw-bold mb-3">{{ $item->title_lv }}</h3>
                        <h8 class="mb-3">Mēģinājumu skaits: {{ $item->attempts_count }}
                            @if ($item->passed == 1)
                                <span class="badge badge-success fw-bold"><i class="fas fa-check-circle"></i>
                                    Nokārtots</span>
                            @endif
                        </h8>
                    </div>
                    <div class="ms-md-auto py-2 py-md-0">
                        <a href="{{ route('test.attempts', [$item->id, $user->id]) }}"
                            class="btn btn-label-info btn-round me-2">Mēģinājumu vēsture <i
                                class="fas fa-angle-right"></i></a>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection
