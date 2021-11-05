@extends('layouts.app')

@section('page_title', 'Thông tin tài khoản')
@section('header')
    <script src="{{ asset('styles/module/user/js/user.js') }}"></script>
@endsection
@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">Thông tin tài khoản</span>
        </h2>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb" style="background: white;margin-bottom: 0;">
            <li><a href="/"><i class="glyphicon glyphicon-home"></i> &nbsp;Trang chủ</a></li>
            <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">&raquo;</li>
            <li><span>Thông tin học viên</span></li>
        </ol>
        @include('user::frontend.layout.menu')
        <div class="tab-content" style="background: #f7f7f7; border: 1px solid #cdcdcd; box-shadow: 0 2px 2px -2px #ccc; position: relative; margin-bottom: 50px; padding-top:10px">
            <div class="row">
                <div class="col-sm-3 text-center">
                    <div class="profile-image">
                        <img src="{{ Config::get('app.datafile.wwwfiledata').'/uploads/profile/'.$user->avatar }}" style="width: 150px; height: 150px">
                    </div>
                    <div class="edit m-2">
                        <a href="javascript:void(0)" id="change-avatar">Đổi ảnh</a> / <a href="javascript:void(0)" id="change-pass"> Đổi mật khẩu</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6 mod-scb-profile">
                            <div class="form-group row">
                                <div class="col-4 col-sm-4 control-label p-0">
                                    {{trans("backend.user")}}
                                </div>
                                <div class="col-8 col-sm-8 control-content">
                                    {{ $user->code . ' - ' . $user->lastname .' '. $user->firstname }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-4 col-sm-4 control-label p-0">
                                    Giới tính
                                </div>
                                <div class="col-8 col-sm-8 control-content">
                                    {{ ($user->gender == 1) ? 'Nam' : 'Nữ' }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-4 col-sm-4 control-label p-0">
                                    Năm sinh
                                </div>
                                <div class="col-8 col-sm-8 control-content">
                                    {{ get_date($user->dob) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-4 col-sm-4 control-label p-0">
                                    Số điện thoại
                                </div>
                                <div class="col-8 col-sm-8 control-content"> {{ $user->phone }} </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-4 col-sm-4 control-label p-0">
                                    Email
                                </div>
                                <div class="col-8 col-sm-8 control-content"> {{ $user->email }} </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-4 col-sm-4 control-label p-0">
                                    Chức danh
                                </div>
                                <div class="col-8 col-sm-8 control-content">
                                    @if(isset($title->name)) {{ $title->name }} @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-md-offset-1 mod-scb-profile">
                            @for($i=1; $i<=$max_unit; $i++)
                                <div class="form-group row">
                                    <div class="col-4 col-sm-4 control-label p-0">
                                        {{ data_locale($level_name($i)->name, $level_name($i)->name_en) }}
                                    </div>
                                    <div class="col-8 col-sm-8 control-content">
                                        @if(isset($unit[$i]))
                                            {{ $unit[$i]->name }}
                                        @endif
                                    </div>
                                </div>
                            @endfor
                            <div class="form-group row">
                                <div class="col-4 col-sm-4 control-label p-0">
                                    Trạng thái
                                </div>
                                <div class="col-8 col-sm-8 control-content">
                                    @switch($user->status)
                                        @case(0) Nghỉ việc @break;
                                        @case(1) Đang làm @break;
                                        @case(2) Thử việc @break;
                                        @case(3) Tạm hoãn @break;
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal-change-avatar" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <form action="{{ route('module.frontend.user.change_avatar') }}" method="post" id="form-change-avatar" enctype="multipart/form-data">
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
                <form action="{{ route('module.frontend.user.change_pass') }}" method="post" id="form-change-pass" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Đổi mật khẩu</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-4 control-label">
                                    <label for="password_old">Mật khẩu cũ</label>
                                </div>
                                <div class="col-md-8">
                                    <input name="password_old" id="password-old" type="password" class="form-control" value="" placeholder="Mật khẩu cũ" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4 control-label">
                                    <label for="password">Mật khẩu mới</label>
                                </div>
                                <div class="col-md-8">
                                    <input name="password" id="password" type="password" class="form-control" value="" placeholder="{{trans('backend.pass')}}" autocomplete="off" required>
                                    <p></p>
                                    <input name="repassword" id="repassword" type="password" class="form-control" value="" placeholder="{{trans('backend.repassword')}}" autocomplete="off" required>
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
    </div>
@endsection
