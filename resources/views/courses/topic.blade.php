@extends('layouts.app')

@section('content')
    @php
        $translations = [
            'lv' => [
                'title' => 'Teorija',
                'next' => 'Nākamais',
                'previous' => 'Iepriekšējais',
            ],
            'en' => [
                'title' => 'Theory',
                'next' => 'Next',
                'previous' => 'Previous',
            ],
            'ru' => [
                'title' => 'Теория',
                'next' => 'Следующий',
                'previous' => 'Предыдущий',
            ],
            'ua' => [
                'title' => 'Теорія',
                'next' => 'Наступний',
                'previous' => 'Попередній',
            ],
        ];

        $lang = Session::get('lang', 'lv');
    @endphp
    <div class="card-body">
        <a href="{{ route($lang . '.courses.index') }}" class="btn btn-label-info btn-round me-2 mb-3 "><i
                class="fas fa-arrow-circle-left "></i>
            Visi moduļi</a>
        <div class="text-center my-3">
            <h1 class="display-6 fw-bold text-primary">
                {{ $title }} | {{ $translations[$lang]['title'] ?? 'Teorija' }}
            </h1>
            <div class="underline mx-auto"></div>
        </div>
        <div class="row px-4">

            {!! $topic->{'content_' . $lang} !!}

            <div class="d-flex justify-content-between mt-5">
                @if ($order_status != 'first')
                    <form action="{{ route($lang . '.courses.module.previous', ['id' => $course_id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-label-info btn-round me-2 mb-3"><i
                                class="fas fa-angle-left"></i>
                            {{ $translations[$lang]['previous'] }}</button>
                    </form>
                @endif
                @if ($order_status != 'last')
                    <form action="{{ route($lang . '.courses.module.next', ['id' => $course_id]) }}" method="POST"
                        class="{{ $order_status == 'first' ? 'absolute-next' : '' }}">
                        @csrf
                        <button type="submit" class="btn btn-label-info btn-round me-2 mb-3">
                            {{ $translations[$lang]['next'] }} <i class="fas fa-angle-right"></i></button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
