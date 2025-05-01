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


        <form method="POST" action="{{ route('privacy.store') }}" id="privacyForm">
            @csrf
            {{-- @if (isset($privacy))
                    @method('PUT')
                @endif --}}
            <a href="/admin" class="btn btn-label-info btn-round me-2 mb-3 "><i class="fas fa-arrow-circle-left "></i>
                Atpakaļ</a>

            @php
                $languages = ['lv' => 'Latviešu', 'ru' => 'Krievu', 'ua' => 'Ukrainian'];
            @endphp


            <div class="mb-3">
                <label class="form-label">Privātuma politika (EN)</label>
                <textarea name="content_en" id="content_en" class="editor" style="height: 400px;">{{ old('content_en', $privacy->content_en ?? '') }}</textarea>
            </div>

            @foreach ($languages as $code => $lang)
                <div class="mb-3">
                    <label class="form-label">
                        Privātuma politika ({{ strtoupper($code) }}) - {{ $lang }}
                        <button type="button" class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4"
                            data-lang="{{ $code == 'ua' ? 'uk' : $code }}" data-type="content">Tulkot no
                            EN</button>
                    </label>
                    <textarea name="content_{{ $code }}" id="content_{{ $code == 'ua' ? 'uk' : $code }}" class="editor"
                        style="height: 400px;">{{ old("content_$code", $privacy?->{"content_$code"} ?? '') }}</textarea>
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
        document.getElementById('privacyForm').addEventListener('submit', function(e) {
            const content_lv = document.getElementById('content_lv').value.trim();
            const content_en = document.getElementById('content_en').value.trim();
            const content_ru = document.getElementById('content_ru').value.trim();
            const content_ua = document.getElementById('content_ua').value.trim();
            if (!content_lv) {
                e.preventDefault();
                Swal.fire('Kļūda', 'Lūdzu ievadiet privātuma politiku(LV)!', 'warning');
            }
            if (!content_en) {
                e.preventDefault();
                Swal.fire('Kļūda', 'Lūdzu ievadiet notprivātuma politikueikumus(EN)!', 'warning');
            }
            if (!content_ru) {
                e.preventDefault();
                Swal.fire('Kļūda', 'Lūdzu ievadiet privātuma politiku(RU)!', 'warning');
            }
            if (!content_ua) {
                e.preventDefault();
                Swal.fire('Kļūda', 'Lūdzu ievadiet privātuma politiku(UA)!', 'warning');
            }
        });
    </script>



    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    <script>
        const editors = {};
        const allEditorIDs = ['content_en', 'content_lv', 'content_ru', 'content_uk'];

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
                // const target_lang = lang;

                const type = this.dataset.type;
                const sourceId = 'content_en';
                const targetId = `content_${lang}`;
                console.log(lang, type, sourceId, targetId);


                const sourceValue = editors[sourceId].getData();


                const translated = await translateField(sourceValue, lang);

                editors[targetId].setData(translated);

            });
        });
    </script>
@endsection
