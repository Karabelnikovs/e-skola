<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\WelcomeEmail as WelcomeEmailModel;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $email = WelcomeEmailModel::first();
        $lang = $this->user->language ?? 'lv';
        $contentField = 'content_' . $lang;
        $content = $email->{$contentField} ?? $email->content_en;

        switch ($lang) {
            case 'ua':
                $welcome = 'Ласкаво просимо';
                $welcome_to = 'Ласкаво просимо до';
                break;
            case 'ru':
                $welcome = 'Добро пожаловать в';
                $welcome_to = 'Добро пожаловать';
                break;
            case 'lv':
                $welcome = 'Laipni lūdzam';
                $welcome_to = 'Laipni lūdzam';
                break;
            default:
                $welcome = 'Welcome';
                $welcome_to = 'Welcome to';
        }

        return $this->subject($welcome_to . ' ' . config('app.name'))
            ->view('emails.welcome', compact('content', 'welcome'));
    }
}