@extends('layouts.admin')
@section('content')
    <div class="card-body">
        @if (isset($module))
            <a href="{{ route('module', $module->id) }}" class="btn btn-label-info btn-round me-2 mb-3 "><i
                    class="fas fa-arrow-circle-left "></i>
                Atpakaļ</a>
        @endif
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h2 class="font-bold mb-2 text-4xl">
                    {{ isset($module) ? 'Rediģēt Moduli' : 'Jauns Modulis' }}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card-round">
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
                    @if (session('error'))
                        <script>
                            Swal.fire('Kļūda!', '{{ session('error') }}', 'error');
                        </script>
                    @endif

                    <form id="moduleForm"
                        action="{{ isset($module) ? route('module.update', $module->id) : route('module.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($module))
                            <input type="hidden" name="module_id" value="{{ $module->id }}">
                        @endif

                        <div class="mb-3">
                            <label for="name_en" class="form-label">Title (EN)</label>
                            <input type="text" class="form-control" id="name_en" name="name_en"
                                value="{{ old('name_en', $module->title_en ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="name_lv" class="form-label">Nosaukums (LV)<button type="button"
                                    class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4" data-lang="lv"
                                    data-type="name">Tulkot no
                                    EN</button></label>

                            <input type="text" class="form-control" id="name_lv" name="name_lv"
                                value="{{ old('name_lv', $module->title_lv ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="name_ru" class="form-label">Название (RU)
                                <button type="button" class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4"
                                    data-lang="ru" data-type="name">Tulkot no
                                    EN</button>
                            </label>
                            <input type="text" class="form-control" id="name_ru" name="name_ru"
                                value="{{ old('name_ru', $module->title_ru ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label for="name_ua" class="form-label">Назва (UA)
                                <button type="button" class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4"
                                    data-lang="uk" data-type="name">Tulkot no
                                    EN</button>
                            </label>
                            <input type="text" class="form-control" id="name_uk" name="name_ua"
                                value="{{ old('name_ua', $module->title_ua ?? '') }}">
                        </div>


                        <div class="mb-3">
                            <label for="description_en" class="form-label">Description (EN)</label>
                            <textarea class="form-control" id="description_en" name="description_en" rows="3">{{ old('description_en', $module->description_en ?? '') }}</textarea>
                            </textarea>
                        </div>
                        <div class="mb-3">
                            <label for="description_lv" class="form-label">Apraksts (LV)
                                <button type="button" class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4"
                                    data-lang="lv" data-type="description">Tulkot no
                                    EN</button></label>
                            <textarea class="form-control" id="description_lv" name="description_lv" rows="3">{{ old('description_lv', $module->description_lv ?? '') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="description_ru" class="form-label">Описание (RU)
                                <button type="button" class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4"
                                    data-lang="ru" data-type="description">Tulkot no
                                    EN</button>
                            </label>
                            <textarea class="form-control" id="description_ru" name="description_ru" rows="3">{{ old('description_ru', $module->description_ru ?? '') }}</textarea>
                            </textarea>
                        </div>
                        <div class="mb-3">
                            <label for="description_uk" class="form-label">Опис (UA)
                                <button type="button" class="btn btn-sm btn-outline-secondary translate-btn btn-round mx-4"
                                    data-lang="uk" data-type="description">Tulkot no
                                    EN</button>
                            </label>
                            <textarea class="form-control" id="description_uk" name="description_ua" rows="3">{{ old('description_uk', $module->description_ua ?? '') }}</textarea>
                            </textarea>
                        </div>


                        <div class="d-flex align-items-center gap-3 my-3">
                            <div class="flex-shrink-0">

                                <img id="preview_img"
                                    src="{{ isset($module) && isset($module->img) ? asset($module->img) : asset('assets/img/course.jpg') }}"
                                    class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">

                            </div>
                            <div>
                                <label for="formFile" class="form-label visually-hidden">Moduļa foto</label>
                                <input class="form-control" type="file" id="formFile" name="formFile"
                                    accept="image/*" onchange="loadFile(event)">
                            </div>
                        </div>

                        <button type="submit"
                            class="btn btn-success btn-round">{{ isset($module) ? 'Saglabāt' : 'Izveidot' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('moduleForm').addEventListener('submit', function(e) {
            const name_lv = document.getElementById('name_lv').value.trim();
            const name_en = document.getElementById('name_en').value.trim();
            const name_ru = document.getElementById('name_ru').value.trim();
            const name_ua = document.getElementById('name_ua').value.trim();
            if (!name_lv) {
                e.preventDefault();
                Swal.fire('Kļūda', 'Lūdzu ievadiet moduļa nosaukumu(LV)!', 'warning');
            }
            if (!name_en) {
                e.preventDefault();
                Swal.fire('Kļūda', 'Lūdzu ievadiet moduļa nosaukumu(EN)!', 'warning');
            }
            if (!name_ru) {
                e.preventDefault();
                Swal.fire('Kļūda', 'Lūdzu ievadiet moduļa nosaukumu(RU)!', 'warning');
            }
            if (!name_ua) {
                e.preventDefault();
                Swal.fire('Kļūda', 'Lūdzu ievadiet moduļa nosaukumu(UA)!', 'warning');
            }
        });
    </script>

    <script>
        var loadFile = function(event) {

            var input = event.target;
            var file = input.files[0];
            var type = file.type;

            var output = document.getElementById('preview_img');


            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src)
            }
        };
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
                const sourceId = type === 'name' ? 'name_en' : 'description_en';
                const targetId = type === 'name' ? `name_${lang}` : `description_${lang}`;

                const sourceValue = document.getElementById(sourceId).value;
                const translated = await translateField(sourceValue, lang);

                document.getElementById(targetId).value = translated;

            });
        });
    </script>
@endsection
