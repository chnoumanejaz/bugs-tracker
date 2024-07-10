<?php

namespace App\Console\Commands;

use App\Models\Bugs;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schedule;

class SendEmailToQa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send-qa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending email to that qa who have no image attached to there bug';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bugs = Bugs::all();
        foreach ($bugs as $bug) {
            if ($bug->screenshot !== null) 
            {
                continue;
            }
            $user = User::find($bug->qa_id);
            echo "\nBug {$bug->id} ({$bug->title}) is has no screenshot\n Sending an email to qa for improvement {$user->name}...\n";
        }
}
}