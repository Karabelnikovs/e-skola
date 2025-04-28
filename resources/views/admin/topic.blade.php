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
            {{-- @if (isset($topic))
                    @method('PUT')
                @endif --}}
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
                    <input type="text" class="form-control" id="title_{{ $code }}"
                        name="title_{{ $code }}"
                        value="{{ old('title_' . $code, $topic->{'title_' . $code} ?? '') }}">
                </div>
            @endforeach

            <div class="mb-3">
                <label class="form-label">Saturs (EN)</label>
                <textarea name="content_en" id="content_en" class="editor" style="height: 400px;">{{ old('content_en', $topic->content_en ?? '') }}</textarea>
            </div>

            @foreach ($languages as $code => $lang)
                <div class="mb-3">
                    <label class="form-label">
                        Saturs ({{ strtoupper($code) }}) - {{ $lang }}
                        <button type="button" class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4"
                            data-lang="{{ $code == 'ua' ? 'uk' : $code }}" data-type="content">Tulkot no
                            EN</button>
                    </label>
                    <textarea name="content_{{ $code }}" id="content_{{ $code }}" class="editor" style="height: 400px;">{{ old("content_$code", $topic?->{"content_$code"} ?? '') }}</textarea>
                </div>
            @endforeach

            {{-- <div class="mb-3">
                    <button type="button" id="translateAll" class="btn btn-primary">Translate All from English</button>
                </div> --}}

            <button type="submit" class="btn btn-success btn-round">Saglabāt</button>
        </form>




    </div>

    <script>
        ClassicEditor
            .create(document.querySelector('.editor'), {
                ckfinder: {
                    uploadUrl: '{{ route('upload.image') }}?&_token={{ csrf_token() }}'
                }

            })
            .then(editor => {
                editor.editing.view.change(writer => {
                    writer.setStyle('min-height', '200px', editor.editing.view.document.getRoot());
                });
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        document.getElementById('topicForm').addEventListener('submit', function(e) {
            const title_lv = document.getElementById('title_lv').value.trim();
            const title_en = document.getElementById('title_en').value.trim();
            const title_ru = document.getElementById('title_ru').value.trim();
            const title_ua = document.getElementById('title_ua').value.trim();
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



    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    <script>
        const editors = {};
        const allEditorIDs = ['content_en', 'content_lv', 'content_ru', 'content_ua'];

        Promise.all(allEditorIDs.map(id => {
            return ClassicEditor.create(document.querySelector('#' + id), {
                ckfinder: {
                    uploadUrl: '{{ route('upload.image') }}?&_token={{ csrf_token() }}'
                }

            }).then(editor => {
                editors[id] = editor;

                editor.editing.view.change(writer => {
                    writer.setStyle('min-height', '200px', editor.editing.view.document
                        .getRoot());
                });
            });
        }));

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
                if (lang === 'uk') {
                    target_lang = 'ua';
                }
                const type = this.dataset.type;
                const sourceId = type === 'title' ? 'title_en' : 'content_en';
                const targetId = type === 'title' ? `title_${target_lang}` : `content_${target_lang}`;

                const sourceValue = type === 'title' ?
                    document.getElementById(sourceId).value :
                    editors[sourceId].getData();

                const translated = await translateField(sourceValue, lang);
                if (type === 'title') {
                    document.getElementById(targetId).value = translated;
                } else {
                    editors[targetId].setData(translated);
                }
            });
        });

        // document.getElementById('translateAll').addEventListener('click', async function() {
        //     const langs = ['lv', 'ru', 'uk'];

        //     const titleEN = document.getElementById('title_en').value;
        //     const contentEN = editors['content_en'].getData();

        //     for (const lang of langs) {
        //         const titleTrans = await translateField(titleEN, lang);
        //         document.getElementById(`title_${lang}`).value = titleTrans;

        //         const contentTrans = await translateField(contentEN, lang);
        //         editors[`content_${lang}`].setData(contentTrans);
        //     }

        //     Swal.fire('Gatavs!', 'Visi lauki pārtulkoti!', 'success');
        // });
    </script>
@endsection
