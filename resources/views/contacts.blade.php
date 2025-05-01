@extends('layouts.app')

@section('content')
    @php
        $translations = [
            'lv' => [
                'title' => 'Kontakti',
                'adress' => 'Adrese',
                'phone' => 'Telefons',
                'email' => 'E-pasts',
                'map' => 'Karte',
                'no_address' => 'Adrese nav pieejama kartei...',
            ],
            'en' => [
                'title' => 'Contacts',
                'adress' => 'Address',
                'phone' => 'Phone',
                'email' => 'Email',
                'map' => 'Map',
                'no_address' => 'Address is not available on the map...',
            ],
            'ru' => [
                'title' => 'Контакты',
                'adress' => 'Адреса',
                'phone' => 'Телефон',
                'email' => 'Электронная почта',
                'map' => 'Карта',
                'no_address' => 'Адрес недоступен на карте...',
            ],
            'ua' => [
                'title' => 'Контакти',
                'adress' => 'Адреса',
                'phone' => 'Телефон',
                'email' => 'Електронна пошта',
                'map' => 'Карта',
                'no_address' => 'Адреса недоступна на карті...',
            ],
        ];

        $lang = Session::get('lang', 'lv');
        $phones = json_decode($contacts->phone_numbers ?? '[]', true);
        $emails = json_decode($contacts->emails ?? '[]', true);
        $addresses = json_decode($contacts->addresses ?? '[]', true);
    @endphp
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="container py-5">
        <div class="text-center my-5">
            <h1 class="display-4 fw-bold text-primary">{{ $translations[$lang]['title'] }}</h1>
            <div class="underline mx-auto"></div>
        </div>

        <div class="row g-5">
            <div class="col-lg-6">
                <div class="card-contact shadow-lg border-0 h-100">
                    <div class="card-body p-4">
                        <div class="mb-5">
                            <h3 class="fw-bold mb-4 text-primary">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $translations[$lang]['adress'] }}
                            </h3>
                            <div class="ps-4">
                                @foreach ($addresses as $address)
                                    <p class="fs-5 mb-3">{{ $address }}</p>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-5">
                            <h3 class="fw-bold mb-4 text-primary">
                                <i class="fas fa-phone me-2"></i>
                                {{ $translations[$lang]['phone'] }}
                            </h3>
                            <div class="ps-4">
                                @foreach ($phones as $phone)
                                    <p class="fs-5 mb-3">
                                        <a href="tel:{{ $phone }}" class="text-decoration-none link-hover">
                                            <i class="fas fa-arrow-right me-2 text-primary"></i>{{ $phone }}
                                        </a>
                                    </p>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <h3 class="fw-bold mb-4 text-primary">
                                <i class="fas fa-envelope me-2"></i>
                                {{ $translations[$lang]['email'] }}
                            </h3>
                            <div class="ps-4">
                                @foreach ($emails as $email)
                                    <p class="fs-5 mb-3">
                                        <a href="mailto:{{ $email }}" class="text-decoration-none link-hover">
                                            <i class="fas fa-arrow-right me-2 text-primary"></i>{{ $email }}
                                        </a>
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card-contact shadow-lg border-0 h-100">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4 text-primary">
                            <i class="fas fa-map me-2"></i>
                            {{ $translations[$lang]['map'] }}
                        </h3>
                        @if (!empty($addresses[0]))
                            <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                                <iframe class="map-iframe" loading="lazy" allowfullscreen
                                    referrerpolicy="no-referrer-when-downgrade"
                                    src="https://www.google.com/maps?q={{ urlencode($addresses[0]) }}&output=embed">
                                </iframe>
                            </div>
                        @else
                            <div class="alert alert-warning mt-3" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ $translations[$lang]['no_address'] }}
                            </div>
                        @endif
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
