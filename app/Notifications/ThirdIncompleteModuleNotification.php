<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ThirdIncompleteModuleNotification extends Notification
{
    use Queueable;

    protected $course;

    protected $translations = [
        'en' => [
            'greeting' => 'Hello!',
            'subject' => 'Third Reminder: Complete Your Module',
            'line1' => 'You still have not completed the module ',
            'line2' => 'It has been a week since your last progress.',
            'actionText' => 'Continue Module',
            'thankYou' => 'Good luck completing the module!',
            'salutation' => 'Regards, Vizii E-skola',
        ],
        'lv' => [
            'greeting' => 'Sveiki!',
            'subject' => 'Trešais atgādinājums: Pabeidziet savu moduli',
            'line1' => 'Jūs joprojām neesat pabeidzis moduli ',
            'line2' => 'Ir pagājusi nedēļa kopš jūsu pēdējā progresa.',
            'actionText' => 'Turpināt moduli',
            'thankYou' => 'Veiksmi pabeidzot moduli!',
            'salutation' => 'Ar cieņu, Vizii E-skola',
        ],
        'ru' => [
            'greeting' => 'Здравствуйте!',
            'subject' => 'Третье напоминание: Завершите свой модуль',
            'line1' => 'Вы все еще не завершили модуль ',
            'line2' => 'Прошла неделя с момента вашего последнего прогресса.',
            'actionText' => 'Продолжить модуль',
            'thankYou' => 'Удачи в завершении модуля!',
            'salutation' => 'С уважением, Vizii E-skola',
        ],
        'ua' => [
            'greeting' => 'Вітаємо!',
            'subject' => 'Третє нагадування: Завершіть свій модуль',
            'line1' => 'Ви все ще не завершили модуль ',
            'line2' => 'Минув тиждень з моменту вашого останнього прогресу.',
            'actionText' => 'Продовжити модуль',
            'thankYou' => 'Удачі в завершенні модуля!',
            'salutation' => 'З повагою, Vizii E-skola',
        ],
    ];

    public function __construct($course)
    {
        $this->course = $course;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $lang = $notifiable->language ?? 'lv';
        if (!isset($this->translations[$lang])) {
            $lang = 'lv';
        }
        $t = $this->translations[$lang];

        $titleField = 'title_' . $lang;
        $title = $this->course->{$titleField} ?? $this->course->title_lv ?? '';

        return (new MailMessage)
            ->greeting($t['greeting'])
            ->subject($t['subject'])
            ->line($t['line1'] . '"' . $title . '".')
            ->line($t['line2'])
            ->action($t['actionText'], url($lang . '/module/' . $this->course->id))
            ->line($t['thankYou'])
            ->salutation($t['salutation']);
    }
}