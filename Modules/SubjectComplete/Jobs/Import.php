<?php

namespace Modules\SubjectComplete\Jobs;

use App\Notifications\ImportUnitCompleted;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\SubjectComplete\Notifications\ImportCompleted;

class Import implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
//        \Log::info($this->user);
        $this->user->notify(new ImportCompleted());
    }
}
