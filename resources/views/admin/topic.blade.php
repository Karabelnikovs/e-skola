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

            <div class="mb-3">
                <label class="form-label">Saturs (EN)</label>
                <textarea name="content_en" id="content_en" class="editor">{{ old('content_en', $topic->content_en ?? '') }}</textarea>
            </div>

            @foreach ($languages as $code => $lang)
                <div class="mb-3">
                    <label class="form-label">
                        Saturs ({{ strtoupper($code) }}) - {{ $lang }}
                        <button type="button" class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4"
                            data-lang="{{ $code == 'ua' ? 'uk' : $code }}" data-type="content">Tulkot no
                            EN</button>
                    </label>
                    <textarea name="content_{{ $code }}" id="content_{{ $code == 'ua' ? 'uk' : $code }}" class="editor">{{ old("content_$code", $topic?->{"content_$code"} ?? '') }}</textarea>
                </div>
            @endforeach

            <button type="submit" class="btn btn-success btn-round">Saglabāt</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tinymce@5.10.9/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: 'textarea.editor',
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | image',
            images_upload_handler: function(blobInfo, success, failure) {
                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                fetch('{{ route('upload.image') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Upload failed');
                        return response.json();
                    })
                    .then(data => {
                        if (data.location) {
                            success(data.location);
                        } else {
                            failure(data.error || 'Upload failed');
                        }
                    })
                    .catch(error => failure(error.message));
            },
            height: 400
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
                const sourceId = type === 'title' ? 'title_en' : 'content_en';
                const targetId = type === 'title' ? `title_${lang}` : `content_${lang}`;

                const sourceValue = type === 'title' ?
                    document.getElementById(sourceId).value :
                    tinymce.get(sourceId).getContent();

                const translated = await translateField(sourceValue, lang);
                if (type === 'title') {
                    document.getElementById(targetId).value = translated;
                } else {
                    tinymce.get(targetId).setContent(translated);
                }
            });
        });
    </script>
@endsection
