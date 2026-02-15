<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TestQueueJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    
    public function handle(): void
    {
        
        \Log::info('Queue System is ACTIVE: Test job successfully processed!');
    }
}
