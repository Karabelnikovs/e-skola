<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserProgress;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\FirstIncompleteModuleNotification;
use App\Notifications\SecondIncompleteModuleNotification;

class NotifyIncompleteModules extends Command
{
    protected $signature = 'notify:incomplete-modules';
    protected $description = 'Send notifications to users who have not completed their modules';

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

        foreach ($userProgresses as $userProgress) {
            $courseId = $userProgress->course_id;
            if (!isset($lastOrders[$courseId]) || $lastOrders[$courseId] === null) {
                continue;
            }
            $lastOrder = $lastOrders[$courseId];

            if ($userProgress->current_order < $lastOrder) {
                $hoursSinceUpdate = Carbon::now()->diffInHours($userProgress->updated_at);

                if ($hoursSinceUpdate >= 48 && is_null($userProgress->first_notification_sent_at)) {
                    $userProgress->user->notify(new FirstIncompleteModuleNotification($userProgress->course));
                    $userProgress->first_notification_sent_at = Carbon::now();
                    $userProgress->save();
                }

                if ($hoursSinceUpdate >= 168 && is_null($userProgress->second_notification_sent_at)) {
                    $userProgress->user->notify(new SecondIncompleteModuleNotification($userProgress->course));
                    $userProgress->second_notification_sent_at = Carbon::now();
                    $userProgress->save();
                }
            }
        }

        $this->info('Notifications checked and sent successfully.');
    }
}