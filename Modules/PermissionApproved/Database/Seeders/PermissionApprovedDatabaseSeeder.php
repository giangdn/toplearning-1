<?php

namespace Modules\PermissionApproved\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PermissionApprovedDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call([
            ApprovedObjectLevelTableSeederTableSeeder::class,
            ModelApprovedTableSeederTableSeeder::class,
        ]);
        // $this->call("OthersTableSeeder");
    }
}
