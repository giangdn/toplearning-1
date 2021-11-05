@extends('layouts.backend')

@section('page_title', 'Thiết lập cảnh báo')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="tPanel">
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <form method="post" action="{{ route('module.quiz.save_setting_alert') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{ $model ? $model->id : '' }}">

                        <div class="row">
                            <div class="col-md-9 col-sm-12">
                                <div class="form-group row">
                                    <div class="col-md-9 form-inline">
                                        Từ ngày:
                                        <span class="ml-2 mr-2"><input name="from_time" type="text" class="form-control is-number" min="1" value="{{ $model ? $model->from_time : '' }}" required></span>
                                        Đến ngày:
                                        <span class="ml-2 mr-2"><input name="to_time" type="text" class="form-control is-number" min="2" value="{{ $model ? $model->to_time : '' }}" required></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group text-right">
                                    <div class="btn-group act-btns">
                                        @can('quiz-setting-alert-create')
                                            <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                                        @endcan
                                        <a href="{{ route('module.quiz.setting_alert') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
