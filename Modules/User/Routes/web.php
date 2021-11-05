<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => '/user', 'middleware' => ['quiz.secondary','auth']], function() {
    Route::get('/my-course/{type}', 'Frontend\UserController@getMyCourse')->name('module.frontend.user.my_course');

    Route::get('/my-course/getdata', 'Frontend\UserController@getData')->name('module.frontend.user.my_course.getdata');
});

Route::group(['prefix' => '/user', 'middleware' => 'auth'], function() {
    Route::get('/roadmap', 'Frontend\UserController@index')->name('module.frontend.user.roadmap');

    Route::get('/roadmap/getData', 'Frontend\UserController@getDataRoadmap')->name('module.frontend.user.roadmap.getDataRoadmap');

    Route::post('/roadmap/modal-content', 'Frontend\UserController@getModalContent')->name('module.frontend.user.modal_content');

    Route::get('/trainingprocess', 'Frontend\UserController@index')->name('module.frontend.user.trainingprocess');

    Route::get('/trainingprocess/getData', 'Frontend\UserController@getDataTrainingProcess')->name('module.frontend.user.trainingprocess.getData');

    Route::get('/quizresult', 'Frontend\UserController@index')->name('module.frontend.user.quizresult');

    Route::get('/quizresult/getData', 'Frontend\UserController@getDataQuizResult')->name('module.frontend.user.quizresult.getData');

    Route::get('/info', 'Frontend\UserController@index')->name('module.frontend.user.info');

    Route::get('/referer', 'Frontend\UserController@index')->name('frontend.user.referer');

    Route::get('/referer-hist', 'Frontend\UserController@getRefererHist')->name('frontend.user.referer.getRefererHist');

    Route::post('/referer/save', 'Frontend\UserController@saveReferer')->name('frontend.user.referer.save');

    Route::post('/referer/modal', 'Frontend\UserController@showModalReferer')->name('frontend.user.referer.show_modal');

    Route::get('/point-hist', 'Frontend\UserController@index')->name('module.frontend.user.point_hist');

    Route::get('/point-hist/getdata', 'Frontend\UserController@getDataPointHist')->name('frontend.user.point_hist.getData');

    Route::post('/change-avatar', 'Frontend\UserController@changeAvatar')->name('module.frontend.user.change_avatar');

    Route::get('/trainingprocess/certificate/{course_id}/{course_type}/{user_id}', 'Frontend\UserController@certificate')
        ->name('module.frontend.user.trainingprocess.certificate')
        ->where(['user_id'=>'[0-9]+']);

    Route::post('/change-pass', 'Frontend\UserController@changePass')->name('module.frontend.user.change_pass');

    Route::post('/change-user-info', 'Frontend\UserController@changeUserInfo')->name('module.frontend.user.change_info');
    /*** plan suggest*/
    Route::get('/plan-suggest', 'Frontend\UserController@showPlanSuggest')->name('module.frontend.user.plan_suggest');

    Route::get('/plan-suggest/getData', 'Frontend\UserController@getDataPlanSuggest')->name('module.frontend.user.plan_suggest.getData');

    Route::get('/plan-suggest/create', 'Frontend\UserController@createFormPlanSuggest')->name('module.frontend.user.plan_suggest.form.create');

    Route::post('/plan-suggest/save', 'Frontend\UserController@savePlanSuggest')->name('module.frontend.user.plan_suggest.save');

    Route::get('/plan-suggest/edit', 'Frontend\UserController@createFormPlanSuggest')->name('module.frontend.user.plan_suggest.form.edit');

    /*Route::get('/my-course/{type}', 'Frontend\UserController@getMyCourse')->name('module.frontend.user.my_course');

    Route::get('/my-course/getdata', 'Frontend\UserController@getData')->name('module.frontend.user.my_course.getdata');*/

    Route::get('/my-promotion', 'Frontend\UserController@index')->name('module.frontend.user.my_promotion');

    Route::get('/my-promotion/getData','Frontend\UserController@getPromotionHistory')->name('module.frontend.user.my_promotion.history');

    Route::get('/my-capabilities', 'Frontend\UserController@myCapabilities')->name('module.frontend.user.my_capabilities');

    Route::get('/my-career-roadmap', 'Frontend\UserController@index')->name('module.frontend.user.my_career_roadmap');
    Route::post('/show-modal-roadmap/{subject}', 'Frontend\UserController@showModalRoadmap')->name('module.frontend.user.show_modal_roadmap')->where('subject','[0-9]+');
    Route::get('/get-course-subject/{subject}', 'Frontend\UserController@getCourseBySubject')->name('module.frontend.user.roadmap.getCourseBySubject')->where('subject','[0-9]+');
    Route::put('/registerRoadmap', 'Frontend\UserController@registerRoadmap')->name('module.frontend.user.roadmap.register');
    Route::group(['prefix'=>'/user/','middleware' => 'auth','namespace'=>'frontend'], function() {
        Route::resource('subjectregister','SubjectRegisterController',['as'=>'frontend']);
    });
    Route::get('/subjectregister', 'Frontend\UserController@index')->name('module.frontend.user.subjectregister');
    Route::get('subjectregister/getData','Frontend\UserController@getSubjectRegister')->name('module.frontend.user.subjectregister.getData');
    Route::put('subjectregister/update/','Frontend\UserController@updateSubjectRegister')->name('module.frontend.user.subjectregister.update');

    Route::get('/training-by-title', 'Frontend\UserController@index')->name('module.frontend.user.training_by_title');

    Route::get('/training-by-title/getData', 'Frontend\UserController@getDataTrainingByTitle')->name('module.frontend.user.training_by_title.getDataTrainingByTitle');

    Route::get('/training-by-title/tree/getChild', 'Frontend\UserController@getChildTrainingByTitleCategory')->name('module.frontend.user.training_by_title.tree_folder.get_child');

    Route::get('/violate-rules', 'Frontend\UserController@index')->name('module.frontend.user.violate_rules');
    Route::get('/violate-rules/getData', 'Frontend\UserController@violateGetData')->name('module.frontend.user.violate_rules.get_data');

    // CHI PHÍ HỌC VIÊN
    Route::get('/student-cost', 'Frontend\UserController@index')->name('module.frontend.user.student_cost');
});
//Back end
Route::group(['prefix' => '/admin-cp/user', 'middleware' => 'auth'], function() {

    /* User */
    Route::get('/', 'Backend\UserController@index')->name('module.backend.user');

    Route::get('/getdata', 'Backend\UserController@getData')->name('module.backend.user.getdata');

    Route::get('/edit/{id}', 'Backend\UserController@form')->name('module.backend.user.edit')->where('id', '[0-9]+')/*->middleware('permission:user-edit')*/;

    Route::get('/create', 'Backend\UserController@form')->name('module.backend.user.create')->middleware('permission:user-create');

    Route::post('/save', 'Backend\UserController@save')->name('module.backend.user.save');

    Route::post('/remove', 'Backend\UserController@remove')->name('module.backend.user.remove');

    Route::post('/import-user', 'Backend\UserController@importUser')->name('module.backend.user.import_user');

    Route::post('/import-working-process', 'Backend\UserController@importWorkingProcess')->name('module.backend.user.import_working_process');

    Route::post('/import-training-program-learned', 'Backend\UserController@importTrainingProgramLearned')->name('module.backend.user.import_training_program_learned');

    Route::get('/export-user', 'Backend\UserController@exportUser')->name('module.backend.user.export_user');

    Route::post('/get-unit-by-user/{user_id}', 'Backend\UserController@getUnitByUser')
        ->name('module.backend.user.get_unit')
        ->where('user_id', '[0-9]+');

    Route::post('/get-area-by-user/{user_id}', 'Backend\UserController@getAreaByUser')
        ->name('module.backend.user.get_area')
        ->where('user_id', '[0-9]+');

    /*****training process *********/
    Route::get('/trainingprocess/{user_id}', 'Backend\UserController@showTrainingProcess')
        ->name('module.backend.user.trainingprocess')
        ->where(['user_id'=>'[0-9]+']);

    Route::get('/trainingprocess/getData/{user_id}', 'Backend\UserController@getDataTrainingProcess')
        ->name('module.backend.user.trainingprocess.getdata')
        ->where(['user_id'=>'[0-9]+']);

    Route::get('/trainingprocess/certificate/{course_id}/{course_type}/{user_id}', 'Backend\UserController@certificate')
        ->name('module.backend.user.trainingprocess.certificate')
        ->where(['user_id'=>'[0-9]+']);

    /********quiz result *************/
    Route::get('/quizresult/{user_id}', 'Backend\UserController@showQuizResult')
        ->name('module.backend.user.quizresult')
        ->where(['user_id'=>'[0-9]+']);

    Route::get('/quizresult/getData/{user_id}', 'Backend\UserController@getDataQuizResult')
        ->name('module.backend.user.quizresult.getdata')
        ->where(['user_id'=>'[0-9]+']);

    /********road map *************/
    Route::get('/roadmap/{user_id}', 'Backend\UserController@showRoadmap')
        ->name('module.backend.user.roadmap')
        ->where(['user_id'=>'[0-9]+']);

    Route::get('/roadmap/getData/{user_id}', 'Backend\UserController@getDataRoadmap')
        ->name('module.backend.user.roadmap.getdata')
        ->where(['user_id'=>'[0-9]+']);

    /******approve info change********/
    Route::get('/user-info-change', 'Backend\UserController@infoChange')->name('module.backend.user.approve_info');

    Route::get('/approve-info/getdata', 'Backend\UserController@getDataHistoryChange')->name('module.backend.user.getdata_history_change_info');

    Route::post('/approve-info-change', 'Backend\UserController@approveUserInfo')->name('module.backend.user.approve_info_change');

    /********training by title *************/
    Route::get('/training-by-title/{user_id}', 'Backend\UserController@showTrainingByTitle')
        ->name('module.backend.user.training_by_title')
        ->where(['user_id'=>'[0-9]+']);

    Route::get('/training-by-title/getData/{user_id}', 'Backend\UserController@getDataTrainingByTitle')
        ->name('module.backend.user.training_by_title.getdata')
        ->where(['user_id'=>'[0-9]+']);
});

Route::group(['prefix' => '/admin-cp/user/{user_id}', 'middleware' => 'auth'], function() {
    Route::get('/working-process', 'Backend\WorkingProcessController@index')
        ->name('module.backend.working_process')
        ->where('user_id', '[0-9]+');

    Route::get('/working-process/getdata', 'Backend\WorkingProcessController@getData')
        ->name('module.backend.working_process.getdata')
        ->where('user_id', '[0-9]+');

    Route::get('/working-process/create', 'Backend\WorkingProcessController@form')
        ->name('module.backend.working_process.create')
        ->where('user_id', '[0-9]+');

    Route::get('/working-process/edit/{id}', 'Backend\WorkingProcessController@form')
        ->name('module.backend.working_process.edit')
        ->where('id', '[0-9]+')
        ->where('user_id', '[0-9]+');

    Route::post('/working-process/save', 'Backend\WorkingProcessController@save')
        ->name('module.backend.working_process.save')
        ->where('user_id', '[0-9]+');

    Route::post('/working-process/remove', 'Backend\WorkingProcessController@remove')
        ->name('module.backend.working_process.remove')
        ->where('user_id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/user/{user_id}', 'middleware' => 'auth'], function() {
    Route::get('/training-program-learned', 'Backend\TrainingProgramLearnedController@index')
        ->name('module.backend.training_program_learned')
        ->where('user_id', '[0-9]+');

    Route::get('/training-program-learned/getdata', 'Backend\TrainingProgramLearnedController@getData')
        ->name('module.backend.training_program_learned.getdata')
        ->where('user_id', '[0-9]+');

    Route::get('/training-program-learned/create', 'Backend\TrainingProgramLearnedController@form')
        ->name('module.backend.training_program_learned.create')
        ->where('user_id', '[0-9]+');

    Route::get('/training-program-learned/edit/{id}', 'Backend\TrainingProgramLearnedController@form')
        ->name('module.backend.training_program_learned.edit')
        ->where('id', '[0-9]+')
        ->where('user_id', '[0-9]+');

    Route::post('/training-program-learned/save', 'Backend\TrainingProgramLearnedController@save')
        ->name('module.backend.training_program_learned.save')
        ->where('user_id', '[0-9]+');

    Route::post('/training-program-learned/remove', 'Backend\TrainingProgramLearnedController@remove')
        ->name('module.backend.training_program_learned.remove')
        ->where('user_id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/manager-level', 'middleware' => 'auth'], function() {
    /* User Manager level */
    Route::get('/', 'Backend\ManagerController@index')->name('module.backend.manager_level');

    Route::get('/getdata', 'Backend\ManagerController@getData')->name('module.backend.manager_level.getdata');

    Route::get('/edit/{id}', 'Backend\ManagerController@form')->name('module.backend.manager_level.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\ManagerController@save')->name('module.backend.manager_level.save');

    Route::post('/remove', 'Backend\ManagerController@remove')->name('module.backend.manager_level.remove');

    Route::post('/approve', 'Backend\ManagerController@approve')->name('module.backend.manager_level.approve');

    Route::post('/status', 'Backend\ManagerController@status')->name('module.backend.manager_level.status');

    Route::post('/change-manager', 'Backend\ManagerController@changeManager')->name('module.backend.manager_level.change_manager');

    Route::post('/change-level', 'Backend\ManagerController@changeLevel')->name('module.backend.manager_level.change_level');

    Route::post('/change-start-date', 'Backend\ManagerController@changeStartDate')->name('module.backend.manager_level.change_start_date');

    Route::post('/change-end-date', 'Backend\ManagerController@changeEndDate')->name('module.backend.manager_level.change_end_date');

    Route::post('/get-manager-by-user/{user_id}', 'Backend\ManagerController@getManagerByUser')
        ->name('module.backend.manager_level.get_manager')
        ->where('user_id', '[0-9]+');
});

/*Route::group(['prefix' => '/dashboard', 'middleware' => 'auth'], function() {
    Route::get('/', 'Frontend\DashBoardController@index')
        ->name('module.user.dashboard');
});*/

Route::group(['prefix' => '/admin-cp/user-take-leave', 'middleware' => 'auth'], function() {

    /* User take leave */
    Route::get('/', 'Backend\UserTakeLeaveController@index')->name('module.backend.user_take_leave');

    Route::get('/getdata', 'Backend\UserTakeLeaveController@getData')->name('module.backend.user_take_leave.getdata');

    Route::get('/edit/{id}', 'Backend\UserTakeLeaveController@form')->name('module.backend.user_take_leave.edit')->where('id', '[0-9]+');

    Route::get('/create', 'Backend\UserTakeLeaveController@form')->name('module.backend.user_take_leave.create');

    Route::post('/save', 'Backend\UserTakeLeaveController@save')->name('module.backend.user_take_leave.save');

    Route::post('/remove', 'Backend\UserTakeLeaveController@remove')->name('module.backend.user_take_leave.remove');

    Route::post('/import', 'Backend\UserTakeLeaveController@import')->name('module.backend.user_take_leave.import');

    Route::get('/export', 'Backend\UserTakeLeaveController@export')->name('module.backend.user_take_leave.export');
});
