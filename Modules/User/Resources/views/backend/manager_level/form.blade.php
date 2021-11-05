@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.backend.manager_level') }}">{{ trans('backend.manager_level') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <form method="get" class="form-inline form-search">
            <div class="row content-fill">
                <div class="col-md-12 text-left">
                    <div class="input-group">
                        <input type="text" name="fromdate" class="form-control datepicker" autocomplete="off" placeholder="{{ trans('backend.start_date') }}">
                        <input type="text" name="todate" class="form-control datepicker" autocomplete="off" placeholder="{{ trans('backend.end_date') }}">
                        <button type="submit" class="btn btn-primary btn-search"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </div>
            </div>
        </form>
        <p></p>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <form method="post" action="{{ route('module.backend.manager_level.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="{{ $model->user_id }}">
                    <div class="form-group row m-2">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <div class="control-label"> {{ trans('backend.manager_level') }} <span class="text-danger"> * </span></div>
                                        <div class="col-md-8">
                                            <select name="user_manager_id" class="form-control select2" data-placeholder="{{ data_locale('Chọn', 'Choose') . ' ' . trans('backend.manager_level') }}">
                                                <option value=""></option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->user_id }}"> {{ $user->code . ' - ' . $user->lastname . ' ' . $user->firstname }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <div class="control-label"> {{ trans('backend.levels') }} <span class="text-danger"> * </span></div>
                                        <div class="col-md-8">
                                            <select name="level" class="form-control select2" data-placeholder="{{ data_locale('Chọn', 'Choose') . ' ' . trans('backend.levels') }}">
                                                <option value=""></option>
                                                @for($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}"> {{ 'Cấp ' . $i }} </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group row">
                                        <div class="control-label">
                                            <label> {{ trans('backend.time') }}</label><span class="text-danger"> * </span>
                                        </div>
                                        <div class="col-md-4">
                                            <input name="start_date" type="text" class="datepicker form-control" placeholder="{{ trans('backend.start_date') }}" autocomplete="off" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <input name="end_date" type="text" class="datepicker form-control" placeholder="{{ trans('backend.end_date') }}" autocomplete="off" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 text-right">
                            <div class="btn-group act-btns">
                                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                                <a href="{{ route('module.backend.manager_level') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-12">
                        <div id="manager-list">
                            @if($manager_level)
                                @foreach($manager_level as $key => $manager)
                                    <div class="manager-item" data-index="{{ ($key + 1) }}">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group row">
                                                                    <div class="control-label">
                                                                        {{ trans('backend.manager_level') }} <span class="text-danger"> * </span>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <select @if($manager->approve == 1) disabled @else name="user_manager_id" @endif class="form-control select2 change-manager" data-manager="{{ $manager->id }}">
                                                                            @foreach($users as $user)
                                                                                <option value="{{ $user->user_id }}" {{ $manager->user_manager_id == $user->user_id ? 'selected' : '' }}> {{ $user->code . ' - ' . $user->lastname . ' ' . $user->firstname }} </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group row">
                                                                    <div class="control-label">
                                                                        {{ trans('backend.levels') }} <span class="text-danger"> * </span>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <select @if($manager->approve == 1) disabled @else name="level" @endif class="form-control select2 change-level" data-manager="{{ $manager->id }}">
                                                                            @for($i = 1; $i <= 10; $i++)
                                                                                <option value="{{ $i }}" {{ $manager->level == $i ? 'selected' : '' }}> {{ 'Cấp ' . $i }} </option>
                                                                            @endfor
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="form-group row">
                                                                    <div class="control-label">
                                                                        <label>{{ trans('backend.time') }}</label><span class="text-danger"> * </span>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input @if($manager->approve == 1) disabled @else name="start_date" @endif type="text"
                                                                               class="datepicker form-control change-start-date" data-manager="{{ $manager->id }}" placeholder="{{ trans('backend.start_date') }}"
                                                                               autocomplete="off" value="{{ get_date($manager->start_date) }}">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input @if($manager->approve == 1 && $manager->end_date) disabled @else name="end_date" @endif type="text"
                                                                               class="datepicker form-control change-end-date" data-manager="{{ $manager->id }}" placeholder="{{ trans('backend.end_date') }}"
                                                                               autocomplete="off" value="{{ $manager->end_date ? get_date($manager->end_date) : '' }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 text-right">
                                                        @if($manager->approve != 1)
                                                            <a href="javascript:void(0)" class="btn text-danger remove-manager" data-manager="{{ $manager->id }}">{{ trans('backend.delete') }}</a>
                                                        @endif
                                                        <a href="javascript:void(0)" class="btn btn-{{$manager->status == 1 ? 'danger' : 'primary'}} status-manager" data-manager="{{ $manager->id }}"
                                                           data-status="{{ $manager->status == 1 ? '0' : '1' }}">{{ $manager->status == 1 ? trans('backend.hide') : trans('backend.open') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p></p>
        <div class="row">
            <div class="col-md-12 text-right">
                {{ $manager_level->links() }}
            </div>
        </div>
        <script>
            var remove_manager = "{{ route('module.backend.manager_level.remove') }}";
            var status_manager = "{{ route('module.backend.manager_level.status') }}";
            var change_manager = "{{ route('module.backend.manager_level.change_manager') }}";
            var change_level = "{{ route('module.backend.manager_level.change_level') }}";
            var change_start_date = "{{ route('module.backend.manager_level.change_start_date') }}";
            var change_end_date = "{{ route('module.backend.manager_level.change_end_date') }}";
        </script>
    </div>

    <script type="text/javascript">
        $('#manager-list').on('click', '.remove-manager', function () {
            $(this).closest('.manager-item').remove();
            var manager_id = $(this).data('manager');

            $.ajax({
                url: remove_manager,
                type: 'post',
                data: {
                    manager_id: manager_id,
                },
            }).done(function (data) {
                return false;
            }).fail(function (data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });

        $('#manager-list').on('click', '.status-manager', function () {
            var manager_id = $(this).data('manager');
            var status = $(this).data('status');

            $.ajax({
                url: status_manager,
                type: 'post',
                data: {
                    manager_id: manager_id,
                    status: status,
                },
            }).done(function (data) {
                show_message(data.message, data.status);
                window.location = '';
                return false;
            }).fail(function (data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });

        $('#manager-list').on('change', '.change-manager', function () {
            var manager_id = $(this).data('manager');
            var user_manager = $(this).val();

            $.ajax({
                url: change_manager,
                type: 'post',
                data: {
                    manager_id: manager_id,
                    user_manager: user_manager,
                },
            }).done(function (data) {
                show_message(data.message, data.status);
                window.location = '';
                return false;
            }).fail(function (data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });

        $('#manager-list').on('change', '.change-level', function () {
            var manager_id = $(this).data('manager');
            var level = $(this).val();

            $.ajax({
                url: change_level,
                type: 'post',
                data: {
                    manager_id: manager_id,
                    level: level,
                },
            }).done(function (data) {
                show_message(data.message, data.status);
                window.location = '';
                return false;
            }).fail(function (data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });

        $('#manager-list').on('change', '.change-start-date', function () {
            var manager_id = $(this).data('manager');
            var start_date = $(this).val();

            $.ajax({
                url: change_start_date,
                type: 'post',
                data: {
                    manager_id: manager_id,
                    start_date: start_date,
                },
            }).done(function (data) {
                show_message(data.message, data.status);
                window.location = '';
                return false;
            }).fail(function (data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });

        $('#manager-list').on('change', '.change-end-date', function () {
            var manager_id = $(this).data('manager');
            var end_date = $(this).val();

            $.ajax({
                url: change_end_date,
                type: 'post',
                data: {
                    manager_id: manager_id,
                    end_date: end_date,
                },
            }).done(function (data) {
                show_message(data.message, data.status);
                window.location = '';
                return false;
            }).fail(function (data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });
    </script>
@stop
