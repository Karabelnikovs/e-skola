<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;


class FirstIncompleteModuleNotification extends Notification
{
    use Queueable;

    protected $course;
    public $translations = [
        'en' => [
            'subject' => 'Reminder: Complete Your Module',
            'line1' => 'You have not completed the module ',
            'line2' => 'It has been 48 hours since your last progress.',
            'actionText' => 'Continue Module',
            'thankYou' => 'Good luck completing module!',
        ],
        'lv' => [
            'subject' => 'Atgādinājums: Pabeidziet savu moduli',
            'line1' => 'Jūs neesat pabeidzis moduli ',
            'line2' => 'Ir pagājušas 48 stundas kopš jūsu pēdējās progresēšanas.',
            'actionText' => 'Turpināt moduli',
            'thankYou' => 'Veiksmi pabeidzot moduli!',
        ],
        'ru' => [
            'subject' => 'Напоминание: Завершите свой модуль',
            'line1' => 'Вы не завершили модуль ',
            'line2' => 'Прошло 48 часов с момента вашего последнего прогресса.',
            'actionText' => 'Продолжить модуль',
            'thankYou' => 'Удачи в завершении модуля!',
        ],
        'ua' => [
            'subject' => 'Нагадування: Завершіть свій модуль',
            'line1' => 'Ви не завершили модуль ',
            'line2' => 'Минуло 48 годин з моменту вашого останнього прогресу.',
            'actionText' => 'Продовжити модуль',
            'thankYou' => 'Удачі в завершенні модуля!',
        ],
    ];

    public $lang = \Session::get('lang', 'lv');
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
        return (new MailMessage)
            ->subject($this->translations[$this->lang]['subject'])
            ->line($this->translations[$this->lang]['line1'] . $this->course->title . '".')
            ->line($this->translations[$this->lang]['line2'])
            ->action($this->translations[$this->lang]['actionText'], url('/courses/' . $this->course->id))
            ->line($this->translations[$this->lang]['thankYou']);
    }
}