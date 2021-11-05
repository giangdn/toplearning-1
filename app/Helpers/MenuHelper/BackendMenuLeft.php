<?php

namespace App\Helpers\MenuHelper;

use App\Permission;
use App\User;
use App\Config;
use Illuminate\Support\Facades\Auth;
use TorMorten\Eventy\Facades\Events as Eventy;

class BackendMenuLeft
{
    public static function render() {
        $menu = new BackendMenuLeft();
        return $menu->view();
    }

    public function view() {
        // dd($this->items());
        return view('backend.menu.item', [
            'items' => $this->items(),
        ]);
    }

    protected function items() {
        $items = [
            'Thống kê' => [
                'id'=>'1',
                'name' => trans('lacore.summary'),
                'url' => Permission::isUnitManager() ? route('module.dashboard_unit') : route('module.dashboard'),
                'icon' => 'uil uil-apps menu--icon',
                'permission' => userCan('dashboard-unit') || \Auth::user()->existsRole() || Permission::isUnitManager(),
                'name_url' => 'menu_summary',
                'url_child' => [],
            ],
            // QUẢN LÝ
            'manager' => [
                'id'=>'2',
                'permission' =>  User::canPermissionCategoryUnit() || User::canPermissionCategoryCourse() || User::canPermissionCategoryQuiz() || User::canPermissionCategoryCost()
                    || User::canPermissionCategoryTeacher() || User::canPermissionCategoryProvince() || userCan('user') || userCan('forum') || userCan('suggest') || userCan('plan-app-template')
                    || userCan('rating-template') || userCan('survey') || Permission::isUnitManager() || userCan('plan-suggest') || userCan('career-roadmap'),
                'name' => trans('backend.management'),
                'icon' => 'fas fa-tasks menu--icon',
                'url' => route('backend.category'),
                'name_url' => 'menu_manager',
                'url_child' => ['category','topic-situations','user','forums','suggest','note','survey','career-roadmap','plan-suggest','model-history','user-contact','manual-api','faq','guide','table-manager','cron','user-take-leave','user-contact','login-history','log-view-course']
            ],
            //ĐÀO TẠO
            'learning_opening' => [
                'id'=>'3',
                'permission' => User::canPermissionTrainingOrganization(),
                'name' => trans('backend.training'),
                'icon' => 'fas fa-chalkboard-teacher menu--icon',
                'url' => route('module.online.management'),
                'name_url' => 'menu_learning_opening',
                'url_child' => ['online','offline','training-plan','course-plan','courseold','trainingroadmap','training-by-title','training-by-title-result','mergesubject','splitsubject','subjectcomplete','movetrainingprocess','subjectregister','indemnify','certificate','evaluationform','rating-organization','report-new']
            ],
            //KỲ THI
            'quiz' => [
                'id'=>'4',
                'permission' => User::canPermissionQuiz(),
                'name' => trans('lamanager.quiz_manager'),
                'icon' => 'far fa-question-circle menu--icon',
                'url' => route('module.quiz.questionlib'),
                'name_url' => 'menu_quiz',
                'url_child' => ['question-lib','quiz-template','quiz','data-old','grading','dashboard','setting-alert','user-second-note','user-secondary','history']
            ],
            // 'report' => [
            //     'id'=>'5',
            //     'name' => trans('backend.report'),
            //     'url' => route('module.report'),
            //     'icon' => 'uil uil-clipboard-alt menu--icon',
            //     'permission' => User::canPermissionReport(),
            // ],
            //THƯ VIỆN
            'library' => [
                'id'=>'5',
                'permission' => userCan('libraries-book') || userCan('libraries-ebook') || userCan('libraries-document') || userCan('libraries-video')
                    || userCan('libraries-category') || userCan('libraries-book-register') || userCan('libraries-book-register'),
                'name' => trans('backend.library'),
                'icon' => 'fas fa-book menu--icon',
                'url' => route('module.libraries.category'),
                'name_url' => 'menu_libraires',
                'url_child' => ['category-libraries','book','ebook','document','audiobook','video']
            ],
            //TIN TỨC
            'news' => [
                'id'=>'6',
                'permission' => userCan('news-category') || userCan('news-list'),
                'name' => trans('backend.news'),
                'icon' => 'far fa-newspaper menu--icon',
                'url' => route('module.news.category'),
                'name_url' => 'menu_news',
                'url_child' => ['category-news','news','advertising-photo','category-news-outside','news-outside']
            ],
            // TÍCH LŨY ĐIỂM THƯỞNG
            'study_promotion_program' => [
                'id'=>'7',
                'permission' => userCan('promotion') || userCan('promotion-group') || userCan('promotion-level') || userCan('promotion-purchase-history'),
                'name' => trans('backend.study_promotion_program'),
                'icon' => 'fas fa-gift menu--icon',
                'url' => route('module.promotion.group'),
                'name_url' => 'menu_promotion',
                'url_child' => ['promotion-group','promotion','promotion-orders','donate-points','promotion-level','emulation-program']
            ],
            //HỌC LIỆU ĐÀO TẠO VIDEO
            'training_video' => [
                'id'=>'8',
                'permission' => userCan('daily-training') || userCan('score-view') || userCan('score-like') || userCan('score-comment'),
                'name' => trans('backend.training_video'),
                'icon' => 'uil uil-video menu--icon',
                'url' => route('module.daily_training'),
                'name_url' => 'menu_training_video',
                'url_child' => ['daily-training','score-views','score-like','score-comment']
            ],
            //PHÂN QUYỀN
            'permission' => [
                'id'=>'9',
                'permission' => userCan('role') || userCan('permission-group') || userCan('approved-process') || userCan('unit-manager-setting'),
                'name' => trans('backend.permission'),
                'icon' => 'fas fa-user-tag menu--icon',
                'url' => route('module.permission.type'),
                'name_url' => 'menu_permission',
                'url_child' => ['permission-type','role','approved-process','unit-manager']
            ],
            //CÀI ĐẶT
            'setting' => [
                'id'=>'10',
                'permission' => userCan('config') || userCan('config-email') ||
                    userCan('config-notify-send') || userCan('config-notify-template') || userCan('config-app-mobile') || userCan('config-favicon') || userCan('config-logo') || userCan('config-login-image') || userCan('config-point-refer')
                    || userCan('mail-template') || userCan('mail-template-history') || userCan('guide')|| userCan('banner') || userCan('donate-point') || userCan('FAQ'),
                'name' => trans('backend.setting'),
                'icon' => 'uil uil-cog menu--icon',
                'url' => route('backend.setting'),
                'name_url' => 'menu_setting',
                'url_child' => ['config','config-email','login-image','logo','logo-outside','favicon','app-mobile','notify-send','notify-template','mail-template','mail-signature','mail-history','contact','google-map','slider','slider-outside','infomation-company','banner-login-mobile','setting-color','languages','setting-time'],
            ],
            //ĐƠN VỊ
            'units_func' => [
                'id'=>'11',
                'name' =>trans('lamanager.unit'),
                'icon' => 'uil uil-black-berry menu--icon',
                'permission' => Permission::isUnitManager() || Permission::isAdmin(),
                'url' => route('module.training_unit.approve_course'),
                'name_url' => 'menu_unit',
                'url_child' => ['approve-course','approve-student-cost','plan-app','course-educate-plan','quiz-educate-plan','authorized-unit']
            ]
        ];

        return Eventy::filter('backend.menu_left', $items);
    }
}
