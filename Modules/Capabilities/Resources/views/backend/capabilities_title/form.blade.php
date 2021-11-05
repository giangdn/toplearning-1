@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.capabilities.title') }}">{{trans('backend.framework_title')}}</a>
            <i class="uil uil-angle-right"></i>
            {{ $page_title }}
        </h2>
    </div>
@endsection

@section('content')

<div role="main">

    <form method="post" action="{{ route('module.capabilities.title.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['category-capabilities-title-create', 'category-capabilities-title-edit'])
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.capabilities.title') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                    <label for="weight">STT <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="number_title" type="text" class="form-control is-number" value="{{ $model->number_title }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="category_id">{{trans('backend.capacity_category')}}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="category_id" id="category_id" class="form-control select2" data-placeholder="--{{trans('backend.choose_capacity_category')}}--" required>
                                        <option value=""></option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"  @if(isset($capability)) {{ $capability->category_id == $category->id ? 'selected': '' }} @endif  >{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="capabilities_id">{{trans('backend.capability')}}</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="capabilities_id" id="capabilities_id" class="form-control select2" data-placeholder="--Chọn năng lực--">
                                        <option value=""></option>
                                        @foreach($capabilities as $capability)
                                            <option value="{{ $capability->id }}" {{ $model->capabilities_id == $capability->id ? 'selected': '' }}>{{ $capability->code .' - '. $capability->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="title_id">{{trans('backend.title')}}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="title_id" id="title_id" class="form-control select2" data-placeholder="--{{trans('backend.choose_title')}}--" required>
                                        <option value=""></option>
                                        @foreach($titles as $item)
                                            <option value="{{ $item->id }}" {{ $model->title_id == $item->id ? 'selected': '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="weight">{{trans('backend.weight')}}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input name="weight" type="text" class="form-control is-number" value="{{ $model->weight }}" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="weight">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="critical_level">{{trans('backend.critical_level')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="critical_level" type="text" class="form-control is-number" value="{{ $model->critical_level }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="level">{{trans('backend.levels')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="level" id="level" class="form-control select2" data-placeholder="--{{trans('backend.choose_levels')}}--">
                                        <option value=""></option>
                                        <option value="1" {{ $model->level == 1 ? 'selected': '' }}>1</option>
                                        <option value="2" {{ $model->level == 2 ? 'selected': '' }}>2</option>
                                        <option value="3" {{ $model->level == 3 ? 'selected': '' }}>3</option>
                                        <option value="4" {{ $model->level == 4 ? 'selected': '' }}>4</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        var ajax_get_capabilities = "{{ route('module.capabilities.title.ajax_get_capabilities') }}";
    </script>
</div>
<script type="text/javascript" src="{{ asset('styles/module/capabilities/js/capabilities_title.js') }}"></script>
@stop
