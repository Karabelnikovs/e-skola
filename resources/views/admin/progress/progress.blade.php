@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="">
            <div class="d-flex justify-content-between align-items-center p-4">
                <div class="">
                    <h3 class="mb-0">{{ $title }} - {{ $user->name }}</h3>
                    <p class="mb-0 mt-2">E-pasts: {{ $user->email }}</p>
                </div>
                <a href="/users-progress" class="btn btn-label-info btn-round me-2 mb-3 "><i
                        class="fas fa-arrow-circle-left "></i>
                    Atpakaļ</a>

            </div>

            <div class="">
                @if ($progress->isEmpty())
                    <div class="alert alert-info">Lietotājam vēl nav progresa.</div>
                @else
                    <div class="row">
                        @foreach ($progress as $prog)
                            @php
                                $courseTitle = 'course_title_' . app()->getLocale();
                                $title = $prog->$courseTitle ?? $prog->course_title_en;
                            @endphp

                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card card-animate h-100 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">{{ $title }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="progress w-75" style="height: 25px; position: relative;">
                                                <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                                                    style="width: {{ $prog->percentage }}%" aria-valuenow="{{ $prog->percentage }}"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                                <span
                                                    style="position: absolute; left: 50%; transform: translateX(-50%); font-weight: 900; color: {{ round($prog->percentage) >= 50 ? '#ffffff' : '#000000' }}; text-shadow: {{ round($prog->percentage) >= 30 ? '1px 1px 2px rgba(0,0,0,0.5)' : '1px 1px 2px rgba(255,255,255,0.8)' }}; line-height: 25px;">
                                                    {{ round($prog->percentage) }}%
                                                </span>
                                            </div>
                                        </div>

                                        <div class="row text-center small">
                                            <div class="col-4">
                                                <div class="text-muted">Modulis sākts</div>
                                                <div class="fw-bold">
                                                    {{ \Carbon\Carbon::parse($prog->created_at)->format('d.m.Y') }}
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-muted">Pēdējā aktivitāte</div>
                                                <div class="fw-bold">
                                                    {{ \Carbon\Carbon::parse($prog->updated_at)->format('d.m.Y') }}
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-muted">Statuss</div>
                                                <div class="fw-bold">
                                                    @if ($prog->percentage >= 100)
                                                        <span class="text-success">Pabeigts</span>
                                                    @else
                                                        <span class="text-warning">Procesā</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <small class="text-muted">
                                            Pēdējā atjaunināšana:
                                            {{ \Carbon\Carbon::parse($prog->updated_at)->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .progress-bar {
            transition: width 0.5s ease-in-out;
            font-weight: 500;
        }

        .card-animate {
            transition: transform 0.2s;
        }

        .card-animate:hover {
            transform: translateY(-5px);
        }
    </style>
@endsection