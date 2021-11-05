<?php

namespace App\Console\Commands;

use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Profile;
use Illuminate\Console\Command;

class SqlManager extends Command
{
    protected $signature = 'sql:command {sql}';

    protected $description = 'update database';
    protected $expression ='* * * * *';
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
        /*$units = Unit::all();
        foreach ($units as $index => $unit) {
            $model = Unit::find($unit->id);
            $model->note1="'";
            $model->save();
        }*/
        $sql = $this->argument('sql');
        \DB::statement($sql);
        $this->info('Cập nhật thành công');
    }
}
