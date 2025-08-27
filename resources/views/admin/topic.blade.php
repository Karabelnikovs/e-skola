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

        <form method="POST" action="{{ isset($topic) ? route('topic.update', $topic->id) : route('topics.store') }}"
            id="topicForm">
            @csrf
            @if (isset($topic))
                @method('POST')
            @endif
            <a href='/module/{{ $module->id ?? $topic->course_id }}' class="btn btn-label-info btn-round me-2 mb-3"><i
                    class="fas fa-arrow-circle-left "></i>
                Atpakaļ</a>
            <input type="hidden" name="course_id" value="{{ $module->id ?? $topic->course_id }}">

            @php
                $languages = ['lv' => 'Latviešu', 'ru' => 'Krievu', 'ua' => 'Ukrainian'];
            @endphp

            <div class="mb-3">
                <label for="title_en" class="form-label">Title (EN)</label>
                <input type="text" class="form-control" id="title_en" name="title_en"
                    value="{{ old('title_en', $topic->title_en ?? '') }}">
            </div>
            @foreach ($languages as $code => $lang)
                <div class="mb-3">
                    <label for="title_{{ $code }}" class="form-label">
                        Nosaukums ({{ strtoupper($code) }}) - {{ $lang }}
                        <button type="button" class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4"
                            data-lang="{{ $code == 'ua' ? 'uk' : $code }}" data-type="title">Tulkot no
                            EN</button>
                    </label>
                    <input type="text" class="form-control" id="title_{{ $code == 'ua' ? 'uk' : $code }}"
                        name="title_{{ $code }}"
                        value="{{ old('title_' . $code, $topic->{'title_' . $code} ?? '') }}">
                </div>
            @endforeach

            <input type="hidden" name="content_en" id="hidden_content_en">
            <input type="hidden" name="content_lv" id="hidden_content_lv">
            <input type="hidden" name="content_ru" id="hidden_content_ru">
            <input type="hidden" name="content_ua" id="hidden_content_ua">

            <div class="mb-3">
                <label class="form-label">Saturs (EN)</label>
                <div id="content_en" data-editable data-name="content_en"
                    style="border:1px solid #ccc; min-height:400px; padding:10px;">{!! old('content_en', $topic->content_en ?? '') !!}</div>
            </div>

            @foreach ($languages as $code => $lang)
                <div class="mb-3">
                    <label class="form-label">
                        Saturs ({{ strtoupper($code) }}) - {{ $lang }}
                    </label>
                    <div id="content_{{ $code == 'ua' ? 'uk' : $code }}" data-editable
                        data-name="content_{{ $code }}"
                        style="border:1px solid #ccc; min-height:400px; padding:10px;">{!! old("content_$code", $topic?->{"content_$code"} ?? '') !!}</div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-success btn-round">Saglabāt</button>
        </form>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jodit/build/jodit.min.js"></script>
    <script>
        const joditConfig = {
            uploader: {
                url: '{{ route('upload.image') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                filesVariableName: (i) => 'file',
                withCredentials: false,
                pathVariableName: 'path',
                format: 'json',
                method: 'POST',
                isSuccess: function(resp) {
                    return !!resp.location;
                },
                process: function(resp) {
                    return {
                        files: [resp.location],
                        error: '',
                        msg: '',
                        baseurl: ''
                    };
                },
                error: function(e) {
                    console.error('Upload error:', e);
                }
            },
            height: 400
        };

        window.editorEn = Jodit.make('#content_en', joditConfig);
        window.editorLv = Jodit.make('#content_lv', joditConfig);
        window.editorRu = Jodit.make('#content_ru', joditConfig);
        window.editorUk = Jodit.make('#content_uk', joditConfig);

        document.getElementById('topicForm').addEventListener('submit', function(e) {
            e.preventDefault();

            document.getElementById('hidden_content_en').value = editorEn.value;
            document.getElementById('hidden_content_lv').value = editorLv.value;
            document.getElementById('hidden_content_ru').value = editorRu.value;
            document.getElementById('hidden_content_ua').value = editorUk.value;

            this.submit();
        });
    </script>
    <script>
        document.getElementById('topicForm').addEventListener('submit', function(e) {
            const title_lv = document.getElementById('title_lv').value.trim();
            const title_en = document.getElementById('title_en').value.trim();
            const title_ru = document.getElementById('title_ru').value.trim();
            const title_ua = document.getElementById('title_uk').value.trim();
            if (!title_lv) {
                e.preventDefault();
                Swal.fire('Kļūda', 'Lūdzu ievadiet tēmas nosaukumu(LV)!', 'warning');
            }
            if (!title_en) {
                e.preventDefault();
                Swal.fire('Kļūda', 'Lūdzu ievadiet tēmas nosaukumu(EN)!', 'warning');
            }
            if (!title_ru) {
                e.preventDefault();
                Swal.fire('Kļūda', 'Lūdzu ievadiet tēmas nosaukumu(RU)!', 'warning');
            }
            if (!title_ua) {
                e.preventDefault();
                Swal.fire('Kļūda', 'Lūdzu ievadiet tēmas nosaukumu(UA)!', 'warning');
            }
        });
    </script>
    <script>
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

        document.querySelectorAll('.translate-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const lang = this.dataset.lang;
                const type = this.dataset.type;
                const sourceId = 'title_en';
                const targetId = `title_${lang}`;

                const sourceValue = document.getElementById(sourceId).value.trim();

                const translated = await translateField(sourceValue, lang);

                document.getElementById(targetId).value = translated;
            });
        });
    </script>
@endsection
