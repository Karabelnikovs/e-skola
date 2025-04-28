@extends('layouts.admin')
@section('content')
    <div class="card-body">
        <h1>Moduļi</h1>
        <div class="row px-4">
            @foreach ($courses as $course)
                <div class="card me-4" style="width: 24rem;">
                    <div class="card-img-top-container overflow-hidden rounded m-2" style="height: 14rem;">
                        <img src="{{ $course->img }}" alt="card-image" class="w-100 h-100 object-fit-cover">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-dark mb-2 fw-semibold">
                            {{ $course->title_lv }}
                        </h5>
                        <p class="card-text text-secondary font-weight-light">
                            {{ $course->description_lv }}
                        </p>
                        <a href="{{ route('module', $course->id) }}" class="btn btn-primary btn-round">
                            Rediģēt moduli
                        </a>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection
