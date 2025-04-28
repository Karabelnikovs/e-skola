@extends('layouts.app')

@section('content')
    @php
        $translations = [
            'lv' => ['title' => 'Tests'],
            'en' => ['title' => 'Test'],
            'ru' => ['title' => 'Тест'],
            'ua' => ['title' => 'Тест'],
        ];

        $lang = Session::get('lang', 'lv');
    @endphp
    <div class="card-body">
        <a href="{{ route('module.show', $course_id) }}" class="btn btn-label-info btn-round me-2 mb-3">
            <i class="fas fa-arrow-circle-left"></i> Atpakaļ
        </a>
        <h1>{{ $title }} | {{ $translations[$lang]['title'] ?? 'Tests' }}</h1>
        <div class="row px-4">
            <form id="testForm">
                @foreach ($questions as $question)
                    <div class="card mb-3 question" data-question-id="{{ $question->id }}">
                        <div class="card-body">
                            @if ($lang == 'ua')
                                <h5 class="card-title">Question {{ $loop->iteration }}:
                                    {{ $question->question_uk }}
                                </h5>
                            @else
                                <h5 class="card-title">Question {{ $loop->iteration }}:
                                    {{ $question->{'question_' . $lang} }}
                                </h5>
                            @endif
                            <div class="list-group">
                                @php
                                    $options =
                                        json_decode($question->{'options_' . $lang}, true) ??
                                        explode(',', $question->{'options_' . $lang});
                                @endphp
                                @foreach ($options as $option)
                                    <label class="list-group-item d-flex align-items-center">
                                        <input type="radio" name="question_{{ $question->id }}"
                                            value="{{ $option }}" class="me-3">
                                        <span class="option-text">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary mt-3 btn-round">Submit Test</button>
            </form>
        </div>
    </div>

    <style>
        .list-group-item {
            cursor: pointer;
            padding: 12px 20px;
            margin-bottom: 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }

        .list-group-item.selected {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .list-group-item.selected .option-text {
            color: white;
        }

        input[type="radio"] {
            display: none;
        }
    </style>

    <script>
        const numberOfQuestions = {{ $questions->count() }};

        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const questionDiv = this.closest('.question');
                questionDiv.querySelectorAll('.list-group-item').forEach(item => {
                    item.classList.remove('selected');
                });
                if (this.checked) {
                    this.closest('.list-group-item').classList.add('selected');
                }
            });
        });

        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let answers = {};
            document.querySelectorAll('.question').forEach(questionDiv => {
                const questionId = questionDiv.dataset.questionId;
                const selectedOption = questionDiv.querySelector('input[type="radio"]:checked');
                if (selectedOption) {
                    answers[questionId] = selectedOption.value;
                }
            });

            if (Object.keys(answers).length !== numberOfQuestions) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Kļūda',
                    text: 'Lūdzu, atbildiet uz visiem jautājumiem!',
                });
                return;
            }

            fetch('/{{ $lang }}/test/submit/{{ $id }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        answers: answers
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    const percentage = ((data.score / numberOfQuestions) * 100).toFixed(1);
                    Swal.fire({
                        title: data.passed ? 'Pareizi!' : 'Nepareizi!',
                        html: `Jūsu rezultāts: ${data.score}/${numberOfQuestions} (${percentage}%)<br>` +
                            (data.passed ? 'Apsveicam! Jūs nokārtojāt testu!' :
                                'Jūs nenokārtojāt testu. Mēģiniet vēlreiz!'),
                        icon: data.passed ? 'success' : 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        document.querySelectorAll('input[type="radio"]').forEach(radio => {
                            radio.disabled = true;
                        });
                        document.querySelector('button[type="submit"]').disabled = true;
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kļūda',
                        text: error.error || 'Radās kļūda, lūdzu mēģiniet vēlreiz.',
                    });
                });
        });
    </script>
@endsection
