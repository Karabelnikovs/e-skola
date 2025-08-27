@extends('layouts.admin')
@section('content')
    <div class="card-body">
        @if (session('success'))
            <script>
                Swal.fire('Veiksmīgi!', '{{ session('success') }}', 'success');
            </script>
        @endif
        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Kļūda!',
                    html: `{!! implode('<br>', $errors->all()) !!}`
                });
            </script>
        @endif
        <style>
            .checkbox-wrapper-2 {
                display: flex;
                align-items: center;
                justify-content: center;

            }

            .checkbox-wrapper-2 .ikxBAC {
                appearance: none;
                background-color: #dfe1e4;
                border-radius: 72px;
                border-style: none;
                flex-shrink: 0;
                height: 20px;
                margin: 0;
                position: relative;
                width: 30px;

            }

            .checkbox-wrapper-2 .ikxBAC::before {
                bottom: -6px;
                content: "";
                left: -6px;
                position: absolute;
                right: -6px;
                top: -6px;
            }

            .checkbox-wrapper-2 .ikxBAC,
            .checkbox-wrapper-2 .ikxBAC::after {
                transition: all 100ms ease-out;
            }

            .checkbox-wrapper-2 .ikxBAC::after {
                background-color: #fff;
                border-radius: 50%;
                content: "";
                height: 14px;
                left: 3px;
                position: absolute;
                top: 3px;
                width: 14px;
            }

            .checkbox-wrapper-2 input[type=checkbox] {
                cursor: default;
            }

            .checkbox-wrapper-2 .ikxBAC:hover {
                background-color: #c9cbcd;
                transition-duration: 0s;
            }

            .checkbox-wrapper-2 .ikxBAC:checked {
                background-color: #6e79d6;
            }

            .checkbox-wrapper-2 .ikxBAC:checked::after {
                background-color: #fff;
                left: 13px;
            }

            .checkbox-wrapper-2 :focus:not(.focus-visible) {
                outline: 0;
            }

            .checkbox-wrapper-2 .ikxBAC:checked:hover {
                background-color: #535db3;
            }
        </style>
        <form method="POST" action="{{ isset($test) ? route('test.update', $test->id) : route('test.store') }}"
            id="testForm">
            @csrf
            {{-- @if (isset($test))
                    @method('PUT')
                @endif --}}
            <a href='/module/{{ $module->id ?? $topic->course_id }}' class="btn btn-label-info btn-round me-2 mb-3"><i
                    class="fas fa-arrow-circle-left "></i>
                Atpakaļ</a>
            <div id="test-details">
                <div class="form-group">
                    <label for="title">Testa nosaukums (EN)</label>
                    <input type="text" class="form-control" name="title_en" value="{{ $test->title_en ?? '' }}"
                        id="title_en" required>
                </div>
                <div class="form-group">
                    <label for="title">Testa nosaukums (LV)</label>
                    <button type="button" class="btn btn-sm btn-outline-secondary translate-title-btn mx-4 my-2 btn-round"
                        data-lang="lv" data-type="title">Tulkot no
                        EN</button>
                    <input type="text" class="form-control" name="title_lv" value="{{ $test->title_lv ?? '' }}"
                        id="title_lv" required>
                </div>
                <div class="form-group">
                    <label for="title">Testa nosaukums (UA)</label>
                    <button type="button" class="btn btn-sm btn-outline-secondary translate-title-btn mx-4 my-2 btn-round"
                        data-lang="uk" data-type="title">Tulkot no
                        EN</button>
                    <input type="text" class="form-control" name="title_ua" value="{{ $test->title_ua ?? '' }}"
                        id="title_uk" required>
                </div>
                <div class="form-group">
                    <label for="title">Testa nosaukums (RU)</label>
                    <button type="button" class="btn btn-sm btn-outline-secondary translate-title-btn mx-4 my-2 btn-round"
                        data-lang="ru" data-type="title">Tulkot no
                        EN</button>
                    <input type="text" class="form-control" name="title_ru" value="{{ $test->title_ru ?? '' }}"
                        id="title_ru" required>
                </div>
                <div class="form-group">
                    <label for="passing_score">Nolikšanas %</label>
                    <input type="number" class="form-control" name="passing_score" value="{{ $test->passing_score ?? '' }}"
                        min="0" required>
                </div>
                <div class=" checkbox-wrapper-2">
                    <label for="is_final_test">Vai šis ir gala tests?</label>
                    <input type="checkbox" name="is_final_test" id="is_final_test" class="mx-5 sc-gJwTLC ikxBAC"
                        {{ isset($test) && $test->type === 'final' ? 'checked' : '' }}>
                </div>


                <input type="hidden" name="course_id" value="{{ $module->id }}">
            </div>
            <div id="questions" class="mt-4">
                @if (isset($questions) && $questions->isNotEmpty())
                    @foreach ($questions as $index => $question)
                        <div class="question-block card mb-3" data-question-index="{{ $index }}">
                            <div class="card-header d-flex justify-content-between align-items-center"
                                style="cursor: move;">
                                <h3 class="d-inline">Jautājums <span class="question-number">{{ $index + 1 }}</span>
                                </h3>
                                <button type="button"
                                    class="remove-question btn btn-danger float-right btn-round">Dzēst</button>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Jautājums (EN)</label>
                                    <input type="text" name="questions[{{ $index }}][question_en]"
                                        class="form-control" value="{{ $question->question_en ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Jautājums (LV)</label>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-secondary translate-btn mx-4 my-2 btn-round"
                                        data-lang="lv" data-type="question">Tulkot no
                                        EN</button>
                                    <input type="text" name="questions[{{ $index }}][question_lv]"
                                        class="form-control" value="{{ $question->question_lv ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Jautājums (RU)</label>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-secondary translate-btn mx-4 my-2 btn-round"
                                        data-lang="ru" data-type="question">Tulkot no
                                        EN</button>
                                    <input type="text" name="questions[{{ $index }}][question_ru]"
                                        class="form-control" value="{{ $question->question_ru ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Jautājums (UA)</label>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-secondary translate-btn mx-4 my-2 btn-round"
                                        data-lang="uk" data-type="question">Tulkot no
                                        EN</button>
                                    <input type="text" name="questions[{{ $index }}][question_uk]"
                                        class="form-control" value="{{ $question->question_uk ?? '' }}" required>
                                </div>
                                <div class="options mt-2">
                                    @foreach (json_decode($question->options_en) as $optIndex => $optionEn)
                                        <div class="option-block mb-2" data-option-index="{{ $optIndex }}">
                                            <div class="separator-dashed"></div>
                                            <div class="row d-flex justify-content-between align-items-center">
                                                <div class="col-md-2">
                                                    <div class="form-check">
                                                        <input type="radio"
                                                            name="questions[{{ $index }}][correct_answer]"
                                                            value="{{ $optIndex }}" class="form-check-input"
                                                            {{ $question->correct_answer == $optIndex ? 'checked' : '' }}
                                                            required>
                                                        <label class="form-check-label">Pareizā atbilde</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text"
                                                        name="questions[{{ $index }}][options][{{ $optIndex }}][en]"
                                                        placeholder="Opcija EN" class="form-control"
                                                        value="{{ $optionEn }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text"
                                                        name="questions[{{ $index }}][options][{{ $optIndex }}][lv]"
                                                        class="form-control" placeholder="Opcija LV"
                                                        value="{{ json_decode($question->options_lv)[$optIndex] ?? '' }}"
                                                        required>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary translate-btn mx-4 my-2 btn-round"
                                                        data-lang="lv" data-type="option">Tulkot no EN</button>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text"
                                                        name="questions[{{ $index }}][options][{{ $optIndex }}][ru]"
                                                        class="form-control" placeholder="Opcija RU"
                                                        value="{{ json_decode($question->options_ru)[$optIndex] ?? '' }}"
                                                        required>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary translate-btn mx-4 my-2 btn-round"
                                                        data-lang="ru" data-type="option">Tulkot no EN</button>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text"
                                                        name="questions[{{ $index }}][options][{{ $optIndex }}][uk]"
                                                        class="form-control" placeholder="Opcija UA"
                                                        value="{{ json_decode($question->options_ua)[$optIndex] ?? '' }}"
                                                        required>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary translate-btn mx-4 my-2 btn-round"
                                                        data-lang="ua" data-type="option">Tulkot no EN</button>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button"
                                                        class="remove-option btn btn-danger btn-sm mt-2 btn-round">Dzēst</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="separator-dashed"></div>
                                <button type="button" class="add-option btn btn-secondary mt-2 btn-round">Jauna
                                    atbilde</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <button type="button" id="add-question" class="btn btn-primary mt-3 btn-round">Pielikt
                jautājumu</button>
            <button type="submit" class="btn btn-success mt-3 btn-round">Saglabāt</button>
        </form>
    </div>

    @verbatim
        <script type="text/template" id="question-template">
        <div class="question-block card mb-3" data-question-index="[questionIndex]">
            <div class="card-header d-flex justify-content-between align-items-center" style="cursor: move;">
                <h3 class="d-inline">Jautājums <span class="question-number"></span></h3>
                <button type="button" class="remove-question btn btn-danger float-right btn-round" >Dzēst</button>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Jautājums (EN)</label>
                    <input type="text" name="questions[[questionIndex]][question_en]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Jautājums (LV)</label>
                    <button type="button" class="btn btn-sm btn-outline-secondary translate-btn mx-4 my-2 btn-round"  data-lang="lv" data-type="question">Tulkot no EN</button>
                    <input type="text" name="questions[[questionIndex]][question_lv]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Jautājums (RU)</label>
                    <button type="button" class="btn btn-sm btn-outline-secondary translate-btn mx-4 my-2 btn-round"  data-lang="ru" data-type="question">Tulkot no EN</button>
                    <input type="text" name="questions[[questionIndex]][question_ru]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Jautājums (UA)</label>
                    <button type="button" class="btn btn-sm btn-outline-secondary translate-btn mx-4 my-2 btn-round"  data-lang="uk" data-type="question">Tulkot no EN</button>
                    <input type="text" name="questions[[questionIndex]][question_uk]" class="form-control" required>
                </div>
                <div class="options mt-2"></div>
                <div class="separator-dashed"></div>
                <button type="button" class="add-option btn btn-secondary mt-2 btn-round" >Jauna atbilde</button>
            </div>
        </div>
        </script>
    @endverbatim

    @verbatim
        <script type="text/template" id="option-template">
        <div class="option-block mb-2" data-option-index="[optionIndex]">
            <div class="separator-dashed"></div>
            <div class="row d-flex justify-content-between align-items-center">
                <div class="col-md-2">
                    <div class="form-check">
                        <input type="radio" name="questions[[questionIndex]][correct_answer]" value="[optionIndex]" class="form-check-input" required>
                        <label class="form-check-label">Pareizā atbilde</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="text" name="questions[[questionIndex]][options][[optionIndex]][en]" class="form-control" placeholder="Opcija EN" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="questions[[questionIndex]][options][[optionIndex]][lv]" class="form-control" placeholder="Opcija LV" required>
                    <button type="button" class="btn btn-sm btn-outline-secondary translate-btn mx-4 my-2 btn-round"  data-lang="lv" data-type="option">Tulkot no EN</button>
                </div>
                <div class="col-md-2">
                    <input type="text" name="questions[[questionIndex]][options][[optionIndex]][ru]" class="form-control" placeholder="Opcija RU" required>
                    <button type="button" class="btn btn-sm btn-outline-secondary translate-btn mx-4 my-2 btn-round"  data-lang="ru" data-type="option">Tulkot no EN</button>
                </div>
                <div class="col-md-2">
                    <input type="text" name="questions[[questionIndex]][options][[optionIndex]][uk]" class="form-control" placeholder="Opcija UA" required>
                    <button type="button" class="btn btn-sm btn-outline-secondary translate-btn mx-4 my-2 btn-round"  data-lang="uk" data-type="option">Tulkot no EN</button>
                </div>
                <div class="col-md-2">
                    <button type="button"  class="remove-option btn btn-danger btn-sm mt-2 btn-round">Dzēst</button>
                </div>
            </div>
        </div>
        </script>
    @endverbatim

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let questionCounter = {{ count($questions ?? []) }};

            function addQuestion() {
                const template = document.getElementById('question-template').innerHTML.replace(
                    /\[questionIndex\]/g, questionCounter);
                const questionBlock = document.createElement('div');
                questionBlock.innerHTML = template;
                document.getElementById('questions').appendChild(questionBlock);

                addOption(questionCounter);
                addOption(questionCounter);

                updateQuestionNumbers();
                questionCounter++;
            }

            function addOption(questionIndex) {
                const questionBlock = document.querySelector(
                    `.question-block[data-question-index="${questionIndex}"]`);
                const optionsContainer = questionBlock.querySelector('.options');
                const optionCount = optionsContainer.children.length;

                if (optionCount >= 4) {
                    Swal.fire('Kļūda', 'Maksimālais atbilžu skaits ir 4.', 'warning');
                    return;
                }

                const template = document.getElementById('option-template').innerHTML
                    .replace(/\[questionIndex\]/g, questionIndex)
                    .replace(/\[optionIndex\]/g, optionCount);
                const optionBlock = document.createElement('div');
                optionBlock.innerHTML = template;
                optionsContainer.appendChild(optionBlock);
            }

            function removeQuestion(event) {
                const questionBlock = event.target.closest('.question-block');
                if (document.querySelectorAll('.question-block').length > 1) {
                    questionBlock.remove();
                    updateQuestionNumbers();
                } else {
                    Swal.fire('Kļūda', 'Nepieciešams vismaz viens jautājums.', 'warning');
                }
            }

            function removeOption(event) {
                const optionBlock = event.target.closest('.option-block');
                const optionsContainer = optionBlock.closest('.options');
                if (optionsContainer.children.length > 2) {
                    optionBlock.remove();
                } else {
                    Swal.fire('Kļūda', 'Katram jautājumam ir obligātas vismaz 2 atbildes.', 'warning');
                }
            }

            function updateQuestionNumbers() {
                document.querySelectorAll('.question-block').forEach((block, index) => {
                    block.querySelector('.question-number').textContent = index + 1;
                });
            }

            document.getElementById('add-question').addEventListener('click', addQuestion);
            document.getElementById('questions').addEventListener('click', function(e) {
                if (e.target.classList.contains('add-option')) {
                    const questionIndex = e.target.closest('.question-block').dataset.questionIndex;
                    addOption(questionIndex);
                } else if (e.target.classList.contains('remove-question')) {
                    removeQuestion(e);
                } else if (e.target.classList.contains('remove-option')) {
                    removeOption(e);
                }
            });

            new Sortable(document.getElementById('questions'), {
                animation: 150,
                handle: '.card-header',
                onEnd: function() {
                    updateQuestionNumbers();
                }
            });

            if (document.querySelectorAll('.question-block').length === 0) {
                addQuestion();
            }
        });
    </script>

    <script>
        document.getElementById('questions').addEventListener('click', async function(e) {
            if (e.target.classList.contains('translate-btn')) {
                const btn = e.target;
                const lang = btn.dataset.lang;
                const type = btn.dataset.type;

                let sourceInput, targetInput;

                if (type === 'question') {
                    const questionBlock = btn.closest('.question-block');
                    const questionIndex = questionBlock.dataset.questionIndex;
                    sourceInput = questionBlock.querySelector(
                        `input[name="questions[${questionIndex}][question_en]"]`);
                    targetInput = questionBlock.querySelector(
                        `input[name="questions[${questionIndex}][question_${lang}]"]`);
                } else if (type === 'option') {
                    const optionBlock = btn.closest('.option-block');
                    const optionIndex = optionBlock.dataset.optionIndex;
                    const questionBlock = optionBlock.closest('.question-block');
                    const questionIndex = questionBlock.dataset.questionIndex;
                    sourceInput = optionBlock.querySelector(
                        `input[name="questions[${questionIndex}][options][${optionIndex}][en]"]`);
                    targetInput = optionBlock.querySelector(
                        `input[name="questions[${questionIndex}][options][${optionIndex}][${lang}]"]`);
                }

                if (sourceInput && targetInput) {
                    const sourceValue = sourceInput.value;
                    const translated = await translateField(sourceValue, lang);
                    targetInput.value = translated;
                } else {
                    console.error('Source or target input not found');
                }
            }
        });
        document.querySelectorAll('.translate-title-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const lang = this.dataset.lang;
                const type = this.dataset.type;
                const sourceId = 'title_en';
                const targetId = `title_${lang}`;

                const sourceValue = type === 'title' ?
                    document.getElementById(sourceId).value :
                    editors[sourceId].getData();

                const translated = await translateField(sourceValue, lang);
                if (type === 'title') {
                    document.getElementById(targetId).value = translated;
                }
            });
        });

        async function translateField(fromText, toLang) {
            console.log('translate');
            const res = await fetch('{{ route('translate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    text: fromText,
                    from: 'en',
                    to: toLang
                })
            });
            const data = await res.json();
            return data.translated;
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (isset($test))
                const finalTestCheckbox = document.getElementById('is_final_test');

                finalTestCheckbox.addEventListener('change', function() {
                    const isChecked = this.checked;
                    const url = "{{ route('test.toggleFinal', $test->id) }}";

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                is_final_test: isChecked
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Saglabāts!',
                                    text: data.message,
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true
                                });
                            } else {
                                this.checked = !isChecked;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Kļūda!',
                                    text: data.message || 'An error occurred while saving.'
                                });
                            }
                        })
                        .catch(error => {
                            this.checked = !isChecked;
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Kļūda!',
                                text: 'Kaut kas nogāja greizi... Lūdzu, pamēģiniet vēlreiz.'
                            });
                        });
                });
            @endif
        });
    </script>
@endsection
