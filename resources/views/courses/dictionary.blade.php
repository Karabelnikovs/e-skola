@extends('layouts.app')

@section('content')
    @php
        $translations = [
            'lv' => [
                'title' => 'Vārdnīca',
            ],
            'en' => [
                'title' => 'Dictionary',
            ],
            'ru' => [
                'title' => 'Словарь',
            ],
            'ua' => [
                'title' => 'Словник',
            ],
        ];

        $lang = Session::get('lang', 'lv');
    @endphp
    <div class="card-body">
        <a href="{{ $type == 'module' ? route($lang . '.module.show', $course_id) : route($lang . '.dictionaries.view') }}"
            class="btn btn-label-info btn-round me-2 mb-3 "><i class="fas fa-arrow-circle-left "></i>
            Atpakaļ</a>
        <h1>{{ $title }} | {{ $translations[$lang]['title'] ?? 'Vārdnīca' }}</h1>
        <div class="row px-4">
            @foreach ($items as $item)
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center gap-2">
                        <span class="badge bg-dark fw-bold mx-0">LV</span>
                        <svg xmlns="http://www.w3.org/2000/svg" id="flag-icons-lv" viewBox="0 0 640 480" width="24"
                            height="16">
                            <g fill-rule="evenodd">
                                <path fill="#fff" d="M0 0h640v480H0z" />
                                <path fill="#981e32" d="M0 0h640v192H0zm0 288h640v192H0z" />
                            </g>
                        </svg>
                        <span class="fs-4">{{ $item->phrase_lv }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge mx-0 bg-secondary fw-bold">EN</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" id="flag-icons-gb" viewBox="0 0 640 480"
                                        width="24" height="16">
                                        <path fill="#012169" d="M0 0h640v480H0z" />
                                        <path fill="#FFF"
                                            d="m75 0 244 181L562 0h78v62L400 241l240 178v61h-80L320 301 81 480H0v-60l239-178L0 64V0z" />
                                        <path fill="#C8102E"
                                            d="m424 281 216 159v40L369 281zm-184 20 6 35L54 480H0zM640 0v3L391 191l2-44L590 0zM0 0l239 176h-60L0 42z" />
                                        <path fill="#FFF" d="M241 0v480h160V0zM0 160v160h640V160z" />
                                        <path fill="#C8102E" d="M0 193v96h640v-96zM273 0v480h96V0z" />
                                    </svg>
                                    <span>{{ $item->phrase_en }}</span>
                                </div>
                            </div>
                            <div class="col-4 border-start">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge mx-0 bg-secondary fw-bold">UA</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" id="flag-icons-ua" viewBox="0 0 640 480"
                                        width="24" height="16">
                                        <g fill-rule="evenodd" stroke-width="1pt">
                                            <path fill="gold" d="M0 0h640v480H0z" />
                                            <path fill="#0057b8" d="M0 0h640v240H0z" />
                                        </g>
                                    </svg>
                                    <span>{{ $item->phrase_ua }}</span>
                                </div>
                            </div>
                            <div class="col-4 border-start">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge mx-0 bg-secondary fw-bold">RU</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" id="flag-icons-ru" viewBox="0 0 640 480"
                                        width="24" height="16">
                                        <path fill="#fff" d="M0 0h640v160H0z" />
                                        <path fill="#0039a6" d="M0 160h640v160H0z" />
                                        <path fill="#d52b1e" d="M0 320h640v160H0z" />
                                    </svg>
                                    <span>{{ $item->phrase_ru }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach







        </div>
    </div>
@endsection
