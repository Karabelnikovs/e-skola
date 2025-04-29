@extends('layouts.admin')

@section('content')
    @php

        $lang = Session::get('lang', 'lv');
    @endphp
    <div class="card-body">
        <a href="/admin" class="btn btn-label-info btn-round me-2 mb-3 "><i class="fas fa-arrow-circle-left "></i>
            Atpakaļ</a>
        <h1>{{ $title }}</h1>
        <div>
            @if (count($topics) == 0)
                <div class="alert alert-info" role="alert">
                    Pagaidām nav nevienas tēmas...
                </div>
            @endif
            @foreach ($topics as $item)
                <div class="card card-body d-flex flex-row justify-content-between align-items-center mb-3">
                    <div>
                        <h3 class="fw-bold mb-3">{{ $item->title_lv }}</h3>
                        <h8 class="mb-3">{{ $courses->where('id', $item->course_id)->first()?->title_lv }}
                        </h8>
                    </div>
                    <div class="ms-md-auto py-2 py-md-0">
                        <a href="{{ route('topic.view', ['id' => $item->id, 'type' => 'topics']) }}"
                            class="btn btn-label-info btn-round me-2">Apskatīt tēmu <i class="fas fa-angle-right"></i></a>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection
