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

Route::prefix('trainingunit')->group(function() {
    //Route::get('/', 'TrainingUnitController@index');
});

Route::group(['prefix' => '/admin-cp/training-unit', 'middleware' => 'auth'], function() {
    Route::get('/', 'BackendController@index')->name('module.training_unit');
});

Route::group(['prefix' => '/admin-cp/training-unit/online', 'middleware' => 'auth'], function() {
    Route::get('/', 'OnlineController@index')->name('module.training_unit.online');

    Route::get('/getdata', 'OnlineController@getData')->name('module.training_unit.online.getdata');

    Route::get('/edit/{id}', 'OnlineController@form')
        ->name('module.training_unit.online.edit')
        ->where('id', '[0-9]+');

    Route::get('/create', 'OnlineController@form')->name('module.training_unit.online.create');

    Route::post('/approve', 'OnlineController@approve')->name('module.training_unit.online.approve');

    Route::post('/remove', 'OnlineController@remove')->name('module.training_unit.online.remove');

    Route::post('/ajax-isopen-publish', 'OnlineController@ajaxIsopenPublish')
        ->name('module.training_unit.online.ajax_isopen_publish');

    Route::get('/register/{id}', 'OnlineController@register')
        ->name('module.training_unit.online.register')
        ->where('id', '[0-9]+');

    Route::get('/register/{id}/create', 'OnlineController@registerForm')
        ->name('module.training_unit.online.register.create')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/training-unit/offline', 'middleware' => 'auth'], function() {
    Route::get('/', 'OfflineController@index')->name('module.training_unit.offline');

    Route::get('/getdata', 'OfflineController@getData')->name('module.training_unit.offline.getdata');

    Route::get('/edit/{id}', 'OfflineController@form')
        ->name('module.training_unit.offline.edit')
        ->where('id', '[0-9]+');

    Route::get('/create', 'OfflineController@form')->name('module.training_unit.offline.create');

    Route::post('/approve', 'OfflineController@approve')->name('module.training_unit.offline.approve');

    Route::post('/remove', 'OfflineController@remove')->name('module.training_unit.offline.remove');

    Route::post('/ajax-isopen-publish', 'OfflineController@ajaxIsopenPublish')
        ->name('module.training_unit.offline.ajax_isopen_publish');

    Route::get('/register/{id}', 'OfflineController@register')
        ->name('module.training_unit.offline.register')
        ->where('id', '[0-9]+');

    Route::get('/register/{id}/create', 'OfflineController@registerForm')
        ->name('module.training_unit.offline.register.create')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/teacher', 'OfflineController@teacher')
        ->name('module.training_unit.offline.teacher')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/attendance', 'OfflineController@attendance')
        ->name('module.training_unit.offline.attendance')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/result', 'OfflineController@result')
        ->name('module.training_unit.offline.result')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/training-unit/quiz', 'middleware' => 'auth'], function() {
    Route::get('/', 'QuizController@index')->name('module.training_unit.quiz');

    Route::get('/getdata', 'QuizController@getData')->name('module.training_unit.quiz.getdata');

    Route::get('/create', 'QuizController@form')->name('module.training_unit.quiz.create');

    Route::get('/edit/{id}', 'QuizController@form')->name('module.training_unit.quiz.edit')
        ->where('id', '[0-9]+');

    Route::post('/remove', 'QuizController@remove')->name('module.training_unit.quiz.remove');

    Route::get('/{id}/add-question', 'QuizController@addQuestion')
        ->name('module.training_unit.quiz.question')
        ->where('id', '[0-9]+');

    Route::get('/register/{id}', 'QuizController@register')
        ->name('module.training_unit.quiz.register')
        ->where('id', '[0-9]+');

    Route::get('/register/{id}/create', 'QuizController@registerForm')
        ->name('module.training_unit.quiz.register.create')
        ->where('id', '[0-9]+');

    Route::get('/register-user-secondary/{id}', 'QuizController@userSecondary')
        ->name('module.training_unit.quiz.register.user_secondary')
        ->where('id', '[0-9]+');

    Route::get('/register-user-secondary/{id}/create', 'QuizController@userSecondaryForm')
        ->name('module.training_unit.quiz.register.user_secondary.create')
        ->where('id', '[0-9]+');

    Route::get('/result/{id}', 'QuizController@result')
        ->name('module.training_unit.quiz.result')
        ->where('id', '[0-9]+');

    Route::get('/export_quiz/{id}', 'QuizController@exportQuiz')
        ->name('module.training_unit.quiz.export_quiz')
        ->where('id', '[0-9]+');

    Route::post('/ajax-is-open', 'QuizController@saveIsOpen')->name('module.training_unit.quiz.ajax_is_open');

    Route::post('/ajax-status', 'QuizController@saveStatus')->name('module.training_unit.quiz.ajax_status');

    Route::post('/ajax-view-result', 'QuizController@saveViewResult')->name('module.training_unit.quiz.ajax_view_result');

    Route::post('/ajax-copy-quiz', 'QuizController@copyQuiz')->name('module.training_unit.quiz.ajax_copy_quiz');
});

Route::group(['prefix' => '/admin-cp/training-unit/proposed-question', 'middleware' => 'auth'], function() {
    Route::get('/', 'ProposedQuestionController@index')->name('module.training_unit.questionlib');

    Route::get('/getdata-category', 'ProposedQuestionController@getDataCategory')->name('module.training_unit.questionlib.getdata_category');

    Route::post('/save-category', 'ProposedQuestionController@saveCategory')->name('module.training_unit.questionlib.save_category');

    Route::post('/remove-category', 'ProposedQuestionController@removeCategory')->name('module.training_unit.questionlib.remove_category');

    Route::post('/get-modal', 'ProposedQuestionController@showModal')->name('module.training_unit.questionlib.get_modal');
});

Route::group(['prefix' => '/admin-cp/training-unit/question-lib/{id}', 'middleware' => 'auth'], function() {
    Route::get('/', 'ProposedQuestionController@question')
        ->name('module.training_unit.questionlib.question')
        ->where('id', '[0-9]+');

    Route::get('/getdata-question', 'ProposedQuestionController@getDataQuestion')
        ->name('module.training_unit.questionlib.question.getdata')
        ->where('id', '[0-9]+');

    Route::get('/create', 'ProposedQuestionController@questionForm')
        ->name('module.training_unit.questionlib.question.create')
        ->where('id', '[0-9]+');

    Route::get('/edit/{qid}', 'ProposedQuestionController@questionForm')
        ->name('module.training_unit.questionlib.question.edit')
        ->where('id', '[0-9]+')
        ->where('qid', '[0-9]+');

    Route::post('/save-question', 'ProposedQuestionController@saveQuestion')
        ->name('module.training_unit.questionlib.save_question')
        ->where('id', '[0-9]+');

    Route::post('/remove-question', 'ProposedQuestionController@removeQuestion')
        ->name('module.training_unit.questionlib.remove_question')
        ->where('id', '[0-9]+');

    Route::post('/remove-question-answer', 'ProposedQuestionController@removeQuestionAnswer')
        ->name('module.training_unit.questionlib.remove_question_answer')
        ->where('id', '[0-9]+');

    Route::post('/ajax-status', 'ProposedQuestionController@saveStatus')
        ->name('module.training_unit.questionlib.ajax_status')
        ->where('id', '[0-9]+');

    Route::post('/import-question', 'ProposedQuestionController@importQuestion')
        ->name('module.training_unit.questionlib.import_question')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/training-unit/approve-course', 'middleware' => 'auth'], function() {
    Route::get('/', 'ApproveCourseController@index')->name('module.training_unit.approve_course');

    Route::get('/getdata', 'ApproveCourseController@getData')->name('module.training_unit.approve_course.getdata');

    Route::get('/course/{id}/{type}', 'ApproveCourseController@course')
        ->name('module.training_unit.approve_course.course')
        ->where('id', '[0-9]+')
        ->where('type', '[0-9]+');

    Route::get('/course/{id}/{type}/getdata', 'ApproveCourseController@getDataRegister')
        ->name('module.training_unit.approve_course.course.getdata')
        ->where('id', '[0-9]+')
        ->where('type', '[0-9]+');

    Route::post('/course/{id}/{type}', 'ApproveCourseController@approveCourse')
        ->name('module.training_unit.approve_course.course.approve')
        ->where('id', '[0-9]+')
        ->where('type', '[0-9]+');

});

Route::group(['prefix' => '/admin-cp/training-unit/user', 'middleware' => 'auth'], function() {
    Route::get('/', 'ResultController@index')->name('module.training_unit.result');
    Route::get('/getuser', 'ResultController@getUser')->name('module.training_unit.result.getuser');
    Route::get('/{user_id}/result', 'ResultController@getResult')->name('module.training_unit.result.user')->where('user_id', '[0-9]+');
    Route::get('/{user_id}/export-result', 'ResultController@exportResult')->name('module.training_unit.result.user.export_result')->where('user_id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/training-unit/approve-student-cost', 'middleware' => 'auth'], function() {
    Route::get('/', 'ApproveStudentCostController@index')->name('module.training_unit.approve_student_cost');

    Route::get('/getdata', 'ApproveStudentCostController@getData')->name('module.training_unit.approve_student_cost.getdata');

    Route::get('/course/{id}', 'ApproveStudentCostController@course')
        ->name('module.training_unit.approve_student_cost.course')
        ->where('id', '[0-9]+');

    Route::get('/course/{id}/getdata', 'ApproveStudentCostController@getDataRegister')
        ->name('module.training_unit.approve_student_cost.getdata_register')
        ->where('id', '[0-9]+');

    Route::post('/course/{id}', 'ApproveStudentCostController@approved')
        ->name('module.training_unit.approve_student_cost.approve')
        ->where('id', '[0-9]+');

    Route::post('/modal/{id}', 'ApproveStudentCostController@getModalStudentCost')
        ->name('module.training_unit.approve_student_cost.modal')
        ->where('id', '[0-9]+');

});
