@extends('layouts.app')

@section('content')
    @php
        $translations = [
            'lv' => [],
            'en' => [],
            'ru' => [],
            'ua' => [],
        ];

        $lang = Session::get('lang', 'lv');
    @endphp

    <div class="container py-5">
        <div class="text-center my-5">
            <h1 class="display-4 fw-bold text-primary">{{ $title }}</h1>
            <div class="underline mx-auto"></div>
        </div>

        <div class="row">
            <div class="card-contact shadow-lg border-0 h-100">
                <div class="card-body p-4">
                    <div class="mb-5">
                        <h3 class="fw-bold mb-4 text-primary">
                            {!! $terms->{'content_' . $lang} !!}
                        </h3>
                        <div class="ps-4">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .underline {
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #9810FA 0%, rgba(13, 110, 253, 0) 70%);
            margin: 1rem 0;
        }

        .link-hover {
            color: #333;
            transition: all 0.3s ease;
        }

        .link-hover:hover {
            color: #9810FA;
            transform: translateX(5px);
        }

        .card-contact {
            transition: transform 0.3s ease;
            border-radius: 15px;
        }

        .card-contact:hover {
            transform: translateY(-5px);
        }

        .map-iframe {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
