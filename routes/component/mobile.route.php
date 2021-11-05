<?php

Route::get('/profile-mobile', 'Mobile\ProfileController@index')
    ->name('themes.mobile.frontend.profile');

Route::get('/qr-code-user', 'Mobile\ProfileController@qrCodeUser')
    ->name('themes.mobile.frontend.profile.qr_code');

Route::post('/change-avatar-profile', 'Mobile\ProfileController@changeAvatar')
    ->name('themes.mobile.frontend.profile.change_avatar');

Route::get('/training-process', 'Mobile\ProfileController@trainingProcess')
    ->name('themes.mobile.frontend.training_process');

Route::get('/my-course', 'Mobile\ProfileController@myCourse')
    ->name('themes.mobile.frontend.my_course');

Route::get('/get-rank', 'Mobile\ProfileController@getRank')
    ->name('themes.mobile.frontend.get_rank');

Route::get('/accumulated-from-course', 'Mobile\ProfileController@accumulatedCourse')
    ->name('themes.mobile.frontend.accumulated_from_course');

Route::get('/accumulated-from-video', 'Mobile\ProfileController@accumulatedVideo')
    ->name('themes.mobile.frontend.accumulated_from_video');

Route::get('/accumulated-from-bonus-points', 'Mobile\ProfileController@accumulatedBonusPoints')
    ->name('themes.mobile.frontend.accumulated_from_bonus_points');


Route::get('/chart-mobile', 'Mobile\ChartController@chart')
    ->name('themes.mobile.frontend.chart');

Route::post('/data-chart', 'Mobile\ChartController@dataChart')
    ->name('themes.mobile.frontend.chart.data');

Route::get('/training-roadmap-course', 'Mobile\ChartController@trainingRoadmapCourse')
    ->name('themes.mobile.frontend.training_roadmap_course');


Route::get('/online-course', 'Mobile\OnlineController@index')
    ->name('themes.mobile.frontend.online.index');

Route::get('/online-course/view-scorm/{id}/{activity_id}/{attempt_id}', 'Mobile\OnlineController@viewScorm')
    ->name('themes.mobile.frontend.online.view_scorm')
    ->where('id', '[0-9]+')
    ->where('activity_id', '[0-9]+')
    ->where('attempt_id', '[0-9]+');

Route::get('/online-course/{course_id}', 'Mobile\OnlineController@detail')
    ->name('themes.mobile.frontend.online.detail')
    ->where('course_id', '[0-9]+');

Route::post('/online-course/comment/{course_id}', 'Mobile\OnlineController@comment')
    ->name('themes.mobile.frontend.online.comment')
    ->where('course_id', '[0-9]+');

Route::post('/online-course/ask_answer/{course_id}', 'Mobile\OnlineController@ask_answer')
    ->name('themes.mobile.frontend.online.ask_answer')
    ->where('course_id', '[0-9]+');

Route::post('/online-course/note/{course_id}', 'Mobile\OnlineController@note')
    ->name('themes.mobile.frontend.online.note')
    ->where('course_id', '[0-9]+');


Route::get('/offline-course', 'Mobile\OfflineController@index')
    ->name('themes.mobile.frontend.offline.index');

Route::post('/offline-course/check-pdf', 'Mobile\OfflineController@checkPDF')
    ->name('themes.mobile.frontend.offline.check_pdf');

Route::post('/offline-course/view-pdf', 'Mobile\OfflineController@viewPDF')
    ->name('themes.mobile.frontend.offline.view_pdf');

Route::get('/offline-course/{course_id}', 'Mobile\OfflineController@detail')
    ->name('themes.mobile.frontend.offline.detail')
    ->where('course_id', '[0-9]+');

Route::post('/offline-course/comment/{course_id}', 'Mobile\OfflineController@comment')
    ->name('themes.mobile.frontend.offline.comment')
    ->where('course_id', '[0-9]+');

Route::get('/notify', 'Mobile\NotifyController@index')
    ->name('themes.mobile.frontend.notify.index');

Route::get('/notify/{id}/{type}', 'Mobile\NotifyController@detail')
    ->name('themes.mobile.frontend.notify.detail')
    ->where('id', '[0-9]+')
    ->where('type', '[0-9]+');

Route::get('/faq', 'Mobile\FAQController@index')
    ->name('themes.mobile.frontend.faq.index');

Route::get('/search-mobile', 'Mobile\SearchController@index')
    ->name('themes.mobile.frontend.search.index');

Route::get('/approve-course', 'Mobile\ApproveCourseController@index')
    ->name('themes.mobile.frontend.approve_course.course');

Route::get('/approve-course/{id}/{type}', 'Mobile\ApproveCourseController@course')
    ->name('themes.mobile.frontend.approve_course.user')
    ->where('id', '[0-9]+')
    ->where('type', '[0-9]+');

Route::post('/approve-course/{id}/{type}', 'Mobile\ApproveCourseController@approveCourse')
    ->name('themes.mobile.frontend.approve_course.approve')
    ->where('id', '[0-9]+')
    ->where('type', '[0-9]+');

Route::get('/manager', 'Mobile\ManagerController@index')
    ->name('themes.mobile.frontend.manager');

Route::post('/manager/chart-all-courses', 'Mobile\ManagerController@dataChartAllCourses')
    ->name('themes.mobile.frontend.manager.chart_all_courses');

Route::post('/manager/chart-user-new', 'Mobile\ManagerController@dataChartUserNew')
    ->name('themes.mobile.frontend.manager.chart_user_new');

Route::post('/manager/chart-user-by-course', 'Mobile\ManagerController@dataChartUserByCourse')
    ->name('themes.mobile.frontend.manager.chart_user_by_course');

