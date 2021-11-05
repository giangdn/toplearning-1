@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.setting') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.footer') }}">Quản lý footer</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <form method="post" action="{{ route('backend.footer.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="row">
                <div class="col-md-8">

                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        @can('footer-edit')
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                        @endcan
                        <a href="{{ route('backend.footer') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="">{{trans('backend.titles')}}</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="name" class="form-control" value="{{ $model->name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="">Email</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="email" class="form-control" value="{{ $model->email }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="">Link youtobe</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="link_youtobe" class="form-control" value="{{ $model->link_youtobe }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="">Link facebook</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="link_fb" class="form-control" value="{{ $model->link_fb }}">
                            </div>
                        </div>
{{--                        <div class="form-group row">--}}
{{--                            <div class="col-sm-3 control-label">--}}
{{--                                <label for="status">Trạng thái <span class="text-danger">*</span></label>--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-6">--}}
{{--                                <select name="status" id="status" class="form-control select2" data-placeholder="-- {{trans('backend.status')}} --" required>--}}
{{--                                    <option value="1" {{ $model->status == 1 ? 'selected' : '' }}>{{trans("backend.enable")}}</option>--}}
{{--                                    <option value="0" {{ (!is_null($model->status) && $model->status == 0) ? 'selected' : '' }}>{{trans("backend.disable")}}</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
