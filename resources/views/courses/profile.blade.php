@extends('layouts.app')

@section('content')
    @php
        $translations = [
            'lv' => [
                'back' => 'Atpakaļ',
                'button' => 'Saglabāt',
                'name' => 'Vārds',
                'name_placeholder' => 'Ievadiet vārdu',
                'email' => 'E-pasts',
                'email_placeholder' => 'Ievadiet e-pastu',
                'password' => 'Jauna Parole',
                'password_placeholder' => 'Ievadiet jauno paroli',
                'password_confirmation' => 'Apstiprināt paroli',
                'password_confirmation_placeholder' => 'Apstiprināt paroli',
                'language' => 'Valoda',
            ],
            'en' => [
                'back' => 'Back',
                'button' => 'Save',
                'name' => 'Name',
                'name_placeholder' => 'Enter name',
                'email' => 'Email',
                'email_placeholder' => 'Enter email',
                'password' => 'New Password',
                'password_placeholder' => 'Enter new password',
                'password_confirmation' => 'Confirm Password',
                'password_confirmation_placeholder' => 'Confirm password',
                'language' => 'Language',
            ],
            'ru' => [
                'back' => 'Назад',
                'button' => 'Сохранить',
                'name' => 'Имя',
                'name_placeholder' => 'Введите имя',
                'email' => 'Электронная почта',
                'email_placeholder' => 'Введите электронную почту',
                'password' => 'Новый пароль',
                'password_placeholder' => 'Введите новый пароль',
                'password_confirmation' => 'Подтвердить пароль',
                'password_confirmation_placeholder' => 'Подтвердить пароль',
                'language' => 'Язык',
            ],
            'ua' => [
                'back' => 'Назад',
                'button' => 'Зберегти',
                'name' => 'Ім\'я',
                'name_placeholder' => 'Введіть ім\'я',
                'email' => 'Електронна пошта',
                'email_placeholder' => 'Введіть електронну пошту',
                'password' => 'Новий пароль',
                'password_placeholder' => 'Введіть новий пароль',
                'password_confirmation' => 'Підтвердити пароль',
                'password_confirmation_placeholder' => 'Підтвердити пароль',
                'language' => 'Мова',
            ],
        ];

        $lang = Session::get('lang', 'lv');
    @endphp
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <div class="card-body">
        <a href="/" class="btn btn-label-info btn-round me-2 mb-3 "><i class="fas fa-arrow-circle-left "></i>
            {{ $translations[$lang]['back'] }}</a>
        <h1>{{ $title }}</h1>
        <div class="row px-4">

            <div class="" id="addRowModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form id="addUserForm" action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group form-group-default">
                                            <label>{{ $translations[$lang]['name'] }}</label>
                                            <input name="name" type="text" class="form-control"
                                                placeholder="{{ $translations[$lang]['name_placeholder'] }}"
                                                value="{{ $user->name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-group-default">
                                            <label>{{ $translations[$lang]['email'] }}</label>
                                            <input name="email" type="email" class="form-control"
                                                placeholder="{{ $translations[$lang]['email_placeholder'] }}"
                                                value="{{ $user->email }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 pe-0">
                                        <div class="form-group form-group-default">
                                            <label>{{ $translations[$lang]['password'] }}</label>
                                            <input name="password" type="password" class="form-control"
                                                placeholder="{{ $translations[$lang]['password_placeholder'] }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default">
                                            <label>{{ $translations[$lang]['password_confirmation'] }}</label>
                                            <input name="password_confirmation" type="password" class="form-control"
                                                placeholder="{{ $translations[$lang]['password_confirmation_placeholder'] }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 pe-6">
                                        <div class="form-group form-group-default">
                                            <label> {{ $translations[$lang]['language'] }}</label>
                                            <select name="language" class="form-control">
                                                <option value="lv" {{ $user->language == 'lv' ? 'selected' : '' }}>LV
                                                </option>
                                                <option value="en" {{ $user->language == 'en' ? 'selected' : '' }}>EN
                                                </option>
                                                <option value="ua" {{ $user->language == 'ua' ? 'selected' : '' }}>UA
                                                </option>
                                                <option value="ru" {{ $user->language == 'ru' ? 'selected' : '' }}>RU
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" form="addUserForm"
                                class="btn btn-primary btn-round">{{ $translations[$lang]['button'] }}</button>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
@endsection
