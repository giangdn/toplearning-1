@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <style>
        .select2-selection.error {
            background-color: #fff;
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 25%);
        }
    </style>
<div class="forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.management') }}
        <i class="uil uil-angle-right"></i>
        <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a>
        <i class="uil uil-angle-right"></i>
        <a href="{{ route('backend.category.titles') }}">{{ trans('backend.title') }}</a>
        <i class="uil uil-angle-right"></i>
        <span class="">{{ $page_title }}</span>
    </h2>
</div>
<div role="main">
    <form method="post" action="{{ route('backend.category.titles.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['category-titles-create', 'category-titles-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('backend.category.titles') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                    <label>{{ trans('backend.title_code') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="code" type="text" class="form-control" value="{{ $model->code }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.title_name') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="position_id">Chức vụ</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="position_id" id="position_id" class="form-control load-position" data-placeholder="--Chức vụ--">
                                        @if(isset($position))
                                            <option value="{{ $position->id }}">{{ $position->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div> --}}

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="group">Cấp bậc chức danh <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="group" id="group" class="form-control select2" data-placeholder="--Chọn cấp bậc chức danh--" required>
                                        <option value=""></option>
                                        @foreach ($title_ranks as $title_rank)
                                            <option value="{{ $title_rank->id }}" {{ $model->group == $title_rank->id ? 'selected' : '' }}> {{ $title_rank->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="unit_type">Loại đơn vị <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="unit_type" id="unit_type" class="form-control select2" data-placeholder="--Chọn cấp bậc chức danh--" required>
                                        <option value=""></option>
                                        @foreach ($units_type as $unit_type)
                                            <option value="{{ $unit_type->id }}" {{ $model->unit_type == $unit_type->id ? 'selected' : '' }}> {{ $unit_type->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @php
                            $max_level = \App\Models\Categories\Unit::getMaxUnitLevel();
                            @endphp
                            @for($i = 2; $i <= 5; $i++)
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="group">{{ trans('backend.unit_level', ['level' => $i]) }}</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="unit_id" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="{{ trans('backend.unit_level', ['level' => $i]) }}" data-level="{{ $i }}" data-loadchild="unit-{{ ($i+1) }}">
                                        @if(isset($unit[$i]))
                                            <option value="{{ $unit[$i]->id }}">{{ $unit[$i]->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @endfor

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.status') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <label class="radio-inline"><input required type="radio" name="status" value="1" @if($model->status == 1 || is_null($model->status)) checked @endif>{{ trans('backend.enable') }}</label>
                                    <label class="radio-inline"><input required type="radio" name="status" value="0" @if($model->status == 0 && !is_null($model->status)) checked @endif>{{ trans('backend.disable') }}</label>
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
