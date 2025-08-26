<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PendingUser;

class InvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $pending;

    public function __construct(PendingUser $pending)
    {
        $this->pending = $pending;
    }

    public function build()
    {
        $lang = $this->pending->language;
        $link = url("/{$lang}/register/{$this->pending->token}");

        $translations = [
            'lv' => [
                'subject' => 'Aicinām Tevi uzsākt mācības mūsu jaunajā e-skolā!',
                'hello' => 'Sveiks, kolēģi!',
                'text' => 'SIA <b>Vizii</b> ir ieviesis digitālu <b>e-skolu</b>, lai ikvienam mūsu darbiniekam būtu iespēja pilnveidot savas zināšanas un paaugstināt kvalifikāciju. <br>Aicinām Tevi reģistrēties sistēmā un iziet nepieciešamās apmācības, lai iegūtu sertifikātu.',
                'register' => 'Reģistrācijas saite: ',
                'eskola' => 'E-skola',
                'text2' => 'Jūsu ieguldījums zināšanās ir nozīmīgs arī mūsu kopīgajiem panākumiem. <br>Paldies, ka veltiet laiku savai profesionālajai izaugsmei!',
                'goodbye' => 'Ar cieņu, <br><b>SIA Vizii komanda</b>',
                'list' => '
                        <li>✅ Apmācību tests aizņems ne vairāk kā <b>1 stundu.</b></li>
                        <li>✅ To iespējams kārtot <b>vairākos piegājienos</b>, pielāgojot mācības savam laikam un ērtībām.</li>
                        <li>✅ Viss mācību saturs ir pieejams 4 valodās – <b>Latviešu, Angļu, Ukraiņu un Krievu</b> valodās </li>
                ',
            ],
            'en' => [
                'subject' => 'We invite you to start studying at our new e-school!',
                'hello' => 'Hello, colleague!',
                'text' => 'SIA <b>Vizii</b> has implemented a digital <b>e-school</b> so that every one of our employees has the opportunity to improve their knowledge and increase their qualifications. <br>We invite you to register in the system and complete the necessary training to obtain a certificate.',
                'register' => 'Registration link:',
                'eskola' => 'E-school',
                'text2' => 'Your contribution to knowledge is also important for our common success. <br>Thank you for taking the time for your professional growth!',
                'goodbye' => 'Sincerely, <br><b>The SIA Vizii Team</b>',
                'list' => '
                        <li>✅ The training test will take no more than <b>1 hour.</b></li>
                        <li>✅ It can be taken in <b>multiple attempts</b>, adapting the training to your time and convenience.</li>
                        <li>✅ All training content is available in 4 languages – <b>Latvian, English, Ukrainian, and Russian</b> languages </li>
                ',
            ],
            'ru' => [
                'subject' => 'Приглашаем вас начать обучение в нашей новой электронной школе!',
                'hello' => 'Здравствуйте, коллега!',
                'text' => 'Компания SIA <b>Vizii</b> внедрила цифровую <b>электронную школу</b>, чтобы каждый сотрудник имел возможность улучшить свои знания и повысить квалификацию. <br>Приглашаем вас зарегистрироваться в системе и пройти необходимое обучение для получения сертификата.',
                'register' => 'Ссылка для регистрации:',
                'eskola' => 'Электронная школа',
                'text2' => 'Ваш вклад в знания важен и для наших общих успехов. <br>Спасибо, что уделяете время своему профессиональному росту!',
                'goodbye' => 'С уважением, <br><b>Команда SIA Vizii</b>',
                'list' => '
                        <li>✅ Тест на обучение займет не более <b>1 часа.</b></li>
                        <li>✅ Его можно сдавать в <b>нескольких попытках</b>, адаптируя обучение к вашему времени и удобству.</li>
                        <li>✅ Весь учебный материал доступен на 4 языках – <b>латышском, английском, украинском и русском</b> языках </li>
                ',
            ],
            'ua' => [
                'subject' => 'Запрошуємо вас розпочати навчання в нашій новій електронній школі!',
                'hello' => 'Привіт, колего!',
                'text' => 'SIA <b>Vizii</b> впровадила цифрову <b>електронну школу</b>, щоб кожен співробітник мав можливість покращити свої знання та підвищити кваліфікацію. <br>Запрошуємо вас зареєструватися в системі та пройти необхідне навчання для отримання сертифіката.',
                'register' => 'Посилання для реєстрації:',
                'eskola' => 'Електронна школа',
                'text2' => 'Ваш внесок у знання важливий і для наших спільних успіхів. <br>Дякуємо, що приділяєте час своєму професійному зростанню!',
                'goodbye' => 'З повагою, <br><b>Команда SIA Vizii</b>',
                'list' => '
                        <li>✅ Тест на навчання займе не більше <b>1 години.</b></li>
                        <li>✅ Його можна складати в <b>кількох спробах</b>, адаптуючи навчання до вашого часу та зручності.</li>
                        <li>✅ Весь навчальний матеріал доступний 4 мовами – <b>латиською, англійською, українською та російською</b> мовами </li>
                ',
            ],
        ];
        return $this->subject($translations[$lang]['subject'] ?? 'Aicinām Tevi uzsākt mācības mūsu jaunajā e-skolā!')
            ->view('emails.invitation')
            ->with([
                'link' => $link,
                'lang' => $lang,
                'hello' => $translations[$lang]['hello'] ?? 'Sveiks, kolēģi!',
                'text' => $translations[$lang]['text'] ?? 'SIA <b>Vizii</b> ir ieviesis digitālu <b>e-skolu</b>, lai ikvienam mūsu darbiniekam būtu iespēja pilnveidot savas zināšanas un paaugstināt kvalifikāciju. Aicinām Tevi reģistrēties sistēmā un iziet nepieciešamās apmācības, lai iegūtu sertifikātu.',
                'register' => $translations[$lang]['register'] ?? 'Reģistrācijas saite: ',
                'eskola' => $translations[$lang]['eskola'] ?? 'E-skola',
                'text2' => $translations[$lang]['text2'] ?? 'Jūsu ieguldījums zināšanās ir nozīmīgs arī mūsu kopīgajiem panākumiem. Paldies, ka veltiet laiku savai profesionālajai izaugsmei!',
                'goodbye' => $translations[$lang]['goodbye'] ?? 'Ar cieņu, SIA Vizii komanda',
                'list' => $translations[$lang]['list'] ?? '
                        <li>✅ Apmācību tests aizņems ne vairāk kā <b>1 stundu.</b></li>
                        <li>✅ To iespējams kārtot <b>vairākos piegājienos</b>, pielāgojot mācības savam laikam un ērtībām.</li>
                        <li>✅ Viss mācību saturs ir pieejams 4 valodās – <b>Latviešu, Angļu, Ukraiņu un Krievu</b> valodās </li>
                ',
            ]);
    }
}