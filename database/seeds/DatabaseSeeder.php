<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use Modules\PermissionApproved\Database\Seeders\PermissionApprovedDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;
use \Modules\API\Database\Seeders\APIDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
//            TitlesTableSeeder::class,
//            UnitTableSeeder::class,
            UserDatabaseSeeder::class,
//            TrainingProgramTableSeeder::class,
//            LevelSubjectTableSeeder::class,
 //           SubjectTableSeeder::class,
            //CourseCategoriesTableSeeder::class,
//            TrainingFormTableSeeder::class,
//            OnlineTableSeeder::class,
//            OfflineTableSeeder::class,
//            LibrariesCategoryTableSeeder::class,
 //           LibrariesTableSeeder::class,
 //           NewsCategoryTableSeeder::class,
 //           NewsTableSeeder::class,
 //           ForumCategoriesTableSeeder::class,
  //          ForumThreatTableSeeder::class,
 //           OnlineRegisterTableSeeder::class,
//            OfflineRegisterTableSeeder::class,
//            TrainingPlanTableSeeder::class,
//            TrainingPlanDeatailTableSeeder::class,
//            TrainingCostTableSeeder::class,
//            TrainingPartnerTableSeeder::class,
            //UserSecondaryTableSeeder::class,
//            ProvinceTableSeeder::class,
//            DistrictTableSeeder::class,
//            CapabilitiesGroupTableSeeder::class,
//            CapabilitiesCategoryTableSeeder::class,
//            CapabilitiesCategoryGroupTableSeeder::class,
//            CapabilitiesTableSeeder::class,
//            CapabilitiesTitleTableSeeder::class,
//            CapabilitiesTitleSubjectTableSeeder::class,
//            PromotionGroupSeeder::class,
//            PromotionSeeder::class,
 //           PromotionLevelSeeder::class,
            PermissionTableSeeder::class,
            RoleTableSeeder::class,
            CronTableSeeder::class,
            PermissionApprovedDatabaseSeeder::class,
            APIDatabaseSeeder::class,
        ]);
    }
}
