@extends('layouts.backend')

@section('page_title', $model->name)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('backend.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.mailtemplate') }}">{{ trans('backend.mailtemplate_manager') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $model->name }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <form method="post" action="{{ route('backend.mailtemplate.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="row">
                <div class="col-md-8">

                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        @can('mail-template-edit')
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                        @endcan
                        <a href="{{ route('backend.mailtemplate') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                            <div class="col-sm-2 control-label">
                                <label for="name">{{ trans('backend.name') }} </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" value="{{ $model->name }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label for="name">{{trans('backend.titles')}} </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="title" value="{{ $model->title }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label for="name">{{ trans('backend.content') }} </label>
                            </div>
                            <div class="col-sm-9">
                                <textarea name="content" id="ckeditor" class="form-control" rows="10">{!! $model->content !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        /*CKEDITOR.replace( 'ckeditor', {
            disallowedContent: 'img a'
        } );*/
        CKEDITOR.replace('content', {
            filebrowserImageBrowseUrl: '/filemanager?type=image',
            filebrowserBrowseUrl: '/filemanager?type=file',
            filebrowserUploadUrl : null, //disable upload tab
            filebrowserImageUploadUrl : null, //disable upload tab
            filebrowserFlashUploadUrl : null, //disable upload tab
        });
    </script>
@stop
