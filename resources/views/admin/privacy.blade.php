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

            <input type="hidden" name="content_en" id="hidden_content_en">
            <input type="hidden" name="content_lv" id="hidden_content_lv">
            <input type="hidden" name="content_ru" id="hidden_content_ru">
            <input type="hidden" name="content_ua" id="hidden_content_ua">

            <a href="/admin" class="btn btn-label-info btn-round me-2 mb-3 "><i class="fas fa-arrow-circle-left "></i>
                Atpakaļ</a>

            @php
                $languages = ['lv' => 'Latviešu', 'ru' => 'Krievu', 'ua' => 'Ukrainian'];
            @endphp


            <div class="mb-3">
                <label class="form-label">Privātuma politika (EN)</label>
                <div id="content_en" style="border:1px solid #ccc; min-height:400px; padding:10px;">{!! old('content_en', $privacy->content_en ?? '') !!}
                </div>
            </div>

            @foreach ($languages as $code => $lang)
                <div class="mb-3">
                    <label class="form-label">
                        Privātuma politika ({{ strtoupper($code) }}) - {{ $lang }}
                        <button type="button" class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4"
                            data-lang="{{ $code == 'ua' ? 'uk' : $code }}" data-type="content">Tulkot no
                            EN</button>
                    </label>
                    <div id="content_{{ $code == 'ua' ? 'uk' : $code }}"
                        style="border:1px solid #ccc; min-height:400px; padding:10px;">{!! old("content_$code", $privacy?->{"content_$code"} ?? '') !!}</div>
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
                isSuccess: function(resp) {
                    return !!resp.location;
                },
                process: function(resp) {
                    return {
                        files: [resp.location],
                        baseurl: '',
                        msg: '',
                        error: '',
                        isImages: [true]
                    };
                },
                error: function(e) {
                    console.error('Upload error:', e);
                }
            },
            height: 400
        };

        const editorEn = Jodit.make('#content_en', joditConfig);
        const editorLv = Jodit.make('#content_lv', joditConfig);
        const editorRu = Jodit.make('#content_ru', joditConfig);
        const editorUk = Jodit.make('#content_uk', joditConfig);

        const editors = {
            'content_en': editorEn,
            'content_lv': editorLv,
            'content_ru': editorRu,
            'content_uk': editorUk
        };

        document.getElementById('privacyForm').addEventListener('submit', function(e) {
            e.preventDefault();

            document.getElementById('hidden_content_en').value = editorEn.value;
            document.getElementById('hidden_content_lv').value = editorLv.value;
            document.getElementById('hidden_content_ru').value = editorRu.value;
            document.getElementById('hidden_content_ua').value = editorUk.value;

            if (!editorEn.value.trim()) {
                Swal.fire('Kļūda', 'Lūdzu ievadiet privātuma politiku(EN)!', 'warning');
                return;
            }
            if (!editorLv.value.trim()) {
                Swal.fire('Kļūda', 'Lūdzu ievadiet privātuma politiku(LV)!', 'warning');
                return;
            }
            if (!editorRu.value.trim()) {
                Swal.fire('Kļūda', 'Lūdzu ievadiet privātuma politiku(RU)!', 'warning');
                return;
            }
            if (!editorUk.value.trim()) {
                Swal.fire('Kļūda', 'Lūdzu ievadiet privātuma politiku(UA)!', 'warning');
                return;
            }

            this.submit();
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

        document.querySelectorAll('.translate-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const lang = this.dataset.lang;
                const sourceId = 'content_en';
                const targetId = `content_${lang}`;

                const sourceValue = editors[sourceId].value;
                if (sourceValue.trim()) {
                    const translated = await translateField(sourceValue, lang);
                    editors[targetId].value = translated;
                } else {
                    Swal.fire('Tukšs', 'Nav teksta, ko tulkot (EN).', 'info');
                }
            });
        });
    </script>
@endsection
