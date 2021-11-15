<?php

namespace App\Providers;

use App\Helpers\Tracking;
use App\Models\Categories\Area;
use App\Models\Categories\Position;
use App\Models\Categories\Subject;
use App\Models\Categories\TeacherType;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\TrainingType;
use App\Models\Categories\Unit;
use App\Observers\ApprovedProcessObserver;
use App\Observers\AreaObserver;
use App\Observers\CertificateObserver;
use App\Observers\CoursePlanCostObserver;
use App\Observers\CoursePlanObjectObserver;
use App\Observers\CoursePlanObserver;
use App\Observers\IndemnifyObserver;
use App\Observers\MergeSubjectObserver;
use App\Observers\MoveTrainingProcessObserver;
use App\Observers\OfflineAttendanceObserver;
use App\Observers\OfflineConditionObserver;
use App\Observers\OfflineCourseCostObserver;
use App\Observers\OfflineCourseObserver;
use App\Observers\OfflineCourseUploadObserver;
use App\Observers\OfflineInviteRegisterObserver;
use App\Observers\OfflineMonitoringStaffObserver;
use App\Observers\OfflineObjectObserver;
use App\Observers\OfflineRegisterObserver;
use App\Observers\OfflineResultObserver;
use App\Observers\OfflineScheduleParentObserver;
use App\Observers\OfflineTeacherObserver;
use App\Observers\OnlineActivityObserver;
use App\Observers\OnlineConditionObserver;
use App\Observers\OnlineCourseActivityCompletionObserver;
use App\Observers\OnlineCourseActivityObserver;
use App\Observers\OnlineCourseAskAnswerObserver;
use App\Observers\OnlineCourseCostObserver;
use App\Observers\OnlineCourseLessonObserver;
use App\Observers\OnlineCourseObserver;
use App\Observers\OnlineCourseSettingPercentObserver;
use App\Observers\OnlineCourseUploadObserver;
use App\Observers\OnlineInviteRegisterObserver;
use App\Observers\OnlineObjectObserver;
use App\Observers\OnlineRegisterObserver;
use App\Observers\OnlineResultObserver;
use App\Observers\PermissionApprovedObserver;
use App\Observers\PermissionTypeObserver;
use App\Observers\PlanAppTemplateObserver;
use App\Observers\PositionObserver;
use App\Observers\ProfileObserver;
use App\Observers\PromotionCourseSettingObserver;
use App\Observers\QuestionCategoryObserver;
use App\Observers\QuestionCategoryUserObserver;
use App\Observers\QuestionObserver;
use App\Observers\QuizNoteByUserSecondObserver;
use App\Observers\QuizObserver;
use App\Observers\QuizPartObserver;
use App\Observers\QuizQuestionCategoryObserver;
use App\Observers\QuizQuestionObserver;
use App\Observers\QuizRankObserver;
use App\Observers\QuizRegisterObserver;
use App\Observers\QuizResultObserver;
use App\Observers\QuizSettingAlertObserver;
use App\Observers\QuizSettingObserver;
use App\Observers\QuizTeacherObserver;
use App\Observers\QuizTemplatesObserver;
use App\Observers\QuizTemplatesQuestionCategoryObserver;
use App\Observers\QuizTemplatesQuestionObserver;
use App\Observers\QuizTemplatesRankObserver;
use App\Observers\QuizTemplatesSettingObserver;
use App\Observers\QuizUserSecondaryObserver;
use App\Observers\RatingTemplateObserver;
use App\Observers\RoleHasPermissionObserver;
use App\Observers\RoleObserver;
use App\Observers\RolePermissionTypeObserver;
use App\Observers\SubjectObserver;
use App\Observers\TeacherTypeObserver;
use App\Observers\TitlesObserver;
use App\Observers\TrainingByTitleCategoryObserver;
use App\Observers\TrainingByTitleDetailObserver;
use App\Observers\TrainingByTitleObserver;
use App\Observers\TrainingFormObserver;
use App\Observers\TrainingPartnerObserver;
use App\Observers\TrainingPlanDetailObserver;
use App\Observers\TrainingPlanObserver;
use App\Observers\TrainingProcessLogsObserver;
use App\Observers\TrainingProgramObserver;
use App\Observers\TrainingRoadmapObserver;
use App\Observers\TrainingTypeObserver;
use App\Observers\UnitObserver;
use App\Observers\UserRoleObserver;
use App\PermissionType;
use App\Profile;
use App\Role;
use App\RolePermissionType;
use App\UserRole;
use Illuminate\Support\ServiceProvider;
use Modules\Certificate\Entities\Certificate;
use Modules\CoursePlan\Entities\CoursePlan;
use Modules\CoursePlan\Entities\CoursePlanCost;
use Modules\CoursePlan\Entities\CoursePlanObject;
use Modules\Indemnify\Entities\Indemnify;
use Modules\MergeSubject\Entities\MergeSubject;
use Modules\MoveTrainingProcess\Entities\MoveTrainingProcess;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCondition;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineCourseUpload;
use Modules\Offline\Entities\OfflineInviteRegister;
use Modules\Offline\Entities\OfflineMonitoringStaff;
use Modules\Offline\Entities\OfflineObject;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineScheduleParent;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\Online\Entities\OnlineActivity;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\Online\Entities\OnlineCourseAskAnswer;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineCourseLesson;
use Modules\Online\Entities\OnlineCourseSettingPercent;
use Modules\Online\Entities\OnlineCourseUpload;
use Modules\Online\Entities\OnlineInviteRegister;
use Modules\Online\Entities\OnlineObject;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\PermissionApproved\Entities\ApprovedProcess;
use Modules\PermissionApproved\Entities\PermissionApproved;
use Modules\PlanApp\Entities\PlanAppTemplate;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionCategory;
use Modules\Quiz\Entities\QuestionCategoryUser;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizNoteByUserSecond;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizQuestionCategory;
use Modules\Quiz\Entities\QuizRank;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizSetting;
use Modules\Quiz\Entities\QuizSettingAlert;
use Modules\Quiz\Entities\QuizTeacher;
use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizTemplatesQuestion;
use Modules\Quiz\Entities\QuizTemplatesQuestionCategory;
use Modules\Quiz\Entities\QuizTemplatesRank;
use Modules\Quiz\Entities\QuizTemplatesSetting;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Rating\Entities\RatingTemplate;
use Modules\Role\Entities\RoleHasPermission;
use Modules\TrainingByTitle\Entities\TrainingByTitle;
use Modules\TrainingByTitle\Entities\TrainingByTitleCategory;
use Modules\TrainingByTitle\Entities\TrainingByTitleDetail;
use Modules\TrainingPlan\Entities\TrainingPlan;
use Modules\TrainingPlan\Entities\TrainingPlanDetail;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\User\Entities\TrainingProcessLogs;
use Modules\User\Entities\User;

use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //$this->app->useStoragePath(config('app.datafile.dataroot'));
        \Illuminate\Database\Query\Builder::macro('toRawSql', function () {
            return array_reduce($this->getBindings(), function ($sql, $binding) {
                return preg_replace('/\?/', is_numeric($binding) ? $binding : "'" . $binding . "'", $sql, 1);
            }, $this->toSql());
        });

        \Illuminate\Database\Eloquent\Builder::macro('toRawSql', function () {
            return ($this->getQuery()->toRawSql());
        });
        //$this->app->bind(ItemsController::class, \App\Http\Controllers\Vendor\LaravelFilemanager\ItemsController::class);
    }

    public function boot()
    {
        \Schema::defaultStringLength(256);
        if (explode(':', config('app.url'))[0] == 'https') {
            $this->app['request']->server->set('HTTPS', 'on');
            \URL::forceScheme('https');
        }
        view()->composer('*', function ($view) {
            if (auth()->check())
                $view->with('userUnits', User::getRoleAndManagerUnitUser());
        });

        OfflineCourse::observe(OfflineCourseObserver::class);
        OnlineCourse::observe(OnlineCourseObserver::class);
        OfflineRegister::observe(OfflineRegisterObserver::class);
        OnlineRegister::observe(OnlineRegisterObserver::class);
        Profile::observe(ProfileObserver::class);
        Unit::observe(UnitObserver::class);
        Area::observe(AreaObserver::class);
        Subject::observe(SubjectObserver::class);
        TrainingProgram::observe(TrainingProgramObserver::class);
        TrainingPartner::observe(TrainingPartnerObserver::class);
        RatingTemplate::observe(RatingTemplateObserver::class);
        PlanAppTemplate::observe(PlanAppTemplateObserver::class);
        Quiz::observe(QuizObserver::class);
        TeacherType::observe(TeacherTypeObserver::class);
        TrainingType::observe(TrainingTypeObserver::class);
        TrainingForm::observe(TrainingFormObserver::class);
        Titles::observe(TitlesObserver::class);
        Position::observe(PositionObserver::class);
        QuizResult::observe(QuizResultObserver::class);
        OnlineCourseActivityCompletion::observe(OnlineCourseActivityCompletionObserver::class);
        OfflineCondition::observe(OfflineConditionObserver::class);
        OnlineCourseCondition::observe(OnlineConditionObserver::class);
        OfflineResult::observe(OfflineResultObserver::class);
        CoursePlan::observe(CoursePlanObserver::class);
        CoursePlanObject::observe(CoursePlanObjectObserver::class);
        CoursePlanCost::observe(CoursePlanCostObserver::class);
        TrainingPlan::observe(TrainingPlanObserver::class);
        TrainingPlanDetail::observe(TrainingPlanDetailObserver::class);
        OfflineObject::observe(OfflineObjectObserver::class);
        OfflineScheduleParent::observe(OfflineScheduleParentObserver::class);
        OfflineCourseCost::observe(OfflineCourseCostObserver::class);
        Indemnify::observe(IndemnifyObserver::class);
        OfflineCourseUpload::observe(OfflineCourseUploadObserver::class);
        PromotionCourseSetting::observe(PromotionCourseSettingObserver::class);
        OfflineTeacher::observe(OfflineTeacherObserver::class);
        OfflineMonitoringStaff::observe(OfflineMonitoringStaffObserver::class);
        OfflineAttendance::observe(OfflineAttendanceObserver::class);
        OnlineObject::observe(OnlineObjectObserver::class);
        OnlineCourseCost::observe(OnlineCourseCostObserver::class);
        OnlineCourseLesson::observe(OnlineCourseLessonObserver::class);
        OnlineCourseActivity::observe(OnlineCourseActivityObserver::class);
        OnlineCourseUpload::observe(OnlineCourseUploadObserver::class);
        OnlineCourseAskAnswer::observe(OnlineCourseAskAnswerObserver::class);
        OnlineCourseSettingPercent::observe(OnlineCourseSettingPercentObserver::class);
        OnlineInviteRegister::observe(OnlineInviteRegisterObserver::class);
        OnlineResult::observe(OnlineResultObserver::class);
        TrainingRoadmap::observe(TrainingRoadmapObserver::class);
        TrainingByTitle::observe(TrainingByTitleObserver::class);
        TrainingByTitleCategory::observe(TrainingByTitleCategoryObserver::class);
        TrainingByTitleDetail::observe(TrainingByTitleDetailObserver::class);
        MergeSubject::observe(MergeSubjectObserver::class);
        TrainingProcessLogs::observe(TrainingProcessLogsObserver::class);
        MoveTrainingProcess::observe(MoveTrainingProcessObserver::class);
        Certificate::observe(CertificateObserver::class);
        QuestionCategory::observe(QuestionCategoryObserver::class);
        Question::observe(QuestionObserver::class);
        QuestionCategoryUser::observe(QuestionCategoryUserObserver::class);
        QuizTemplates::observe(QuizTemplatesObserver::class);
        QuizTemplatesQuestion::observe(QuizTemplatesQuestionObserver::class);
        QuizTemplatesQuestionCategory::observe(QuizTemplatesQuestionCategoryObserver::class);
        QuizTemplatesRank::observe(QuizTemplatesRankObserver::class);
        QuizTemplatesSetting::observe(QuizTemplatesSettingObserver::class);
        QuizQuestion::observe(QuizQuestionObserver::class);
        QuizQuestionCategory::observe(QuizQuestionCategoryObserver::class);
        QuizPart::observe(QuizPartObserver::class);
        QuizRank::observe(QuizRankObserver::class);
        QuizTeacher::observe(QuizTeacherObserver::class);
        QuizSetting::observe(QuizSettingObserver::class);
        QuizRegister::observe(QuizRegisterObserver::class);
        QuizSettingAlert::observe(QuizSettingAlertObserver::class);
        QuizUserSecondary::observe(QuizUserSecondaryObserver::class);
        QuizNoteByUserSecond::observe(QuizNoteByUserSecondObserver::class);
        PermissionType::observe(PermissionTypeObserver::class);
        Role::observe(RoleObserver::class);
        UserRole::observe(UserRoleObserver::class);
        RoleHasPermission::observe(RoleHasPermissionObserver::class);
        RolePermissionType::observe(RolePermissionTypeObserver::class);
        ApprovedProcess::observe(ApprovedProcessObserver::class);
        PermissionApproved::observe(PermissionApprovedObserver::class);
        $modules = \Module::all();
        foreach ($modules as $module) {
            $this->loadMigrationsFrom([$module->getPath() . '/Database/Migrations']);
        }
        \Response::macro('attachment', function ($name, $content) {

            $headers = [
                'Content-type'        => 'text/plain',
                'Content-Disposition' => 'attachment; filename="' . $name . '"',
            ];

            return \Response::make($content, 200, $headers);
        });

        // listen query if app in debug mode
        config('app.debug', false) && DB::listen(function ($query) {
            $rawQuery = $query->sql;
            if (
                is_array($query->bindings)
                && count($query->bindings) > 0
            ) {
                foreach ($query->bindings as $val) {
                    $rawQuery = preg_replace('[\?]', "'" . $val . "'", $rawQuery, 1);
                }
            }

            Tracking::put((object)['sql' => $rawQuery, 'time' => $query->time]);
        });
    }
}
