@extends('layouts.backend')

@section('page_title', 'Chỉnh thời gian')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('backend.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <form method="post" action="{{ route('backend.setting_time.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $settingTimeObject->id }}">
            <div class="row">
                <div class="col-md-8">
                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <br>
            <div class="tPanel">
                <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                    <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="base" class="tab-pane active">
                        <div class="form-group row" id="select_posts">
                            <div class="col-md-3 control-label">
                                <label for="content">Buổi sáng:</label>
                            </div>
                            <div class="col-md-8">
                                <span>
                                    <input name="start_time_morning" type="text" class="form-control timepicker d-inline-block w-25" placeholder="Chọn giờ bắt đầu" autocomplete="off" required value="{{ $get_time_morning ? $get_time_morning->start_time : '05:00:00' }}">
                                </span>
                                <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                <span>
                                    <input name="end_time_morning" type="text" class="form-control timepicker d-inline-block w-25" placeholder="Chọn giờ kết thúc" autocomplete="off" required value="{{ $get_time_morning ? $get_time_morning->end_time : '11:00:00' }}">
                                </span>
                                <span>
                                    <input type="text" name="value_morning" maxlength="30" class="form-control d-inline-block w-30" value="{{ $get_time_morning ? $get_time_morning->value : 'Chào buổi sáng' }}">
                                </span>
                            </div>
                        </div>
                        <div class="form-group row" id="select_posts">
                            <div class="col-md-3 control-label">
                                <label for="content">Buổi trưa:</label>
                            </div>
                            <div class="col-md-8">
                                <span>
                                    <input name="start_time_noon" type="text" class="form-control timepicker d-inline-block w-25" placeholder="Chọn giờ bắt đầu" autocomplete="off" required value="{{ $get_time_noon ? $get_time_noon->start_time : '11:01:00' }}">
                                </span>
                                <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                <span>
                                    <input name="end_time_noon" type="text" class="form-control timepicker d-inline-block w-25" placeholder="Chọn giờ kết thúc" autocomplete="off" required value="{{ $get_time_noon ? $get_time_noon->end_time : '13:00:00' }}">
                                </span>
                                <span>
                                    <input type="text" name="value_noon" maxlength="30" class="form-control d-inline-block w-30" value="{{ $get_time_noon ? $get_time_noon->value : 'Chào buổi trưa' }}">
                                </span>
                            </div>
                        </div>
                        <div class="form-group row" id="select_posts">
                            <div class="col-md-3 control-label">
                                <label for="content">Buổi chiều:</label>
                            </div>
                            <div class="col-md-8">
                                <span>
                                    <input name="start_time_afternoon" type="text" class="form-control timepicker d-inline-block w-25" placeholder="Chọn giờ bắt đầu" autocomplete="off" required value="{{ $get_time_afternoon ? $get_time_afternoon->start_time : '13:01:00' }}">
                                </span>
                                <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                <span>
                                    <input name="end_time_afternoon" type="text" class="form-control timepicker d-inline-block w-25" placeholder="Chọn giờ kết thúc" autocomplete="off" required value="{{ $get_time_afternoon ? $get_time_afternoon->end_time : '18:00:00' }}">
                                </span>
                                <span>
                                    <input type="text" class="form-control d-inline-block w-30" maxlength="30" name="value_afternoon" value="{{ $get_time_afternoon ? $get_time_afternoon->value : 'Rất vui được gặp lại' }}">
                                </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="object">{{ trans('backend.object') }} </label>
                            </div>
                            <div class="col-sm-7">
                                <select name="object[]" id="object" class="form-control select2" data-placeholder="-- {{ trans('backend.object') }} --" multiple>
                                    <option value=""></option>
                                    @foreach($unit as $item)
                                        <option value="{{ $item->id }}" {{ !empty($get_object) && in_array($item->id, $get_object) ? 'selected' : '' }}> {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        $('.timepicker').datetimepicker({
            locale:'vi',
            format: 'HH:mm'
        });
    </script>
@stop
