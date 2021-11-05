<div class="row">
    <div class="col-md-12">
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Chương trình đào tạo<span style="color:red"> * </span></label>
            </div>
            <div class="col-md-6">
                <select name="training_program_id" id="training_program_id" class="form-control load-training-program" required>
                    <option value=""></option>
                    @if(isset($training_program))
                        <option value="{{ $training_program->id }}" selected> {{ $training_program->name }} </option>
                    @endif
                </select>
            </div>
        </div>
        {{--<div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{ trans('backend.levels') }} <span class="text-danger">*</span></label>
            </div>
            <div class="col-md-6">
                <select name="level_subject_id" id="level_subject_id" class="form-control load-level-subject" data-training-program="{{ $model->training_program_id }}" data-placeholder="-- {{ trans('backend.levels') }} --" required>
                    <option value=""></option>
                    @if(isset($level_subject))
                        <option value="{{ $level_subject->id }}" selected>{{ $level_subject->name }}</option>
                    @endif
                </select>
            </div>
        </div>--}}
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label for="subject_id">Khóa học</label><span style="color:red"> * </span>
            </div>
            <div class="col-md-6">
                <select name="subject_id" id="subject_id" class="form-control" data-level-subject="{{ $model->level_subject_id }}" data-training-program="{{ $model->training_program_id }}" data-placeholder="-- {{ trans('backend.document') }} --" required>
                    @if (isset($subjects))
                        @foreach ($subjects as $s)
                            @if(isset($subject) && $subject->id == $s->id)
                                <option value="{{ $subject->id }}"  selected > {{ $subject->name }} </option>
                            @else
                                <option value="{{ $s->id }}"> {{ $s->name }} </option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        {{-- HÌNH THỨC ĐÀO TẠO --}}
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{trans('backend.training_program_form')}}</label><span class="text-danger"> * </span>
            </div>
            <div class="col-md-6">
                <select name="course_type[]" id="course_type" class="form-control select2" data-placeholder="-- {{trans('backend.choose_training_program_form')}} --" multiple required>
                    <option value=""></option>
                    <option value="1" {{ isset($model->course_type) && in_array(1, $course_type) ? 'selected' : '' }}>Đào tạo trực tuyến</option>
                    <option value="2" {{ isset($model->course_type) && in_array(2, $course_type) ? 'selected' : '' }}>Đào tạo tập trung</option>
                </select>
            </div>
        </div>

        {{-- LOẠI HÌNH ĐÀO TẠO --}}
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{trans('backend.training_form')}}</label><span class="text-danger"> * </span>
            </div>
            <div class="col-md-6">
                <select name="training_form_id[]" class="form-control select2" id="training_form_id" data-placeholder="-- Chọn loại hình --" multiple required>
                    @foreach ($training_forms as $training_form)
                        <option value="{{ $training_form->id }}" {{ isset($training_form_id) && in_array($training_form->id, $training_form_id) ? 'selected' : '' }}>{{ $training_form->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Đơn vị tổ chức</label>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-4 col-md-3 pr-0">
                        <select name="training_partner_type" id="type_training_partner" class="type_training_partner select2">
                            <option value="" disabled selected>Đơn vị</option>
                            <option value="0" {{ $model->training_partner_type == 0 ? 'selected' : '' }}>Nội bộ</option>
                            <option value="1" {{ $model->training_partner_type == 1 ? 'selected' : '' }}>Bên ngoài</option>
                        </select>
                    </div>
                    <div class="col-8 col-md-9">
                        <div id="unit_type_training_partner">
                            <select name="training_partner[]" id="choose_unit_training_partner" class="form-control select2" data-placeholder="{{ trans('backend.unit') }}" multiple>
                                <option value=""></option>
                                @foreach($units as $item)
                                    <option value="{{ $item->id }}" {{ isset($training_partner) && in_array($item->id, $training_partner) ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="training_partner">
                            <select name="training_partner[]" id="select_training_partner" class="form-control select2" data-placeholder="{{ trans('backend.unit') }}" multiple>
                                @foreach ($training_partners as $tp)
                                    <option value="{{ $tp->id }}" {{ isset($training_partner) && in_array($tp->id, $training_partner) ? 'selected' : '' }}>{{ $tp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Đơn vị phối hợp</label>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-4 col-md-3 pr-0">
                        <select name="responsable_type" id="type_responsable" class="type_responsable select2">
                            <option value="" disabled selected></option>
                            <option value="0" {{ $model->responsable_type == 0 ? 'selected' : '' }}>Nội bộ</option>
                            <option value="1" {{ $model->responsable_type == 1 ? 'selected' : '' }}>Bên ngoài</option>
                        </select>
                    </div>
                    <div class="col-8 col-md-9">
                        <div id="unit_type_responsable">
                            <select name="responsable[]" id="choose_unit_responsable" class="form-control load-unit" data-placeholder="{{ trans('backend.unit') }}" multiple>
                                @foreach($units as $item)
                                    <option value="{{ $item->id }}" {{ isset($responsable) && in_array($item->id, $responsable) ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="responsable">
                            <select name="responsable[]" id="select_responsable" class="form-control select2" multiple>
                                @foreach($training_partners as $tp)
                                    <option value="{{ $tp->id }}" {{ isset($responsable) && in_array($tp->id, $responsable) ? 'selected' : '' }}>{{ $tp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- THỜI LƯỢNG ĐÀO TẠO LỚP --}}
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{trans('backend.training_time')}}/Lớp</label><span class="text-danger"> * </span>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-4 col-md-3 pr-0">
                        <input name="periods" type="{{ $model->periods ? 'text' : 'number' }}" class="form-control" value="{{ $model->periods }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- QUÝ 1 --}}
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Quý 1</label>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-4 col-md-3 pr-0">
                        <input name="quarter1" id="quarter1" type="number" class="form-control month is-number" min="0"
                            placeholder="{{trans('backend.enter_the_course_number')}}" onblur="findTotal()" value="{{ $model->quarter1 }}" min="0">
                    </div>
                </div>
            </div>
        </div>

        {{-- QUÝ 2 --}}
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Quý 2</label>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-4 col-md-3 pr-0">
                        <input name="quarter2" id="quarter2" type="number" class="form-control month is-number" min="0"
                        placeholder="{{trans('backend.enter_the_course_number')}}" onblur="findTotal()" value="{{ $model->quarter2 }}" min="0">
                    </div>
                </div>
            </div>
        </div>

        {{-- QUÝ 3 --}}
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Quý 3</label>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-4 col-md-3 pr-0">
                        <input name="quarter3" id="quarter3" type="number" class="form-control month is-number" min="0"
                        placeholder="{{trans('backend.enter_the_course_number')}}" onblur="findTotal()" value="{{ $model->quarter3 }}" min="0">
                    </div>
                </div>
            </div>
        </div>

        {{-- QUÝ 4 --}}
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Quý 4</label>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-4 col-md-3 pr-0">
                        <input name="quarter4" id="quarter4" type="number" class="form-control month is-number" min="0"
                            placeholder="{{trans('backend.enter_the_course_number')}}" onblur="findTotal()" value="{{ $model->quarter4 }}" min="0">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Tổng số lớp trong năm</label><span style="color:red"> * </span>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-4 col-md-3 pr-0">
                        <input name="total_course" id="total_course" type="text" class="form-control is-number"
                        value="{{ $model->total_course }}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Số lượng học viên/Lớp</label><span class="text-danger"> * </span>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-4 col-md-3 pr-0">
                        <input name="total_student" type="number" class="form-control" value="{{ $model->total_student }}" min="0" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Nhu cầu đào tạo CBNV hiện hữu</label>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-4 col-md-3 pr-0">
                        <input name="exis_training_CBNV" type="number" class="form-control" value="{{ $model->exis_training_CBNV }}" min="0" >
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Nhu cầu đào tạo CBNV tân tuyển</label>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-4 col-md-3 pr-0">
                        <input name="recruit_training_CBNV" type="number" class="form-control" value="{{ $model->recruit_training_CBNV }}" min="0" >
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>Đối tượng đào tạo</label>
            </div>
            <div class="col-md-6">
                <select name="training_object_id[]" multiple class="form-control select2" id="" data-placeholder="-- Chọn đối tượng đào tạo --">
                    @foreach ($training_objects as $training_object)
                        <option value="{{ $training_object->id }}" {{ isset($training_object_id) && in_array($training_object->id, $training_object_id) ? 'selected' : '' }}>{{ $training_object->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
            
        @foreach ($type_costs as $type_cost)
            @if (!empty($get_type_model_costs))
                @foreach ($get_type_model_costs as $item)
                    @if ($item->id == $type_cost->id)
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ $type_cost->name }}</label>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-4 col-md-3 pr-0">
                                        <input type="hidden" name="hidden_type_cost_id[]" id="" value="{{ $type_cost->id }}">
                                        <input type="text" name="money_costs_plan_detail[]" id="money_cost_plan_detail_{{$type_cost->id}}" class="form-control is-number money_cost_plan_detail" value="{{ $item->money_cost ? number_format($item->money_cost, 0) : 0 }}">
                                    </div>
                                    <div class="col-3 detail_cost">
                                        <span onclick="detailCost({{$type_cost->id}})">Chi tiết</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label>{{ $type_cost->name }}</label>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4 col-md-3 pr-0">
                                <input type="hidden" name="hidden_type_cost_id[]" id="" value="{{ $type_cost->id }}">
                                <input type="text" name="money_costs_plan_detail[]" id="money_cost_plan_detail_{{$type_cost->id}}" class="form-control is-number money_cost_plan_detail" value="0">
                            </div>
                            <div class="col-3 detail_cost">
                                <span onclick="detailCost({{$type_cost->id}})">Chi tiết</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
        @if ( !empty($get_type_cost_id) && !empty(array_diff($array_type_cost, $get_type_cost_id)) && !empty($type_costs_new) )
            @foreach ($type_costs_new as $type_cost_new)
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ $type_cost_new[1] }}</label>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-4 col-md-3 pr-0">
                            <input type="hidden" name="hidden_type_cost_id[]" id="" value="{{ $type_cost_new[0] }}">
                            <input type="text" name="money_costs_plan_detail[]" id="money_cost_plan_detail_{{ $type_cost_new[0] }}" class="form-control is-number money_cost_plan_detail" value="0">
                        </div>
                        <div class="col-3 detail_cost">
                            <span onclick="detailCost({{ $type_cost_new[0] }})">Chi tiết</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
        <div class="form-group row">
            <div class="col-sm-3 control-label">
                <label>{{trans('backend.note')}}</label>
            </div>
            <div class="col-md-6">
                <textarea name="note" type="text" class="form-control">{{ $model->note }}</textarea>
            </div>
        </div>
        @if (!empty($sum_training_plans))
            @foreach ($sum_training_plans as $key => $sum_training_plan)
                <div>
                    <input type="hidden" name="type_costs_plan_id[]" id="type_costs_plan_id_{{$key}}" value="{{ $key }}">
                </div>
                <div>
                    <input type="hidden" name="set_type_costs_plan[]" id="set_type_cost_{{$key}}" value="{{ $sum_training_plan }}">
                </div>
            @endforeach
        @endif
    </div>
</div>

<div class="modal fade" id="modal-detail-cost" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="" method="post" class="form-ajax">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Chi phí</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body model_body_object pt-0 mt-2">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Chi phí đào tạo</th>
                                <th>Chi phí (VNĐ)</th>
                                <th>Tính Chi phí</th>
                            </tr>
                        </thead>
                        <tbody class="tbody_table">
                            
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('#training_program_id').on('change', function () {
        var training_program_id = $('#training_program_id option:selected').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('module.training_plan.detail.ajax_level_subject') }}',
            dataType: 'json',
            data: {
                'training_program_id': training_program_id,
                '_token': '{{ csrf_token() }}',
            }
        }).done(function(data) {
            let html = '';
            $.each(data, function (i, item){
                html+='<option value='+ item.id +'>'+ item.name +'</option>';
            });
            $("#subject_id").html(html);
        }).fail(function(data) {
            show_message("{{ trans('lageneral.data_error ') }}", 'error');
            return false;
        });
    });

    $('#level_subject_id').on('change', function () {
        var training_program_id = $('#training_program_id option:selected').val();
        var level_subject_id = $('#level_subject_id option:selected').val();
        $("#subject_id").empty();
        $("#subject_id").data('training-program', training_program_id);
        $("#subject_id").data('level-subject', level_subject_id);
        $('#subject_id').trigger('change');
    });

    // LẤY GIÁ TRỊ QUÝ TÍNH TỔNG LỚP
    function findTotal() {
        var quarter1 = $('#quarter1').val() ? parseInt($('#quarter1').val()) : 0;
        var quarter2 = $('#quarter2').val() ? parseInt($('#quarter2').val()) : 0;
        var quarter3 = $('#quarter3').val() ? parseInt($('#quarter3').val()) : 0;
        var quarter4 = $('#quarter4').val() ? parseInt($('#quarter4').val()) : 0;
        var total = quarter1 + quarter2 + quarter3 + quarter4;
        $('#total_course').val(total);
        costCalculate(total) 
    }

    // TÍNH CHI PHÍ
    function costCalculate(total) {
        var training_form =  $('#training_form_id').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('module.training_plan.detail.ajax_cost_calculate',['id' => $plan_id]) }}',
            dataType: 'json',
            data: {
                'training_form': training_form,
                'total': total,
                '_token': '{{ csrf_token() }}',
            }
        }).done(function(data) {
            var get_total_course = parseInt($('#total_course').val());
            var type_course = $('#course_type').val();
            if (type_course.includes('2') || type_course.includes('3')) {
                $.each(data, function (index, obj) {
                    document.getElementById("money_cost_plan_detail_"+index).value = obj * get_total_course;
                });
            } else {
                $.each(data, function (index, obj) {
                    document.getElementById("money_cost_plan_detail_"+index).value = "";
                });
            }
        }).fail(function(data) {
            show_message("{{ trans('lageneral.data_error ') }}", 'error');
            return false;
        });
    }

    //CHI TIẾT CHI PHÍ
    function detailCost(id){
        var type_course = $('#course_type').val();
        console.log(type_course);
        var check_isset_training_form =  $('#training_form_id').val();
        var check_isset_total = $('#total_course').val();
        var detail_id = '<?php echo $model ? $model->id : '' ?>'
        $.ajax({
            type: 'POST',
            url: '{{ route('module.training_plan.detail.ajax_detail_cost',['id' => $plan_id]) }}',
            dataType: 'json',
            data: {
                'type_course': type_course,
                'detail_id': detail_id,
                'type_cost_id': id,
                'check_isset_training_form': check_isset_training_form,
                'check_isset_total': check_isset_total,
                '_token': '{{ csrf_token() }}',
            }
        }).done(function(data) {
            $('.tbody_table tr').remove();
            $.each(data, function(i, item) {
                var checked = item[4] == '1' ? 'checked' : '';
                var cost = parseInt( item[2], 10 );
                cost = cost.toLocaleString( "en-US" )
                $('.tbody_table').append(`<tr>
                                            <td>`+ item[1] +`</td>
                                            <td>`+ cost +`</td>
                                            <td>
                                                <input type="checkbox" name="scales" onclick="calculateTypeCost(`+ check_isset_total +`, `+ item[3] +`, `+ item[0] +`)" class="btnselect" id="cost_detail_check_`+item[0]+`" `+ checked +` >
                                            </td>
                                        </tr>`);
            });
            $('#modal-detail-cost').modal();
        }).fail(function(data) {
            show_message("{{ trans('lageneral.data_error ') }}", 'error');
            return false;
        });
    }

    // TÍNH LẠI CHI PHÍ KHI CHỌN TRONG CHI TIẾT
    function calculateTypeCost(total, type_cost_id, cost_id) {
        $("input[name=scales]").attr("disabled", true);
        var check_cost_checked = $('#cost_detail_check_' + cost_id).is(":checked") ? 1 : 0;
        var money_cost_plan = $('#money_cost_plan_detail_'+type_cost_id).val();
        var detail_id = '<?php echo $model ? $model->id : '' ?>'
        $.ajax({
            type: 'POST',
            url: '{{ route('module.training_plan.detail.ajax_type_cost_calculate',['id' => $plan_id]) }}',
            dataType: 'json',
            data: {
                'detail_id': detail_id,
                'check_cost_checked': check_cost_checked,
                'money_cost_plan': money_cost_plan,
                'type_cost_id': type_cost_id,
                'cost_id': cost_id,
                'total': total,
                '_token': '{{ csrf_token() }}',
            }
        }).done(function(data) {
            $('#money_cost_plan_detail_'+type_cost_id).val(data);
            $("input[name=scales]").removeAttr("disabled");
        }).fail(function(data) {
            show_message("{{ trans('lageneral.data_error ') }}", 'error');
            return false;
        });
    }

    var training_partner_type = '<?php echo $model->training_partner_type ?>';
    var responsable_type = '<?php echo $model->responsable_type ?>';
    if(training_partner_type == 0) {
        $('#unit_type_training_partner').css('display','block');
        $('#training_partner').css('display','none');
        $("#select_training_partner").prop('disabled', true);
        $("#choose_unit_training_partner").prop('disabled', false);
    } else {
        $('#unit_type_training_partner').css('display','none');
        $('#training_partner').css('display','block');
        $("#choose_unit_training_partner option").val();
        $("#select_training_partner").prop('disabled', false);
    }

    if(responsable_type == 0) {
        $('#unit_type_responsable').css('display','block');
        $('#responsable').css('display','none');
        $("#select_responsable").prop('disabled', true);
        $("#choose_unit_responsable").prop('disabled', false);
    } else {
        $('#unit_type_responsable').css('display','none');
        $('#responsable').css('display','block');
        $("#choose_unit_responsable").prop('disabled', true);
        $("#select_responsable").prop('disabled', false);
    }

    $('#type_training_partner').on('change', function() {
        if ( $("#type_training_partner").val() == 0 ) {
            $('#unit_type_training_partner').css('display','block');
            $('#training_partner').css('display','none');
            $("#select_training_partner").prop('disabled', true);
            $("#choose_unit_training_partner").prop('disabled', false);
        } else {
            $('#unit_type_training_partner').css('display','none');
            $('#training_partner').css('display','block');
            $("#choose_unit_training_partner").prop('disabled', true);
            $("#select_training_partner").prop('disabled', false);
        }
    })

    $('#type_responsable').on('change', function() {
        if ( $("#type_responsable").val() == 0 ) {
            $('#unit_type_responsable').css('display','block');
            $('#responsable').css('display','none');
            $("#select_responsable").prop('disabled', true);
            $("#choose_unit_responsable").prop('disabled', false);
        } else {
            $('#unit_type_responsable').css('display','none');
            $('#responsable').css('display','block');
            $("#choose_unit_responsable").prop('disabled', true);
            $("#select_responsable").prop('disabled', false);
        }
    })

    $(document).ready(function () {
        var $form = $( "#form" );
        var $input = $form.find( "input[name='money_costs_plan_detail[]']" );
        $input.on( "keyup", function( event ) {
            var $this = $( this );
            // Get the value.
            var input = $this.val();
            var input = input.replace(/[\D\s\._\-]+/g, "");
            input = input ? parseInt( input, 10 ) : 0;

            $this.val( function() {
                return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );
            } );
        });
    });
</script>