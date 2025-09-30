@extends('layouts.guest')
@section('content')
    @php
        $lang = Session::get('lang', 'en');

        $termsUrl = route($lang . '.guest.privacy', ['type' => 'terms']);
        $privacyUrl = route($lang . '.guest.privacy', ['type' => 'privacy']);
        $cookiesUrl = route($lang . '.guest.privacy', ['type' => 'cookies']);

        $translations = [
            'en' => [
                'email' => 'Email',
                'password' => 'Password',
                'remember_me' => 'Remember Me',
                'login' => 'Login',
                'register' => 'Register',
                'logout' => 'Logout',
                'name' => 'Name Surname',
                'confirm_password' => 'Confirm Password',
                'forgot_password' => 'Forgot Your Password?',
                'main_lang' => 'Primary Language',
                'lang' => 'English',
                'length_error' => 'The name must be at least 2 characters long.',
                'error' => 'Error',
                'email_error' => 'Please enter a valid email address.',
                'password_error' => 'The password must be at least 8 characters long.',
                'confirm_error' => 'The password confirmation does not match.',
                'unique_error' => 'The email address has already been taken.',
                'bad_email' => 'Email is invalid or cannot receive emails. Try a different one.',
                'terms_conditions' =>
                    'I agree to the <a href="' .
                    $termsUrl .
                    '" class="text-purple-500 hover:underline" target="_blank" rel="noopener">Terms &amp; Conditions</a>',
                'privacy_policy' =>
                    'I agree to the <a href="' .
                    $privacyUrl .
                    '" class="text-purple-500 hover:underline" target="_blank" rel="noopener">Privacy Policy</a>',
                'cookies_policy' =>
                    'I agree to the <a href="' .
                    $cookiesUrl .
                    '" class="text-purple-500 hover:underline" target="_blank" rel="noopener">Cookies Policy</a>',
                'checkbox_error' => 'You must accept the terms, privacy policy, and cookies policy to register.',
                'welcome_text' => '<div class="text-sm leading-relaxed space-y-3">
    <p class="font-semibold text-lg">Welcome to Vizii e-School!</p>
    <p>This platform is designed so that you can acquire the knowledge necessary for work at a convenient time, place, and pace.</p>
    <p>Various courses and useful materials are available in Vizii e-School.</p>
    <p>The e-School will be regularly updated with new training topics.</p>
    <p>After registration, you will have a personal profile with all the necessary learning resources.</p>
    <p class="mt-1"><strong>👉 To join Vizii e-School, please register!</strong></p>
</div>',
            ],
            'lv' => [
                'email' => 'E-pasts',
                'password' => 'Parole',
                'remember_me' => 'Atcerēties mani',
                'login' => 'Pieteikties',
                'register' => 'Reģistrēties',
                'logout' => 'Iziet',
                'name' => 'Vārds Uzvārds',
                'confirm_password' => 'Apstiprināt paroli',
                'forgot_password' => 'Aizmirsāt paroli?',
                'main_lang' => 'Pamatvaloda',
                'lang' => 'Latviešu',
                'length_error' => 'Vārdam ir jābūt vismaz 2 rakstzīmēm garam.',
                'error' => 'Kļūda',
                'email_error' => 'Lūdzu, ievadiet derīgu e-pasta adresi.',
                'password_error' => 'Parolei ir jābūt vismaz 8 rakstzīmēm garai.',
                'confirm_error' => 'Paroles apstiprinājums nesakrīt.',
                'unique_error' => 'E-pasta adrese jau ir aizņemta.',
                'bad_email' => 'E-pasts ir nederīgs vai nevar saņemt e-pastus. Mēģiniet citu.',
                'terms_conditions' =>
                    'Es piekrītu <a href="' .
                    $termsUrl .
                    '" class="text-purple-500 hover:underline" target="_blank" rel="noopener">Lietošanas noteikumiem</a>',
                'privacy_policy' =>
                    'Es piekrītu <a href="' .
                    $privacyUrl .
                    '" class="text-purple-500 hover:underline" target="_blank" rel="noopener">Privātuma politikai</a>',
                'cookies_policy' =>
                    'Es piekrītu <a href="' .
                    $cookiesUrl .
                    '" class="text-purple-500 hover:underline" target="_blank" rel="noopener">Sīkdatņu politikai</a>',
                'checkbox_error' =>
                    'Lai reģistrētos, jums ir jāpiekrīt lietošanas noteikumiem, privātuma politikai un sīkdatņu politikai.',
                'welcome_text' => '<div class="text-sm leading-relaxed space-y-3">
    <p class="font-semibold text-lg">Laipni lūdzam Vizii e-skolā!</p>
    <p>Šī platforma veidota, lai Jūs varētu apgūt darbam nepieciešamās zināšanas sev ērtā laikā, vietā un tempā.</p>
    <p>Vizii e-skolā pieejami dažādi kursi un noderīgi materiāli.</p>
    <p>E-skola regulāri tiks papildināta ar jaunām apmācību tēmām.</p>
    <p>Pēc reģistrēšanās Jums tiks izveidots personīgais profils ar visiem nepieciešamajiem mācību resursiem.</p>
    <p class="mt-1"><strong>👉 Lai pievienotos Vizii e-skolai, reģistrējieties!</strong></p>
</div>',
            ],
            'ru' => [
                'email' => 'Электронная почта',
                'password' => 'Пароль',
                'remember_me' => 'Запомнить меня',
                'login' => 'Войти',
                'register' => 'Регистрация',
                'logout' => 'Выйти',
                'name' => 'Имя Фамилия',
                'confirm_password' => 'Подтвердить пароль',
                'forgot_password' => 'Забыли пароль?',
                'main_lang' => 'Основной язык',
                'lang' => 'Русский',
                'length_error' => 'Имя должно содержать не менее 2 символов.',
                'error' => 'Ошибка',
                'email_error' => 'Пожалуйста, введите действительный адрес электронной почты.',
                'password_error' => 'Пароль должен содержать не менее 8 символов.',
                'confirm_error' => 'Подтверждение пароля не совпадает.',
                'unique_error' => 'Электронная почта уже занята.',
                'bad_email' =>
                    'Электронная почта недействительна или не может получать электронные письма. Попробуйте другую.',
                'terms_conditions' =>
                    'Я согласен с <a href="' .
                    $termsUrl .
                    '" class="text-purple-500 hover:underline" target="_blank" rel="noopener">Условиями и положениями</a>',
                'privacy_policy' =>
                    'Я согласен с <a href="' .
                    $privacyUrl .
                    '" class="text-purple-500 hover:underline" target="_blank" rel="noopener">Политикой конфиденциальности</a>',
                'cookies_policy' =>
                    'Я согласен с <a href="' .
                    $cookiesUrl .
                    '" class="text-purple-500 hover:underline" target="_blank" rel="noopener">Политикой использования файлов cookie</a>',
                'checkbox_error' =>
                    'Вы должны принять условия, политику конфиденциальности и политику в отношении файлов cookie для регистрации.',
                'welcome_text' => '<div class="text-sm leading-relaxed space-y-3">
    <p class="font-semibold text-lg">Добро пожаловать в Vizii e-школу!</p>
    <p>Эта платформа создана для того, чтобы вы могли получить необходимые для работы знания в удобное для вас время, месте и темпе.</p>
    <p>В Vizii e-школе доступны различные курсы и полезные материалы.</p>
    <p>Э-школа будет регулярно пополняться новыми учебными темами.</p>
    <p>После регистрации для вас будет создан личный профиль со всеми необходимыми учебными ресурсами.</p>
    <p class="mt-1"><strong>👉 Чтобы присоединиться к Vizii e-школе, зарегистрируйтесь!</strong></p>
</div>',
            ],
            'ua' => [
                'email' => 'Електронна пошта',
                'password' => 'Пароль',
                'remember_me' => 'Запам\'ятати мене',
                'login' => 'Увійти',
                'register' => 'Реєстрація',
                'logout' => 'Вийти',
                'name' => 'Ім\'я Прізвище',
                'confirm_password' => 'Підтвердити пароль',
                'forgot_password' => 'Забули пароль?',
                'main_lang' => 'Основна мова',
                'lang' => 'Українська',
                'length_error' => 'Ім\'я повинно містити не менше 2 символів.',
                'error' => 'Помилка',
                'email_error' => 'Будь ласка, введіть дійсну електронну адресу.',
                'password_error' => 'Пароль повинен містити не менше 8 символів.',
                'confirm_error' => 'Підтвердження пароля не збігається.',
                'unique_error' => 'Електронна пошта вже зайнята.',
                'bad_email' => 'Електронна пошта недійсна або не може отримувати електронні листи. Спробуйте іншу.',
                'terms_conditions' =>
                    'Я погоджуюся з <a href="' .
                    $termsUrl .
                    '" class="text-purple-500 hover:underline" target="_blank" rel="noopener">Правилами та умовами</a>',
                'privacy_policy' =>
                    'Я погоджуюся з <a href="' .
                    $privacyUrl .
                    '" class="text-purple-500 hover:underline" target="_blank" rel="noopener">Політикою конфіденційності</a>',
                'cookies_policy' =>
                    'Я погоджуюся з <a href="' .
                    $cookiesUrl .
                    '" class="text-purple-500 hover:underline" target="_blank" rel="noopener">Політикою щодо файлів cookie</a>',
                'checkbox_error' =>
                    'Ви повинні прийняти умови, політику конфіденційності та політику щодо файлів cookie, щоб зареєструватися.',
                'welcome_text' => '<div class="text-sm leading-relaxed space-y-3">
    <p class="font-semibold text-lg">Ласкаво просимо до Vizii e-школи!</p>
    <p>Ця платформа створена для того, щоб ви могли здобути необхідні для роботи знання у зручний для вас час, місці та темпі.</p>
    <p>У Vizii e-школі доступні різноманітні курси та корисні матеріали.</p>
    <p>E-школа регулярно поповнюватиметься новими навчальними темами.</p>
    <p>Після реєстрації для вас буде створено особистий профіль з усіма необхідними навчальними ресурсами.</p>
    <p class="mt-1"><strong>👉 Щоб приєднатися до Vizii e-школи, зареєструйтесь!</strong></p>
</div>',
            ],
        ];
    @endphp

    <div class="flex flex-row items-center gap-3 justify-center">
        <img src="https://vizii.lv/urban/wp-content/uploads/sites/2/2021/09/cropped-vizii_fav-32x32.png" alt="Vizii Logo"
            class="navbar-brand h-16 w-auto mt-2" />

        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-auto" viewBox="0 0 83 36" fill="none">
                <path d="M0 12.4503H4.94253L11.9024 28.3754L18.8623 12.4503H23.704L13.4911 35.7504H10.2129L0 12.4503Z"
                    fill="#36225f"></path>
                <path d="M27.2598 12.4503H32.0006V35.7504H27.2598V12.4503Z" fill="#36225f"></path>
                <path
                    d="M49.8794 16.6503H37.7248V12.4503H56.4862V15.4253L43.5247 31.5254H57.4697V35.7504H36.9683V32.7754L49.8794 16.6503Z"
                    fill="#36225f"></path>
                <path d="M62.0591 12.4503H66.7999V35.7504H62.0591V12.4503Z" fill="#36225f"></path>
                <path d="M73.1797 12.4503H77.9205V35.7504H73.1797V12.4503Z" fill="#36225f"></path>
                <path
                    d="M75.626 0C74.5164 1.95 72.4234 3.275 70.0026 3.275C67.5817 3.275 65.4635 1.95 64.3792 0L60.4453 2.25C62.3618 5.525 65.9174 7.75 70.0278 7.75C74.1129 7.75 77.6938 5.55 79.6103 2.25L75.626 0Z"
                    fill="#36225f"></path>
            </svg>

            <span class="flex items-center font-bold text-4xl text-[#36225f] mt-4">E-skola</span>
        </div>
    </div>

    <div class="flex flex-col">
        <div class="my-4">
            <p>{!! $translations[$lang]['welcome_text'] !!}</p>
        </div>

        <div class="mt-4 bg-white shadow-md rounded-lg text-left">
            <form method="POST" action="{{ route($lang . '.register') }}" id="registerForm"
                onsubmit="return validateForm()">
                @csrf
                <div class="h-2 bg-purple-400 rounded-t-md"></div>
                <div class="px-8 py-6">
                    @if ($errors->any())
                        @if (collect($errors->all())->contains(__('validation.unique')))
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: `{{ $translations[$lang]['error'] }}!`,
                                    html: `{{ $translations[$lang]['unique_error'] }}`
                                });
                            </script>
                        @elseif (collect($errors->all())->contains(__('bad_email')))
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: `{{ $translations[$lang]['error'] }}!`,
                                    html: `{{ $translations[$lang]['bad_email'] }}`
                                });
                            </script>
                        @else
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: `{{ $translations[$lang]['error'] }}!`,
                                    html: `{!! implode('<br>', $errors->all()) !!}`
                                });
                            </script>
                        @endif
                    @endif

                    <div id="errorModal"
                        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" role="dialog"
                        aria-labelledby="errorModalTitle">
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="flex justify-between items-center">
                                <h3 id="errorModalTitle" class="text-lg font-semibold text-red-600">
                                    {{ $translations[$lang]['register'] }} Error</h3>
                                <button id="closeModal" class="text-gray-500 hover:text-gray-700 text-2xl"
                                    aria-label="Close modal">×</button>
                            </div>
                            <div class="mt-2">
                                <p id="errorMessage" class="text-red-500"></p>
                            </div>
                        </div>
                    </div>

                    <label class="block font-semibold">{{ $translations[$lang]['name'] }}</label>
                    <input type="text" placeholder="{{ $translations[$lang]['name'] }}..." id="name" name="name"
                        value="{{ old('name', isset($prefill) ? $prefill->name : '') }}" required autofocus
                        class="border w-full h-5 px-3 py-5 mt-2 hover:outline-none focus:outline-none focus:ring-indigo-500 focus:ring-1 rounded-md">

                    <label class="block mt-3 font-semibold">{{ $translations[$lang]['email'] }}</label>
                    <input type="email" placeholder="{{ $translations[$lang]['email'] }}..." id="email"
                        name="email" value="{{ old('email', isset($prefill) ? $prefill->email : '') }}"
                        {{ isset($prefill) ? 'readonly' : '' }} required
                        class="border w-full h-5 px-3 py-5 mt-2 hover:outline-none focus:outline-none focus:ring-indigo-500 focus:ring-1 rounded-md">

                    <label class="block mt-3 font-semibold">{{ $translations[$lang]['password'] }}</label>
                    <input type="password" placeholder="{{ $translations[$lang]['password'] }}..." id="password"
                        name="password" required
                        class="border w-full h-5 px-3 py-5 mt-2 hover:outline-none focus:outline-none focus:ring-indigo-500 focus:ring-1 rounded-md">

                    <label class="block mt-3 font-semibold">{{ $translations[$lang]['confirm_password'] }}</label>
                    <input type="password" placeholder="{{ $translations[$lang]['confirm_password'] }}..."
                        id="password_confirmation" name="password_confirmation" required
                        class="border w-full h-5 px-3 py-5 mt-2 hover:outline-none focus:outline-none focus:ring-indigo-500 focus:ring-1 rounded-md">

                    <label class="block mt-3 font-semibold">{{ $translations[$lang]['main_lang'] }}</label>
                    <select name="main_lang" id="main_lang" class="border w-full h-10 px-3 py-2 mt-2 rounded-md">
                        @foreach (config('locale.supported') as $locale)
                            <option value="{{ $locale }}"
                                {{ (isset($prefill) && $prefill->language == $locale) || $locale == $lang ? 'selected' : '' }}>
                                {{ $translations[$locale]['lang'] }}
                            </option>
                        @endforeach
                    </select>
                    @if (isset($prefill))
                        <input type="hidden" name="main_lang" value="{{ $prefill->language }}">
                    @endif
                    @if (isset($prefill))
                        <input type="hidden" name="token" value="{{ $prefill->token }}">
                    @endif

                    <div class="mt-4 space-y-3">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input id="terms" name="terms" type="checkbox" required class="sr-only peer" />
                            <div class="w-5 h-5 flex items-center justify-center rounded-md border-2 border-gray-300 peer-checked:border-purple-500 peer-checked:bg-purple-500 transition-all peer-focus:ring-2 peer-focus:ring-indigo-300"
                                aria-hidden="true">
                                <svg class="w-3 h-3 hidden peer-checked:block text-white" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.5 10.5L8.2 14.2L15.5 6.9" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="text-sm">{!! $translations[$lang]['terms_conditions'] !!}</span>
                        </label>

                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input id="privacy" name="privacy" type="checkbox" required class="sr-only peer" />
                            <div class="w-5 h-5 flex items-center justify-center rounded-md border-2 border-gray-300 peer-checked:border-purple-500 peer-checked:bg-purple-500 transition-all peer-focus:ring-2 peer-focus:ring-indigo-300"
                                aria-hidden="true">
                                <svg class="w-3 h-3 hidden peer-checked:block text-white" viewBox="0 0 20 20"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.5 10.5L8.2 14.2L15.5 6.9" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="text-sm">{!! $translations[$lang]['privacy_policy'] !!}</span>
                        </label>

                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input id="cookies" name="cookies" type="checkbox" required class="sr-only peer" />
                            <div class="w-5 h-5 flex items-center justify-center rounded-md border-2 border-gray-300 peer-checked:border-purple-500 peer-checked:bg-purple-500 transition-all peer-focus:ring-2 peer-focus:ring-indigo-300"
                                aria-hidden="true">
                                <svg class="w-3 h-3 hidden peer-checked:block text-white" viewBox="0 0 20 20"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.5 10.5L8.2 14.2L15.5 6.9" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <span class="text-sm">{!! $translations[$lang]['cookies_policy'] !!}</span>
                        </label>
                    </div>


                    <div class="flex justify-between items-baseline">
                        <button type="submit"
                            class="mt-4 bg-purple-500 text-white py-2 px-6 rounded-md hover:bg-purple-600">{{ $translations[$lang]['register'] }}</button>
                        <a class="text-purple-500 hover:text-purple-600 text-sm"
                            href="/{{ $lang }}/login">{{ $translations[$lang]['login'] }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function showErrorModal(message) {
            const modal = document.getElementById('errorModal');
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.textContent = message;
            modal.classList.remove('hidden');
        }

        function hideErrorModal() {
            const modal = document.getElementById('errorModal');
            modal.classList.add('hidden');
        }

        document.getElementById('closeModal').addEventListener('click', hideErrorModal);

        document.getElementById('errorModal').addEventListener('click', function(event) {
            if (event.target === this) {
                hideErrorModal();
            }
        });

        function validateForm() {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            const terms = document.getElementById('terms').checked;
            const privacy = document.getElementById('privacy').checked;
            const cookies = document.getElementById('cookies').checked;

            if (!terms || !privacy || !cookies) {
                Swal.fire({
                    icon: 'error',
                    title: `{{ $translations[$lang]['error'] }}!`,
                    html: `{{ $translations[$lang]['checkbox_error'] }}`
                });
                return false;
            }

            if (name.length < 2) {
                Swal.fire({
                    icon: 'error',
                    title: `{{ $translations[$lang]['error'] }}!`,
                    html: `{{ $translations[$lang]['length_error'] }}`
                });
                return false;
            }

            if (!emailRegex.test(email)) {
                Swal.fire({
                    icon: 'error',
                    title: `{{ $translations[$lang]['error'] }}!`,
                    html: `{{ $translations[$lang]['email_error'] }}`
                });
                return false;
            }

            if (password.length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: `{{ $translations[$lang]['error'] }}!`,
                    html: `{{ $translations[$lang]['password_error'] }}`
                });
                return false;
            }

            if (password !== passwordConfirmation) {
                Swal.fire({
                    icon: 'error',
                    title: `{{ $translations[$lang]['error'] }}!`,
                    html: `{{ $translations[$lang]['confirm_error'] }}`
                });
                return false;
            }

            return true;
        }
    </script>
@endsection
