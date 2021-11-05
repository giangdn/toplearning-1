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

Route::group(['prefix' => '/rating', 'middleware' => 'auth'], function() {
    Route::get('/', 'FrontendController@index');
    Route::get('/course/{type}/{id}', 'FrontendController@getCourse')->name('module.rating.course')->where('type', '[0-9]+')->where('id', '[0-9]+');
    Route::get('/edit-course/{type}/{id}', 'FrontendController@editCourse')->name('module.rating.edit_course')->where('type', '[0-9]+')->where('id', '[0-9]+');
    Route::post('/save-rating-course', 'FrontendController@saveRatingCourse')->name('module.rating.save_rating_course');
});

Route::group(['prefix' => '/rating-level', 'middleware' => 'auth'], function() {
    Route::get('/', 'RatingLevelController@index')->name('module.rating_level');

    Route::get('/getdata', 'RatingLevelController@getData')
        ->name('module.rating_level.getdata');

    Route::match(['get', 'post'], '/modal-add-object-colleague/{course_id}/{course_type}/{course_rating_level}/{rating_user}', 'RatingLevelController@modalAddObjectColleague')
        ->name('module.rating_level.modal_add_object_colleague')
        ->where('course_id', '[0-9]+')
        ->where('course_type', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('rating_user', '[0-9]+');

    Route::post('/add-object-colleague/{course_id}/{course_type}/{course_rating_level}/{rating_user}', 'RatingLevelController@addObjectColleague')
        ->name('module.rating_level.add_object_colleague')
        ->where('course_id', '[0-9]+')
        ->where('course_type', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('rating_user', '[0-9]+');

    Route::get('/getdata-object-colleague/{course_type}/{course_rating_level}/{rating_user}', 'RatingLevelController@getDataObjectColleague')
        ->name('module.rating_level.getdata_object_colleague')
        ->where('course_type', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('rating_user', '[0-9]+');

    Route::post('/remove-object-colleague/{course_type}', 'RatingLevelController@removeObjectColleague')
        ->name('module.rating_level.remove_object_colleague')
        ->where('course_type', '[0-9]+');

    Route::get('/{course_id}/{course_type}/{course_rating_level}/{rating_user}/course', 'RatingLevelController@getCourse')
        ->name('module.rating_level.course')
        ->where('course_id', '[0-9]+')
        ->where('course_type', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('rating_user', '[0-9]+');

    Route::get('/{course_id}/{course_type}/{course_rating_level}/{rating_user}/edit-course', 'RatingLevelController@editCourse')
        ->name('module.rating_level.edit_course')
        ->where('course_id', '[0-9]+')
        ->where('course_type', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('rating_user', '[0-9]+');

    Route::post('/{course_id}/{course_type}/{course_rating_level}/{rating_user}/save-rating-level-course', 'RatingLevelController@saveRatingCourse')
        ->name('module.rating_level.save_rating_course')
        ->where('course_id', '[0-9]+')
        ->where('course_type', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('rating_user', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/evaluationform/rating', 'middleware' => 'auth'], function() {
    Route::get('/', 'BackendController@index')->name('module.rating.template');
    Route::get('/getdata', 'BackendController@getData')->name('module.rating.template.getdata');
    Route::get('/edit/{id}', 'BackendController@form')->name('module.rating.template.edit')->where('id', '[0-9]+');
    Route::get('/create', 'BackendController@form')->name('module.rating.template.create');
    Route::post('/save', 'BackendController@save')->name('module.rating.template.save');
    Route::post('/remove', 'BackendController@remove')->name('module.rating.template.remove');
    Route::post('/copy', 'BackendController@copy')->name('module.rating.template.copy');
    Route::post('/remove-category', 'BackendController@removeCategory')->name('module.rating.template.remove_category');
    Route::post('/remove-question', 'BackendController@removeQuestion')->name('module.rating.template.remove_question');
    Route::post('/remove-answer', 'BackendController@removeAnswer')->name('module.rating.template.remove_answer');
    Route::get('/export-word/{id}', 'BackendController@exportWord')->name('module.rating.template.export_word')->where('id', '[0-9]+');
    Route::get('/export-pdf/{id}', 'BackendController@exportPDF')->name('module.rating.template.export_pdf')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/course/rating', 'middleware' => 'auth'], function() {
    Route::get('/result/{course_id}/{type}', 'BackendController@result')
        ->name('module.rating.result.index')
        ->where('course_id', '[0-9]+')
        ->where('type', '[0-9]+');

    Route::get('/result/getdata/{course_id}/{type}', 'BackendController@getDataResult')
        ->name('module.rating.result.getdata')
        ->where('course_id', '[0-9]+')
        ->where('type', '[0-9]+');

    Route::get('/result/rating-detail/{course_id}/{type}/{user_id}', 'BackendController@resultDetail')
        ->name('module.rating.result.view')
        ->where('course_id', '[0-9]+')
        ->where('type', '[0-9]+')
        ->where('user_id', '[0-9]+');

    //Kết quả đánh giá cấp độ
    Route::get('/list-course-register/{course_id}/{course_type}', 'BackendController@listCourseRegister')
        ->name('module.rating_level.result.list_course_register')
        ->where('course_id', '[0-9]+')
        ->where('course_type', '[0-9]+');

    Route::get('/getdata-course-register/{course_id}/{course_type}', 'BackendController@getDataCourseRegister')
        ->name('module.rating_level.result.getdata_course_register')
        ->where('course_id', '[0-9]+')
        ->where('course_type', '[0-9]+');

    Route::get('/result-rating-level/{course_id}/{course_type}/{user_id}', 'BackendController@resultRatingLevel')
        ->name('module.rating_level.result.index')
        ->where('course_id', '[0-9]+')
        ->where('course_type', '[0-9]+')
        ->where('user_id', '[0-9]+');

    Route::get('/result-level/getdata/{course_id}/{course_type}/{user_id}', 'BackendController@getDataResultRatingLevel')
        ->name('module.rating_level.result.getdata')
        ->where('course_id', '[0-9]+')
        ->where('course_type', '[0-9]+')
        ->where('user_id', '[0-9]+');

    Route::get('/result/rating-level-detail/{course_id}/{course_type}/{user_id}/{course_rating_level_id}', 'BackendController@resultRatingLevelDetail')
        ->name('module.rating_level.result.view')
        ->where('course_id', '[0-9]+')
        ->where('course_type', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('course_rating_level_id', '[0-9]+');

    Route::get('/result/rating-level-detail/export-word/{course_id}/{course_type}/{user_id}/{course_rating_level_id}', 'BackendController@exportWordRatingLevelDetail')
        ->name('module.rating_level.result.export_word')
        ->where('course_id', '[0-9]+')
        ->where('course_type', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('course_rating_level_id', '[0-9]+');

    Route::get('/list-report-rating-level/{course_id}/{course_type}', 'BackendController@listReportRatingLevel')
        ->name('module.rating_level.list_report')
        ->where('course_id', '[0-9]+')
        ->where('course_type', '[0-9]+');

    Route::get('/list-report-rating-level/getdata/{course_id}/{course_type}', 'BackendController@getdataListReportRatingLevel')
        ->name('module.rating_level.list_report.getdata')
        ->where('course_id', '[0-9]+')
        ->where('course_type', '[0-9]+');

    Route::get('/report-rating-level/{course_id}/{course_type}/{course_rating_level_id}', 'BackendController@reportRatingLevel')
        ->name('module.rating_level.report')
        ->where('course_id', '[0-9]+')
        ->where('course_type', '[0-9]+')
        ->where('course_rating_level_id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/rating-organization', 'middleware' => 'auth'], function() {
    //Kỳ đánh giá
    Route::get('/', 'RatingOrganizationController@index')->name('module.rating_organization');
    Route::get('/getdata', 'RatingOrganizationController@getData')->name('module.rating_organization.getdata');
    Route::get('/edit/{id}', 'RatingOrganizationController@form')->name('module.rating_organization.edit')->where('id', '[0-9]+');
    Route::get('/create', 'RatingOrganizationController@form')->name('module.rating_organization.create');
    Route::post('/save', 'RatingOrganizationController@save')->name('module.rating_organization.save');
    Route::post('/remove', 'RatingOrganizationController@remove')->name('module.rating_organization.remove');
    Route::post('/open', 'RatingOrganizationController@open')->name('module.rating_organization.open');

    //Thiết lập
    Route::get('/setting/{rating_levels_id}', 'RatingOrganizationController@setting')
        ->name('module.rating_organization.setting')
        ->where('rating_levels_id', '[0-9]+');

    Route::get('/setting/{rating_levels_id}/get-data', 'RatingOrganizationController@getDataSetting')
        ->name('module.rating_organization.setting.getData')
        ->where('rating_levels_id', '[0-9]+');

    Route::post('/setting/{rating_levels_id}/save', 'RatingOrganizationController@saveSetting')
        ->name('module.rating_organization.setting.save')
        ->where('rating_levels_id', '[0-9]+');

    Route::post('/setting/{rating_levels_id}/remove', 'RatingOrganizationController@removeSetting')
        ->name('module.rating_organization.setting.remove')
        ->where('rating_levels_id', '[0-9]+');

    Route::post('/setting/{rating_levels_id}/modal-add-object/{course_rating_level_id}', 'RatingOrganizationController@modalAddObject')
        ->name('module.rating_organization.setting.modal_add_object')
        ->where('rating_levels_id', '[0-9]+')
        ->where('course_rating_level_id', '[0-9]+');

    Route::get('/setting/{rating_levels_id}/get-data-object/{course_rating_level_id}', 'RatingOrganizationController@getDataObject')
        ->name('module.rating_organization.setting.getDataObject')
        ->where('rating_levels_id', '[0-9]+')
        ->where('course_rating_level_id', '[0-9]+');

    Route::post('/setting/{rating_levels_id}/save-object/{course_rating_level_id}', 'RatingOrganizationController@saveObject')
        ->name('module.rating_organization.setting.save_object')
        ->where('rating_levels_id', '[0-9]+')
        ->where('course_rating_level_id', '[0-9]+');

    Route::post('/setting/{rating_levels_id}/remove-object/{course_rating_level_id}', 'RatingOrganizationController@removeObject')
        ->name('module.rating_organization.setting.remove_object')
        ->where('rating_levels_id', '[0-9]+')
        ->where('course_rating_level_id', '[0-9]+');

    //ghi danh HV
    Route::get('/register/{id}', 'RatingOrganizationController@register')
        ->name('module.rating_organization.register')
        ->where('id', '[0-9]+');

    Route::get('/register/{id}/getdata', 'RatingOrganizationController@getDataRegister')
        ->name('module.rating_organization.register.getdata')
        ->where('id', '[0-9]+');

    Route::get('/register/{id}/getDataNotRegister', 'RatingOrganizationController@getDataNotRegister')
        ->name('module.rating_organization.register.getdata_not_register')
        ->where('id', '[0-9]+');

    Route::get('/register/{id}/create', 'RatingOrganizationController@formRegister')
        ->name('module.rating_organization.register.create')
        ->where('id', '[0-9]+');

    Route::post('/register/{id}/save', 'RatingOrganizationController@saveRegister')
        ->name('module.rating_organization.register.save')
        ->where('id', '[0-9]+');

    Route::post('/register/{id}/remove', 'RatingOrganizationController@removeRegister')
        ->name('module.rating_organization.register.remove')
        ->where('id', '[0-9]+');

    //Kết quả
    Route::get('/list-report/{rating_levels_id}', 'RatingOrganizationController@listReport')
        ->name('module.rating_organization.list_report')
        ->where('rating_levels_id', '[0-9]+');

    Route::get('/list-report/{rating_levels_id}/getdata', 'RatingOrganizationController@getdataListReport')
        ->name('module.rating_organization.list_report.getdata')
        ->where('rating_levels_id', '[0-9]+');

    Route::get('/list-report/{rating_levels_id}/list-user-rating/{course_rating_level_id}/getdata', 'RatingOrganizationController@getdataListUserRating')
        ->name('module.rating_organization.list_user_rating.getdata')
        ->where('rating_levels_id', '[0-9]+')
        ->where('course_rating_level_id', '[0-9]+');

    Route::post('/list-report/{rating_levels_id}/{course_rating_level}/{user_id}/{rating_user}/modal-rating-level', 'RatingOrganizationController@modalRatingLevel')
        ->name('module.rating_organization.modal_rating_level')
        ->where('rating_levels_id', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('rating_user', '[0-9]+');

    Route::post('/list-report/{rating_levels_id}/{course_rating_level}/{user_id}/{rating_user}/modal-edit-rating-level', 'RatingOrganizationController@modalEditRatingLevel')
        ->name('module.rating_organization.modal_edit_rating_level')
        ->where('rating_levels_id', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('rating_user', '[0-9]+');

    Route::post('/list-report/{rating_levels_id}/{course_rating_level}/{user_id}/{rating_user}/save-rating-level-course', 'RatingOrganizationController@saveRatingCourse')
        ->name('module.rating_organization.save_rating_course')
        ->where('rating_levels_id', '[0-9]+')
        ->where('course_rating_level', '[0-9]+')
        ->where('user_id', '[0-9]+')
        ->where('rating_user', '[0-9]+');
});
