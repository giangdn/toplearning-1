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

Route::group(['prefix' => '/offline', 'middleware' => 'auth'], function() {
    Route::get('/', 'FrontendController@index')
        ->name('module.offline');

    Route::get('/detail/{id}', 'FrontendController@detail')
        ->name('module.offline.detail')
        ->where('id', '[0-9]+');

    Route::post('/rating/{id}', 'FrontendController@rating')
        ->name('module.offline.rating')
        ->where('id', '[0-9]+');

    Route::post('/register-course/{id}', 'FrontendController@registerCourse')
        ->name('module.offline.register_course')
        ->where('id', '[0-9]+');

    Route::post('/commnent/{id}', 'FrontendController@comment')
        ->name('module.offline.comment')
        ->where('id', '[0-9]+');

    Route::get('/view-pdf/{id}/{key}', 'FrontendController@viewPDF')->name('module.offline.view_pdf')->where('id', '[0-9]+');

    Route::get('/search','FrontendController@search')->name('module.offline.search');

    Route::post('/referer/qrcode/{id}', 'FrontendController@showModalQrcodeReferer')
        ->name('frontend.offline.referer.show_modal')
        ->where('id', '[0-9]+');

    Route::post('/share-course/{id}/{type}', 'FrontendController@shareCourse')
        ->name('module.offline.detail.share_course')
        ->where('id', '[0-9]+')->where('type', '[0-9]+');

    Route::get('/getdata-rating-level/{id}', 'FrontendController@getDataRatingLevel')
        ->name('module.offline.detail.rating_level.getdata')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => '/student-cost', 'middleware' => 'auth'], function() {
    Route::get('/','FrontendController@studentCost')->name('module.offline.student_cost');

    Route::get('/getdata', 'FrontendController@getDataCourse')->name('module.offline.student_cost.getdata');

    Route::post('/save', 'FrontendController@saveStudentCost')->name('module.offline.student_cost.save');

    Route::post('/modal', 'FrontendController@getModalStudentCost')->name('module.offline.student_cost.modal');
});

/* backend */
Route::group(['prefix' => '/admin-cp/offline', 'middleware' => 'auth'], function() {
    Route::get('/', 'BackendController@index')->name('module.offline.management')->middleware('permission:offline-course');

    Route::get('/getdata', 'BackendController@getData')->name('module.offline.getdata')->middleware('permission:offline-course');

    Route::get('/edit/{id}', 'BackendController@form')->name('module.offline.edit')->where('id', '[0-9]+')->middleware('permission:offline-course-edit');

    Route::get('/create', 'BackendController@form')->name('module.offline.create')->middleware('permission:offline-course-create');

    Route::post('/save', 'BackendController@save')->name('module.offline.save')->middleware('permission:offline-course-edit|offline-course-create');

    Route::post('/remove', 'BackendController@remove')->name('module.offline.remove')->middleware('permission:offline-course-delete');

    Route::post('/approve', 'BackendController@approve')->name('module.offline.approve')->middleware('permission:offline-course-approve');

    Route::post('/send-mail-approve', 'BackendController@sendMailApprove')->name('module.offline.send_mail_approve');

    Route::post('/send-mail-change', 'BackendController@sendMailChange')->name('module.offline.send_mail_change');

    Route::post('/ajax-get-course-code', 'BackendController@ajaxGetCourseCode')->name('module.offline.ajax_get_course_code');

    Route::post('/ajax-get-subject', 'BackendController@ajaxGetSubject')->name('module.offline.ajax_get_subject');

    Route::post('/ajax-isopen-publish', 'BackendController@ajaxIsopenPublish')->name('module.offline.ajax_isopen_publish');

    Route::get('/filter-location','BackendController@filterLocation')->name('module.offline.filter.location');

    Route::get('/filter-traininglocation','BackendController@filterTrainingLocation')->name('module.offline.filter.traininglocation');
    //history
    Route::get('/history/getdata/{id}', 'BackendController@getDataHistory')->name('module.offline.history.getdata')->where('id', '[0-9]+');
    //upload file
    Route::post('/uploadfile', 'BackendController@uploadfile')->name('module.offline.uploadfile');
    //Thư viện file
    Route::get('/get-data-library-file/{course_id}', 'BackendController@getDataLibraryFile')->name('module.offline.get_data_library_file');
    Route::post('/library-file-remove', 'BackendController@removeLibraryFile')->name('module.offline.library_file_remove');

    Route::post('/lock', 'BackendController@lockCourse')->name('module.offline.lock');

    // SAO CHÉP KHÓA HỌC
    Route::post('/copy', 'BackendController@copy')->name('module.offline.copy');

    // Đánh GIÁ KHÓA HỌC
    Route::post('/save-ratting-course/{course_id}', 'BackendController@saveRattingCourse')->where('course_id', '[0-9]+')->name('module.offline.save_ratting_course');
});

Route::group(['prefix' => '/admin-cp/offline/edit/{id}', 'middleware' => 'auth'], function() {
    Route::post('/save-object', 'BackendController@saveObject')->name('module.offline.save_object')->where('id', '[0-9]+')->middleware('permission:offline-course-create|offline-course-edit');

    Route::get('/get-object', 'BackendController@getObject')->name('module.offline.get_object')->where('id', '[0-9]+')->middleware('permission:offline-course');

    Route::post('/remove-object', 'BackendController@removeObject')->name('module.offline.remove_object')->where('id', '[0-9]+')->middleware('permission:offline-course-delete');

    Route::post('/save-cost', 'BackendController@saveCost')->name('module.offline.save_cost')->where('id', '[0-9]+')->middleware('permission:offline-course-create|offline-course-edit');

    Route::post('/save-commit-date', 'BackendController@saveCommitDate')->name('module.offline.save_commit_date')->where('id', '[0-9]+')->middleware('permission:offline-course-create|offline-course-edit');

    Route::post('/save-student-cost', 'BackendController@saveStudentCost')->name('module.offline.save_student_cost')->where('id', '[0-9]+')->middleware('permission:offline-course-create|offline-course-edit');

    Route::post('/modal-student-cost', 'BackendController@getModalStudentCost')->name('module.offline.modal_student_cost')->where('id', '[0-9]+');

    Route::post('/save-condition', 'BackendController@saveCondition')->name('module.offline.save_condition')->where('id', '[0-9]+')->middleware('permission:offline-course-create|offline-course-edit');

    Route::post('/save-schedule-parent', 'BackendController@saveScheduleParent')->name('module.offline.save_schedule_parent')->where('id', '[0-9]+')->middleware('permission:offline-course-create|offline-course-edit');

    Route::get('/get-schedule-parent', 'BackendController@getScheduleParent')->name('module.offline.get_schedule_parent')->where('id', '[0-9]+')->middleware('permission:offline-course');

    Route::post('/remove-schedule-parent', 'BackendController@removeScheduleParent')->name('module.offline.remove_schedule_parent')->where('id', '[0-9]+')->middleware('permission:offline-course-delete');

    Route::post('/modal-schedule', 'BackendController@getModalSchedule')->name('module.offline.modal_schedule')->where('id', '[0-9]+');

    Route::post('/save-schedule', 'BackendController@saveSchedule')->name('module.offline.save_schedule')->where('id', '[0-9]+')->middleware('permission:offline-course-create|offline-course-edit');

    Route::get('/get-schedule', 'BackendController@getSchedule')->name('module.offline.get_schedule')->where('id', '[0-9]+')->middleware('permission:offline-course');

    Route::post('/remove-schedule', 'BackendController@removeSchedule')->name('module.offline.remove_schedule')->where('id', '[0-9]+')->middleware('permission:offline-course-delete');

    Route::post('/check-unit-child', 'BackendController@getChild')->name('module.offline.get_child')->where('id', '[0-9]+');

    Route::get('/get-tree-child', 'BackendController@getTreeChild')
        ->name('module.offline.get_tree_child')
        ->where('id', '[0-9]+');

    Route::get('/teacher', 'TeacherController@index')
        ->name('module.offline.teacher')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-teacher');

    Route::post('/save-teacher', 'TeacherController@save')
        ->name('module.offline.save_teacher')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-teacher-create');

    Route::get('/get-teacher', 'TeacherController@getData')
        ->name('module.offline.get_teacher')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-teacher');

    Route::post('/remove-teacher', 'TeacherController@remove')
        ->name('module.offline.remove_teacher')
        ->where('id', '[0-9]+');

    Route::post('/teacher-save-note', 'TeacherController@saveNote')
        ->name('module.offline.teacher.save_note')
        ->where('id', '[0-9]+');

    Route::get('/attendance', 'AttendanceController@index')
        ->name('module.offline.attendance')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-attendance');

    Route::post('/save-all-attendance', 'AttendanceController@saveAll')
        ->name('module.offline.save_all_attendance')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-attendance');

    Route::post('/save-attendance', 'AttendanceController@save')
        ->name('module.offline.save_attendance')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-attendance');

    Route::get('/get-attendance', 'AttendanceController@getData')
        ->name('module.offline.get_attendance')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-attendance');

    Route::post('/remove-attendance', 'AttendanceController@remove')
        ->name('module.offline.remove_attendance')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-attendance');

    Route::post('/save-percent', 'AttendanceController@savePercent')
        ->name('module.offline.save_percent')
        ->where('id', '[0-9]+');

    Route::post('/attendance-save-note', 'AttendanceController@saveNote')
        ->name('module.offline.attendance.save_note')
        ->where('id', '[0-9]+');

    Route::post('/modal-reference', 'AttendanceController@getModalReference')
        ->name('module.offline.modal_reference')
        ->where('id', '[0-9]+');

    Route::post('/save-reference', 'AttendanceController@saveReference')
        ->name('module.offline.save_reference')
        ->where('id', '[0-9]+');

    Route::get('/result', 'ResultController@index')
        ->name('module.offline.result')
        ->where('id', '[0-9]+');

    Route::post('/save-score', 'ResultController@saveScore')
        ->name('module.offline.save_score')
        ->where('id', '[0-9]+');

    Route::get('/get-result', 'ResultController@getData')
        ->name('module.offline.get_result')
        ->where('id', '[0-9]+');

    Route::post('/result-save-note', 'ResultController@saveNote')
        ->name('module.offline.result.save_note')
        ->where('id', '[0-9]+');

    Route::post('/import-result', 'ResultController@importResult')
        ->name('module.offline.result.import_result')
        ->where('id', '[0-9]+');

    Route::get('/export-result', 'ResultController@exportResult')
        ->name('module.offline.result.export_result')
        ->where('id', '[0-9]+');

    Route::post('/save-absent', 'AttendanceController@saveAbsent')
    ->name('module.offline.save_absent')
    ->where('id', '[0-9]+');
    Route::post('/save-absent-reason', 'AttendanceController@saveAbsentReason')
    ->name('module.offline.save_absent_reason')
    ->where('id', '[0-9]+');
    Route::post('/save-discipline', 'AttendanceController@saveDiscipline')
    ->name('module.offline.save_discipline')
    ->where('id', '[0-9]+');

    Route::get('/attendance/export/{schedule}', 'AttendanceController@export')
        ->name('module.offline.attendance.export')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-attendance');

    Route::post('/attendance/import', 'AttendanceController@import')
        ->name('module.offline.attendance.import')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-attendance');

    //Cán bộ theo dõi
    Route::get('/monitoring-staff', 'MonitoringStaffController@index')
        ->name('module.offline.monitoring_staff')
        ->where('id', '[0-9]+');

    Route::post('/save-monitoring-staff', 'MonitoringStaffController@save')
        ->name('module.offline.save_monitoring_staff')
        ->where('id', '[0-9]+');

    Route::get('/get-monitoring-staff', 'MonitoringStaffController@getData')
        ->name('module.offline.get_monitoring_staff')
        ->where('id', '[0-9]+');

    Route::post('/remove-monitoring-staff', 'MonitoringStaffController@remove')
        ->name('module.offline.remove_monitoring_staff')
        ->where('id', '[0-9]+');

    Route::post('/monitoring-staff-save-note', 'MonitoringStaffController@saveNote')
        ->name('module.offline.monitoring_staff.save_note')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/offline/register/{id}', 'middleware' => 'auth'], function() {
    Route::get('/', 'RegisterController@index')
        ->name('module.offline.register')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-register');

    Route::get('/getdata', 'RegisterController@getData')
        ->name('module.offline.register.getdata')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-register');

    Route::get('/getDataNotRegister', 'RegisterController@getDataNotRegister')
        ->name('module.offline.register.getDataNotRegister')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-register');

    Route::get('/create', 'RegisterController@form')
        ->name('module.offline.register.create')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-register-create');

    Route::post('/save', 'RegisterController@save')
        ->name('module.offline.register.save')
        ->where('id', '[0-9]+')->middleware('permission:offline-course-register-create');

    Route::post('/remove', 'RegisterController@remove')
        ->name('module.offline.register.remove')
        ->where('id', '[0-9]+');

    Route::post('/import-register', 'RegisterController@importRegister')
        ->name('module.offline.register.import_register')
        ->where('id', '[0-9]+');

    Route::post('/approve', 'RegisterController@approve')
        ->name('module.offline.register.approve')
        ->where('id', '[0-9]+')
        ->middleware('permission:offline-course-register-approve');

    Route::post('/add-to-quiz', 'RegisterController@addToQuiz')
        ->name('module.offline.register.add_to_quiz')
        ->where('id', '[0-9]+');

    Route::get('/export-register', 'RegisterController@exportRegister')
        ->name('module.offline.register.export_register')
        ->where('id', '[0-9]+');

    Route::post('/invite-user-register', 'RegisterController@inviteUserRegister')->name('module.offline.register.invite_user')->where('id', '[0-9]+');

    Route::get('/invite-user-register/getdata', 'RegisterController@getDataInviteUserRegister')->name('module.offline.register.getdata.invite_user')->where('id', '[0-9]+');

    Route::post('/invite-user-register/remove', 'RegisterController@removeInviteUserRegister')->name('module.offline.register.remove.invite_user')->where('id', '[0-9]+');

    Route::post('/send-mail-user-registed', 'RegisterController@sendMailUserRegisted')->name('module.offline.register.send_mail_user_registed')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/offline/rating-level/{course_id}', 'middleware' => 'auth'], function() {
    Route::get('/get-data', 'RatingLevelController@getData')
        ->name('module.offline.rating_level.getData')
        ->where('course_id', '[0-9]+');

    Route::post('/save', 'RatingLevelController@save')
        ->name('module.offline.rating_level.save')
        ->where('course_id', '[0-9]+');

    Route::post('/remove', 'RatingLevelController@remove')
        ->name('module.offline.rating_level.remove')
        ->where('course_id', '[0-9]+');

    Route::post('/modal-add-object/{id}', 'RatingLevelController@modalAddObject')
        ->name('module.offline.rating_level.modal_add_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::get('/get-data-object/{id}', 'RatingLevelController@getDataObject')
        ->name('module.offline.rating_level.getDataObject')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/save-object/{id}', 'RatingLevelController@saveObject')
        ->name('module.offline.rating_level.save_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/ajax-get-object/{id}', 'RatingLevelController@ajaxGetObject')
        ->name('module.offline.rating_level.ajax_get_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/remove-object/{id}', 'RatingLevelController@removeObject')
        ->name('module.offline.rating_level.remove_object')
        ->where('course_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::get('/list-report', 'RatingLevelController@listReport')
        ->name('module.offline.rating_level.list_report')
        ->where('course_id', '[0-9]+');

    Route::get('/list-report/getdata', 'RatingLevelController@getdataListReport')
        ->name('module.offline.rating_level.list_report.getdata')
        ->where('course_id', '[0-9]+');

    Route::get('/list-user-rating/{course_rating_level_id}/getdata', 'RatingLevelController@getdataListUserRating')
        ->name('module.offline.rating_level.list_user_rating.getdata')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level_id', '[0-9]+');

    Route::post('/{course_rating_level}/{user_id}/{rating_user}/modal-rating-level', 'RatingLevelController@modalRatingLevel')
        ->name('module.offline.rating_level.modal_rating_level')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('rating_user', '[0-9]+');

    Route::post('/{course_rating_level}/{user_id}/{rating_user}/modal-edit-rating-level', 'RatingLevelController@modalEditRatingLevel')
        ->name('module.offline.rating_level.modal_edit_rating_level')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('rating_user', '[0-9]+');

    Route::post('/{course_rating_level}/{user_id}/{rating_user}/save-rating-level-course', 'RatingLevelController@saveRatingCourse')
        ->name('module.offline.rating_level.save_rating_course')
        ->where('course_id', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('rating_user', '[0-9]+');
});
