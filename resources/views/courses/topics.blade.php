@extends('layouts.app')

@section('content')
    @php
        $translations = [
            'en' => [
                'button' => 'View Topic',
                'empty' => 'There are no topics yet...',
            ],
            'lv' => [
                'button' => 'Apskatīt tēmu',
                'empty' => 'Pagaidām nav nevienas tēmas...',
            ],
            'ru' => [
                'button' => 'Посмотреть тему',
                'empty' => 'Пока нет ни одной темы...',
            ],
            'ua' => [
                'button' => 'Переглянути тему',
                'empty' => 'Поки немає жодної теми...',
            ],
        ];

        $lang = Session::get('lang', 'lv');
    @endphp
    <div class="card-body">
        <a href="/" class="btn btn-label-info btn-round me-2 mb-3 "><i class="fas fa-arrow-circle-left "></i>
            Atpakaļ</a>
        <h1>{{ $title }}</h1>
        <div>
            @if (count($topics) == 0)
                <div class="alert alert-info" role="alert">
                    {{ $translations[$lang]['empty'] }}
                </div>
            @endif
            @foreach ($topics as $item)
                <div class="card card-body d-flex flex-row justify-content-between align-items-center mb-3">
                    <div>
                        <h3 class="fw-bold mb-3">{{ $item->{'title_' . $lang} }}</h3>
                        <h8 class="mb-3">{{ $courses->where('id', $item->course_id)->first()?->{'title_' . $lang} }}
                        </h8>
                    </div>
                    <div class="ms-md-auto py-2 py-md-0">
                        <a href="{{ route($lang . '.topic.view', ['id' => $item->id, 'type' => 'topics']) }}"
                            class="btn btn-label-info btn-round me-2">{{ $translations[$lang]['button'] }} <i
                                class="fas fa-angle-right"></i></a>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection
