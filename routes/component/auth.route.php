<?php

Route::get('home-outside/{type}', 'Auth\LoginController@homeOutside')->name('home_outside');

Route::get('detail-home-outside/{id}/{type}', 'Auth\LoginController@detailHomeOutside')->name('detail_home_outside')->where('id', '[0-9]+');

Route::post('like-new-outside', 'Auth\LoginController@likeNewOutside')->name('like_new_outside');

Route::get('hot-news-home-outside', 'Auth\LoginController@hotNewsHomeOutside')->name('hot_news_home_outside');

Route::get('user-contact', 'Auth\LoginController@userContact')->name('user_contact_outside');

Route::post('save-user-contact', 'Auth\LoginController@saveUserContact')->name('save_user_contact');

Route::post('ajax-get-related-news', 'Auth\LoginController@ajaxGetRelatedNews')->name('ajax_get_related_news');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');

Route::post('login', 'Auth\LoginController@login');

Route::match(['get', 'post'], '/logout', function () {
    if(session('autho')=='azure')
        $method = 'logoutAzure';
    else
        $method = 'logout';
    return App::call('App\Http\Controllers\Auth\LoginController@'.$method);
})->name('logout');
Route::match(['get', 'post'],'returnLogoutAzure','Auth\LoginController@returnLogoutAzure')->name('returnLogoutAzure');

Route::get('modal-reset-pass', 'Auth\LoginController@modalResetPass')->name('auth.modal_reset_pass');

Route::post('reset-pass', 'Auth\LoginController@resetPass')->name('auth.reset_pass');

Route::post('reset-pass-user-question', 'Auth\LoginController@resetPassUserQuestion')->name('auth.reset_pass_user_question');

Route::post('check-user-with-question', 'Auth\LoginController@checkUserQuestion')->name('auth.check_user_question');

Route::post('create-user-mobile', 'Auth\LoginController@saveUserThird')
    ->name('auth.save_user_third');
Route::get('/redirect/{provider}', 'Auth\AuthController@redirectToProvider')->name('login.provider');
//Route::get('/auth/callback', 'Auth\AuthController@handleProviderCallback');
Route::get('/auth/callback', 'Auth\AuthController@handleProviderCallbackAzure');
