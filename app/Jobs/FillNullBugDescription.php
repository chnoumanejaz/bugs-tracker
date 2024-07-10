<?php

namespace App\Jobs;

use App\Models\Bugs;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FillNullBugDescription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $bugs = Bugs::all();
        foreach ($bugs as $bug) {
            if ($bug->description !== null) {
                continue;
            }

            $bug->description = fake()->text();
            $bug->save();
        }
    }
}
