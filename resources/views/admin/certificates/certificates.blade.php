@extends('layouts.app')

@section('content')
    @php

        $lang = Session::get('lang', 'lv');
    @endphp
    <div class="card-body">
        <a href="/certificates" class="btn btn-label-info btn-round me-2 mb-3 "><i class="fas fa-arrow-circle-left "></i>
            Atpakaļ</a>
        <h1>{{ $title }}</h1>
        <div>
            @if (count($certificates) == 0)
                <div class="alert alert-info" role="alert">
                    Vēl nav neviena sertifikāta...
                </div>
            @endif
            @foreach ($certificates as $item)
                <div class="card card-body d-flex flex-row justify-content-between align-items-center mb-3">
                    <div>
                        <h3 class="fw-bold mb-3">{{ $item->title_lv }}</h3>
                        @if ($item->is_read == 0)
                            <span class="badge bg-warning text-dark">Jauns</span>
                        @endif
                    </div>
                    <div class="ms-md-auto py-2 py-md-0">
                        <a href="{{ route('certificate.download', [$item->user_id, $item->course_id]) }}"
                            class="btn btn-label-info btn-round me-2"> Lejupielādēt <i class="fas fa-file-download"></i></a>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection
