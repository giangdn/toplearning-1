@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
<div class="forum-container mb-2">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a>
        <i class="uil uil-angle-right"></i>
        <a href="{{ route('backend.category.subject') }}">{{ trans('backend.subject') }}</a>
        <i class="uil uil-angle-right"></i>
        <span class="">{{ $page_title }}</span>
    </h2>
</div>
<div role="main">
    <form method="post" action="{{ route('backend.category.subject.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['category-subject-create', 'category-subject-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('backend.category.subject') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                    <label>{{ trans('backend.subject_code') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="code" type="text" class="form-control" value="{{ $model->code }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.subject_name') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.training_program') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="training_program_id" id="training_program_id" class="form-control load-training-program" data-placeholder="-- {{ trans('backend.training_program') }} --" required>
                                        <option value=""></option>
                                        @if(isset($training_programs))
                                            <option value="{{ $training_programs->id }}" selected>{{ $training_programs->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.type_subject') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="level_subject_id" id="level_subject_id" class="form-control " data-training-program="{{ $model->training_program_id }}" data-placeholder="-- {{ trans('backend.type_subject') }} --" required>
                                        <option value=""></option>
                                        @if(isset($level_subject))
                                            @foreach ($level_subject as $item)
                                                <option value="{{ $item->id }}" {{ $item->id==$model->level_subject_id?'selected':''}}>{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="created_date">{{ trans('backend.created_at') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="created_date" class="form-control datepicker" value="{{ get_date($model->created_date) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="created_by">{{ trans('backend.person_create') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="created_by" id="created_by" class="form-control load-user" data-placeholder="-- {{ trans('backend.person_create') }} --">
                                        <option value=""></option>
                                        @if(isset($profile))
                                            <option value="{{ $profile->user_id }}" selected>{{ $profile->code .' - '. $profile->lastname .' '. $profile->firstname }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="unit_code">{{ trans('backend.training_create') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="unit_id" id="unit_id" class="form-control load-unit" data-placeholder="-- {{ trans('backend.training_create') }} --">
                                        <option value=""></option>
                                        @if(isset($unit))
                                            <option value="{{ $unit->id }}" selected>{{ $unit->code .' - '. $unit->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="description">{{ trans('backend.brief') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <textarea name="description" id="description" rows="4" class="form-control">{{ $model->description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.description') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <textarea name="content" id="" class="form-control ckeditor">{!! $model->content !!}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.status') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <label class="radio-inline"><input type="radio" required name="status" value="1" @if($model->status == 1) checked @endif>{{ trans('backend.enable') }}</label>
                                    <label class="radio-inline"><input type="radio" required name="status" value="0" @if($model->status == 0) checked @endif>{{ trans('backend.disable') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $('#training_program_id').on('change', function () {
        var training_program_id = $('#training_program_id option:selected').val();
    });
</script>
@stop
