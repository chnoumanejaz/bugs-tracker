<?php

use App\Jobs\FillNullBugDescription;
use App\Models\Bugs;
use App\Models\User;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();


// Schedule::command('mail:send-qa')->everyMinute();


Schedule::call(function () {
    $bugs = Bugs::all();
    foreach ($bugs as $bug) {
        if ($bug->deadline > date('Y-m-d')) {
            continue;
        }
        $user = User::find($bug->developer_id);
        echo "\nBug {$bug->id} ({$bug->title}) is overdue\n Sending an email to {$user->name}...\n";
    }
})->everyMinute();

Schedule::job(new FillNullBugDescription)->everyMinute()->withoutOverlapping();