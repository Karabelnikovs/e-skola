<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class CertificateEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $course;
    public $certificateUrl;

    public function __construct(User $user, $course, $certificateUrl)
    {
        $this->user = $user;
        $this->course = $course;
        $this->certificateUrl = $certificateUrl;
    }

    public function build()
    {
        $lang = $this->user->language ?? 'lv';

        switch ($lang) {
            case 'ua':
                $congrats = 'Вітаємо';
                $subject = 'Вітаємо! Ви отримали сертифікат';
                $body = 'Ви успішно завершили курс "' . ($this->course->title_ua ?? $this->course->title_en) . '" і отримали сертифікат.<br>Завантажте його тут: <a href="' . $this->certificateUrl . '">Завантажити сертифікат</a>';
                break;
            case 'ru':
                $congrats = 'Поздравляем';
                $subject = 'Поздравляем! Вы получили сертификат';
                $body = 'Вы успешно завершили курс "' . ($this->course->title_ru ?? $this->course->title_en) . '" и получили сертификат.<br>Скачайте его здесь: <a href="' . $this->certificateUrl . '">Скачать сертификат</a>';
                break;
            case 'lv':
                $congrats = 'Apsveicam';
                $subject = 'Apsveicam! Jūs saņēmāt sertifikātu';
                $body = 'Jūs veiksmīgi pabeidzāt kursu "' . ($this->course->title_lv ?? $this->course->title_en) . '" un saņēmāt sertifikātu.<br>Lejupielādējiet to šeit: <a href="' . $this->certificateUrl . '">Lejupielādēt sertifikātu</a>';
                break;
            default:
                $congrats = 'Congratulations';
                $subject = 'Congratulations! You have received a certificate';
                $body = 'You have successfully completed the course "' . ($this->course->title_en ?? $this->course->title_en) . '" and earned a certificate.<br>Download it here: <a href="' . $this->certificateUrl . '">Download Certificate</a>';
        }

        return $this->subject($subject)
            ->view('emails.certificate', compact('congrats', 'body'));
    }
}