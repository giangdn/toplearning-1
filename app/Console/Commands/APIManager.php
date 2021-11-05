<?php

namespace App\Console\Commands;

use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Profile;
use App\User;
use Illuminate\Console\Command;

class APIManager extends Command
{
    protected $signature = 'api:command';
    protected $description = 'update api';
    protected $expression ='0 1 * * *';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->apiUser();
        $this->info('Cập nhật thành công');
    }

    private function apiUser(){
        $users = User::query()
            ->leftJoin('el_profile as b', 'b.user_id', '=', 'user.id')
            ->get([
                'user.id',
                'user.username',
                'user.password',
                'user.email',
                'user.firstname',
                'user.lastname',
                'b.dob',
                'b.gender',
            ]);

        $list = [];
        foreach ($users as $user){
            $list[$user->id] = [
                'user_id' => $user->id,
                'username' => $user->username,
                'password' => $user->password,
                'email' => $user->email,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'dob' => get_date($user->dob, 'Y-m-d'),
                'gender' => $user->gender,
            ];
        }

        $storage = \Storage::disk('local');
        $attempt_folder = 'api/user';

        if (!$storage->exists($attempt_folder)) {
            \File::makeDirectory($storage->path($attempt_folder), 0777, true);
        }

        $storage->put($attempt_folder . '/api_user'.'.json', json_encode($list));

        return true;
    }
}
