@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.convert_titles') }}">{{trans('backend.convert_titles')}}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.convert_titles.reviews') }}">{{ trans('backend.evaluation_form') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

<div role="main">
    <form method="post" action="{{ route('module.convert_titles.reviews.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['convert-titles-review-create', 'convert-titles-review-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.convert_titles.reviews') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="probation">{{ trans('backend.title') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="title_id" id="title_id" class="form-control select2 load-title" data-placeholder="-- {{trans('backend.choose_convert_titles')}} --" required>
                                        @if($model->title_id)
                                            <option value="{{ $title->id }}" {{ $model->title_id == $title->id  ? 'selected' : '' }}> {{ $title->name }} </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.evaluation_form') }}</label>
                                </div>
                                <div class="col-md-9">
                                    <div>
                                        <a href="javascript:void(0)" id="select-form-review">{{trans('backend.choose_forms')}}</a>
                                        <div id="form-review">
                                            @if($model->file_reviews)
                                                {{ basename($model->file_reviews) }}
                                            @endif
                                        </div>
                                        <input name="file_reviews" id="item-select" type="text" class="d-none" value="{{ $model->file_reviews }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">

    $("#select-form-review").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'files'}, function (url, path) {
            var path2 =  path.split("/");
            $("#form-review").html(path2[path2.length - 1]);
            $("#item-select").val(path);
        });
    });

</script>
@stop
