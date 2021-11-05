@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.setting') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.languages') }}">{{ trans('lacore.languages') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection
@section('content')
    <div role="main">
        <form method="post" action="{{ route('backend.languages.save',$id) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="row">
                <div class="col-md-8">
<h3>Thêm mới nhóm {{ $groups_name }}</h3>
                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('lacore.save') }}</button>
                        <a href="{{ route('backend.languages') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('lacore.cancel') }}</a>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <br>
            <div class="tPanel">
                <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                    <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('lacore.info') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="base" class="tab-pane active">

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="pkey">{{ trans('lacore.keyword') }}</label>
                            </div>
                            <div class="col-sm-9">
                                <input {{ $model->id ? 'readonly' : '' }} type="text" class="form-control" name="pkey" value="{{ $model->pkey }}" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="content">{{ trans('lacore.vietnamese_content') }}</label>
                            </div>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="content" rows="5">{{ $model->content }}</textarea>
                            </div>
                        </div>

						<div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="content_en">{{ trans('lacore.english_content') }}</label>
                            </div>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="content_en" rows="5">{{ $model->content_en }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


@stop
