<header class="header clearfix top_menu">
    @php
        $user_id = getUserId();
        $user_type = getUserType();
        $user_secondary = \Modules\Quiz\Entities\QuizUserSecondary::find($user_id);

        \App\LogoModel::addGlobalScope(new \App\Scopes\CompanyScope());
        $logo = \App\LogoModel::where('status',1)->first();
        // dd($logo);
        $get_unit =  \App\ProfileView::where('user_id', $user_id)->first();
    @endphp
    {{-- @foreach ($logos as $key => $logo) --}}
        @php
            $check_unit = 0;
            if (!empty($logo->object)) {
                $check_objects = json_decode($logo->object);
                foreach ($check_objects as $check_object) {
                    $unit_code = \App\Models\Categories\Unit::find($check_object);
                    $get_array_childs = \App\Models\Categories\Unit::getArrayChild($unit_code->code);
                    if( in_array($get_unit->unit_id, $get_array_childs) || ($get_unit->unit_id == $unit_code->id) ) {
                        $check_unit = 1;
                    }
                }
            }
        @endphp
        @if ( (!empty($logo->object) && $check_unit == 1) || empty($logo->object))
            <div class="main_logo" id="logo" style="text-align: center;">
                <a href="/" class="w-100"><img src="{{ image_file(@$logo->image) }}" alt=""></a>
            </div>
        @endif
    {{-- @endforeach --}}

    <div class="header_right">
        <ul>
         <li class="text-center mr-2">
                @if(App::getLocale() == 'en')
                    <a href="{{ route('change_language',['language'=>'vi']) }}" class="" data-turbolinks="false"><img src="{{ asset('images/i_flag_vietnam.png') }}" alt="">
                        <br> @lang('app.vietnamese')</a>
                @else
                    <a href="{{ route('change_language',['language'=>'en']) }}" class="" data-turbolinks="false"><img src="{{ asset('images/i_flag_england.png') }}" alt="">
                        <br> @lang('app.english')</a>
                @endif
            </li>
            <li>
                <a href="{{ route('home_outside',['type' => 1]) }}" class="btn mr-2">@lang('app.home_page')</a>
            </li>
            @if($user_type == 1)
            <li class="ui dropdown">
                <a href="#" class="option_links"><i class='uil uil-bell'></i>
                    @php
                        $count_noty = \Modules\Notify\Entities\NotifySend::countMessage();
                    @endphp
                    <span class="noti_count">{{ $count_noty > 99 ? '99+' : $count_noty }}</span>
                </a>
                <div class="menu dropdown_mn w-auto" style="max-height: 500px; overflow-y: auto;">
                    @php
                        $notify = \Modules\Notify\Entities\NotifySend::getNotifyNew();
                    @endphp
                    @if ($notify->count() > 0)
                        @foreach($notify as $note)
                            <div class="channel_my item all__noti5">
                                <div class="profile_link">
                                    @if($note->important == 1)
                                        <i class="uil uil-star text-warning"></i>
                                    @endif
                                    <div class="pd_content">
                                        <h6>
                                            <a href="{{ route('module.notify.view', ['id' => $note->id, 'type' => $note->type]) }}">
                                               <span class="{{ $note->viewed == 1 ? 'text-black-50' : 'text-black font-weight-bold' }}" >
                                                   {{ $note->subject }}
                                                   @if ($note->viewed != 1) <img src="{{ asset('images/new.png') }}" align="" style="width: 30px; height: 30px;"> @endif
                                               </span>
                                            </a>
                                        </h6>
                                        <span class="nm_time">
                                            {{ \Illuminate\Support\Carbon::parse($note->created_at)->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <a class="vbm_btn" href="{{ route('module.notify.index') }}">View All <i class='uil uil-arrow-right'></i></a>
                </div>
            </li>
            <li class="mx-2">
                @php
                    $promotion_user_point = \Modules\Promotion\Entities\PromotionUserPoint::whereUserId(\Auth::id())->first();
                @endphp
                {{ $promotion_user_point ? $promotion_user_point->point : 0 }} <img src="{{ asset('images/level/point.png') }}" alt="" style="width: 20px; height: 20px;">
            </li>
            @endif
            <li class="mx-2 name_user">
                @php
                    if ($user_type == 1) {
                        $profile = \App\Profile::where('user_id',Auth::id())->first();
                    }

                    $t = date('H:i');
                    $get_id_setting_object = '';
                    $get_time = '';
                    $check_all = \App\SettingTimeObjectModel::where('object','All')->first();
                    $get_objects = \App\SettingTimeObjectModel::where('object','!=','All')->get();
                    foreach ($get_objects as $key => $get_object) {
                        $objects = json_decode($get_object->object);
                        if (in_array($profile->unit_id, $objects)) {
                            $get_id_setting_object = $get_object->id;
                        }
                    }
                    if ($check_all && !$get_id_setting_object) {
                        $get_time = \App\SettingTimeModel::where('object',$check_all->id)->where('start_time','<=',$t)->where('end_time','>=',$t)->first();
                    } elseif ($get_id_setting_object) {
                        $get_time = \App\SettingTimeModel::where('object',$get_id_setting_object)->where('start_time','<=',$t)->where('end_time','>=',$t)->first();
                    }
                @endphp
                @if (!empty($get_time))
                    <span>{{ $get_time->value }},</span>
                @endif
                <span><strong>{{ $user_type == 1 ? $profile->firstname : @$user_secondary->name }}</strong></span>
            </li>
            <li class="ui dropdown">
                <a href="javascript:void(0)" class="opts_account">
                    <img src="{{ $user_type == 1 ? \App\Profile::avatar() : asset('/images/user_tt.png') }}" alt="">
                </a>
                <div class="menu dropdown_account">
                    <div class="channel_my">
                        @if($user_type == 1)
                        <div class="profile_link">
                            <img src="{{ \App\Profile::avatar() }}" alt="">
                            <div class="pd_content">
                                <div class="rhte85">
                                    <h6>{{ \App\Profile::fullname() }}</h6>
                                    <div class="mef78" title="Verify">
                                        <i class='uil uil-check-circle'></i>
                                    </div>
                                </div>
                                <span>{{ \App\Profile::email() }}</span>
                            </div>
                        </div>
                        <a href="{{ route('module.frontend.user.info') }}" class="dp_link_12">@lang('app.user_info')</a>
                            @if(\App\Permission::isAdmin() || \App\Profile::hasRole() || \App\Permission::isUnitManager())
                                @if(\App\Permission::isUnitManager())
                                    <a href="{{ route('module.dashboard_unit') }}" class="dp_link_12 item channel_item" data-turbolinks="false">@lang('app.admin_panel')</a>
                                @else
                                    <a href="{{ route('module.dashboard') }}" class="dp_link_12 item channel_item" data-turbolinks="false">@lang('app.admin_panel')</a>
                                @endif
                            @elseif (Auth::user()->isTeacher() && !\App\Permission::isAdmin())
                                <a href="{{ route('module.quiz.grading') }}" class="dp_link_12 item channel_item" data-turbolinks="false">@lang('backend.exam_grading')</a>
                            @endif
                        @endif
                        <a href="{{ route('logout') }}" class="dp_link_12 item channel_item">@lang('app.logout')</a>
                    </div>

                    {{-- <div class="night_mode_switch__btn">
                        <a href="#" id="night-mode" class="btn-night-mode">
                            <i class="uil uil-moon"></i> Night mode
                            <span class="btn-night-mode-switch">
                                <span class="uk-switch-button"></span>
                            </span>
                        </a>
                    </div> --}}
                </div>
            </li>
        </ul>
    </div>
</header>
