@extends('layouts.app')

@section('page_title', 'Đề xuất kế hoạch đào tạo tháng')
@section('header')
    <link rel="stylesheet" href="{{ asset('styles/vendor/sweetalert2/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/module/user/css/user.css') }}">
    <script src="{{ asset('styles/module/user/js/plan_suggest.js') }}"></script>
@endsection
@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">{{trans('backend.propose_training_plan')}}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb" style="background: white;margin-bottom: 0;">
            <li><a href="/"><i class="glyphicon glyphicon-home"></i> &nbsp;Trang chủ</a></li>
            <li style="padding-left: 0px; color: #717171; padding-right: 0; font-weight: 700;">&raquo;</li>
            <li><span><a href="{{route('module.frontend.user.info')}}">Thông tin học viên</a></span></li>
            <li style="padding-left: 0px; color: #717171; padding-right: 0; font-weight: 700;">&raquo;</li>
            <li><span>Quá trình đào tạo</span></li>
        </ol>
        @include('user::frontend.layout.menu')
        <div class="row pb-1">
            <div class="col-md-12">
                <div class="btn-group pull-right">
                    <button name="btnCreate" data-url-save="{{route('module.frontend.user.plan_suggest.save')}}" data-url-create="{{route('module.frontend.user.plan_suggest.form.create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                    <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="dg" class="tDefault table table-hover table-bordered bootstrap-table" data-url-edit="{{route('module.frontend.user.plan_suggest.form.edit')}}">
                <thead>
                <tr class="tbl-heading">
                    <th width="5%" data-formatter="index_formatter">#</th>
                    <th data-field="intend">Thời gian dự kiến</th>
                    <th data-field="subject_name">Khóa học đề xuất</th>
                    <th  data-field="duration">{{trans('backend.timer')}} (session)</th>
                    <th  data-field="title" data-align="center">{{ trans('backend.object') }}</th>
                    <th  data-align="amount" data-width="200px" data-formatter="training_date">{{ trans('backend.quantity') }}</th>
                    <th  data-field="teacher" data-align="right">{{ trans('backend.teacher') }}</th>
                    <th  data-field="purpose" data-width="150px" data-formatter="result" data-align="center">Mục tiêu</th>
                    <th  data-field="has_cert" data-align="center" data-formatter="certificate" >File</th>
                    <th  data-align="center" data-formatter="edit_formatter" >Sửa</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-popup" style="display: none">
        <form method="post" action="" name="frm" enctype="multipart/form-data" class="form-ajax">
            <div class="form-group row">
                <div class="col-md-4" stye ="vertical-align:middle"><label>Thời gian dự kiến</label></div>
                <div class="col-md-8"><input name="intend" id="intend" class="form-control"></div>
                </div>
            <div class="form-group row">
                <div class="col-md-4" stye ="vertical-align:middle"><label>Tên học phần</label></div>
                <div class="col-md-8">
                    <select name="subject_name" class="form-control" data-placeholder="Chọn/nhập học phần">
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4" stye ="vertical-align:middle"><label>{{ trans('backend.object') }}</label></div>
                <div class="col-md-8">
                    <select class="form-control" multiple name="title[]" data-placeholder="{{trans('backend.choose_title')}}">
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4" stye ="vertical-align:middle"><label>{{trans('backend.number_student')}}</label></div>
                <div class="col-md-8">
                    <input type="number" name="amount" class="form-control" />
                    </div>
                </div>
            <div class="form-group row">
                <div class="col-md-4" stye ="vertical-align:middle"><label>{{trans('backend.timer')}}</label></div>
                <div class="col-md-8">
                    <input type="number" name="duration" placeholder="{{ trans('backend.enter_number_session') }}" class="form-control" />
                    </div>
                </div>
            <div class="form-group row">
                <div class="col-md-4" stye ="vertical-align:middle"><label>{{ trans('backend.teacher') }}</label></div>
                <div class="col-md-8">
                    <input type="text" name="teacher" class="form-control" />
                    </div>
                </div>
            <div class="form-group row">
                <div class="col-md-4" stye ="vertical-align:middle"><label>{{trans('backend.training_objectives')}}</label></div>
                <div class="col-md-8">
                    <input type="text" name="purpose" class="form-control" />
                    </div>
                </div>
            <div class="form-group row">
                <div class="col-md-4" stye ="vertical-align:middle"><label>{{trans('backend.attachments')}}</label></div>
                <div class="col-md-8">
                    <input type="file" name="attach" accept="image/*" class="form-control" />
                    </div>
                </div>
            <div class="form-group row">
                <div class="col-md-4" stye ="vertical-align:middle"><label>Danh sách học viên</label></div>
                <div class="col-md-8">
                    <textarea class="form-control" name="students" id="" rows="2"></textarea>
                    </div>
                </div>
            <div class="form-group row">
                <div class="col-md-4" stye ="vertical-align:middle"><label>{{ trans('backend.note') }}</label></div>
                <div class="col-md-8">
                    <textarea class="form-control" name="note" id="" rows="2"></textarea>
                    </div>
                </div>
            </form>
    </div>
    <script type="text/javascript">
        function certificate(value, row, index) {
            return value==1? '<a href="javascript:void(0)">' + '<i class="fa fa-certificate"></i>' + '</a>':'-';
        }
        function index_formatter(value, row, index) {
            return (index + 1);
        };
        function edit_formatter(value, row, index) {
            return '<div class="edit"><a href="javascript:void(0)" data-id='+row.id+'><i class="fa fa-edit fa-lg"></i></a></div>';
        }
        function result(value, row, index) {
            return value == 1 ? '{{trans("backend.finish")}}' : 'Chưa hoàn thành';
        }
        function training_date(value,row,index) {
            return row.start_date +' - '+row.end_date;
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.frontend.user.plan_suggest.getData') }}',
        });

    </script>

@endsection
