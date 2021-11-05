<?php

namespace App\Console\Commands;

use App\Models\Categories\Titles;
use App\Profile;
use Illuminate\Console\Command;

class Title extends Command
{
    protected $signature = 'title:update';

    protected $description = 'update số nhân viên theo chức danh 1 ngày chạy 2 lần lúc 5h (0 5,17 * * *)';
    protected $expression ='0 5,17 * * *';
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $titles = Titles::all();
        foreach ($titles as $title){
            $users = Profile::where('title_id',$title->id)->count();
            Titles::where('id',$title->id)->update(['employees'=>$users]);
        }
    }
}
