@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.webservice') }}">Cấu hình login</a> / {{ $page_title }}
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <form method="post" action="{{ route('backend.webservice.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="row">
                <div class="col-md-8">

                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                        <a href="{{ route('backend.webservice') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                <label for="">URL</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="url" class="form-control" value="{{ $model->url }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="key">Key</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="key" class="form-control" value="{{ $model->key }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
