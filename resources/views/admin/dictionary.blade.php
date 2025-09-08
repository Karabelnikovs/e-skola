@extends('layouts.admin')
@section('content')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
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
        <form method="POST"
            action="{{ isset($dictionary) ? route('dictionary.update', $dictionary->id) : route('dictionary.store') }}"
            id="dictionaryForm">
            @csrf

            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">

                <a href='/module/{{ $module->id ?? $topic->course_id }}' class="btn btn-label-info btn-round me-2 mb-3"><i
                        class="fas fa-arrow-circle-left "></i>
                    Atpakaļ</a>
                @if (isset($dictionary))
                    <a href="{{ route('dictionary.delete', $dictionary->id) }}"
                        onclick="return confirm('Vai esat pārliecināts?')"
                        class="btn btn-label-info btn-round me-2 mb-3 ms-md-auto">
                        Dzēst vārdnīcu <i class="fa fa-trash-alt"></i></a>
                @endif
            </div>

            <input type="hidden" name="course_id" value="{{ $module->id ?? $topic->course_id }}">

            @php
                $languages = ['lv' => 'Latviešu', 'ru' => 'Krievu', 'ua' => 'Ukrainian'];
            @endphp

            <div class="mb-3">
                <label for="title_en" class="form-label">Title (EN)</label>
                <input type="text" class="form-control" id="title_en" name="title_en"
                    value="{{ old('title_en', $dictionary->title_en ?? '') }}">
            </div>
            @foreach ($languages as $code => $lang)
                <div class="mb-3">
                    <label for="title_{{ $code }}" class="form-label">
                        Nosaukums ({{ strtoupper($code) }}) - {{ $lang }}
                        <button type="button" class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4"
                            data-lang="{{ $code }}" data-type="title">Tulkot no EN</button>
                    </label>
                    <input type="text" class="form-control" id="title_{{ $code }}"
                        name="title_{{ $code }}"
                        value="{{ old('title_' . $code, $dictionary->{'title_' . $code} ?? '') }}">
                </div>
            @endforeach

            <button type="submit" class="btn btn-success btn-round">Saglabāt</button>
        </form>

        <!-- Translations Section -->
        @if (isset($dictionary))
            <h3 class="mt-4">Tulkojumi</h3>
            <button id="add-translation" class="btn btn-label-info btn-round mb-3">Jauna frāze/vārds</button>
            <ul id="translations-list" style="list-style-type: none; padding: 20px;">
                @foreach ($translations ?? [] as $translation)
                    <li style="margin: 10px 0; padding: 10px; border-radius: 15px; background-color: #e9e9e9; cursor: move;"
                        data-id="{{ $translation->id }}" data-audio="{{ $translation->audio_lv ?? '' }}">
                        <div class="d-flex align-items-center flex-column flex-md-row">
                            <div class="translation-display">
                                @if ($translation->audio_lv)
                                    <audio controls class="audio-player ms-2">
                                        <source src="{{ asset('storage/' . $translation->audio_lv) }}" type="audio/mp4">
                                    </audio>
                                @endif
                                <br>
                                <strong>LV:</strong> <span class="phrase_lv">{{ $translation->phrase_lv }}</span><br>
                                <strong>EN:</strong> <span class="phrase_en">{{ $translation->phrase_en }}</span><br>
                                <strong>RU:</strong> <span class="phrase_ru">{{ $translation->phrase_ru }}</span><br>
                                <strong>UA:</strong> <span class="phrase_ua">{{ $translation->phrase_ua }}</span>
                            </div>
                            <div class="ms-md-auto py-2 py-md-0 translation-actions">
                                <button class="btn btn-label-info btn-round me-2 edit-translation">Rediģēt</button>
                                <button class="btn btn-label-danger btn-round me-2 delete-translation"
                                    data-url="{{ route('translation.delete', ['id' => $translation->id]) }}">Dzēst</button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Dictionary Form Validation -->
    <script>
        document.getElementById('dictionaryForm').addEventListener('submit', function(e) {
            const title_lv = document.getElementById('title_lv').value.trim();
            const title_en = document.getElementById('title_en').value.trim();
            const title_ru = document.getElementById('title_ru').value.trim();
            const title_ua = document.getElementById('title_ua').value.trim();
            if (!title_lv || !title_en || !title_ru || !title_ua) {
                e.preventDefault();
                Swal.fire('Kļūda', 'Lūdzu ievadiet visus nosaukumus (LV, EN, RU, UA)!', 'warning');
            }
        });

        async function translateField(fromText, toLang) {
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

        $(document).on('click', '.translate-btn', async function() {
            const lang = $(this).data('lang');
            const type = $(this).data('type');
            const li = $(this).closest('li');

            const sourceId = type === 'title' ? '#title_en' : '.phrase_en';
            const targetSelector = type === 'title' ? `#title_${lang}` : `.phrase_${lang}`;
            const sourceValue = type === 'title' ?
                $(sourceId).val() :
                li.find(sourceId).val() || li.find(sourceId).text();

            const translated = await translateField(sourceValue, lang);

            if (type === 'title') {
                $(targetSelector).val(translated);
            } else {
                li.find(targetSelector).val(translated);
            }
        });
    </script>

    <!-- Translations Management Scripts -->
    @if (isset($dictionary))
        <script>
            // Add audio event listeners globally
            $(document).on('play', 'audio.audio-player', function() {
                $(this).addClass('playing');
            });
            $(document).on('pause', 'audio.audio-player', function() {
                $(this).removeClass('playing');
            });

            // Add click handler for custom file upload
            $(document).on('click', '.custom-file-upload', function() {
                $(this).next('input.audio_lv').click();
            });

            $('#add-translation').on('click', function() {
                const newLi = $(
                    '<li class="new-translation" style="margin: 10px 0; padding: 10px; border-radius: 15px; background-color: #e9e9e9;">' +
                    '<div class="translation-inputs">' +
                    '<input type="text" class="form-control phrase_en" placeholder="EN">' +
                    '<button class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4 my-2" data-lang="lv" data-type="phrase">Tulkot no EN</button>' +
                    '<input type="text" class="form-control phrase_lv" placeholder="LV">' +
                    '<button class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4 my-2" data-lang="ru" data-type="phrase">Tulkot no EN</button>' +
                    '<input type="text" class="form-control phrase_ru" placeholder="RU">' +
                    '<button class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4 my-2" data-lang="uk" data-type="phrase">Tulkot no EN</button>' +
                    '<input type="text" class="form-control phrase_uk" placeholder="UA">' +
                    '<div class="mt-2">' +
                    '<label class="custom-file-upload"><i class="fas fa-upload"></i> Augšupielādēt audio (LV, .m4a)</label>' +
                    '<input type="file" class="form-control audio_lv" accept=".m4a">' +
                    '</div>' +
                    '</div>' +
                    '<div class="translation-actions mt-2">' +
                    '<button class="btn btn-label-success btn-round me-2 save-translation">Saglabāt</button>' +
                    '<button class="btn btn-label-secondary btn-round me-2 cancel-translation">Atcelt</button>' +
                    '</div>' +
                    '</li>');
                $('#translations-list').append(newLi);
            });

            $(document).on('click', '.save-translation', function() {
                const li = $(this).closest('li');
                const phrases = {
                    phrase_en: li.find('.phrase_en').val(),
                    phrase_lv: li.find('.phrase_lv').val(),
                    phrase_ru: li.find('.phrase_ru').val(),
                    phrase_ua: li.find('.phrase_uk').val(),
                };
                if (!phrases.phrase_en || !phrases.phrase_lv || !phrases.phrase_ru || !phrases.phrase_ua) {
                    Swal.fire('Kļūda', 'Lūdzu ievadiet frāzi visās valodās!', 'warning');
                    return;
                }
                $.ajax({
                    url: '{{ route('translation.store', $dictionary->id) }}',
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    data: (function() {
                        let formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('phrase_en', phrases.phrase_en);
                        formData.append('phrase_lv', phrases.phrase_lv);
                        formData.append('phrase_ru', phrases.phrase_ru);
                        formData.append('phrase_ua', phrases.phrase_ua);
                        const audioFile = li.find('.audio_lv')[0].files[0];
                        if (audioFile) {
                            formData.append('audio_lv', audioFile);
                        }
                        return formData;
                    })(),
                    success: function(response) {
                        if (response.success) {
                            const audioHtml = response.audio_lv ?
                                '<audio controls class="audio-player ms-2"><source src="/storage/' +
                                response.audio_lv + '" type="audio/mp4"></audio>' : '';
                            li.html(
                                '<div class="d-flex align-items-center flex-column flex-md-row">' +
                                '<div class="translation-display">' +
                                audioHtml + '<br>' +
                                '<strong>LV:</strong> <span class="phrase_lv">' + phrases.phrase_lv +
                                '</span><br>' +
                                '<strong>EN:</strong> <span class="phrase_en">' + phrases.phrase_en +
                                '</span><br>' +
                                '<strong>RU:</strong> <span class="phrase_ru">' + phrases.phrase_ru +
                                '</span><br>' +
                                '<strong>UA:</strong> <span class="phrase_ua">' + phrases.phrase_ua +
                                '</span>' +
                                '</div>' +
                                '<div class="ms-md-auto py-2 py-md-0 translation-actions">' +
                                '<button class="btn btn-label-info btn-round me-2 edit-translation">Rediģēt</button>' +
                                '<button class="btn btn-label-danger btn-round me-2 delete-translation" data-url="/translation/delete/' +
                                response.id + '">Dzēst</button>' +
                                '</div>' +
                                '</div>'
                            );
                            li.attr('data-id', response.id);
                            li.attr('data-audio', response.audio_lv || '');
                            li.removeClass('new-translation');
                        }
                    },
                    error: function() {
                        Swal.fire('Kļūda', 'Neizdevās saglabāt tulkojumu!', 'error');
                    }
                });
            });

            $(document).on('click', '.cancel-translation', function() {
                $(this).closest('li').remove();
            });

            $(document).on('click', '.edit-translation', function() {
                const li = $(this).closest('li');
                li.data('original-html', li.html());
                const phrases = {
                    phrase_en: li.find('.phrase_en').text(),
                    phrase_lv: li.find('.phrase_lv').text(),
                    phrase_ru: li.find('.phrase_ru').text(),
                    phrase_ua: li.find('.phrase_ua').text(),
                };
                const currentAudio = li.data('audio');
                const currentAudioHtml = currentAudio ?
                    ' Esošais audio: <audio controls class="audio-player" src="/storage/' + currentAudio +
                    '"></audio>' : '';
                li.html(
                    '<div class="translation-inputs">' +
                    '<input type="text" class="form-control phrase_en" value="' + phrases.phrase_en + '">' +
                    '<button class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4 my-2" data-lang="lv" data-type="phrase">Tulkot no EN</button>' +
                    '<input type="text" class="form-control phrase_lv" value="' + phrases.phrase_lv + '">' +
                    '<button class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4 my-2" data-lang="ru" data-type="phrase">Tulkot no EN</button>' +
                    '<input type="text" class="form-control phrase_ru" value="' + phrases.phrase_ru + '">' +
                    '<button class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4 my-2" data-lang="uk" data-type="phrase">Tulkot no EN</button>' +
                    '<input type="text" class="form-control phrase_uk" value="' + phrases.phrase_ua + '">' +
                    '<div class="mt-2 d-flex align-items-center">' +
                    '<label class="custom-file-upload"><i class="fas fa-upload"></i> Augšupielādēt audio (LV, .m4a)</label>' +
                    '<input type="file" class="form-control audio_lv" accept=".m4a">' +
                    (currentAudioHtml ? '<span class="ms-2">' + currentAudioHtml + '</span>' : '') +
                    '</div>' +
                    '</div>' +
                    '<div class="translation-actions mt-2">' +
                    '<button class="btn btn-label-success btn-round me-2 update-translation" data-id="' + li.data(
                        'id') +
                    '" data-url="/translation/update/' + li.data('id') + '">Saglabāt</button>' +
                    '<button class="btn btn-label-secondary btn-round me-2 cancel-edit">Atcelt</button>' +
                    '</div>'
                );
            });

            $(document).on('click', '.update-translation', function() {
                const li = $(this).closest('li');
                const id = $(this).data('id');
                const url = $(this).data('url');

                const phrases = {
                    phrase_en: li.find('.phrase_en').val(),
                    phrase_lv: li.find('.phrase_lv').val(),
                    phrase_ru: li.find('.phrase_ru').val(),
                    phrase_ua: li.find('.phrase_uk').val(),
                };
                if (!phrases.phrase_en || !phrases.phrase_lv || !phrases.phrase_ru || !phrases.phrase_ua) {
                    Swal.fire('Kļūda', 'Lūdzu ievadiet frāzi visās valodās!', 'warning');
                    return;
                }
                $.ajax({
                    url: url,
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    data: (function() {
                        let formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('phrase_en', phrases.phrase_en);
                        formData.append('phrase_lv', phrases.phrase_lv);
                        formData.append('phrase_ru', phrases.phrase_ru);
                        formData.append('phrase_ua', phrases.phrase_ua);
                        const audioFile = li.find('.audio_lv')[0].files[0];
                        if (audioFile) {
                            formData.append('audio_lv', audioFile);
                        }
                        return formData;
                    })(),
                    success: function(response) {
                        if (response.success) {
                            const audioHtml = response.audio_lv ?
                                '<audio controls class="audio-player ms-2"><source src="/storage/' +
                                response.audio_lv + '" type="audio/mp4"></audio>' : '';
                            li.html(
                                '<div class="d-flex align-items-center flex-column flex-md-row">' +
                                '<div class="translation-display">' +
                                audioHtml + '<br>' +
                                '<strong>LV:</strong> <span class="phrase_lv">' + phrases.phrase_lv +
                                '</span><br>' +
                                '<strong>EN:</strong> <span class="phrase_en">' + phrases.phrase_en +
                                '</span><br>' +
                                '<strong>RU:</strong> <span class="phrase_ru">' + phrases.phrase_ru +
                                '</span><br>' +
                                '<strong>UA:</strong> <span class="phrase_ua">' + phrases.phrase_ua +
                                '</span>' +
                                '</div>' +
                                '<div class="ms-md-auto py-2 py-md-0 translation-actions">' +
                                '<button class="btn btn-label-info btn-round me-2 edit-translation">Rediģēt</button>' +
                                '<button class="btn btn-label-danger btn-round me-2 delete-translation" data-url="/translation/delete/' +
                                id + '">Dzēst</button>' +
                                '</div>' +
                                '</div>'
                            );
                            li.attr('data-audio', response.audio_lv || '');
                        }
                    },
                    error: function() {
                        Swal.fire('Kļūda', 'Neizdevās atjaunināt tulkojumu!', 'error');
                    }
                });
            });

            $(document).on('click', '.cancel-edit', function() {
                const li = $(this).closest('li');
                li.html(li.data('original-html'));
            });

            $(document).on('click', '.delete-translation', function() {
                const li = $(this).closest('li');
                const id = li.data('id');
                const url = $(this).data('url');

                if (confirm('Vai tiešām vēlaties dzēst šo tulkojumu?')) {
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                li.remove();
                            }
                        },
                        error: function() {
                            Swal.fire('Kļūda', 'Neizdevās dzēst tulkojumu!', 'error');
                        }
                    });
                }
            });

            $('#translations-list').sortable({
                update: function(event, ui) {
                    let order = [];
                    $('#translations-list li').each(function() {
                        const id = $(this).data('id');
                        if (id) order.push(id);
                    });
                    $.ajax({
                        url: '{{ route('translation.updateOrder', $dictionary->id) }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            order: order
                        },
                        success: function(response) {
                            console.log(response.message);
                        },
                        error: function() {
                            Swal.fire('Kļūda', 'Neizdevās atjaunināt kārtību!', 'error');
                        }
                    });
                }
            });
        </script>
    @endif
@endsection
