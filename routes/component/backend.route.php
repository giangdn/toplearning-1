<?php

Route::group(['prefix' => '/admin-cp'], function() {
    Route::get('/', 'Backend\CategoryController@dashboard')->name('backend.dashboard');
    Route::post('/get-user-creatd-updated/{created}/{updated}', 'Backend\CategoryController@getUserCreateUpdated')->name('backend.get_user_created_updated')
    ->where('created', '[0-9]+')
    ->where('updated', '[0-9]+');
});

//ĐÓNG MỞ MENU
Route::post('/close-open-menu-backend', 'Backend\SettingController@closeOpendMenu')->name('backend.close_open_menu');

Route::group(['prefix' => '/admin-cp/setting'], function() {
    Route::get('/', 'Backend\SettingController@index')->name('backend.setting');
});

Route::group(['prefix' => '/admin-cp/speed-text'], function() {
    Route::get('/', 'Backend\SpeedTextController@index')->name('backend.speed_text');

    Route::get('/getdata', 'Backend\SpeedTextController@getData')->name('backend.speed_text.getdata');

    Route::post('/remove', 'Backend\SpeedTextController@remove')->name('backend.speed_text.remove');

    Route::get('/create', 'Backend\SpeedTextController@form')->name('backend.speed_text.create');

    Route::get('/edit/{id}', 'Backend\SpeedTextController@form')->name('backend.speed_text.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\SpeedTextController@save')->name('backend.speed_text.save');
});

Route::group(['prefix' => '/admin-cp/footer'], function() {
    Route::get('/', 'Backend\FooterController@index')->name('backend.footer');

    Route::get('/getdata', 'Backend\FooterController@getData')->name('backend.footer.getdata');

    Route::post('/remove', 'Backend\FooterController@remove')->name('backend.footer.remove');

    Route::get('/create', 'Backend\FooterController@form')->name('backend.footer.create');

    Route::get('/edit/{id}', 'Backend\FooterController@form')->name('backend.footer.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\FooterController@save')->name('backend.footer.save');
});

Route::group(['prefix'=> '/admin-cp'], function () {
    Route::get('/logo-outside', 'Backend\LogoController@logoOutside')->name('backend.logo_outside')->middleware('permission:config-logo');
    Route::post('/logo-outside/save', 'Backend\LogoController@saveLogoOutside')->name('backend.logo_outside.save')->middleware('permission:config-logo');

    Route::get('/favicon', 'Backend\LogoController@favicon')->name('backend.logo.favicon')->middleware('permission:config-favicon');
    Route::post('/save-favicon', 'Backend\LogoController@saveFavicon')->name('backend.logo.save.favicon')->middleware('permission:config-favicon');

    Route::get('/logo', 'Backend\LogoController@index')->name('backend.logo')->middleware('permission:config-logo');

    Route::get('/logo/getdata', 'Backend\LogoController@getData')->name('backend.logo.getdata')->middleware('permission:config-logo');

    Route::post('/logo/remove', 'Backend\LogoController@remove')->name('backend.logo.remove')->middleware('permission:config-logo');

    Route::get('/logo/create', 'Backend\LogoController@form')->name('backend.logo.create')->middleware('permission:config-logo');

    Route::get('/logo/edit/{id}', 'Backend\LogoController@form')->name('backend.logo.edit')->where('id', '[0-9]+')->middleware('permission:config-logo');

    Route::post('/logo/save', 'Backend\LogoController@save')->name('backend.logo.save')->middleware('permission:config-logo');

    Route::post('/logo/ajax_isopen_publish', 'Backend\LogoController@ajaxIsopenPublish')->name('backend.logo.ajax_isopen_publish');
});

Route::group(['prefix'=> '/admin-cp/login-image'], function () {
    Route::get('/', 'Backend\LoginImageController@index')->name('backend.login_image')->middleware('permission:config-login-image');

    Route::get('/getdata', 'Backend\LoginImageController@getData')->name('backend.login_image.getdata')->middleware('permission:config-login-image');

    Route::post('/remove', 'Backend\LoginImageController@remove')->name('backend.login_image.remove')->middleware('permission:config-login-image');

    Route::post('/edit', 'Backend\LoginImageController@form')->name('backend.login_image.edit')->where('id', '[0-9]+')->middleware('permission:config-login-image');

    Route::post('/save', 'Backend\LoginImageController@save')->name('backend.login_image.save')->middleware('permission:config-login-image-save');

    Route::post('/ajax_isopen_publish', 'Backend\LoginImageController@ajaxIsopenPublish')->name('backend.login_image.ajax_isopen_publish');
});

//CHỈNH MÀU NÚT
Route::group(['prefix' => '/admin-cp/setting-color'], function() {
    Route::get('/', 'Backend\SettingColorController@index')->name('backend.setting_color');

    Route::get('/create', 'Backend\SettingColorController@form')->name('backend.setting_color.create');

    Route::get('/edit/{id}', 'Backend\SettingColorController@form')->name('backend.setting_color.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\SettingColorController@save')->name('backend.setting_color.save');
});

//CHỈNH THỜI GIAN
Route::group(['prefix' => '/admin-cp/setting-time'], function() {
    Route::get('/', 'Backend\SettingTimeController@index')->name('backend.setting_time');

    Route::get('/getdata', 'Backend\SettingTimeController@getData')->name('backend.setting_time.getdata');

    Route::get('/create', 'Backend\SettingTimeController@form')->name('backend.setting_time.create');

    Route::get('/edit/{id}', 'Backend\SettingTimeController@form')->name('backend.setting_time.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\SettingTimeController@save')->name('backend.setting_time.save');

    Route::post('/remove', 'Backend\SettingTimeController@remove')->name('backend.setting_time.remove');
});

Route::group(['prefix' => '/admin-cp/webservice'], function() {
    Route::get('/', 'Backend\WebServiceController@index')->name('backend.webservice');

    Route::get('/getdata', 'Backend\WebServiceController@getData')->name('backend.webservice.getdata');

    Route::post('/remove', 'Backend\WebServiceController@remove')->name('backend.webservice.remove');

    Route::get('/create', 'Backend\WebServiceController@form')->name('backend.webservice.create');

    Route::get('/edit/{id}', 'Backend\WebServiceController@form')->name('backend.webservice.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\WebServiceController@save')->name('backend.webservice.save');
});

Route::group(['prefix' => '/admin-cp/slider'], function() {
    Route::get('/', 'Backend\SliderController@index')->name('backend.slider')->middleware('permission:banner');

    Route::get('/getdata', 'Backend\SliderController@getData')->name('backend.slider.getdata')->middleware('permission:banner');

    Route::post('/remove', 'Backend\SliderController@remove')->name('backend.slider.remove')->middleware('permission:banner-delete');

    Route::get('/create', 'Backend\SliderController@form')->name('backend.slider.create')->middleware('permission:banner-create');

    Route::get('/edit/{id}', 'Backend\SliderController@form')->name('backend.slider.edit')->where('id', '[0-9]+')->middleware('permission:banner-edit');

    Route::post('/save', 'Backend\SliderController@save')->name('backend.slider.save')->middleware('permission:banner-create');

    Route::post('/ajax_isopen_publish', 'Backend\SliderController@ajaxIsopenPublish')->name('backend.slider.ajax_isopen_publish');
});

Route::group(['prefix' => '/admin-cp/slider-outside'], function() {
    Route::get('/', 'Backend\SliderOutsideController@index')->name('backend.slider_outside');

    Route::get('/getdata', 'Backend\SliderOutsideController@getData')->name('backend.slider_outside.getdata');

    Route::post('/remove', 'Backend\SliderOutsideController@remove')->name('backend.slider_outside.remove');

    Route::get('/create', 'Backend\SliderOutsideController@form')->name('backend.slider_outside.create');

    Route::get('/edit/{id}', 'Backend\SliderOutsideController@form')->name('backend.slider_outside.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\SliderOutsideController@save')->name('backend.slider_outside.save');

    Route::post('/ajax_isopen_publish', 'Backend\SliderOutsideController@ajaxIsopenPublish')->name('backend.slider_outside.ajax_isopen_publish');
});

//BANNER LOGIN MOBILE
Route::group(['prefix' => '/admin-cp/banner-login-mobile'], function() {
    Route::get('/', 'Backend\BannerLoginMobileController@index')->name('backend.banner_login_mobile');

    Route::get('/getdata', 'Backend\BannerLoginMobileController@getData')->name('backend.banner_login_mobile.getdata');

    Route::post('/remove', 'Backend\BannerLoginMobileController@remove')->name('backend.banner_login_mobile.remove');

    Route::get('/create', 'Backend\BannerLoginMobileController@form')->name('backend.banner_login_mobile.create');

    Route::get('/edit/{id}', 'Backend\BannerLoginMobileController@form')->name('backend.banner_login_mobile.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\BannerLoginMobileController@save')->name('backend.banner_login_mobile.save');

    Route::post('/ajax_isopen_publish', 'Backend\BannerLoginMobileController@ajaxIsopenPublish')->name('backend.banner_login_mobile.ajax_isopen_publish');
});

// THÔNG TIN CÔNG TY
Route::group(['prefix' => '/admin-cp/infomation-company'], function() {
    Route::get('/', 'Backend\InfomationCompanyController@index')->name('backend.infomation_company');

    Route::get('/create', 'Backend\InfomationCompanyController@form')->name('backend.infomation_company.create');

    Route::get('/edit/{id}', 'Backend\InfomationCompanyController@form')->name('backend.infomation_company.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\InfomationCompanyController@save')->name('backend.infomation_company.save');
});

Route::group(['prefix' => '/admin-cp/advertising-photo'], function() {
    Route::get('/{type}', 'Backend\AdvertisingPhotoController@index')->name('backend.advertising_photo');

    Route::get('/getdata/{type}', 'Backend\AdvertisingPhotoController@getData')->name('backend.advertising_photo.getdata');

    Route::post('/remove', 'Backend\AdvertisingPhotoController@remove')->name('backend.advertising_photo.remove');

    Route::post('/edit/{type}', 'Backend\AdvertisingPhotoController@form')->name('backend.advertising_photo.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\AdvertisingPhotoController@save')->name('backend.advertising_photo.save');

    Route::post('/ajax_isopen_publish', 'Backend\AdvertisingPhotoController@ajaxIsopenPublish')->name('backend.advertising_photo.ajax_isopen_publish');
});

Route::group(['prefix' => '/admin-cp/guide'], function() {
    Route::get('/', 'Backend\GuideController@index')->name('backend.guide')->middleware('permission:guide');

    Route::get('/getdata', 'Backend\GuideController@getData')->name('backend.guide.getdata')->middleware('permission:guide');

    Route::post('/remove', 'Backend\GuideController@remove')->name('backend.guide.remove')->middleware('permission:guide-delete');

    Route::get('/create', 'Backend\GuideController@form')->name('backend.guide.create')->middleware('permission:guide-create');

    Route::get('/edit/{id}', 'Backend\GuideController@form')->name('backend.guide.edit')->where('id', '[0-9]+')->middleware('permission:guide-edit');

    Route::post('/save', 'Backend\GuideController@save')->name('backend.guide.save')->middleware('permission:guide-create');
});


// Liên hệ
Route::group(['prefix' => '/admin-cp/contact'], function() {
    Route::get('/', 'Backend\ContactController@index')->name('backend.contact')->middleware('permission:guide');

    Route::get('/getdata', 'Backend\ContactController@getData')->name('backend.contact.getdata')->middleware('permission:guide');

    Route::post('/remove', 'Backend\ContactController@remove')->name('backend.contact.remove')->middleware('permission:guide-delete');

    Route::get('/create', 'Backend\ContactController@form')->name('backend.contact.create')->middleware('permission:guide-create');

    Route::get('/edit/{id}', 'Backend\ContactController@form')->name('backend.contact.edit')->where('id', '[0-9]+')->middleware('permission:guide-edit');

    Route::post('/save', 'Backend\ContactController@save')->name('backend.contact.save')->middleware('permission:guide-create');
});

// Địa điểm đào tạo
Route::group(['prefix' => '/admin-cp/google-map'], function() {
    Route::get('/','Backend\GoogleMapController@index')->name('backend.google.map');

    Route::post('/post','Backend\GoogleMapController@store')->name('backend.google.map.store');

    Route::get('/list-local', 'Backend\GoogleMapController@listLocal')->name('backend.google.map.list')->middleware('permission:guide');

    Route::get('/getdata', 'Backend\GoogleMapController@getData')->name('backend.google.map.getdata')->middleware('permission:guide');

    Route::post('/remove', 'Backend\GoogleMapController@remove')->name('backend.google.map.remove')->middleware('permission:guide-delete');

    Route::get('/create', 'Backend\GoogleMapController@form')->name('backend.google.map.create')->middleware('permission:guide-create');

    Route::get('/edit/{id}', 'Backend\GoogleMapController@form')->name('backend.google.map.edit')->where('id', '[0-9]+')->middleware('permission:guide-edit');

    Route::post('/save', 'Backend\GoogleMapController@save')->name('backend.google.map.save')->middleware('permission:guide-create');
});

Route::group(['prefix' => '/admin-cp/category'], function() {
    Route::get('/', 'Backend\CategoryController@index')->name('backend.category');
    /* province */
    Route::get('/province', 'Backend\ProvinceController@index')->name('backend.category.province')->middleware('permission:category-province');
    Route::get('/province/getdata', 'Backend\ProvinceController@getData')->name('backend.category.province.getdata')->middleware('permission:category-province');
    Route::post('/province/remove','Backend\ProvinceController@remove')->name('backend.category.province.remove')->middleware('permission:category-province-delete');
    Route::post('/province/edit','Backend\ProvinceController@form')->name('backend.category.province.edit')->where(['id'=>'[0-9]+'])->middleware('permission:category-province-edit');
    Route::post('/province/save','Backend\ProvinceController@save')->name('backend.category.province.save')->middleware('permission:category-province-create|category-province-edit');
    Route::post('/province/import', 'Backend\ProvinceController@import')->name('backend.category.province.import')->middleware('permission:category-province-import');

    /*district*/
    Route::get('/district', 'Backend\DistrictController@index')->name('backend.category.district')->middleware('permission:category-district');
    Route::get('/district/getdata', 'Backend\DistrictController@getData')->name('backend.category.district.getdata')->middleware('permission:category-district');
    Route::post('/district/remove','Backend\DistrictController@remove')->name('backend.category.district.remove')->middleware('permission:category-district-delete');
    Route::post('/district/edit','Backend\DistrictController@form')->name('backend.category.district.edit')->where(['id'=>'[0-9]+'])->middleware('permission:category-district-edit');
    Route::post('/district/save','Backend\DistrictController@save')->name('backend.category.district.save')->middleware('permission:category-district-create');
    Route::get('/district/filter','Backend\DistrictController@filter')->name('backend.category.district.filter')->middleware('permission:category-district');

    /* Unit */
    Route::get('/unit/{level}', 'Backend\UnitController@index')->name('backend.category.unit')->where('level', '[0-9]+')->middleware('permission:category-unit');
    Route::get('/unit/{level}/getdata', 'Backend\UnitController@getData')->name('backend.category.unit.getdata')->middleware('permission:category-unit');
    Route::post('/unit/{level}/edit', 'Backend\UnitController@form')->name('backend.category.unit.edit')->where('level', '[0-9]+')->where('id', '[0-9]+')->middleware('permission:category-unit-edit');
    Route::post('/unit/{level}/save', 'Backend\UnitController@save')->name('backend.category.unit.save')->where('level', '[0-9]+')->middleware('permission:category-unit-create|category-unit-edit');
    Route::post('/unit/{level}/remove', 'Backend\UnitController@remove')->name('backend.category.unit.remove')->where('level', '[0-9]+')->middleware('permission:category-unit-delete');
    Route::post('/unit/import', 'Backend\UnitController@import')->name('backend.category.unit.import')->middleware('permission:category-unit-import');
    Route::get('/unit/tree', 'Backend\UnitController@treeFolder')->name('backend.category.unit.tree_folder')->middleware('permission:category-unit');
    Route::post('/unit/tree/getChild', 'Backend\UnitController@getChild')->name('backend.category.unit.tree_folder.get_child')->middleware('permission:category-unit');
    Route::get('/unit/export/{level}', 'Backend\UnitController@export')->name('backend.category.unit.export')->middleware('permission:category-unit-export');
    Route::post('/unit/import_update', 'Backend\UnitController@importUpdate')->name('backend.category.unit.import_update')->middleware('permission:category-unit-import');
    Route::post('/unit/ajax_isopen_publish', 'Backend\UnitController@ajaxIsopenPublish')->name('backend.category.unit.ajax_isopen_publish');

    /* Area */
    Route::get('/area/{level}', 'Backend\AreaController@index')->name('backend.category.area')->where('level', '[0-9]+');
    Route::get('/area/{level}/getdata', 'Backend\AreaController@getData')->name('backend.category.area.getdata');
    Route::post('/area/{level}/edit', 'Backend\AreaController@form')->name('backend.category.area.edit')->where('level', '[0-9]+')->where('id', '[0-9]+');
    Route::post('/area/{level}/save', 'Backend\AreaController@save')->name('backend.category.area.save')->where('level', '[0-9]+');
    Route::post('/area/{level}/remove', 'Backend\AreaController@remove')->name('backend.category.area.remove')->where('level', '[0-9]+');
    Route::post('/area/import', 'Backend\AreaController@import')->name('backend.category.area.import');
    Route::post('/area/ajax_isopen_publish', 'Backend\AreaController@ajaxIsopenPublish')->name('backend.category.area.ajax_isopen_publish');

    /* Titles */
    Route::get('/titles', 'Backend\TitlesController@index')->name('backend.category.titles')->middleware('permission:category-titles');
    Route::get('/titles/getdata', 'Backend\TitlesController@getData')->name('backend.category.titles.getdata')->middleware('permission:category-titles');
    Route::post('/titles/edit', 'Backend\TitlesController@form')->name('backend.category.titles.edit')->where('id', '[0-9]+')->middleware('permission:category-titles-edit');
    Route::post('/titles/save', 'Backend\TitlesController@save')->name('backend.category.titles.save')->middleware('permission:category-titles-create|category-titles-edit');
    Route::post('/titles/remove', 'Backend\TitlesController@remove')->name('backend.category.titles.remove')->middleware('permission:category-titles-delete');
    Route::post('/titles/import', 'Backend\TitlesController@import')->name('backend.category.titles.import')->middleware('permission:category-titles-import');
    Route::get('/titles/export', 'Backend\TitlesController@export')->name('backend.category.titles.export')->middleware('permission:category-titles-export');
    Route::post('/title/ajax_isopen_publish', 'Backend\TitlesController@ajaxIsopenPublish')->name('backend.category.title.ajax_isopen_publish');

    // CẤP BẬC CHỨC DANH
    Route::get('/title_rank', 'Backend\TitleRankController@index')->name('backend.category.title_rank');
    Route::get('/title_rank/getdata', 'Backend\TitleRankController@getData')->name('backend.category.title_rank.getdata');
    Route::post('/title_rank/edit', 'Backend\TitleRankController@form')->name('backend.category.title_rank.edit')->where('id', '[0-9]+');
    Route::post('/title_rank/save', 'Backend\TitleRankController@save')->name('backend.category.title_rank.save');
    Route::post('/title_rank/remove', 'Backend\TitleRankController@remove')->name('backend.category.title_rank.remove');
    Route::post('/title_rank/ajax_isopen_publish', 'Backend\TitleRankController@ajaxIsopenPublish')->name('backend.category.title_rank.ajax_isopen_publish');

	/* Position */
    Route::get('/position', 'Backend\PositionController@index')->name('backend.category.position')->middleware('permission:category-position');
    Route::get('/position/getdata', 'Backend\PositionController@getData')->name('backend.category.position.getdata')->middleware('permission:category-position');
    Route::post('/position/edit', 'Backend\PositionController@form')->name('backend.category.position.edit')->where('id', '[0-9]+')->middleware('permission:category-position-edit');
    Route::post('/position/save', 'Backend\PositionController@save')->name('backend.category.position.save')->middleware('permission:category-position-create|category-position-edit');
    Route::post('/position/remove', 'Backend\PositionController@remove')->name('backend.category.position.remove')->middleware('permission:category-position-delete');
    Route::post('/position/ajax_isopen_publish', 'Backend\PositionController@ajaxIsopenPublish')->name('backend.category.position.ajax_isopen_publish');

	 /* Training Type */
    Route::get('/training-type', 'Backend\TrainingTypeController@index')->name('backend.category.training-type')->middleware('permission:category-training-type');
    Route::get('/training-type/getdata', 'Backend\TrainingTypeController@getData')->name('backend.category.training-type.getdata')->middleware('permission:category-training-type');
    Route::post('/training-type/edit', 'Backend\TrainingTypeController@form')->name('backend.category.training-type.edit')->where('id', '[0-9]+')->middleware('permission:category-training-type-edit');
    Route::post('/training-type/save', 'Backend\TrainingTypeController@save')->name('backend.category.training-type.save')->middleware('permission:category-training-type-create|category-training-type-edit');
    Route::post('/training-type/remove', 'Backend\TrainingTypeController@remove')->name('backend.category.training-type.remove')->middleware('permission:category-training-type-delete');

	 /* Absent */
    Route::get('/absent', 'Backend\AbsentController@index')->name('backend.category.absent')->middleware('permission:category-absent');
    Route::get('/absent/getdata', 'Backend\AbsentController@getData')->name('backend.category.absent.getdata')->middleware('permission:category-absent');
    Route::post('/absent/edit', 'Backend\AbsentController@form')->name('backend.category.absent.edit')->where('id', '[0-9]+')->middleware('permission:category-absent-edit');
    Route::post('/absent/save', 'Backend\AbsentController@save')->name('backend.category.absent.save')->middleware('permission:category-absent-create|category-absent-edit');
    Route::post('/absent/remove', 'Backend\AbsentController@remove')->name('backend.category.absent.remove')->middleware('permission:category-absent-delete');
    Route::post('/absent/ajax-isopen-publish', 'Backend\AbsentController@ajaxIsopenPublish')->name('backend.category.absent.ajax_isopen_publish');

	 /* discipline */
    Route::get('/discipline', 'Backend\DisciplineController@index')->name('backend.category.discipline')->middleware('permission:category-discipline');
    Route::get('/discipline/getdata', 'Backend\DisciplineController@getData')->name('backend.category.discipline.getdata')->middleware('permission:category-discipline');
    Route::post('/discipline/edit', 'Backend\DisciplineController@form')->name('backend.category.discipline.edit')->where('id', '[0-9]+')->middleware('permission:category-discipline-edit');
    Route::post('/discipline/save', 'Backend\DisciplineController@save')->name('backend.category.discipline.save')->middleware('permission:category-discipline-create|category-discipline-edit');
    Route::post('/discipline/remove', 'Backend\DisciplineController@remove')->name('backend.category.discipline.remove')->middleware('permission:category-discipline-delete');
    Route::post('/discipline/ajax-isopen-publish', 'Backend\DisciplineController@ajaxIsopenPublish')->name('backend.category.discipline.ajax_isopen_publish');

	 /* Training Object */
    Route::get('/training-object', 'Backend\TrainingObjectController@index')->name('backend.category.training-object')->middleware('permission:category-training-object');
    Route::get('/training-object/getdata', 'Backend\TrainingObjectController@getData')->name('backend.category.training-object.getdata')->middleware('permission:category-training-object');
    Route::post('/training-object/edit', 'Backend\TrainingObjectController@form')->name('backend.category.training-object.edit')->where('id', '[0-9]+')->middleware('permission:category-training-object-edit');
    Route::post('/training-object/save', 'Backend\TrainingObjectController@save')->name('backend.category.training-object.save')->middleware('permission:category-training-object-create|category-training-object-edit');
    Route::post('/training-object/remove', 'Backend\TrainingObjectController@remove')->name('backend.category.training-object.remove')->middleware('permission:category-training-object-delete');
    Route::post('/training-object/ajax_isopen_publish', 'Backend\TrainingObjectController@ajaxIsopenPublish')->name('backend.category.training-object.ajax_isopen_publish');

	 /* Reason Absent */
     Route::get('/absent-reason', 'Backend\AbsentReasonController@index')->name('backend.category.absent-reason')->middleware('permission:category-absent-reason');
    Route::get('/absent-reason/getdata', 'Backend\AbsentReasonController@getData')->name('backend.category.absent-reason.getdata')->middleware('permission:category-absent-reason');
    Route::post('/absent-reason/edit', 'Backend\AbsentReasonController@form')->name('backend.category.absent-reason.edit')->where('id', '[0-9]+')->middleware('permission:category-absent-reason-edit');
    Route::post('/absent-reason/save', 'Backend\AbsentReasonController@save')->name('backend.category.absent-reason.save')->middleware('permission:category-absent-reason-create|category-absent-reason-edit');
    Route::post('/absent-reason/remove', 'Backend\AbsentReasonController@remove')->name('backend.category.absent-reason.remove')->middleware('permission:category-absent-reason-delete');
    Route::post('/discipline/ajax_isopen_publish', 'Backend\AbsentReasonController@ajaxIsopenPublish')->name('backend.category.absent-reason.ajax_isopen_publish');

    /* Chương trình đào tạo */
    Route::get('/training-program', 'Backend\TrainingProgramController@index')->name('backend.category.training_program')->middleware('permission:category-training-program');
    Route::get('/training-program/getdata', 'Backend\TrainingProgramController@getData')->name('backend.category.training_program.getdata')->middleware('permission:category-training-program');
    Route::post('/training-program/edit', 'Backend\TrainingProgramController@form')->name('backend.category.training_program.edit')->where('id', '[0-9]+')->middleware('permission:category-training-program-edit');
    Route::post('/training-program/save', 'Backend\TrainingProgramController@save')->name('backend.category.training_program.save')->middleware('permission:category-training-program-edit|category-training-program-create');
    Route::post('/training-program/remove', 'Backend\TrainingProgramController@remove')->name('backend.category.training_program.remove')->middleware('permission:category-training-program-delete');
    Route::get('/training-program/export', 'Backend\TrainingProgramController@export')->name('backend.category.training_program.export')->middleware('permission:category-training-program-export');
    Route::post('/training-program/import', 'Backend\TrainingProgramController@import')->name('backend.category.training_program.import')->middleware('permission:category-training-program-export');
    Route::post('/training-program/ajax_isopen_publish', 'Backend\TrainingProgramController@ajaxIsopenPublish')->name('backend.category.training_program.ajax_isopen_publish');

    /* Mảng nghiệp vụ/ Cấp độ */
    Route::get('/level-subject', 'Backend\LevelSubjectController@index')->name('backend.category.level_subject')->middleware('permission:category-level-subject');
    Route::get('/level-subject/getdata', 'Backend\LevelSubjectController@getData')->name('backend.category.level_subject.getdata')->middleware('permission:category-level-subject');
    Route::post('/level-subject/edit', 'Backend\LevelSubjectController@form')->name('backend.category.level_subject.edit')->where('id', '[0-9]+')->middleware('permission:category-level-subject-edit');
    Route::post('/level-subject/save', 'Backend\LevelSubjectController@save')->name('backend.category.level_subject.save')->middleware('permission:category-level-subject-create|category-level-subject-edit');
    Route::post('/level-subject/remove', 'Backend\LevelSubjectController@remove')->name('backend.category.level_subject.remove')->middleware('permission:category-level-subject-delete');
    Route::get('/level-subject/export', 'Backend\LevelSubjectController@export')->name('backend.category.level_subject.export')->middleware('permission:category-level-subject-export');
    Route::post('/level-subject/import', 'Backend\LevelSubjectController@import')->name('backend.category.level_subject.import')->middleware('permission:category-level-subject-export');
    Route::post('/level-subject/ajax_isopen_publish', 'Backend\LevelSubjectController@ajaxIsopenPublish')->name('backend.category.level_subject.ajax_isopen_publish');

    /* subject */
    Route::get('/subject', 'Backend\SubjectController@index')->name('backend.category.subject')->middleware('permission:category-subject');
    Route::get('/subject/getdata', 'Backend\SubjectController@getData')->name('backend.category.subject.getdata')->middleware('permission:category-subject');
    Route::post('/subject/edit', 'Backend\SubjectController@form')->name('backend.category.subject.edit')->where('id', '[0-9]+')->middleware('permission:category-subject');
    Route::post('/subject/save', 'Backend\SubjectController@save')->name('backend.category.subject.save')->middleware('permission:category-subject-create|category-subject-edit');
    Route::post('/subject/remove', 'Backend\SubjectController@remove')->name('backend.category.subject.remove')->middleware('permission:category-subject-delete');
    Route::post('/subject/import', 'Backend\SubjectController@import')->name('backend.category.subject.import')->middleware('permission:category-subject-import');
    Route::get('/subject/export', 'Backend\SubjectController@export')->name('backend.category.subject.export')->middleware('permission:category-subject-export');
    Route::post('/subject/ajax_isopen_publish', 'Backend\SubjectController@ajaxIsopenPublish')->name('backend.category.subject.ajax_isopen_publish');

    /* subject conditions */
    Route::get('/subject-conditions', 'Backend\SubjectConditionController@index')->name('backend.category.subject_conditions');
    Route::get('/subject-conditions/getdata', 'Backend\SubjectConditionController@getData')->name('backend.category.subject_conditions.getdata');
    Route::get('/subject-conditions/edit/{id}', 'Backend\SubjectConditionController@form')->name('backend.category.subject_conditions.edit')->where('id', '[0-9]+');
    Route::get('/subject-conditions/create', 'Backend\SubjectConditionController@form')->name('backend.category.subject_conditions.create');
    Route::post('/subject-conditions/save', 'Backend\SubjectConditionController@save')->name('backend.category.subject_conditions.save');

    /* training location */
    Route::get('/training-location', 'Backend\TrainingLocationController@index')->name('backend.category.training_location')->middleware('permission:category-training-location');
    Route::get('/training-location/getdata', 'Backend\TrainingLocationController@getData')->name('backend.category.training_location.getdata')->middleware('permission:category-training-location');
    Route::post('/training-location/edit', 'Backend\TrainingLocationController@form')->name('backend.category.training_location.edit')->where('id', '[0-9]+')->middleware('permission:category-training-location-edit');
    Route::post('/training-location/save', 'Backend\TrainingLocationController@save')->name('backend.category.training_location.save')->middleware('permission:category-training-location-create|category-training-location-edit');
    Route::post('/training-location/remove', 'Backend\TrainingLocationController@remove')->name('backend.category.training_location.remove')->middleware('permission:category-training-location-delete');
    Route::post('/training-location/ajax_isopen_publish', 'Backend\TrainingLocationController@ajaxIsopenPublish')->name('backend.category.training_location.ajax_isopen_publish');

    /* course categories */
    Route::get('/course-categories', 'Backend\CourseCategoriesController@index')->name('backend.category.course_categories');
    Route::get('/course-categories/getdata', 'Backend\CourseCategoriesController@getData')->name('backend.category.course_categories.getdata');
    Route::get('/course-categories/edit/{id}', 'Backend\CourseCategoriesController@form')->name('backend.category.course_categories.edit')->where('id', '[0-9]+');
    Route::get('/course-categories/create', 'Backend\CourseCategoriesController@form')->name('backend.category.course_categories.create');
    Route::post('/course-categories/save', 'Backend\CourseCategoriesController@save')->name('backend.category.course_categories.save');
    Route::post('/course-categories/remove', 'Backend\CourseCategoriesController@remove')->name('backend.category.course_categories.remove');

    /* LOẠI CHI PHÍ ĐÀO TẠO */
    Route::get('/type-cost', 'Backend\TypeCostController@index')->name('backend.category.type_cost');
    Route::get('/type-cost/getdata', 'Backend\TypeCostController@getData')->name('backend.category.type_cost.getdata');
    Route::post('/type-cost/edit', 'Backend\TypeCostController@form')->name('backend.category.type_cost.edit')->where('id', '[0-9]+');
    Route::post('/type-cost/save', 'Backend\TypeCostController@save')->name('backend.category.type_cost.save');
    Route::post('/type-cost/remove', 'Backend\TypeCostController@remove')->name('backend.category.type_cost.remove');

    /* training cost */
    Route::get('/training-cost', 'Backend\TrainingCostController@index')->name('backend.category.training_cost')->middleware('permission:category-training-cost');
    Route::get('/training-cost/getdata', 'Backend\TrainingCostController@getData')->name('backend.category.training_cost.getdata')->middleware('permission:category-training-cost');
    Route::post('/training-cost/edit', 'Backend\TrainingCostController@form')->name('backend.category.training_cost.edit')->where('id', '[0-9]+')->middleware('permission:category-training-cost-edit');
    Route::post('/training-cost/save', 'Backend\TrainingCostController@save')->name('backend.category.training_cost.save')->middleware('permission:category-training-cost-create|category-training-cost-edit');
    Route::post('/training-cost/remove', 'Backend\TrainingCostController@remove')->name('backend.category.training_cost.remove')->middleware('permission:category-training-cost-delete');

    /* student cost */
    Route::get('/student-cost', 'Backend\StudentCostController@index')->name('backend.category.student_cost')->middleware('permission:category-student-cost');
    Route::get('/student-cost/getdata', 'Backend\StudentCostController@getData')->name('backend.category.student_cost.getdata')->middleware('permission:category-student-cost');
    Route::post('/student-cost/edit', 'Backend\StudentCostController@form')->name('backend.category.student_cost.edit')->where('id', '[0-9]+')->middleware('permission:category-student-cost-edit');
    Route::post('/student-cost/save', 'Backend\StudentCostController@save')->name('backend.category.student_cost.save')->middleware('permission:category-student-cost-create|category-student-cost-edit');
    Route::post('/student-cost/remove', 'Backend\StudentCostController@remove')->name('backend.category.student_cost.remove')->middleware('permission:category-training-cost-delete');
    Route::post('/student-cost/ajax_isopen_publish', 'Backend\StudentCostController@ajaxIsopenPublish')->name('backend.category.student_cost.ajax_isopen_publish');

    /* commit month */
    Route::get('/commit-month', 'Backend\CommitMonthController@index')->name('backend.category.commit_month')->middleware('permission:commit-month');
    Route::get('/commit-month/getdata', 'Backend\CommitMonthController@getData')->name('backend.category.commit_month.getdata')->middleware('permission:commit-month');
    Route::post('/commit-month/edit', 'Backend\CommitMonthController@form')->name('backend.category.commit_month.edit')->where('id', '[0-9]+')->middleware('permission:commit-month-edit');
    Route::post('/commit-month/save', 'Backend\CommitMonthController@save')->name('backend.category.commit_month.save')->middleware('permission:commit-month-edit|commit-month-create');
    Route::post('/commit-month/saveGroup', 'Backend\CommitMonthController@saveGroup')->name('backend.category.commit_month.save_group')->middleware('permission:commit-month-edit|commit-month-create');
    Route::post('/commit-group/frame/modal', 'Backend\CommitMonthController@showModalFrameCommit')->name('backend.category.commit_month.modal')->middleware('permission:commit-month');
    Route::get('/commit-group/frame/{commit_group_id}', 'Backend\CommitMonthController@getDataFrame')->name('backend.category.commit_month.getdataframe')->middleware('permission:commit-month')->where('commit_group_id','[0-9]+');
    Route::get('/commit-group/frame/edit/{id}', 'Backend\CommitMonthController@getCommitFrame')->name('backend.category.commit_month.frame.edit')->where('id','[0-9]+')->middleware('permission:commit-month');
    Route::post('/commit-group/frame/delete', 'Backend\CommitMonthController@deleteCommitFrame')->name('backend.category.commit_month.frame.edit')->middleware('permission:commit-month');
    Route::post('/commit-month/remove', 'Backend\CommitMonthController@remove')->name('backend.category.commit_month.remove')->middleware('permission:commit-month-delete');

    /* training teacher */
    Route::get('/training-teacher', 'Backend\TrainingTeacherController@index')->name('backend.category.training_teacher')->middleware('permission:category-teacher');
    Route::get('/training-teacher/getdata', 'Backend\TrainingTeacherController@getData')->name('backend.category.training_teacher.getdata')->middleware('permission:category-teacher');
    Route::post('/training-teacher/edit', 'Backend\TrainingTeacherController@form')->name('backend.category.training_teacher.edit')->where('id', '[0-9]+')->middleware('permission:category-teacher-edit');
    Route::post('/training-teacher/save', 'Backend\TrainingTeacherController@save')->name('backend.category.training_teacher.save')->middleware('permission:category-teacher-create|category-teacher-edit');
    Route::post('/training-teacher/remove', 'Backend\TrainingTeacherController@remove')->name('backend.category.training_teacher.remove')->middleware('permission:category-teacher-delete');
    Route::post('/training-teacher/ajax-get-user', 'Backend\TrainingTeacherController@ajaxGetUser')->name('backend.category.ajax_get_user')->middleware('permission:category-teacher');
    Route::post('/training-teacher/import', 'Backend\TrainingTeacherController@import')->name('backend.category.training_teacher.import')->middleware('permission:category-teacher-import');
    Route::get('/training-teacher/export', 'Backend\TrainingTeacherController@export')->name('backend.category.training_teacher.export')->middleware('permission:category-teacher-export');

    /* Unit Type */
    Route::get('/unit-type', 'Backend\UnitTypeController@index')->name('backend.category.unit_type')->middleware('permission:category-unit-type');
    Route::get('/unit-type/getdata', 'Backend\UnitTypeController@getData')->name('backend.category.unit_type.getdata')->middleware('permission:category-unit-type');
    Route::post('/unit-type/edit', 'Backend\UnitTypeController@form')->name('backend.category.unit_type.edit')->where('id', '[0-9]+')->middleware('permission:category-unit-type-edit');
    Route::get('/unit-type/create', 'Backend\UnitTypeController@form')->name('backend.category.unit_type.create')->middleware('permission:category-unit-type-create');
    Route::post('/unit-type/save', 'Backend\UnitTypeController@save')->name('backend.category.unit_type.save')->middleware('permission:category-unit-type-create|category-unit-type-edit');
    Route::post('/unit-type/remove', 'Backend\UnitTypeController@remove')->name('backend.category.unit_type.remove')->middleware('permission:category-unit-type-delete');

    /* Training Partner */
    Route::get('/training-partner', 'Backend\TrainingPartnerController@index')->name('backend.category.training_partner')->middleware('permission:category-partner');
    Route::get('/training-partner/getdata', 'Backend\TrainingPartnerController@getData')->name('backend.category.training_partner.getdata')->middleware('permission:category-partner');
    Route::post('/training-partner/edit', 'Backend\TrainingPartnerController@form')->name('backend.category.training_partner.edit')->where('id', '[0-9]+')->middleware('permission:category-partner-edit');
    Route::post('/training-partner/save', 'Backend\TrainingPartnerController@save')->name('backend.category.training_partner.save')->middleware('permission:category-partner-create|category-partner-edit');
    Route::post('/training-partner/remove', 'Backend\TrainingPartnerController@remove')->name('backend.category.training_partner.remove')->middleware('permission:category-partner-delete');
    Route::get('/export-training-partner', 'Backend\TrainingPartnerController@exportTrainingPartner')->name('backend.training_partner_export')->middleware('permission:category-partner-export');

    /* Training Form */
    Route::get('/training-form', 'Backend\TrainingFormController@index')->name('backend.category.training_form')->middleware('permission:category-training-form');
    Route::get('/training-form/getdata', 'Backend\TrainingFormController@getData')->name('backend.category.training_form.getdata')->middleware('permission:category-training-form');
    Route::post('/training-form/edit', 'Backend\TrainingFormController@form')->name('backend.category.training_form.edit')->where('id', '[0-9]+')->middleware('permission:category-training-form-edit');
    Route::post('/training-form/save', 'Backend\TrainingFormController@save')->name('backend.category.training_form.save')->middleware('permission:category-training-form-edit|category-training-form-create');
    Route::post('/training-form/remove', 'Backend\TrainingFormController@remove')->name('backend.category.training_form.remove')->middleware('permission:category-training-form-delete');
    Route::post('/training-form/ajax_isopen_publish', 'Backend\TrainingFormController@ajaxIsopenPublish')->name('backend.category.training_form.ajax_isopen_publish');

    /* Teacher Type */
    Route::get('/teacher-type', 'Backend\TeacherTypeController@index')->name('backend.category.teacher_type')->middleware('permission:category-teacher-type');
    Route::get('/teacher-type/getdata', 'Backend\TeacherTypeController@getData')->name('backend.category.teacher_type.getdata')->middleware('permission:category-teacher-type');
    Route::post('/teacher-type/edit', 'Backend\TeacherTypeController@form')->name('backend.category.teacher_type.edit')->where('id', '[0-9]+')->middleware('permission:category-teacher-type-edit');
    Route::post('/teacher-type/save', 'Backend\TeacherTypeController@save')->name('backend.category.teacher_type.save')->middleware('permission:category-teacher-type-create|category-teacher-type-edit');
    Route::post('/teacher-type/remove', 'Backend\TeacherTypeController@remove')->name('backend.category.teacher_type.remove')->middleware('permission:category-teacher-type-delete');
    Route::post('/teacher-type/ajax_isopen_publish', 'Backend\TeacherTypeController@ajaxIsopenPublish')->name('backend.category.teacher_type.ajax_isopen_publish');

    /* Cost Lessons */
    Route::get('/cost-lessons', 'Backend\CostLessonsController@index')->name('backend.category.cost_lessons');
    Route::get('/cost-lessons/getdata', 'Backend\CostLessonsController@getData')->name('backend.category.cost_lessons.getdata');
    Route::get('/cost-lessons/edit/{id}', 'Backend\CostLessonsController@form')->name('backend.category.cost_lessons.edit')->where('id', '[0-9]+');
    Route::get('/cost-lessons/create', 'Backend\CostLessonsController@form')->name('backend.category.cost_lessons.create');
    Route::post('/cost-lessons/save', 'Backend\CostLessonsController@save')->name('backend.category.cost_lessons.save');
    Route::post('/cost-lessons/remove', 'Backend\CostLessonsController@remove')->name('backend.category.cost_lessons.remove');

    /* Cert */
    Route::get('/cert', 'Backend\CertController@index')->name('backend.category.cert');
    Route::get('/cert/getdata', 'Backend\CertController@getData')->name('backend.category.cert.getdata');
    Route::post('/cert/edit', 'Backend\CertController@form')->name('backend.category.cert.edit')->where('id', '[0-9]+');
    Route::post('/cert/save', 'Backend\CertController@save')->name('backend.category.cert.save');
    Route::post('/cert/remove', 'Backend\CertController@remove')->name('backend.category.cert.remove');

});

Route::group(['prefix' => '/admin-cp/permission'], function() {

    Route::get('/', 'Backend\PermissionController@index')->name('backend.permission');

    Route::get('/list-permisstion', 'Backend\PermissionController@listPermisstion')->name('backend.permission.list_permisstion');

    Route::get('/getdata', 'Backend\PermissionController@getDataPermission')->name('backend.permission.list_permisstion.getdata');

    Route::get('/detail/{permission_id}', 'Backend\PermissionController@detail')->name('backend.permission.detail')->where('permission_id', '[0-9]+');

    Route::get('/detail/{permission_id}/getdata', 'Backend\PermissionController@getDataPermissionUser')->name('backend.permission.detail.getdata')->where('permission_id', '[0-9]+');

    Route::get('/detail/{permission_id}/edit/{user_id}/{unit_id}', 'Backend\PermissionController@formUser')->name('backend.permission.detail.edit')->where('permission_id', '[0-9]+')->where('user_id', '[0-9]+')->where('unit_id', '[0-9]+');

    Route::get('/detail/{permission_id}/create', 'Backend\PermissionController@formUser')->name('backend.permission.detail.create')->where('permission_id', '[0-9]+');

    Route::post('/detail/{permission_id}/save', 'Backend\PermissionController@save')->name('backend.permission.detail.save')->where('permission_id', '[0-9]+');

    Route::post('/detail/{permission_id}/remove', 'Backend\PermissionController@remove')->name('backend.permission.detail.remove')->where('permission_id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/permission/group'], function() {

    Route::get('/', 'Backend\PermissionGroupController@index')->name('backend.permission_group');

    Route::get('/getdata', 'Backend\PermissionGroupController@getData')->name('backend.permission_group.getdata');

    Route::post('/save', 'Backend\PermissionGroupController@save')->name('backend.permission_group.save');

    Route::post('/remove', 'Backend\PermissionGroupController@remove')->name('backend.permission_group.remove');

    Route::post('/get-json', 'Backend\PermissionGroupController@getJson')->name('backend.permission_group.getjson');
});

Route::group(['prefix'=>'/admin-cp/evaluationform','middleware'=>'auth'],function(){
    Route::get('/', 'Backend\EvaluationFormController@index')->name('backend.evaluationform.manager');
});

Route::group(['prefix' => '/admin-cp/permission/unit'], function() {
    Route::get('/', 'Backend\UnitPermissionController@index')->name('backend.unit_permission');

    Route::get('/getdata', 'Backend\UnitPermissionController@getData')->name('backend.unit_permission.getdata');

    Route::post('/remove', 'Backend\UnitPermissionController@remove')->name('backend.unit_permission.remove');

    Route::get('/create', 'Backend\UnitPermissionController@form')->name('backend.unit_permission.create');

    Route::get('/edit/{id}', 'Backend\UnitPermissionController@form')->name('backend.unit_permission.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\UnitPermissionController@save')->name('backend.unit_permission.save');
});

Route::group(['prefix' => '/admin-cp/feedback'], function() {
    Route::get('/', 'Backend\FeedbackController@index')->name('backend.feedback');

    Route::get('/getdata', 'Backend\FeedbackController@getData')->name('backend.feedback.getdata');

    Route::post('/remove', 'Backend\FeedbackController@remove')->name('backend.feedback.remove');

    Route::get('/create', 'Backend\FeedbackController@form')->name('backend.feedback.create');

    Route::get('/edit/{id}', 'Backend\FeedbackController@form')->name('backend.feedback.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\FeedbackController@save')->name('backend.feedback.save');
});

Route::group(['prefix' => '/admin-cp/mail-template'], function() {
    Route::get('/', 'Backend\MailTemplateController@index')->name('backend.mailtemplate')->middleware('permission:mail-template');

    Route::get('/getdata', 'Backend\MailTemplateController@getData')->name('backend.mailtemplate.getdata')->middleware('permission:mail-template');

    Route::get('/edit/{id}', 'Backend\MailTemplateController@form')->name('backend.mailtemplate.edit')->where('id', '[0-9]+')->middleware('permission:mail-template-edit');

    Route::post('/save', 'Backend\MailTemplateController@save')->name('backend.mailtemplate.save')->middleware('permission:mail-template-edit');
});

Route::group(['prefix' => '/admin-cp/mail-signature'], function() {
    Route::get('/', 'Backend\MailSignatureController@index')->name('backend.mail_signature')->middleware('permission:mail-template');

    Route::get('/getdata', 'Backend\MailSignatureController@getData')->name('backend.mail_signature.getdata')->middleware('permission:mail-template');

    Route::get('/create', 'Backend\MailSignatureController@form')->name('backend.mail_signature.create')->middleware('permission:mail-template');

    Route::get('/edit/{id}', 'Backend\MailSignatureController@form')->name('backend.mail_signature.edit')->where('id', '[0-9]+')->middleware('permission:mail-template-edit');

    Route::post('/save', 'Backend\MailSignatureController@save')->name('backend.mail_signature.save')->middleware('permission:mail-template-edit');

    Route::post('/remove', 'Backend\MailSignatureController@remove')->name('backend.mail_signature.remove')->middleware('permission:mail-template-edit');
});

Route::group(['prefix' => '/admin-cp/mail-history'], function() {
    Route::get('/', 'Backend\MailHistoryController@index')->name('backend.mailhistory')->middleware('permission:mail-template-history');

    Route::get('/getdata', 'Backend\MailHistoryController@getData')->name('backend.mailhistory.getdata')->middleware('permission:mail-template-history');
});

Route::group(['prefix' => '/admin-cp/IHRP'], function() {
    Route::get('/', 'Backend\IHRPController@index')->name('backend.ihrp');

    Route::get('/template-1', 'Backend\IHRPController@template1')->name('backend.ihrp.template1');
    Route::get('/get-data-template-1', 'Backend\IHRPController@getdataTemplate1')->name('backend.ihrp.getdata_template1');
    Route::get('/export-template-1', 'Backend\IHRPController@exportTemplate1')->name('backend.ihrp.export_template1');

    Route::get('/template-2', 'Backend\IHRPController@template2')->name('backend.ihrp.template2');
    Route::get('/get-data-template-2', 'Backend\IHRPController@getdataTemplate2')->name('backend.ihrp.getdata_template2');
    Route::get('/export-template-2', 'Backend\IHRPController@exportTemplate2')->name('backend.ihrp.export_template2');

    Route::get('/template-3', 'Backend\IHRPController@template3')->name('backend.ihrp.template3');
    Route::get('/get-data-template-3', 'Backend\IHRPController@getdataTemplate3')->name('backend.ihrp.getdata_template3');
    Route::get('/export-template-3', 'Backend\IHRPController@exportTemplate3')->name('backend.ihrp.export_template3');
});

Route::group(['prefix' => '/admin-cp/donate-points'], function() {
    Route::get('/', 'Backend\DonatePointsController@index')->name('backend.donate_points')->middleware('permission:donate-point');

    Route::get('/getdata', 'Backend\DonatePointsController@getData')->name('backend.donate_points.getdata')->middleware('permission:donate-point');

    Route::post('/remove', 'Backend\DonatePointsController@remove')->name('backend.donate_points.remove')->middleware('permission:donate-point-delete');

    Route::post('/edit', 'Backend\DonatePointsController@form')->name('backend.donate_points.edit')->where('id', '[0-9]+')->middleware('permission:donate-point-edit');

    Route::post('/save', 'Backend\DonatePointsController@save')->name('backend.donate_points.save')->middleware('permission:donate-point-edit|donate-point-create');

    Route::post('/get-title-unit-ajax', 'Backend\DonatePointsController@getTitleUnit')->name('backend.donate_points.get_title_unit')->middleware('permission:donate-point');

    Route::post('/import-donate-points', 'Backend\DonatePointsController@import_donate_points')->name('backend.donate_points.import')->middleware('permission:donate-point-import');

    Route::get('/export-donate-points', 'Backend\DonatePointsController@export_donate_points')->name('backend.donate_points.export')->middleware('permission:donate-point-export');
});

Route::group(['prefix'=> '/admin-cp/app-mobile'], function () {
    Route::get('/', 'Backend\AppMobileController@index')->name('backend.app_mobile')->middleware('permission:config-app-mobile');
    Route::post('/save', 'Backend\AppMobileController@save')->name('backend.app_mobile.save')->middleware('permission:config-app-mobile-save');
});

Route::group(['prefix'=> '/admin-cp/config'], function () {
    Route::get('/', 'Backend\ConfigController@index')->name('backend.config')->middleware('permission:config');

    Route::get('/get-form', 'Backend\ConfigController@load')->name('backend.config.get-form')->middleware('permission:config');

    Route::post('/save', 'Backend\ConfigController@save')->name('backend.config.save')->middleware('permission:config-save');
});

// GHI CHÚ NGƯỜI DÙNG
Route::group(['prefix' => '/admin-cp/note'], function() {

    Route::get('/', 'Backend\NoteController@index')->name('backend.note');

    Route::get('/getdata', 'Backend\NoteController@getData')->name('backend.note.getdata');
});

// LỊCH SỬ TRUY CẬP
Route::group(['prefix' => '/admin-cp/login-history'], function() {

    Route::get('/', 'Backend\LoginHistoryController@index')->name('backend.login-history');

    Route::get('/getdata', 'Backend\LoginHistoryController@getData')->name('backend.login-history.getdata');
});

// NGƯỜI DÙNG LIÊN HỆ
Route::group(['prefix' => '/admin-cp/user-contact'], function() {

    Route::get('/', 'Backend\UserContactOutsideController@index')->name('backend.user-contact');

    Route::get('/getdata', 'Backend\UserContactOutsideController@getData')->name('backend.user-contact.getdata');

    Route::post('/remove', 'Backend\UserContactOutsideController@remove')->name('backend.user-contact.remove');
});

// CHƯƠNG TRÌNH THI ĐUA
Route::group(['prefix' => '/admin-cp/emulation-program'], function() {

    Route::get('/', 'Backend\EmulationProgramController@index')->name('backend.emulation_program');

    Route::get('/getdata', 'Backend\EmulationProgramController@getData')->name('backend.emulation_program.getdata');

    Route::get('/edit/{id}', 'Backend\EmulationProgramController@form')->name('backend.emulation_program.edit')->where('id', '[0-9]+');

    Route::get('/create', 'Backend\EmulationProgramController@form')->name('backend.emulation_program.create');

    Route::post('/save', 'Backend\EmulationProgramController@save')->name('backend.emulation_program.save');

    Route::post('/remove', 'Backend\EmulationProgramController@remove')->name('backend.emulation_program.remove');

    Route::post('/approve', 'Backend\EmulationProgramController@approve')->name('backend.emulation_program.approve');

    Route::post('/ajax-isopen-publish', 'Backend\EmulationProgramController@ajaxIsopenPublish')->name('backend.emulation_program.ajax_isopen_publish');

    Route::get('/export', 'Backend\EmulationProgramController@export')->name('backend.emulation_program.export');

    Route::get('/result-emulation/{id}', 'Backend\EmulationProgramController@resultEmulation')->name('backend.emulation_program.result_emulation');

    Route::get('/get-data-result-emulation/{id}', 'Backend\EmulationProgramController@getDataResultEmulation')->name('backend.emulation_program.get_data_result_emulation');

    // HÚY HIỆU
    Route::post('/save-armorial/{id}', 'Backend\EmulationProgramController@saveArmorial')->name('backend.emulation_program.save_armorial');

    // ĐỐI TƯỢNG
    Route::post('/edit/{id}/save-object', 'Backend\EmulationProgramController@saveObject')
        ->name('backend.emulation_program.save_object')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-object', 'Backend\EmulationProgramController@getObject')
        ->name('backend.emulation_program.get_object')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-user-object', 'Backend\EmulationProgramController@getUserObject')
        ->name('backend.emulation_program.get_user_object')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-object', 'Backend\EmulationProgramController@removeObject')
        ->name('backend.emulation_program.remove_object')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/import-object', 'Backend\EmulationProgramController@importObject')
        ->name('backend.emulation_program.import_object')
        ->where('id', '[0-9]+');

    // ĐIỀU KIỆN
    Route::post('/edit/{id}/save-condition', 'Backend\EmulationProgramController@saveCondition')
        ->name('backend.emulation_program.save_condition')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-course-condition/{type}', 'Backend\EmulationProgramController@getCourse')
        ->name('backend.emulation_program.get_course')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-quiz-condition', 'Backend\EmulationProgramController@getQuiz')
        ->name('backend.emulation_program.get_quiz')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-condition/{type}', 'Backend\EmulationProgramController@removeConditon')
        ->name('backend.emulation_program.remove_conditon')
        ->where('id', '[0-9]+');
});
//
Route::group(['prefix' => '/admin-cp/check-select-unit'], function() {
   Route::post('/', 'Controller@checkSelectUnit')->name('backend.check_select_unit');
    Route::post('/save', 'Controller@saveSelectUnit')->name('backend.save_select_unit');
});
Route::group(['prefix' => '/admin-cp/approve'], function() {
    Route::post('/', 'Controller@approve')->name('backend.approve.model');
    Route::post('/modal-note-approved', 'Controller@showModalNoteApproved')->name('backend.show.modal_note_approved');
    Route::post('/modal-step-approved', 'Controller@showModalStepApproved')->name('backend.show.modal_step_approved');
    Route::get('/get-approved-step', 'Controller@getApprovedStep')->name('backend.get_approved_step'); //where('model_id','[0-9]+');
});

Route::group(['prefix' => '/admin-cp/languages'], function() {
    Route::get('/', 'Backend\LanguagesController@index')->name('backend.languages');

    Route::get('/{id}/getdata', 'Backend\LanguagesController@getData')->name('backend.languages.getdata')->where('id', '[0-9]+');

    Route::post('/remove', 'Backend\LanguagesController@remove')->name('backend.languages.remove');

    Route::get('/{id}/create', 'Backend\LanguagesController@form')->name('backend.languages.create')->where('id', '[0-9]+');

    Route::get('/{idg}/edit/{id}', 'Backend\LanguagesController@form')->name('backend.languages.edit')->where('id', '[0-9]+');

    Route::get('/{id}', 'Backend\LanguagesController@index')->name('backend.languages.group')->where('id', '[0-9]+');

    Route::post('/{id}/save', 'Backend\LanguagesController@save')->name('backend.languages.save')->where('id', '[0-9]+');

    Route::get('/synchronize', 'Backend\LanguagesController@synchronize')->name('backend.languages.synchronize');
    Route::get('/export', 'Backend\LanguagesController@export')->name('backend.languages.export');
    Route::get('/export_file', 'Backend\LanguagesController@export_file')->name('backend.languages.export_file');

    Route::post('/languages/import', 'Backend\LanguagesController@import_languages')->name('backend.languages.import');

    Route::post('/get_modal', 'Backend\LanguagesController@showModal')->name('backend.languages.get_modal');

    Route::post('/save_group', 'Backend\LanguagesController@saveGroup')->name('backend.languages.save_group');


});
