<form method="post" action="{{ route('module.course_educate_plan.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <div class="row">
        <div class="col-md-9">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.training_program')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="training_program_id" id="training_program_id" class="form-control load-training-program" data-placeholder="-- {{trans('backend.training_program')}} --" required>
                    @if(isset($training_program))
                        <option value="{{ $training_program->id }}" selected> {{ $training_program->name }} </option>
                    @endif
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="subject_id">{{ trans('backend.subject') }}</label><span style="color:red"> * </span>
                </div>
                <div class="col-md-9">
                    <select name="subject_id" id="subject_id" class="form-control load-subject" data-level-subject="{{ $model->level_subject_id }}" data-training-program="{{ $model->training_program_id }}" data-placeholder="-- {{ trans('backend.subject') }} --" required>
                        @if(isset($subject))
                            <option value="{{ $subject->id }}" selected> {{ $subject->name }} </option>
                        @endif
                    </select>
                </div>
            </div>
            {{--<div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.course_code')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="code" id="code" type="text" class="form-control" value="{{ $model->code }}" required readonly>
                </div>
            </div>--}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.course_name')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.time')}}</label><span class="text-danger"> * </span>
                </div>
                <div class="col-md-9">
                    <span><input name="start_date" type="text" placeholder="{{trans('backend.choose_start_date')}}" class="datepicker form-control
                    d-inline-block w-25" autocomplete="off" value="{{ get_date($model->start_date) }}"></span>

                    <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                    <span><input name="end_date" type="text" placeholder='{{trans("backend.choose_end_date")}}' class="datepicker form-control
                    d-inline-block w-25" autocomplete="off" value="{{ get_date($model->end_date) }}"></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.register_deadline')}}</label>
                </div>
                <div class="col-md-9">
                    <input name="register_deadline" type="text" class="form-control datepicker" placeholder="{{trans('backend.choose_register_deadline')}}"
                           autocomplete="off" value="{{ get_date($model->register_deadline) }}">
                </div>
            </div>
            {{--<div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="check-in-plan">{{trans('backend.plan')}}</label>
                </div>
                <div class="col-md-9">
                    <input id="check-in-plan" type="checkbox" value="{{ $model->in_plan ? 1 : 0 }}" {{ $model->in_plan ? 'checked' : '' }}>
                </div>
            </div>
            <div class="form-group row" id="in-plan">
                <div class="col-sm-3 control-label">
                    <label for="in_plan">{{trans('backend.training_plan')}}</label>
                </div>
                <div class="col-md-9">
                    <select name="in_plan" id="in_plan" class="form-control select2" data-placeholder="-- Chọn kế hoạch --">
                        <option value=""></option>
                        @foreach($training_plan as $item)
                            <option value="{{ $item->id }}" {{ $model->in_plan == $item->id ? 'selected' : '' }} > {{ $item->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>--}}

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.document')}}</label>
                </div>
                <div class="col-md-9">
                    <div>
                        <a href="javascript:void(0)" id="select-document">{{trans('backend.choose_document')}}</a>
                        <div id="document-review">
                            @if($model->document)
                                {{ basename($model->document) }}
                            @endif
                        </div>
                        <input name="document" id="document-select" type="text" class="d-none" value="{{ $model->document }}">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="description">{{trans('backend.brief')}}</label>
                </div>
                <div class="col-md-9">
                    <textarea name="description" id="description" rows="4" class="form-control">{{ $model->description }}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.description')}}</label>
                </div>
                <div class="col-md-9">
                    <textarea name="content" id="content" class="form-control">{!! $model->content  !!}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.training_form')}}</label>
                </div>
                <div class="col-md-9">
                    <select name="training_form_id" id="training_form_id" class="form-control select2" data-placeholder="{{trans('backend.training_form')}}">
                        <option value=""></option>
                        @if($training_forms)
                            @foreach($training_forms as $training_form)
                                <option value="{{ $training_form->id }}" {{ $model->training_form_id == $training_form->id ?
                                'selected' : '' }}>{{ $training_form->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.training_location')}} </label>
                </div>
                <div class="form-group col-sm-9 m-0" style="background: #efefef!important">
                    <div class="col-md-12 pb-1 pt-1 p-0 m-0">
                        <select name="province" data-url="{{route('module.offline.filter.location')}}" class="select2 form-control">
                            <option value="">{{trans('backend.choose_province')}}</option>
                            @if($province)
                                @foreach($province as $item)
                                    <option value="{{$item->id}}" @if($item->id==$model->training_location_province) selected @endif> {{$item->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-12 pb-1 p-0 m-0">
                        <select name="district" class="select2 form-control">
                            <option value="">{{trans('backend.choose_district')}}</option>
                            @if($district)
                                @foreach($district as $item)
                                    <option value="{{$item->id}}" @if($item->id==$model->training_location_district) selected @endif> {{$item->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-12 pb-1 p-0 m-0">
                        <select name="training_location_id" data-placeholder="Chọn địa điểm đào tạo" class="form-control
                        select2" data-url="{{route('module.offline.filter.traininglocation')}}">
                            <option value="">Chọn địa điểm đào tạo</option>
                            @if($training_location)
                                @foreach($training_location as $item)
                                    <option value="{{$item->id}}" @if($item->id==$model->training_location_id) selected @endif> {{$item->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.training_units')}}</label>
                </div>
                <div class="col-md-9">
                    <input type="text" placeholder="{{trans('backend.training_units')}}" name="training_unit" class="form-control" value="{{$model->training_unit}}" />
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row row-acts-btn">
                <div class="col-sm-12">
                    <div class="btn-group act-btns">
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                        <a href="{{ route('module.course_educate_plan.management') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{trans('backend.picture')}} (1000 x 654)</label>
                <div>
                    <a href="javascript:void(0)" id="select-image">{{trans('backend.choose_picture')}}</a>
                    <div id="image-review">
                        @if($model->image)
                            <img src="{{ image_file($model->image) }}" alt="">
                        @endif
                    </div>

                    <input name="image" id="image-select" type="text" class="d-none" value="{{ $model->image }}">
                </div>
            </div>
            <div class="form-group">
                <label>{{trans('backend.time')}}</label>
                <div class="input-group">
                    <input name="course_time" type="text" class="form-control" value="{{ if_empty($course_time, '') }}">
                    <span class="input-group-addon">
                        <select name="course_time_unit" id="course_time_unit" class="form-control">
                            <option value="day" {{ $course_time_unit == 'day' ? 'selected' : '' }}>{{trans('backend.date')}}</option>
                            <option value="session" {{ $course_time_unit == 'session' ? 'selected' : '' }}>{{trans('backend.session')}}</option>
                            <option value="hour" {{ $course_time_unit == 'hour' ? 'selected' : '' }}>{{trans('backend.hour')}}</option>
                        </select>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label>{{trans('backend.lesson')}} ({{trans('backend.number_lesson')}})</label>
                <input name="num_lesson" type="text" class="form-control is-number" value="{{ $model->num_lesson }}">
            </div>
            <div class="form-group">
                <label>{{trans('backend.certificate')}} </label>
                <div>
                    <select style="width: 65%;" name="cert_code" id="cert_code" class="form-control d-inline-block"  @if($model->has_cert == 0) disabled @endif >
                        @foreach($certificate as $item)
                            <option value="{{ $item->id }}" {{ $model->cert_code == $item->id ? 'selected' : '' }}>{{ $item->code }}</option>
                        @endforeach
                    </select>
                    <input name="has_cert" id="has_cert" type="checkbox" class="form-custom" value="{{ $model->has_cert?$model->has_cert : 0}}" {{ $model->has_cert == 1 ? 'checked' : '' }}>{{trans("backend.enable")}}
                </div>
            </div>
            <div class="form-group">
                <label>{{trans('backend.evaluate_training_effectiveness')}}</label>
                <div>
                    <select name="action_plan" id="action_plan" class="form-control " data-placeholder="-- Đánh giá hiệu quả đào tạo --">
                        <option value="0" {{ $model->action_plan == 0 ? 'selected' : '' }}> {{trans('backend.no')}} </option>
                        <option value="1" {{ $model->action_plan == 1 ? 'selected' : '' }}> {{trans('backend.yes')}} </option>
                    </select>
                </div>
                <div class="pt-1 contain_plan_app_template">
                    @if(isset($plan_app_template))
                    <select name="plan_app_template" class="form-control select2" data-placeholder="{{trans('backend.choose_evaluation_form')}}">
                        <option value="">{{trans('backend.choose_evaluation_form')}}</option>
                        @foreach($plan_app_template as $item)
                            <option value="{{$item->id}}" {{ $model->plan_app_template == $item->id ? 'selected':''}}>{{$item->name}}</option>
                        @endforeach
                    </select>
                    @endif
                </div>
                <div class="pt-1">
                    <input type="number" placeholder="Thời gian thực hiện" name="plan_app_day" value="{{$model->plan_app_day}}" class="form-control" >
                </div>
            </div>

            <div class="form-group">
                <label>{{trans('backend.assessment_after_the_course')}}</label>
                <div>
                    <select name="rating" id="rating" class="form-control select2" data-placeholder="-- {{trans('backend.assessment_after_the_course')}} --">
                        <option value=""></option>
                        <option value="1" {{ $model->rating == 1 ? 'selected' : '' }}> {{trans('backend.yes')}} </option>
                        <option value="0" {{ $model->rating == 0 ? 'selected' : '' }}> {{trans('backend.no')}} </option>
                    </select>
                </div>
            </div>
            <div class="form-group" id="form-template">
                <label for="template_id"> {{trans('backend.choose_evaluation_form')}} </label>
                <div>
                    <select name="template_id" id="template_id" class="form-control select2" data-placeholder="-- {{trans('backend.choose_evaluation_form')}} --">
                    @if(isset($templates))
                        <option value=""></option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}" {{ $model->template_id == $template->id ? 'selected' : '' }}> {{ $template->name }}</option>
                        @endforeach
                    @endif
                    </select>
                    <a href="javscript:void(0)" id="modal_qrcode">{{trans('backend.Qrcode_evaluation_form')}}</a>
                </div>
            </div><div class="form-group">
                <label class="form-check-label">
                    {{trans('backend.exam')}}
                </label>
                <div>
                    <select name="quiz_id" class="form-control select2 load-quiz" data-course="{{$model->id}}" data-placeholder="{{trans('backend.choose_quiz')}}">
                        <option value="">{{trans('backend.choose_quiz')}}</option>
                        @foreach($quizs as $item)
                            <option {{ $model->quiz_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name
                            }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-check-label">
                    <input type="checkbox" class="" name="commit" id="commit" value="{{ $model->commit }}"  @if($model->commit == 1) checked @endif >
                    {{trans('backend.commitment_training')}}
                </label>

                <div class="pb-1" >
                    <input  @if(!$model->commit) style="display: none;"@endif class="form-control datepicker"
                            name="commit_date" autocomplete="off" value="{{ get_date($model->commit_date) }}"
                            aria-describedby="helpId" placeholder="Nhập ngày cam kết">
                </div>
                <div>
                    <input type="text" @if(!$model->commit) style="display: none;"@endif step="0.1" class="form-control
                    is-number" value="{{ $model->coefficient }}" name="coefficient" id="" placeholder="Nhập hệ số K">
                </div>
            </div>

            <div class="form-group">
                <label for="unit_id">{{trans('backend.unit')}}</label>
                <select name="unit_id[]" id="unit_id" class="form-control select2" data-placeholder="-- {{ trans('backend.choose_unit') }} --" multiple >
                    <option value=""></option>
                    @foreach($units as $item)
                        @if(count($unit_manager_lv2) > 0 && in_array($item->id, $unit_manager_lv2) && empty($model->id))
                            <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                        @else
                            <option value="{{ $item->id }}" {{ isset($unit) && in_array($item->id, $unit) ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <script src="{{ asset('styles/ckeditor/ckeditor.js') }}"></script>
</form>
<div class="modal fade" id="modal-qrcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{trans('backend.survey_code_QR')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    @if ($qrcode_survey_after_course)
                        <div id="qrcode" > {{--style="position:absolute;width: 60vh;height: calc(60vh * 1.414285714)"--}}
                        {!! QrCode::size(300)->generate($qrcode_survey_after_course); !!}
                        <p>{{trans('backend.scan_code')}}</p>
                        </div>
                    @endif
                        <a href="javascript:void(0)" id="print_qrcode">In QR Code</a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#modal_qrcode').on('click',function () {
        $("#modal-qrcode").modal();
    });
    $('#print_qrcode').on("click", function () {

        $('#qrcode').printThis({header: null,printContainer: true,importStyle: true});
    });
    var item_teacher_evaluation = $('#teacher_evaluation option:selected').val();
    if(item_teacher_evaluation == 1){
        $('#form-teacher').show();
    }else{
        $('#form-teacher').hide();
    }
    $('#teacher_evaluation').on('change', function() {
        var item = $('#teacher_evaluation option:selected').val();

        if(item == 1){
            $('#form-teacher').show();
        }else{
            $('#form-teacher').hide();
        }
    });

    var item_rating = $('#rating option:selected').val();
    if(item_rating == 1){
        $('#form-template').show();
    }else{
        $('#form-template').hide();
    }
    $('#rating').on('change', function() {
        var item = $('#rating option:selected').val();

        if(item == 1){
            $('#form-template').show();
        }else{
            $('#form-template').hide();
        }
    });

    var check_in_plan = $('#check-in-plan').val();
    if(check_in_plan == 1){
        $('#in-plan').show();
    }else{
        $('#in-plan').hide();
    }

    $('#check-in-plan').on('click', function () {
        if ($(this).is(':checked')){
            var check_in_plan = $('#check-in-plan').val(1);
            $('#in-plan').show();
        }else {
            var check_in_plan = $('#check-in-plan').val(0);
            $('#in-plan').hide();
            $('#in_plan option:selected').val(null).trigger('change');
        }
    })
</script>
<script>
    CKEDITOR.replace('content', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });
</script>
