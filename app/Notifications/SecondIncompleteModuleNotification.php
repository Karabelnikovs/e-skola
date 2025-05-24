<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SecondIncompleteModuleNotification extends Notification
{
    use Queueable;

    protected $course;

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
            ->subject('Second Reminder: Complete Your Module')
            ->line('You still have not completed the module "' . $this->course->title . '".')
            ->line('It has been a week since your last progress.')
            ->action('Continue Module', url('/courses/' . $this->course->id))
            ->line('Thank you for using our application!');
    }
}