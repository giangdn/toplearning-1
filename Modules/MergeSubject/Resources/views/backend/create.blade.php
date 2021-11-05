@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.mergesubject.index') }}">{{$page_title}}</a>
        <i class="uil uil-angle-right"></i>
        <span>{{ trans('backend.create') }}</span>
    </h2>
</div>
<div role="main">
    <form method="post" action="{{ route('module.mergesubject.store') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['training-plan-create', 'training-plan-edit'])
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i>  {{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.mergesubject.index') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <div class="tPanel">
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group row">
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-10">
                                    <input type="radio"  class="radio-inline" name="mergeOption" id="option1" value="1" checked >
                                    <label for="option1">Số lượng chuyên đề cần hoàn thành</label>
                                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                    <input type="radio"  class="radio-inline" name="mergeOption" id="option2" value="2" >
                                    <label for="option2">Chọn cụ thể từng chuyên đề cần hoàn thành</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="mergeOption-1">
                        <div class="col-md-10">
                            <div class="form-group row">
                                <div class="col-sm-4 control-label">
                                    <label>Số chuyên đề cần hoàn thành</label><span style="color:red"> * </span>
                                </div>
                                <div class="col-md-8">
                                    <input name="subject_old_complete" type="text" class="form-control" value=" ">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4 control-label">
                                    <label>Chọn chuyên đề cần gộp</label><span style="color:red"> * </span>
                                </div>
                                <div class="col-md-8">
                                    <select name="subject_old[]" class="form-control load-subject" multiple>
                                        @foreach ($subjects as $item=>$value)
                                            <option value="{{$value->code}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4 control-label">
                                    <label>Chuyên đề mới</label> <span style="color:red"> * </span>
                                </div>
                                <div class="col-md-8">
                                    <select name="subject_new" id="subject_new" class="load-subject" data-placeholder="-- {{ trans('backend.subject') }} --">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4 control-label">
                                    <label>Ghi chú</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="note"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="mergeOption-2" hidden>
                        <div class="col-md-10">
                            <div id="wrap-category">
                            <div class="form-group row">
                                <div class="col-sm-4 control-label">
                                    <label>Chọn chuyên đề cần gộp</label><span style="color:red"> * </span>
                                </div>
                                <div class="col-md-6">
                                    <select class="load-subject" name="subject_old_2[]" data-placeholder="-- {{ trans('backend.subject') }} --">
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label >Hoàn thành <input type="checkbox" checked class="subject_old_complete_2" name="subject_old_complete_2[]" /></label>
                                    <input type="hidden" name="subject_old_complete_hidden[]" value="1">
                                </div>
                            </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-success add-oldSubject"><i class="glyphicon glyphicon-plus-sign"></i> Thêm chuyên đề cần gộp</button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4 control-label"><label>Chuyên đề mới</label> <span style="color:red"> * </span></div>
                                <div class="col-sm-6">
                                    <select name="subject_new_2" id="subject_new_2" class="load-subject" data-placeholder="-- {{ trans('backend.subject') }} --"></select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">Ghi chú</div>
                                <div class="col-sm-6">
                                    <textarea class="form-control" name="note_2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
<template id="template">
    <div class="form-group row">
        <div class="col-sm-4 control-label">
            <label>Chọn chuyên đề cần gộp</label><span style="color:red"> * </span>
        </div>
        <div class="col-md-6">
            <select class="subject_old_2" name="subject_old_2[]" data-placeholder="-- {{ trans('backend.subject') }} --">
                @foreach ($subjects as $item=>$value)
                    <option value="{{$value->id}}">{{$value->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label >Hoàn thành <input type="checkbox" checked class="subject_old_complete_2" name="subject_old_complete_2[]" /></label>
            <input type="hidden" name="subject_old_complete_hidden[]" value="1">
        </div>
    </div>
</template>
<script type="text/javascript">
    $(document).ready(function() {
        $("input[name=mergeOption]").on("change", function () {
            var mergeOption = $(this).val();
            if (mergeOption == 1) {
                $("#mergeOption-1").attr('hidden', false);
                $("#mergeOption-2").attr('hidden', true);
            } else if (mergeOption == 2) {
                $("#mergeOption-1").attr('hidden', true);
                $("#mergeOption-2").attr('hidden', false);
            }
        });
        $(document).on('change','.subject_old_complete_2',function () {
            if($(this).is(':checked')){
                $(this).closest(".col-md-2").children("input[type=hidden]").val(1);
            }
            else{
                $(this).closest(".col-md-2").children("input[type=hidden]").val(0);
            }

        });
            // $('.subjectselect2').select2();


        $('.add-oldSubject').on('click', function () {
            var $content = document.getElementById('template').innerHTML;
            $('#wrap-category').append($content);
            $('.subject_old_2').select2({
                allowClear: true,
                dropdownAutoWidth: true,
                width: '100%',
                placeholder: function (params) {
                    return {
                        id: null,
                        text: params.placeholder,
                    }
                },
            }).val('').trigger('change');
        })
    });
</script>
@stop
