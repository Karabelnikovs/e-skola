@extends('layouts.admin')
@section('content')
    <div class="card-body">
        @if (session('success'))
            <script>
                Swal.fire('Veiksmīgi!', @json(session('success')), 'success');
            </script>
        @endif
        @if (session('save_error'))
            <script>
                Swal.fire('Saglabāšana neizdevās', @json(session('save_error')), 'error');
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

        @php
            $topicStoreUrl = isset($topic)
                ? route('topic.save', $topic->id, absolute: false)
                : route('topics.store', array_filter(['course_id' => $module->id ?? ($topic->course_id ?? null)]), absolute: false);
        @endphp
        <form method="POST" action="{{ $topicStoreUrl }}" id="topicForm">
            @csrf
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">

                <a href='/module/{{ $module->id ?? $topic->course_id }}' class="btn btn-label-info btn-round me-2 mb-3"><i
                        class="fas fa-arrow-circle-left "></i>
                    Atpakaļ</a>
                @if (isset($topic))
                    <a href="{{ route('topic.delete', $topic->id) }}" onclick="return confirm('Vai esat pārliecināts?')"
                        class="btn btn-label-info btn-round me-2 mb-3 ms-md-auto">
                        Dzēst tēmu <i class="fa fa-trash-alt"></i></a>
                @endif

            </div>
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
                        name="title_{{ $code }}" value="{{ old('title_' . $code, $topic->{'title_' . $code} ?? '') }}">
                </div>
            @endforeach

            <input type="hidden" name="content_en" id="hidden_content_en">
            <input type="hidden" name="content_lv" id="hidden_content_lv">
            <input type="hidden" name="content_ru" id="hidden_content_ru">
            <input type="hidden" name="content_ua" id="hidden_content_ua">

            <div class="mb-3">
                <label class="form-label">Saturs (EN)</label>
                <div id="content_en" data-editable data-name="content_en"
                    style="border:1px solid #ccc; min-height:400px; padding:10px;">
                    {!! old('content_en', $topic->content_en ?? '') !!}
                </div>
            </div>

            @foreach ($languages as $code => $lang)
                <div class="mb-3">
                    <label class="form-label">
                        Saturs ({{ strtoupper($code) }}) - {{ $lang }}
                    </label>
                    <div id="content_{{ $code == 'ua' ? 'uk' : $code }}" data-editable data-name="content_{{ $code }}"
                        style="border:1px solid #ccc; min-height:400px; padding:10px;">
                        {!! old("content_$code", $topic?->{"content_$code"} ?? '') !!}
                    </div>
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
                isSuccess: function (resp) {
                    return !!resp.location;
                },
                process: function (resp) {
                    return {
                        files: [resp.location],
                        error: '',
                        msg: '',
                        baseurl: '',
                        isImages: [true]
                    };
                },
                error: function (e) {
                    console.error('Upload error:', e);
                }
            },
            height: 400
        };

        if (typeof Jodit !== 'undefined') {
            window.editorEn = Jodit.make('#content_en', joditConfig);
            window.editorLv = Jodit.make('#content_lv', joditConfig);
            window.editorRu = Jodit.make('#content_ru', joditConfig);
            window.editorUk = Jodit.make('#content_uk', joditConfig);
        }

        document.getElementById('topicForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const titles = {
                lv: document.getElementById('title_lv').value.trim(),
                en: document.getElementById('title_en').value.trim(),
                ru: document.getElementById('title_ru').value.trim(),
                ua: document.getElementById('title_uk').value.trim(),
            };
            const labels = { lv: 'LV', en: 'EN', ru: 'RU', ua: 'UA' };
            for (const [code, value] of Object.entries(titles)) {
                if (!value) {
                    Swal.fire('Kļūda', `Lūdzu ievadiet tēmas nosaukumu(${labels[code]})!`, 'warning');
                    return;
                }
            }

            const sync = (hiddenId, editor, fallbackId) => {
                const el = document.getElementById(hiddenId);
                el.value = editor ? editor.value : (document.getElementById(fallbackId)?.innerHTML || '');
            };
            sync('hidden_content_en', window.editorEn, 'content_en');
            sync('hidden_content_lv', window.editorLv, 'content_lv');
            sync('hidden_content_ru', window.editorRu, 'content_ru');
            sync('hidden_content_ua', window.editorUk, 'content_uk');

            const form = this;
            const btn = form.querySelector('[type=submit]');
            btn.disabled = true;
            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json().catch(() => ({}));
                if (res.ok && data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }
                if (res.status === 422 && data.errors) {
                    const msg = Object.values(data.errors).flat().join('<br>');
                    Swal.fire({ icon: 'error', title: 'Kļūda!', html: msg });
                    return;
                }
                if (res.status === 419) {
                    Swal.fire('Sesija beigusies', 'Lūdzu atsvaidziniet lapu un mēģiniet vēlreiz.', 'error');
                    return;
                }
                Swal.fire('Kļūda', 'Saglabāšana neizdevās (HTTP ' + res.status + ').', 'error');
            } catch (err) {
                Swal.fire('Kļūda', 'Neizdevās nosūtīt datus uz serveri.', 'error');
            } finally {
                btn.disabled = false;
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
            btn.addEventListener('click', async function () {
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