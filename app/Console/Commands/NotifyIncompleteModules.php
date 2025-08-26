<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserProgress;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\FirstIncompleteModuleNotification;
use App\Notifications\SecondIncompleteModuleNotification;
use App\Notifications\ThirdIncompleteModuleNotification;
use App\Notifications\FourthIncompleteModuleNotification;

class NotifyIncompleteModules extends Command
{
    protected $signature = 'notify:incomplete-modules';
    protected $description = 'Send notifications to users who have started but not completed their modules';

    public function handle()
    {
        $userProgresses = UserProgress::with('course', 'user')->get();

        $courseIds = UserProgress::distinct()->pluck('course_id')->all();
        $lastOrders = [];
        foreach ($courseIds as $courseId) {
            $lastOrder = DB::table('topics')->where('course_id', $courseId)->select('order')
                ->union(DB::table('tests')->where('course_id', $courseId)->select('order'))
                ->union(DB::table('dictionaries')->where('course_id', $courseId)->select('order'))
                ->max('order');
            $lastOrders[$courseId] = $lastOrder;
        }

        $thresholds = [
            23 => [
                'sent_at' => 'first_notification_sent_at',
                'notification' => FirstIncompleteModuleNotification::class,
            ],
            71 => [
                'sent_at' => 'second_notification_sent_at',
                'notification' => SecondIncompleteModuleNotification::class,
            ],
            168 => [
                'sent_at' => 'third_notification_sent_at',
                'notification' => ThirdIncompleteModuleNotification::class,
            ],
            720 => [
                'sent_at' => 'fourth_notification_sent_at',
                'notification' => FourthIncompleteModuleNotification::class,
            ],
        ];

        foreach ($userProgresses as $userProgress) {
            $courseId = $userProgress->course_id;
            if (!isset($lastOrders[$courseId]) || $lastOrders[$courseId] === null) {
                continue;
            }
            $lastOrder = $lastOrders[$courseId];

            if ($userProgress->current_order > 0 && $userProgress->current_order < $lastOrder) {
                $hoursSinceUpdate = Carbon::now()->diffInHours($userProgress->updated_at, true);

                foreach ($thresholds as $hours => $info) {

                    if ($hoursSinceUpdate >= $hours && is_null($userProgress->{$info['sent_at']})) {
                        $userProgress->user->notify(new $info['notification']($userProgress->course));
                        $userProgress->{$info['sent_at']} = Carbon::now();
                        $userProgress->save();
                    }
                }
            }
        }

        $this->info('Notifications checked and sent successfully.');
    }
}