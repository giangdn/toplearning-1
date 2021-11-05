@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.capabilities.category') }}">Danh mục năng lực</a>
            <i class="uil uil-angle-right"></i>
            {{ $page_title }}
        </h2>
    </div>
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('styles/module/capabilities/css/capabilities.css') }}">
<div role="main">

    <form method="post" action="{{ route('module.capabilities.category.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['category-capabilities-category-create', 'category-capabilities-category-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.capabilities.category') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                    <label for="name">Tên danh mục năng lực <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-7">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="name">Nhóm danh mục năng lực</label>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group row">
                                        <div class="col-md-10" id="group-list">
                                            @if(isset($groups))
                                                @foreach($groups as $group)
                                                    <div class="group-item">
                                                        <input type="text" class="form-control group" name="group[]" value="{{ $group->name }}"><span><a href="javascript:void(0)" class="remove-group text-danger"><i class="fa fa-times"></i></a></span>
                                                    </div>

                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            <a href="javascript:void(0)" id="add-group"><i class="fa fa-plus-circle"></i> Thêm nhóm</a>
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

<script type="text/javascript">
    $("#add-group").on('click', function(){
        $("#group-list").append('<div class="group-item"><input type="text" class="form-control group" name="group[]"><span><a href="javascript:void(0)" class="remove-group text-danger"><i class="fa fa-times"></i></a></span></div>');
    });

    $('#group-list').on('click', '.remove-group', function(){
        $(this).closest('.group-item').remove();
    });
</script>
@stop
