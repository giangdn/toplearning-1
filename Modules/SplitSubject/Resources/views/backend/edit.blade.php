@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.splitsubject.index') }}">{{$page_title}}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.edit') }}</span>
        </h2>
    </div>
@endsection

@section('content')
<div role="main">
    <form method="post" action="{{ route('module.splitsubject.update',['id'=>$id]) }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['splitsubject-create', 'splitsubject-edit'])
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.splitsubject.index') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <div class="tPanel">
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row"  {{$model->merge_option==2  ?'hidden' : ''}}>
                        <div class="col-md-10">
                            <div class="form-group row">
                                <div class="col-sm-4 control-label">
                                    <label>Chuyên đề cần tách</label>
                                </div>
                                <div class="col-md-8">
                                    <select name="subject_new" id="subject_new" class="select2" data-placeholder="-- {{ trans('backend.subject') }} --">
                                        <option value="">-- {{ trans('backend.subject') }} --</option>
                                        @foreach ($subjects as $item=>$value)
                                            <option value="{{$value->id}}" {{$value->id==$model->subject_new?'selected':''}} >{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4 control-label">
                                    <label>Chuyên đề mới</label><span style="color:red"> * </span>
                                </div>
                                <div class="col-md-8">
                                    <select name="subject_old[]" class="form-control select2" multiple>
                                        @foreach ($subjects as $item=>$value)
                                            @php
                                                $q = $value->id;
                                                $equal=array_filter($subject_old_rr, function($v ) use ($q){
                                                    return $v ==$q;
                                                })
                                            @endphp
                                            <option value="{{$value->id}}" {{$equal?'selected':''}}>{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4 control-label">
                                    <label>Ghi chú</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="note">{{$model->note}}</textarea>
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
