@extends('layouts.app')

@section('content')
    @php
        $translations = [
            'lv' => [
                'title' => 'Tests',
                'question' => 'Jautājums',
                'submit' => 'Iesniegt atbildes',
                'next' => 'Nākamais',
                'previous' => 'Iepriekšējais',
                'final' => 'Noslēguma tests',
                'all_modules' => 'Visi moduļi',
                'disclaimer' => 'Šis tests jau ir nokārtots.',
            ],
            'en' => [
                'title' => 'Test',
                'question' => 'Question',
                'submit' => 'Submit Answers',
                'next' => 'Next',
                'previous' => 'Previous',
                'final' => 'Final Test',
                'all_modules' => 'All modules',
                'disclaimer' => 'This test is already passed.',
            ],
            'ru' => [
                'title' => 'Тест',
                'question' => 'Вопрос',
                'submit' => 'Отправить ответы',
                'next' => 'Следующий',
                'previous' => 'Предыдущий',
                'final' => 'Заключительный тест',
                'all_modules' => 'Все модули',
                'disclaimer' => 'Этот тест уже пройден.',
            ],
            'ua' => [
                'title' => 'Тест',
                'question' => 'Питання',
                'submit' => 'Відправити відповіді',
                'next' => 'Наступний',
                'previous' => 'Попередній',
                'final' => 'Фінальний тест',
                'all_modules' => 'Всі модулі',
                'disclaimer' => 'Цей тест вже пройдено.',
            ],
        ];

        $lang = Session::get('lang', 'lv');
    @endphp
    {{-- @dd($test) --}}
    <div class="card-body">
        <a href="{{ route($lang . '.courses.index') }}" class="btn btn-label-info btn-round me-2 mb-3 "><i
                class="fas fa-arrow-circle-left "></i>
            {{ $translations[$lang]['all_modules'] }}</a>
        <div class="text-center my-3">
            <h1 class="display-6 fw-bold text-primary">{{ $title }}
                @if ($test->type == 'final')
                    | {{ $translations[$lang]['final'] }}
                @else
                    | {{ $translations[$lang]['title'] ?? 'Tests' }}
                @endif
            </h1>
            <div class="underline mx-auto"></div>
        </div>
        <div id="disclaimer" class="alert alert-info" style="display: {{ $passed ? 'block' : 'none' }};">
            {{ $translations[$lang]['disclaimer'] }}
        </div>
        <div class="row px-4">
            <form id="testForm">
                @foreach ($questions as $question)
                    <div class="card mb-3 question" data-question-id="{{ $question->id }}">
                        <div class="card-body">
                            @if ($lang == 'ua')
                                <h5 class="card-title">{{ $translations[$lang]['question'] }} {{ $loop->iteration }}:
                                    {{ $question->question_uk }}
                                </h5>
                            @else
                                <h5 class="card-title">{{ $translations[$lang]['question'] }} {{ $loop->iteration }}:
                                    {{ $question->{'question_' . $lang} }}
                                </h5>
                            @endif
                            @if ($question->image)
                                <img src="{{ asset('storage/' . $question->image) }}" alt="Question Image"
                                    class="img-fluid mb-3" style="max-width: 100%; max-height: 300px;">
                            @endif
                            @if ($question->audio)
                                <audio controls class="mb-3 d-block">
                                    <source src="{{ asset('storage/' . $question->audio) }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @endif
                            <div class="list-group">
                                @php
                                    $options =
                                        json_decode($question->{'options_' . $lang}, true) ??
                                        explode(',', $question->{'options_' . $lang});
                                @endphp
                                @foreach ($options as $key => $option)
                                    <label
                                        class="list-group-item d-flex align-items-center {{ $passed && $key == $question->correct_answer ? 'correct' : '' }} {{ $passed ? 'disabled' : '' }}">
                                        <input type="radio" name="question_{{ $question->id }}"
                                            {{ $passed ? 'disabled' : '' }} value="{{ $option }}" class="me-3">
                                        <span class="option-text">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary mt-3 btn-round"
                    {{ $passed ? 'disabled' : '' }}>{{ $translations[$lang]['submit'] }}</button>
            </form>
            {{-- @dd($order_status) --}}
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
                    <form id="nextForm" action="{{ route($lang . '.courses.module.next', ['id' => $course_id]) }}"
                        method="POST" class="{{ $order_status == 'first' ? 'absolute-next' : '' }}"
                        style="display: {{ $passed ? 'block' : 'none' }};">
                        @csrf
                        <button type="submit" class="btn btn-label-info btn-round me-2 mb-3">
                            {{ $translations[$lang]['next'] }} <i class="fas fa-angle-right"></i></button>
                    </form>
                @endif
            </div>
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

        .list-group-item.disabled {
            background-color: #eee;
            color: #666;
            cursor: not-allowed;
        }

        .list-group-item.correct {
            background-color: lightgreen !important;
            color: black;
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
                    if (data.certificate_url) {
                        const link = document.createElement('a');
                        link.href = data.certificate_url;
                        link.setAttribute('download', 'certificate.pdf');
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                    }
                    const percentage = data.percentage;
                    Swal.fire({
                        title: data.passed ? 'Nokārtots!' : 'Nenokārtots!',
                        html: `Jūsu rezultāts: ${data.score}/${numberOfQuestions} (${percentage}%)<br>` +
                            (data.passed ? 'Apsveicam! Jūs nokārtojāt testu!' :
                                'Jūs nenokārtojāt testu. Mēģiniet vēlreiz!'),
                        icon: data.passed ? 'success' : 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        if (data.passed) {
                            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                                radio.disabled = true;
                            });
                            document.querySelector('button[type="submit"]').disabled = true;
                            document.querySelectorAll('.list-group-item').forEach(item => {
                                item.classList.add('disabled');
                            });
                            document.getElementById('disclaimer').style.display = 'block';
                            if (document.getElementById('nextForm')) {
                                document.getElementById('nextForm').style.display = 'block';
                            }
                            if (data.correct_answers) {
                                Object.keys(data.correct_answers).forEach(qid => {
                                    const correct = data.correct_answers[qid];
                                    const questionDiv = document.querySelector(
                                        `.question[data-question-id="${qid}"]`);
                                    if (questionDiv) {
                                        questionDiv.querySelectorAll('.list-group-item')
                                            .forEach(item => {
                                                if (item.querySelector('.option-text')
                                                    .textContent.trim() === correct) {
                                                    item.classList.add('correct');
                                                }
                                            });
                                    }
                                });
                            }
                        }
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
