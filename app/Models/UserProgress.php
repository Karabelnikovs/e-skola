<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'current_order',
        'first_notification_sent_at',
        'second_notification_sent_at',
        'third_notification_sent_at',
        'fourth_notification_sent_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
