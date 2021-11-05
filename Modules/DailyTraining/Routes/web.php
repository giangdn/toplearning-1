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

Route::group(['prefix' => '/daily-training', 'middleware' => 'auth'], function() {
    Route::get('/', 'Frontend\DailyTrainingVideoController@index')->name('module.daily_training.frontend');

    Route::get('/my-video', 'Frontend\DailyTrainingVideoController@myVideo')->name('module.daily_training.frontend.my_video');

    Route::get('/cate/{id}', 'Frontend\DailyTrainingVideoController@dailyCate')->name('module.daily_training_cate.frontend');

    Route::get('/add-video', 'Frontend\DailyTrainingVideoController@addVideo')->name('module.daily_training.frontend.add_video');

    Route::post('/save-video', 'Frontend\DailyTrainingVideoController@saveVideo')->name('module.daily_training.frontend.save_video');

    Route::post('/upload-video', 'Frontend\DailyTrainingVideoController@upload')->name('module.daily_training.frontend.upload_video');

    Route::post('/disable-video', 'Frontend\DailyTrainingVideoController@disableVideo')->name('module.daily_training.frontend.disable_video');

    Route::get('/search-mobile', 'Frontend\DailyTrainingVideoController@search')
        ->name('module.daily_training.frontend.search');

    Route::get('/detail-video/{id}', 'Frontend\DailyTrainingVideoController@detailVideo')
        ->name('module.daily_training.frontend.detail_video')
        ->where('id', '[0-9]+');

    Route::post('/like-video/{id}', 'Frontend\DailyTrainingVideoController@likeVideo')
        ->name('module.daily_training.frontend.like_video')
        ->where('id', '[0-9]+');

    Route::post('/comment-video/{id}', 'Frontend\DailyTrainingVideoController@commentVideo')
        ->name('module.daily_training.frontend.comment_video')
        ->where('id', '[0-9]+');

    Route::post('/like-comment-video/{id}', 'Frontend\DailyTrainingVideoController@likeCommentVideo')
        ->name('module.daily_training.frontend.like_comment_video')
        ->where('id', '[0-9]+');

});

Route::group(['prefix' => '/admin-cp/daily-training', 'middleware' => 'auth'], function() {

    Route::get('/', 'Backend\DailyTrainingCategoryController@index')->name('module.daily_training')->middleware('permission:daily-training');

    Route::get('/getdata', 'Backend\DailyTrainingCategoryController@getData')->name('module.daily_training.getdata')->middleware('permission:daily-training');

    Route::post('/edit', 'Backend\DailyTrainingCategoryController@form')->name('module.daily_training.edit')
        ->where('id', '[0-9]+')->middleware('permission:daily-training-edit');

    Route::post('/save', 'Backend\DailyTrainingCategoryController@save')->name('module.daily_training.save')->middleware('permission:daily-training-save|daily-training-create');

    Route::post('/remove', 'Backend\DailyTrainingCategoryController@remove')->name('module.daily_training.remove')->middleware('permission:daily-training-delete');

    Route::get('/permission/{cate_id}', 'Backend\DailyTrainingCategoryController@permission')->name('module.daily_training.permission');

    Route::get('/user/getdata/{cate_id}', 'Backend\DailyTrainingCategoryController@getUserPermission')
        ->name('module.daily_training.user.getdata')
        ->where('category','[0-9]+');

    Route::post('/user/save-permission', 'Backend\DailyTrainingCategoryController@savePermissionUser')
        ->name('module.daily_training.user.save_permission');
});

Route::group([
    'prefix' => '/admin-cp/daily-training/video/{cate_id}',
    'middleware' => 'auth',
    'where' => [
        'cate_id' => '[0-9]+'
    ]
], function() {

    Route::get('/', 'Backend\DailyTrainingVideoController@index')->name('module.daily_training.video');

    Route::get('/getdata', 'Backend\DailyTrainingVideoController@getData')->name('module.daily_training.video.getdata');

    Route::post('/remove', 'Backend\DailyTrainingVideoController@remove')->name('module.daily_training.video.remove');

    Route::post('/approve', 'Backend\DailyTrainingVideoController@approve')->name('module.daily_training.video.approve');

    Route::get('/view-comment/{video_id}', 'Backend\DailyTrainingVideoController@viewComment')
        ->name('module.daily_training.video.view_comment');

    Route::post('/check-failed-comment/{video_id}', 'Backend\DailyTrainingVideoController@checkFailedComment')
        ->name('module.daily_training.video.check_failed_comment');

    Route::get('/view-report/{video_id}', 'Backend\DailyTrainingVideoController@viewReport')
        ->name('module.daily_training.video.view_report');

    Route::get('/view-report/{video_id}/getdata', 'Backend\DailyTrainingVideoController@getDataReport')
        ->name('module.daily_training.video.report.getdata');
});

Route::group(['prefix' => '/admin-cp/score-views', 'middleware' => 'auth'], function() {

    Route::get('/', 'Backend\DailyTrainingScoreViewsController@index')->name('module.daily_training.score_views')->middleware('permission:score-view');

    Route::get('/getdata', 'Backend\DailyTrainingScoreViewsController@getData')->name('module.daily_training.score_views.getdata')->middleware('permission:score-view');

    Route::post('/edit', 'Backend\DailyTrainingScoreViewsController@form')->name('module.daily_training.score_views.edit')
        ->where('id', '[0-9]+')->middleware('permission:score-edit');

    Route::post('/save', 'Backend\DailyTrainingScoreViewsController@save')->name('module.daily_training.score_views.save')->middleware('permission:score-edit|score-create');

    Route::post('/remove', 'Backend\DailyTrainingScoreViewsController@remove')->name('module.daily_training.score_views.remove')->middleware('permission:score-delete');
});

Route::group(['prefix' => '/admin-cp/score-like', 'middleware' => 'auth'], function() {

    Route::get('/', 'Backend\DailyTrainingScoreLikeController@index')->name('module.daily_training.score_like')->middleware('permission:score-like');

    Route::get('/getdata', 'Backend\DailyTrainingScoreLikeController@getData')->name('module.daily_training.score_like.getdata')->middleware('permission:score-like');

    Route::post('/edit', 'Backend\DailyTrainingScoreLikeController@form')->name('module.daily_training.score_like.edit')
        ->where('id', '[0-9]+')->middleware('permission:score-like-edit');

    Route::post('/save', 'Backend\DailyTrainingScoreLikeController@save')->name('module.daily_training.score_like.save')->middleware('permission:score-like-edit|score-like-create');

    Route::post('/remove', 'Backend\DailyTrainingScoreLikeController@remove')->name('module.daily_training.score_like.remove')->middleware('permission:score-like-delete');
});

Route::group(['prefix' => '/admin-cp/score-comment', 'middleware' => 'auth'], function() {

    Route::get('/', 'Backend\DailyTrainingScoreCommentController@index')->name('module.daily_training.score_comment')->middleware('permission:score-comment');

    Route::get('/getdata', 'Backend\DailyTrainingScoreCommentController@getData')->name('module.daily_training.score_comment.getdata')->middleware('permission:score-comment');

    Route::post('/edit', 'Backend\DailyTrainingScoreCommentController@form')->name('module.daily_training.score_comment.edit')
        ->where('id', '[0-9]+')->middleware('permission:score-comment-edit');

    Route::post('/save', 'Backend\DailyTrainingScoreCommentController@save')->name('module.daily_training.score_comment.save')->middleware('permission:score-comment-edit|score-comment-create');

    Route::post('/remove', 'Backend\DailyTrainingScoreCommentController@remove')->name('module.daily_training.score_comment.remove')->middleware('permission:score-comment-delete');
});
