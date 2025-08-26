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
                'subject' => 'Ielūgums reģistrēties',
                'hello' => 'Labdien!',
                'text' => 'Jūs esat saņēmis ielūgumu reģistrēties. Lai pabeigtu reģistrāciju, noklikšķiniet uz zemāk esošās saites:',
                'register' => 'Reģistrēties',
            ],
            'en' => [
                'subject' => 'Invitation to Register',
                'hello' => 'Hello!',
                'text' => 'You have been invited to register. Click the link below to complete your registration:',
                'register' => 'Register',
            ],
            'ru' => [
                'subject' => 'Приглашение на регистрацию',
                'hello' => 'Здравствуйте!',
                'text' => 'Вас пригласили зарегистрироваться. Нажмите на ссылку ниже, чтобы завершить регистрацию:',
                'register' => 'Зарегистрироваться',
            ],
            'ua' => [
                'subject' => 'Запрошення на реєстрацію',
                'hello' => 'Вітаємо!',
                'text' => 'Вас запросили зареєструватися. Натисніть на посилання нижче, щоб завершити реєстрацію:',
                'register' => 'Зареєструватися',
            ],
        ];
        return $this->subject($translations[$lang]['subject'] ?? 'Invitation')
            ->view('emails.invitation')
            ->with(['link' => $link, 'lang' => $lang, 'hello' => $translations[$lang]['hello'] ?? 'Hello', 'text' => $translations[$lang]['text'] ?? 'You have been invited to register. Click the link below to complete your registration:', 'register' => $translations[$lang]['register'] ?? 'Register']);
    }
}