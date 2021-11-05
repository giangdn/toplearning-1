@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.capabilities.group_percent') }}">Nhóm phần trăm</a> <i class="uil uil-angle-right"></i> {{ $page_title }}
        </h2>
    </div>
@endsection

@section('content')

<div role="main">

    <form method="post" action="{{ route('module.capabilities.group_percent.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['category-capabilities-group-percent-create', 'category-capabilities-group-percent-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.capabilities.group_percent') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                <label class="col-sm-3 control-label">Nhóm <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="percent_group" value="{{ $model->percent_group }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 control-label">Phần trăm <span class="text-danger">*</span></label>

                                <div class="col-sm-2">
                                    <input type="text" class="form-control is-number" name="from_percent" value="{{ $model->from_percent  }}">
                                </div>
                                Đến
                                <div class="col-sm-2">
                                    <input type="text" class="form-control is-number" name="to_percent" value="{{ $model->to_percent }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 control-label">Các đánh giá <span class="text-danger">*</span></label>
                                <div class="col-sm-6"></div>
                                <div class="col-md-3 text-right"><a href="javascript:void(0)" class="btn btn-info" id="add-convent"> Thêm đánh giá</a> </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label"></div>
                                <div class="col-md-6" id="convent-list">
                                    @if(isset($convent))
                                        @foreach($convent as $item)
                                            <div class="convent-item">
                                                <div class="row">
                                                    <input type="hidden" name="convention_id[]" class="convention-id" value="{{ $item->id }}">
                                                    <div class="col-sm-11">
                                                        <input type="text" class="form-control" name="name[]" placeholder="{{ trans('backend.assessments') }}" value="{{ $item->name }}">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <a href="javascript:void(0)" class="text-danger remove-convent" data-convent="{{ $item->id }}">Xóa</a>
                                                    </div>
                                                </div>
                                                <br>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<template id="convent-template">
    <div class="convent-item">
        <div class="row">
            <input type="hidden" name="convention_id[]" class="convention-id" value="">
            <div class="col-sm-11">
                <input type="text" class="form-control" name="name[]" placeholder="{{ trans('backend.assessments') }}">
            </div>
            <div class="col-sm-1">
                <a href="javascript:void(0)" class="text-danger remove-convent">Xóa</a>
            </div>
        </div>
        <br>
    </div>
</template>
<script>
    var remove_convent = "{{ route('module.capabilities.group_percent.remove_convention', ['id' => $model->id]) }}";
</script>
<script type="text/javascript">
    var convent_template = document.getElementById('convent-template').innerHTML;
    $("#add-convent").on('click', function () {
        $("#convent-list").append(convent_template);
    });

    $('#convent-list').on('click', '.remove-convent', function(){
        $(this).closest('.convent-item').remove();
        var convent_id = $(this).data('convent');

        $.ajax({
            url: remove_convent,
            type: 'post',
            data: {
                convent_id: convent_id,
            },
        }).done(function(data) {

            return false;
        }).fail(function(data) {
            show_message('{{ trans('lageneral.data_error ') }}', 'error');
            return false;
        });
    });
</script>
@stop
