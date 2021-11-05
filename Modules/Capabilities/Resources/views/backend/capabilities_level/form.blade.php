@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.capabilities.group') }}">Nhóm năng lực</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

<div role="main">

    <form method="post" action="{{ route('module.capabilities.group.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['category-capabilities-group-create', 'category-capabilities-group-edit'])
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.capabilities.group') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                    <label for="name">Tên nhóm năng lực <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#home">Kiến thức</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#menu1">Kỹ năng</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#menu2">Biểu hiện</a>
                                        </li>
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane container active" id="home">
                                            <div class="form-group row">
                                                <div class="col-sm-3 control-label">
                                                    <label for="basic_knowledge">{{trans('backend.levels')}} 1 (cơ bản)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="basic_knowledge" class="form-control" value="">{{ $model->basic_knowledge }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 control-label">
                                                    <label for="medium_knowledge">{{trans('backend.levels')}} 2 (trung bình)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="medium_knowledge" class="form-control" value="">{{ $model->medium_knowledge }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 control-label">
                                                    <label for="advanced_knowledge">{{trans('backend.levels')}} 3 (nâng cao)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="advanced_knowledge" class="form-control" value="">{{ $model->advanced_knowledge }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 control-label">
                                                    <label for="profession_knowledge">{{trans('backend.levels')}} 4 (chuyên nghiệp)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="profession_knowledge" class="form-control" value="">{{ $model->profession_knowledge }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane container fade" id="menu1">
                                            <div class="form-group row">
                                                <div class="col-sm-3 control-label">
                                                    <label for="basic_skills">{{trans('backend.levels')}} 1 (cơ bản)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="basic_skills" class="form-control" value="">{{ $model->basic_skills }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 control-label">
                                                    <label for="medium_skills">{{trans('backend.levels')}} 2 (trung bình)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="medium_skills" class="form-control" value="">{{ $model->medium_skills }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 control-label">
                                                    <label for="advanced_skills">{{trans('backend.levels')}} 3 (nâng cao)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="advanced_skills" class="form-control" value="">{{ $model->advanced_skills }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 control-label">
                                                    <label for="profession_skills">{{trans('backend.levels')}} 4 (chuyên nghiệp)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="profession_skills" class="form-control" value="">{{ $model->profession_skills }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane container fade" id="menu2">
                                            <div class="form-group row">
                                                <div class="col-sm-3 control-label">
                                                    <label for="basic_expression">{{trans('backend.levels')}} 1 (cơ bản)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="basic_expression" class="form-control" value="">{{ $model->basic_expression }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 control-label">
                                                    <label for="medium_expression">{{trans('backend.levels')}} 2 (trung bình)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="medium_expression" class="form-control" value="">{{ $model->medium_expression }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 control-label">
                                                    <label for="advanced_expression">{{trans('backend.levels')}} 3 (nâng cao)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="advanced_expression" class="form-control" value="">{{ $model->advanced_expression }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-3 control-label">
                                                    <label for="profession_expression">{{trans('backend.levels')}} 4 (chuyên nghiệp)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="profession_expression" class="form-control" value="">{{ $model->profession_expression }}</textarea>
                                                </div>
                                            </div>
                                        </div>
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


@stop
