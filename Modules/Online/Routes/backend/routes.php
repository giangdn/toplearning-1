<?php

Route::group(['prefix' => '/admin-cp/online', 'middleware' => 'quiz.secondary'], function() {
    Route::get('/edit/{id}/get-object', 'BackendController@getObject')->name('module.online.get_object')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/online', 'middleware' => 'auth'], function() {
    Route::get('/', 'BackendController@index')->name('module.online.management')->middleware('permission:online-course');

    Route::get('/getdata', 'BackendController@getData')->name('module.online.getdata')->middleware('permission:online-course');

    Route::get('/edit/{id}', 'BackendController@form')->name('module.online.edit')->where('id', '[0-9]+')->middleware('permission:online-course-edit');

    Route::get('/create', 'BackendController@form')->name('module.online.create')->middleware('permission:online-course-create');

    Route::post('/save', 'BackendController@save')->name('module.online.save')->middleware('permission:online-course-create|online-course-edit');

    Route::post('/save/tutorial', 'BackendController@saveTutorial')->name('module.online.save_tutorial')->middleware('permission:online-course-create|online-course-edit');

    Route::post('/remove', 'BackendController@remove')->name('module.online.remove')->middleware('permission:online-course-delete');

    Route::post('/approve', 'BackendController@approve')->name('module.online.approve')->middleware('permission:online-course-approve');

    Route::post('/send-mail-approve', 'BackendController@sendMailApprove')->name('module.online.send_mail_approve');

    Route::post('/send-mail-change', 'BackendController@sendMailChange')->name('module.online.send_mail_change');

    Route::post('/ajax-get-course-code', 'BackendController@ajaxGetCourseCode')->name('module.online.ajax_get_course_code');

    Route::post('/ajax-get-subject', 'BackendController@ajaxGetSubject')->name('module.online.ajax_get_subject');

    Route::post('/ajax-isopen-publish', 'BackendController@ajaxIsopenPublish')->name('module.online.ajax_isopen_publish');

    Route::post('/edit/{id}/save-object', 'BackendController@saveObject')->name('module.online.save_object')->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-object', 'BackendController@removeObject')->name('module.online.remove_object')->where('id', '[0-9]+');

    Route::post('/edit/{id}/save-cost', 'BackendController@saveCost')->name('module.online.save_cost')->where('id', '[0-9]+');

    Route::post('/edit/{id}/modal-add-activity', 'ActivityController@modalAddActivity')->name('module.online.modal_add_activity')->where('id', '[0-9]+');

    Route::post('/edit/{id}/modal-activity', 'ActivityController@modalActivity')->name('module.online.modal_activity')->where('id', '[0-9]+');

    Route::post('/edit/{id}/save-condition', 'BackendController@saveCondition')->name('module.online.save_condition')->where('id', '[0-9]+');

    Route::get('/edit/{id}/go-add-scorm', 'BackendController@goAddScorm')->name('module.online.go_add_scorm')->where('id', '[0-9]+');

    Route::get('/goto-moodle/{id}', 'BackendController@gotoMoodleCourse')->name('module.online.goto_moodle')->where('id', '[0-9]+');

    Route::post('/edit/{id}/check-unit-child', 'BackendController@getChild')->name('module.online.get_child')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-tree-child', 'BackendController@getTreeChild')
        ->name('module.online.get_tree_child')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/save-setting-score-percent', 'BackendController@saveSettingScorePercent')->name('module.online.save_setting_score_percent')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-setting-percent', 'BackendController@getSettingPercent')->name('module.online.get_setting_percent')->where('id', '[0-9]+');

    //history
    Route::get('/history/{id}/getdata', 'BackendController@getDataHistory')->name('module.online.history.getdata')->where('id', '[0-9]+');

    //quản lý upload file
    Route::post('/uploadfile', 'BackendController@uploadfile')->name('module.online.uploadfile');

    //ẢNH ĐẠI DIỆN HOẠT ĐỘNG
    Route::post('/image-activity-save/{id}', 'BackendController@imageActivitySave')->name('module.online.image_activity.save');

    //Thư viện file
    Route::get('/get-data-library-file/{course_id}', 'BackendController@getDataLibraryFile')->name('module.online.get_data_library_file');
    Route::post('/library-file-remove', 'BackendController@removeLibraryFile')->name('module.online.library_file_remove');

    //HỌC VIÊN GHI CHÉP / ĐÁNH GIÁ
    Route::get('/get-user-note-evaluate/{course_id}', 'BackendController@getUserNoteEvaluate')->name('module.online.get_user_note_evaluate');
    Route::get('/get-content-evaluate/{id}/{course_id}', 'BackendController@getContentEvaluate')->name('module.online.get_content_evaluate');
    Route::get('/view-user-note-evaluate/{id}/{course_id}/{type}', 'BackendController@viewUserNoteEvaluate')->name('module.online.view_user_note_evaluate');
    Route::post('/content-evaluate-remove/{id}/{course_id}/', 'BackendController@removeContentEvaluate')->name('module.online.content_evaluate_remove');
    Route::get('/export/{id}/{course_id}/{user_type}', 'BackendController@export')->name('module.online.export');

    // HỌC VIÊN HỎI ĐÁP
    Route::get('/get-user-ask-answer/{course_id}', 'BackendController@getUserAskAnswer')->name('module.online.get_user_ask_answer');
    Route::post('/save-answer', 'BackendController@saveAnswer')
        ->name('module.online.save_answer')
        ->where('id', '[0-9]+');
    Route::post('/ajax-isopen-status', 'BackendController@ajaxIsopenStatus')->name('module.online.ajax_isopen_status');

    // BÀI HỌC
    Route::get('/get-lesson/{course_id}', 'BackendController@getLesson')->name('module.online.get_lesson');
    Route::post('/save-lesson/{course_id}', 'BackendController@saveLesson')
        ->name('module.online.save_lesson')
        ->where('id', '[0-9]+');
    Route::post('/remove-lesson/{course_id}', 'BackendController@removeLesson')->name('module.online.remove_lesson')->where('id', '[0-9]+');

    Route::post('/lock', 'BackendController@lockCourse')->name('module.online.lock');

    // SAO CHÉP KHÓA HỌC
    Route::post('/copy', 'BackendController@copy')->name('module.online.copy');
//    Route::get('/get-approved-step/{model_id}', 'BackendController@getApprovedStep')->name('module.online.get_approved_step')->where('model_id','[0-9]+');
//    Route::post('/modal-note-approved', 'BackendController@showModalNoteApproved')->name('module.online.modal_note_approved');

    // ĐÁNH GIÁ KHÓA HỌC
    Route::post('/save-ratting-course/{course_id}', 'BackendController@saveRattingCourse')->where('course_id', '[0-9]+')->name('module.online.save_ratting_course');
});

Route::group(['prefix' => '/admin-cp/online/activity/{id}', 'middleware' => ['auth','permission:online-course']], function() {
    Route::get('/load-data/{func}', 'ActivityController@loadData')->name('module.online.activity.loaddata')->where('id', '[0-9]+');

    Route::post('/update-numorder', 'ActivityController@updateNumOrder')->name('module.online.activity.update_numorder')->where('id', '[0-9]+');

    Route::post('/remove', 'ActivityController@remove')->name('module.online.activity.remove')->where('id', '[0-9]+');

    Route::post('/update-status-activity', 'ActivityController@updateStatusActivity')->name('module.online.activity.update_status_activity')->where('id', '[0-9]+');

    Route::post('/url-edit-scorm', 'ActivityController@getUrlEditScorm')->name('module.online.activity.url_edit_scorm')->where('id', '[0-9]+');

    Route::post('/save/{activity}', 'ActivityController@saveActivity')->name('module.online.activity.save')->where('id', '[0-9]+')->where('activity', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/online/register/{id}', 'middleware' => 'auth'], function() {
    Route::get('/', 'RegisterController@index')->name('module.online.register')->where('id', '[0-9]+')->middleware('permission:online-course-register');

    Route::get('/getdata', 'RegisterController@getData')->name('module.online.register.getdata')->middleware('permission:online-course-register');

    Route::get('/getDataNotRegister', 'RegisterController@getDataNotRegister')->name('module.online.register.getDataNotRegister')->where('id', '[0-9]+')->middleware('permission:online-course-register');

    Route::get('/create', 'RegisterController@form')->name('module.online.register.create')->where('id', '[0-9]+')->middleware('permission:online-course-register-create');

    Route::post('/save', 'RegisterController@save')->name('module.online.register.save')->where('id', '[0-9]+')->middleware('permission:online-course-register-create|online-course-register-edit');

    Route::post('/remove', 'RegisterController@remove')->name('module.online.register.remove')->where('id', '[0-9]+')->middleware('permission:online-course-register-delete');

    Route::post('/import-register', 'RegisterController@importRegister')->name('module.online.register.import_register')->where('id', '[0-9]+')->middleware('permission:online-course-register');

    Route::post('/approve', 'RegisterController@approve')->name('module.online.register.approve')->where('id', '[0-9]+')->middleware('permission:online-course-register-approve');

    Route::post('/add-to-quiz', 'RegisterController@addToQuiz')->name('module.online.register.add_to_quiz')->where('id', '[0-9]+');

    Route::post('/invite-user-register', 'RegisterController@inviteUserRegister')->name('module.online.register.invite_user')->where('id', '[0-9]+');

    Route::get('/invite-user-register/getdata', 'RegisterController@getDataInviteUserRegister')->name('module.online.register.getdata.invite_user')->where('id', '[0-9]+');

    Route::post('/invite-user-register/remove', 'RegisterController@removeInviteUserRegister')->name('module.online.register.remove.invite_user')->where('id', '[0-9]+');

    Route::post('/send-mail-user-registed', 'RegisterController@sendMailUserRegisted')->name('module.online.register.send_mail_user_registed')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/online/register-secondary/{id}', 'middleware' => 'auth'], function() {
    Route::get('/', 'RegisterSecondaryController@index')
        ->name('module.online.register_secondary')
        ->where('id', '[0-9]+')
        ->middleware('permission:online-course-register');

    Route::get('/getdata', 'RegisterSecondaryController@getData')
        ->name('module.online.register_secondary.getdata')
        ->where('id', '[0-9]+')
        ->middleware('permission:online-course-register');

    Route::get('/getDataNotRegister', 'RegisterSecondaryController@getDataNotRegister')
        ->name('module.online.register_secondary.getDataNotRegister')
        ->where('id', '[0-9]+')
        ->middleware('permission:online-course-register');

    Route::get('/create', 'RegisterSecondaryController@form')
        ->name('module.online.register_secondary.create')
        ->where('id', '[0-9]+')
        ->middleware('permission:online-course-register-create');

    Route::post('/save', 'RegisterSecondaryController@save')
        ->name('module.online.register_secondary.save')
        ->where('id', '[0-9]+')
        ->middleware('permission:online-course-register-create|online-course-register-edit');

    Route::post('/remove', 'RegisterSecondaryController@remove')
        ->name('module.online.register_secondary.remove')
        ->where('id', '[0-9]+')
        ->middleware('permission:online-course-register-delete');

    Route::post('/import-register', 'RegisterSecondaryController@importRegister')
        ->name('module.online.register_secondary.import_register')
        ->where('id', '[0-9]+')
        ->middleware('permission:online-course-register');

    Route::post('/add-to-quiz', 'RegisterSecondaryController@addToQuiz')->name('module.online.register_secondary.add_to_quiz')->where('id', '[0-9]+');

    Route::post('/send-mail-user-registed', 'RegisterSecondaryController@sendMailUserRegisted')
        ->name('module.online.register_secondary.send_mail_user_registed')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/online/edit/{id}', 'middleware' => 'auth'], function() {
    Route::get('/result', 'ResultController@index')
        ->name('module.online.result')
        ->where('id', '[0-9]+')->middleware('permission:online-course-result');

    Route::get('/get-result', 'ResultController@getData')
        ->name('module.online.get_result')
        ->where('id', '[0-9]+')->middleware('permission:online-course-result');

    Route::post('/update-activity-complete', 'ResultController@updateActivityComplete')
        ->name('module.online.result.update_activity_complete')
        ->where('id', '[0-9]+')->middleware('permission:online-course-result');

    Route::get('/export-result', 'ResultController@exportResult')->name('module.online.export_result');

    Route::get('/result/{user_id}/view_history_learning', 'ResultController@viewHistoryLearning')
        ->name('module.online.result.view_history_learning')
        ->where('id', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->middleware('permission:online-course-result');
});

Route::group(['prefix' => '/admin-cp/online/{course_id}/quiz', 'middleware' => 'auth'], function() {
    Route::get('/', 'QuizController@index')->name('module.online.quiz')->where('course_id', '[0-9]+');

    Route::get('/get-quiz', 'QuizController@getData')->name('module.online.get_quiz')->where('course_id', '[0-9]+');

    Route::get('/create', 'QuizController@form')->name('module.online.quiz.create')->where('course_id', '[0-9]+');

    Route::get('/edit/{id}', 'QuizController@form')->name('module.online.quiz.edit')->where('course_id', '[0-9]+')->where('id', '[0-9]+');

    Route::post('/save', 'QuizController@save')->name('module.online.quiz.save')->where('course_id', '[0-9]+');

    Route::post('/remove', 'QuizController@remove')->name('module.online.quiz.remove')->where('course_id', '[0-9]+');

    Route::post('/edit/{id}/save-part', 'QuizController@savePart')->name('module.online.quiz.edit.savepart')->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-part', 'QuizController@getDataPart')->name('module.online.quiz.edit.getpart')->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/add-question', 'QuizController@questionQuiz')
        ->name('module.online.quiz.question')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/online/rating-level/{course_id}', 'middleware' => 'auth'], function() {
    Route::get('/get-data', 'RatingLevelController@getData')
        ->name('module.online.rating_level.getData')
        ->where('course_id', '[0-9]+');

    Route::post('/save', 'RatingLevelController@save')
        ->name('module.online.rating_level.save')
        ->where('course_id', '[0-9]+');

    Route::post('/remove', 'RatingLevelController@remove')
        ->name('module.online.rating_level.remove')
        ->where('course_id', '[0-9]+');

    Route::post('/modal-add-object/{id}', 'RatingLevelController@modalAddObject')
        ->name('module.online.rating_level.modal_add_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::get('/get-data-object/{id}', 'RatingLevelController@getDataObject')
        ->name('module.online.rating_level.getDataObject')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/save-object/{id}', 'RatingLevelController@saveObject')
        ->name('module.online.rating_level.save_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/ajax-get-object/{id}', 'RatingLevelController@ajaxGetObject')
        ->name('module.online.rating_level.ajax_get_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/remove-object/{id}', 'RatingLevelController@removeObject')
        ->name('module.online.rating_level.remove_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::get('/list-report', 'RatingLevelController@listReport')
        ->name('module.online.rating_level.list_report')
        ->where('course_id', '[0-9]+');

    Route::get('/list-report/getdata', 'RatingLevelController@getdataListReport')
        ->name('module.online.rating_level.list_report.getdata')
        ->where('course_id', '[0-9]+');

    Route::get('/list-user-rating/{course_rating_level_id}/getdata', 'RatingLevelController@getdataListUserRating')
        ->name('module.online.rating_level.list_user_rating.getdata')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level_id', '[0-9]+');

    Route::post('/{course_rating_level}/{user_id}/{rating_user}/modal-rating-level', 'RatingLevelController@modalRatingLevel')
        ->name('module.online.rating_level.modal_rating_level')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('rating_user', '[0-9]+');

    Route::post('/{course_rating_level}/{user_id}/{rating_user}/modal-edit-rating-level', 'RatingLevelController@modalEditRatingLevel')
        ->name('module.online.rating_level.modal_edit_rating_level')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('rating_user', '[0-9]+');

    Route::post('/{course_rating_level}/{user_id}/{rating_user}/save-rating-level-course', 'RatingLevelController@saveRatingCourse')
        ->name('module.online.rating_level.save_rating_course')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('rating_user', '[0-9]+');
});
