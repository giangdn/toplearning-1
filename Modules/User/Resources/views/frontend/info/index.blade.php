<div class="tab-pane fade show active" id="nav-about" role="tabpanel">
    <div class="row mb-5" id="user-info">
        <div class="col-xl-3 col-lg-8">
            <div class="fcrse_2">
                <div class="info-avatar p-0">
                    <a href="javascript:void(0)" id="change-avatar">
                        <img src="{{ image_file(\App\Profile::avatar()) }}" alt="">
                    </a>
                </div>
                <div class="tutor_content_dt mb-0">
                    <div class="">
                        <a href="#" class="tutor_name">{{ $user->lastname." ".$user->firstname }} </a>
                        {{-- <div class="mef78" title="Verify">
                            <a href="javascript:void(0)" id="change-pass"> ({{ trans('app.change_pass') }})</a>
                        </div> --}}
                    </div>
                    @if($promotion)
                        @if(!empty($promotion_level))
                            <h5 class="rainbow rainbow_text_animated mt-3">{{ $promotion_level->name }}</h5>
                        @endif
                        <div class="tutor_cate">
                            <div id="shadowBox">
                                <div class="title-name">
                                    <span class="point">{{ $promotion->point }}</span>
                                    <img class="img_point" src="{{ asset('images/level/point.png') }}" alt="">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @if(!empty($promotion_level))
                <div class="fcrse_2">
                    <div class="info-avatar p-0">
                        <img src="{{ $promotion_level->images }}" alt="">
                    </div>
                </div>
            @endif
        </div>
        <div class="col-xl-9 col-lg-8">
            <div class="fcrse_2">
                <div class="_htg451">
                    <div class="_htg452">
                        <ul class="_ttl120_custom row">
                            <li class="w-auto col-lg-4 col-sm-12 m-auto">
                                <div class="visible-print text-center mr-2 mt-1">
                                    {!! QrCode::size(120)->generate($info_qrcode); !!}
                                    <p class="qr_scan">@lang('app.scan_code_infomation')</p>
                                </div>
                            </li>
                            <li class="col-lg-4 col-sm-12">
                                <div class="_ttl121_custom">
                                    <div class="_ttl123_custom mt-0">@lang('app.login_code'):
                                        <span class="_ttl122_custom">
                                            {{ $user_name }}
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">@lang('app.employee_code'):
                                        <span class="_ttl122_custom">
                                            {{ $user->code }}
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">@lang('app.full_name'):
                                        <span class="_ttl122_custom">
                                            {{ $user->lastname .' '. $user->firstname }}
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">@lang('app.dob'):
                                        <span class="_ttl122_custom">
                                            {{ get_date($user->dob) }}
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">@lang('app.gender'):
                                        <span class="_ttl122_custom">
                                            {{ $user->gender == 1 ? 'Nam' : 'Nữ' }}
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">@lang('app.title'):
                                        <span class="_ttl122_custom">
                                             @if(isset($title->name)) {{ $title->name }} @endif
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">@lang('backend.day_work'):
                                        <span class="_ttl122_custom">
                                            {{ get_date($user->join_company) }}
                                        </span>
                                    </div>
                                    {{-- <div class="_ttl123_custom">
                                        @lang('app.permanent_residence'):
                                        <span class="_ttl122_custom">
                                            {{ $user->address }}
                                        </span>
                                    </div> --}}
                                    <div class="_ttl123_custom">
                                        {{-- <a href="javascript:void(0)" class="change-info" data-key="current_address" data-value-old="{{ !is_null($user_meta('current_address')) ? $user_meta('current_address')->value : '' }}"><i class="fa fa-edit"></i></a> --}}
                                        @lang('app.current_address'):
                                        <span class="_ttl122_custom">
                                            {{ !is_null($user_meta('current_address')) ? $user_meta('current_address')->value : '' }}
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">
                                        {{-- <a href="javascript:void(0)" class="change-info" data-key="phone" data-value-old="{{ $user->phone }}"><i class="fa fa-edit"></i></a> --}}
                                        @lang('app.phone'):
                                        <span class="_ttl122_custom">
                                            {{ $user->phone }}
                                        </span>
                                    </div>
                                    <div class="_ttl123_custom">
                                        {{-- <a href="javascript:void(0)" class="change-info" data-key="email" data-value-old="{{ $user->email }}"><i class="fa fa-edit"></i></a> --}}
                                        Email:
                                        <span class="_ttl122_custom">
                                            {{ $user->email }}
                                        </span>
                                    </div>
                                    {{-- <div class="_ttl123_custom">
                                        <a href="javascript:void(0)" class="change-info" data-key="name_contact_person" data-value-old="{{ !is_null($user_meta('name_contact_person')) ? $user_meta('name_contact_person')->value : '' }}"><i class="fa fa-edit"></i></a>
                                        @lang('app.name_contact_person'):
                                        <span class="_ttl122_custom">
                                            {{ !is_null($user_meta('name_contact_person')) ? $user_meta('name_contact_person')->value : '' }}
                                        </span>
                                    </div> --}}
                                    {{-- <div class="_ttl123_custom">
                                        <a href="javascript:void(0)" class="change-info" data-key="phone_contact_person" data-value-old="{{ !is_null($user_meta('phone_contact_person')) ? $user_meta('phone_contact_person')->value : '' }}"><i class="fa fa-edit"></i></a>
                                        @lang('app.phone_contact_person'):
                                        <span class="_ttl122_custom">
                                            {{ !is_null($user_meta('phone_contact_person')) ? $user_meta('phone_contact_person')->value : '' }}
                                        </span>
                                    </div> --}}
                                    <div class="_ttl123_custom">@lang('app.code'):
                                        <span class="_ttl122_custom">
                                             {{ $user->id_code }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                            <li class=" col-lg-4 col-sm-12">
                                <div class="_ttl121_custom">
                                    @for($i=1; $i<=5; $i++)
                                        <div class="_ttl123_custom {{ $i == 1 ? 'mt-0' : '' }}">
                                            @lang('app.unit') {{$i}}  :
                                            @if(isset($unit[$i]))
                                                <span class="_ttl122_custom">{{ $unit[$i]->name }}</span>
                                            @endif
                                        </div>
                                    @endfor
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-change-avatar" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form action="{{ route('module.frontend.user.change_avatar') }}" method="post" id="form-change-avatar" enctype="multipart/form-data" class="form-ajax">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Đổi ảnh đại diện</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <div class="show-demo">
                        <img src="" width="100"/>
                    </div>
                    <div class="text-center">
                        <input type="file" name="selectavatar" accept="image/*">
                        <br/><em>Kích thước đề nghị: 100x100px</em>
                    </div>
                    <div id="error-msg" class="alert-danger">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{trans('backend.save')}}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('backend.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="modal-change-pass" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form action="{{ route('module.frontend.user.change_pass') }}" method="post" id="form-change-pass" enctype="multipart/form-data" class="form-ajax">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('app.change_pass')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-4 control-label">
                            <label for="password_old">@lang('app.old_password')</label>
                        </div>
                        <div class="col-md-8">
                            <input name="password_old" id="password-old" type="password" class="form-control" value="" placeholder="@lang('app.old_password')" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4 control-label">
                            <label for="password">@lang('app.new_password')</label>
                        </div>
                        <div class="col-md-8">
                            <input name="password" id="password" type="password" class="form-control" value="" placeholder="@lang('app.password')" autocomplete="off" required>
                            <p></p>
                            <input name="repassword" id="repassword" type="password" class="form-control" value="" placeholder="@lang('app.confirm_password')" autocomplete="off" required>
                        </div>
                    </div>
                    <div id="error-msg-pass" class="alert-danger">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{trans('backend.save')}}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('backend.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="modal-change-info" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('module.frontend.user.change_info') }}" method="post" id="form-change-info" enctype="multipart/form-data" class="form-ajax">
            @csrf
            <input type="hidden" name="key" value="">
            <input type="hidden" name="value_old" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Đổi thông tin</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="value_new">Giá trị cũ</label>
                        </div>
                        <div class="col-md-8" id="value-old"></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="value_new">Giá trị thay đổi</label>
                        </div>
                        <div class="col-md-8">
                            <textarea name="value_new" id="value-new" type="text" class="form-control" placeholder="Giá trị thay đổi" autocomplete="off" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="value_new">{{ trans('backend.note') }}</label>
                        </div>
                        <div class="col-md-8">
                            <textarea name="note" id="note" type="text" class="form-control" placeholder="{{ trans('backend.note') }}" autocomplete="off"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{trans('backend.save')}}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('backend.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $('#user-info').on('click', '.change-info', function (event) {
        event.preventDefault();
        var key = $(this).data('key');
        var value_old = $(this).data('value-old');

        $('#modal-change-info #value-old').text(value_old).trigger('change');
        $('#modal-change-info input[name=value_old]').val(value_old).trigger('change');
        $('#modal-change-info input[name=key]').val(key).trigger('change');

        $('#modal-change-info').modal();
        return false;
    });

    $("#change-avatar").on('click', function (event) {
        event.preventDefault();
        $("#modal-change-avatar").modal();
        return false;
    });

    $("#change-pass").on('click', function (event) {
        event.preventDefault();
        $("#modal-change-pass").modal();
        return false;
    });
</script>
