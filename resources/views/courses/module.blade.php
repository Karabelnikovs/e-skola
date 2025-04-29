@extends('layouts.app')

@section('content')
    @php
        $translations = [
            'lv' => [
                'title' => 'Pieejamie Moduļi',
                'button' => 'Apskatīt moduli',
                'view_theory' => 'Apskatīt teoriju',
                'take_test' => 'Iziet testu',
                'view_dictionary' => 'Apskatīt vārdnīcu',
                'topic' => 'Tēma',
                'test' => 'Tests',
                'dictionary' => 'Vārdnīca',
                'empty' => 'Šajā modulī pagaidām nekā nav...',
            ],
            'en' => [
                'title' => 'Available Modules',
                'button' => 'View Module',
                'view_theory' => 'View Theory',
                'take_test' => 'Take Test',
                'view_dictionary' => 'View Dictionary',
                'topic' => 'Topic',
                'test' => 'Test',
                'dictionary' => 'Dictionary',
                'empty' => 'There is nothing in this module yet...',
            ],
            'ru' => [
                'title' => 'Доступные модули',
                'button' => 'Посмотреть модуль',
                'view_theory' => 'Посмотреть теорию',
                'take_test' => 'Пройти тест',
                'view_dictionary' => 'Посмотреть словарь',
                'topic' => 'Тема',
                'test' => 'Тест',
                'dictionary' => 'Словарь',
                'empty' => 'В этом модуле пока ничего нет...',
            ],
            'ua' => [
                'title' => 'Доступні модулі',
                'button' => 'Переглянути модуль',
                'view_theory' => 'Переглянути теорію',
                'take_test' => 'Пройти тест',
                'view_dictionary' => 'Переглянути словник',
                'topic' => 'Тема',
                'test' => 'Тест',
                'dictionary' => 'Словник',
                'empty' => 'У цьому модулі поки нічого немає...',
            ],
        ];

        $lang = Session::get('lang', 'lv');
    @endphp
    <div class="card-body">
        <a href="/" class="btn btn-label-info btn-round me-2 mb-3 "><i class="fas fa-arrow-circle-left "></i>
            Atpakaļ</a>

        <h1>{{ $module->{'title_' . $lang} }}</h1>

        <div>
            @if (count($items) == 0)
                <div class="alert alert-info" role="alert">
                    {{ $translations[$lang]['empty'] }}
                </div>
            @endif
            @foreach ($items as $item)
                <div class="card card-body d-flex flex-row justify-content-between align-items-center mb-3">
                    <h3>{{ $item['title'] }} ( @if ($item['type'] == 'topic')
                            {{ $translations[$lang]['topic'] }}
                        @elseif($item['type'] == 'test')
                            {{ $translations[$lang]['test'] }}
                        @elseif($item['type'] == 'dictionary')
                            {{ $translations[$lang]['dictionary'] }}
                        @endif)</h3>
                    <div class="ms-md-auto py-2 py-md-0">
                        @if ($item['type'] == 'topic')
                            <a href="{{ route($lang . '.topic.view', ['id' => $item['id'], 'type' => 'module']) }}"
                                class="btn btn-label-info btn-round me-2">{{ $translations[$lang]['view_theory'] }} <i
                                    class="fas fa-angle-right"></i></a>
                        @elseif ($item['type'] == 'test')
                            <a href="{{ route($lang . '.test.show', [$item['id']]) }}"
                                class="btn btn-label-info btn-round me-2">{{ $translations[$lang]['take_test'] }} <i
                                    class="fas fa-angle-right"></i></a>
                        @elseif ($item['type'] == 'dictionary')
                            <a href="{{ route($lang . '.dictionary.view', [$item['id'], 'type' => 'module']) }}"
                                class="btn btn-label-info btn-round me-2">{{ $translations[$lang]['view_dictionary'] }} <i
                                    class="fas fa-angle-right"></i></a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
