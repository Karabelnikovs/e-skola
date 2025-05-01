@extends('layouts.admin')
@section('content')
    <div class="card-body">
        <a href="/admin" class="btn btn-label-info btn-round me-2 mb-3 "><i class="fas fa-arrow-circle-left "></i>
            Atpakaļ</a>
        <style>
            .remove-btn {
                cursor: pointer;
                color: red;
                font-size: 20px;
                margin-left: 10px;
            }
        </style>
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h2 class="font-bold mb-2 text-4xl">
                    {{ isset($module) ? 'Rediģēt Moduli' : 'Jauns Modulis' }}</h2>
            </div>
        </div>
        <form id="contactForm">
            <div id="addresses" class="form-section mb-3">
                <label>Adreses</label>
                @php $addresses = json_decode($contacts->addresses ?? '[]', true); @endphp
                @foreach ($addresses as $address)
                    <div>
                        <input name="addresses[]" type="text" class="form-control d-inline-block w-75 mb-2"
                            value="{{ $address }}" />
                        <span class="remove-btn">✕</span>
                    </div>
                @endforeach
                @if (empty($addresses))
                    <div>
                        <input name="addresses[]" type="text" class="form-control d-inline-block w-75 mb-2" />
                        <span class="remove-btn d-none">✕</span>
                    </div>
                @endif
            </div>
            {{-- <button type="button" class="btn btn-label-info btn-round me-2" onclick="addField('addresses')">+ Pielikt
                Adresi</button> --}}


            <div id="phones" class="form-section">
                <label>Telefona nr.</label>
                @php $phones = json_decode($contacts->phone_numbers ?? '[]', true); @endphp
                @foreach ($phones as $phone)
                    <div>
                        <input name="phones[]" type="text" class="form-control d-inline-block w-75 mb-2"
                            value="{{ $phone }}" />
                        <span class="remove-btn">✕</span>
                    </div>
                @endforeach
                @if (empty($phones))
                    <div>
                        <input name="phones[]" type="text" class="form-control d-inline-block w-75 mb-2" />
                        <span class="remove-btn d-none">✕</span>
                    </div>
                @endif
            </div>

            <button type="button" class="btn btn-label-info btn-round me-2 mb-4" onclick="addField('phones')">+ Pielikt
                tel.
                nr.</button>

            <div id="emails" class="form-section">
                <label>E-pasti</label>
                @php $emails = json_decode($contacts->emails ?? '[]', true); @endphp
                @foreach ($emails as $email)
                    <div>
                        <input name="emails[]" type="email" class="form-control d-inline-block w-75 mb-2"
                            value="{{ $email }}" />
                        <span class="remove-btn">✕</span>
                    </div>
                @endforeach
                @if (empty($emails))
                    <div>
                        <input name="emails[]" type="email" class="form-control d-inline-block w-75 mb-2" />
                        <span class="remove-btn d-none">✕</span>
                    </div>
                @endif
            </div>

            <button type="button" class="btn btn-label-info btn-round me-2  mb-3" onclick="addField('emails')">+ Pielikt
                E-pastu</button>

            <div class="my-3">
                <button type="submit" class="btn btn-success mt-6 btn-round">Saglabāt</button>
            </div>
        </form>
    </div>
    <script>
        function addField(sectionId) {
            const section = document.getElementById(sectionId);
            const count = section.querySelectorAll('input').length;

            if (count >= 4) {
                Swal.fire('Kļūda', 'Maksimālais lauku skaits ir 4.', 'warning');
                return;
            }

            const div = document.createElement('div');
            div.innerHTML = `<input name="${sectionId}[]" type="${sectionId === 'emails' ? 'email' : 'text'}" class="form-control d-inline-block w-75 mb-2" />
                     <span class="remove-btn">✕</span>`;
            section.appendChild(div);

            div.querySelector('.remove-btn').addEventListener('click', () => div.remove());
        }

        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.classList.remove('d-none');
            btn.addEventListener('click', (e) => {
                e.target.parentElement.remove();
            });
        });

        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('/contact-section/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Saglabāts', 'Kontaktinformācija saglabāta veiksmīgi.', 'success');
                    } else {
                        Swal.fire('Kļūda', 'Neizdevās saglabāt kontaktinformāciju.', 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Kļūda', 'Neizdevās saglabāt kontaktinformāciju.', 'error');
                });
        });
    </script>
@endsection
