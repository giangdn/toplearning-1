<header class="header clearfix top_menu_backend">
    @php
        \App\LogoModel::addGlobalScope(new \App\Scopes\CompanyScope());

        $logo = \App\LogoModel::where('status',1)->first();
        $user_id = \Auth::id();
        $get_unit =  \App\ProfileView::where('user_id', $user_id)->first();
        $check_unit = 0;
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
            @if (count($userUnits)>1)
                <li>
                    <select class="form-control" name="user-unit" id="user-unit-top" role="button" data-url="{{route('backend.save_select_unit')}}">
                        @foreach($userUnits as $index =>$item)
                            {{$selected = $item->id==session('user_unit')?'selected':''}}
                            <option value="{{$item->id}}" {{$selected}}>{{$item->name}}  - {{$item->code}}</option>
                        @endforeach
                    </select>
                </li>
            @endif
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
                                            <a href="{{ route('module.notify.view', ['id' => $note->id, 'type' => $note->type]) }}" >
                                               <span class="{{ $note->viewed == 1 ? 'text-black-50' : 'text-black font-weight-bold' }}">
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
            <li class="mx-2 name_user">
                @php
                    $profile = \App\Profile::where('user_id',Auth::id())->first();
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
                <span><strong>{{$profile->firstname}}</strong></span>
            </li>
            <li class="ui dropdown">
                <a href="javascript:void(0)" class="opts_account">
                    <img src="{{ image_file(\App\Profile::avatar()) }}" alt="">
                </a>
                <div class="menu dropdown_account">
                    <div class="channel_my">
                        <div class="profile_link">
                            <img src="{{ image_file(\App\Profile::avatar()) }}" alt="">
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
<div class="modal fade " data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" id="modal-select-unit" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chọn vai trò</h4>
            </div>
            <form action="{{route('backend.save_select_unit')}}" id="frm-course" method="post" class="form-ajax">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 block-left">
                            <label>Chọn vai trò cần thực hiện</label>
                        </div>
                        <div class="col-md-7">
                            <select class="form-control" name="unit-select" role="button">
                                @foreach($userUnits as $index =>$item)
                                    <option value="{{$item->id}}">{{$item->name}} - {{$item->code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id=""><i class="fa fa-check-circle"></i> Chọn</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    let url_modal_unit = '{{route('backend.check_select_unit')}}';
    $(window).on('load',function() {
        $.ajax({
            url: url_modal_unit,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'post',
            data: {},
        }).done(function(data) {
            if(data.modal)
                $('#modal-select-unit').modal();
            return false;
        }).fail(function(data) {
            return false;
        });

    });
</script>
