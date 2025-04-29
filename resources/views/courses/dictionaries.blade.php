@extends('layouts.app')

@section('content')
    @php
        $translations = [
            'en' => [
                'button' => 'View Diztionary',
                'empty' => 'There are no dictionaries yet...',
                'module' => 'Module',
            ],
            'lv' => [
                'button' => 'Apskatīt vārdnīcu',
                'empty' => 'Pagaidām nav nevienas vārdnīcas...',
                'module' => 'Modulis',
            ],
            'ru' => [
                'button' => 'Посмотреть словарь',
                'empty' => 'Пока нет ни одного словаря...',
                'module' => 'Модуль',
            ],
            'ua' => [
                'button' => 'Переглянути словник',
                'empty' => 'Поки немає жодного словника...',
                'module' => 'Модуль',
            ],
        ];

        $lang = Session::get('lang', 'lv');
    @endphp
    <div class="card-body">
        <a href="/" class="btn btn-label-info btn-round me-2 mb-3 "><i class="fas fa-arrow-circle-left "></i>
            Atpakaļ</a>
        <h1>{{ $title }}</h1>
        <div>
            @if (count($dictionaries) == 0)
                <div class="alert alert-info" role="alert">
                    {{ $translations[$lang]['empty'] }}
                </div>
            @endif
            @foreach ($dictionaries as $item)
                <div class="card card-body d-flex flex-row justify-content-between align-items-center mb-3">
                    <div>
                        <h3 class="fw-bold mb-3">{{ $item->{'title_' . $lang} }}</h3>
                        <h8 class="mb-3">{{ $translations[$lang]['module'] }}:
                            {{ $courses->where('id', $item->course_id)->first()?->{'title_' . $lang} }}
                        </h8>
                    </div>
                    <div class="ms-md-auto py-2 py-md-0">
                        <a href="{{ route($lang . '.dictionary.view', ['id' => $item->id, 'type' => 'dictionaries']) }}"
                            class="btn btn-label-info btn-round me-2">{{ $translations[$lang]['button'] }} <i
                                class="fas fa-angle-right"></i></a>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection
