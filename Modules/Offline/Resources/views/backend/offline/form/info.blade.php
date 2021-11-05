<form method="post" action="{{ route('module.offline.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="unit_id" value="{{ $model->unit_id ? $model->unit_id : $is_unit }}">
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
                    <select name="subject_id" id="subject_id" class="form-control load-subject" {{--data-level-subject="{{ $model->level_subject_id }}"--}} data-training-program="{{ $model->training_program_id }}" data-placeholder="-- Chuyên đề --" required>
                        @if(isset($subject))
                            <option value="{{ $subject->id }}" selected> {{ $subject->name }} </option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.course_code')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="code" id="code" type="text" class="form-control" value="{{ $model->code }}" required readonly>
                </div>
            </div>
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
                    <label>Mảng nghiệp vụ</label>
                </div>
                <div class="col-md-9">
                    <input type="text" id="level_subject" class="form-control" value="{{ isset($level_subject) ? $level_subject->name : '' }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Điểm tối đa</label>
                </div>
                <div class="col-md-9">
                    <input name="max_grades" type="text" class="form-control" value="{{ $model->max_grades }}">
                </div>
            </div>


            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Điểm đạt</label>
                </div>
                <div class="col-md-9">
                    <input name="min_grades" type="text" class="form-control" value="{{ $model->min_grades }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Số học viên tối đa <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="max_student" type="text" class="form-control" value="{{ $model->max_student }}" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Chức danh tham gia(bắt buộc) <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="title_join_id[]" id="title_join_id" class="form-control select2" data-placeholder="-- Chức danh tham gia --" multiple>
                        <option value="0" {{ !empty($get_title_join_model_id) && in_array(0, $get_title_join_model_id) ? 'selected' : '' }}>Chọn tất cả</option>
                        @foreach ($titles as $title)
                            <option value="{{ $title->id }}" {{ !empty($get_title_join_model_id) && in_array($title->id, $get_title_join_model_id) ? 'selected' : '' }}>{{ $title->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Chức danh khuyến khích</label>
                </div>
                <div class="col-md-9">
                    <select name="title_recommend_id[]" id="title_recommend_id" class="form-control select2" multiple data-placeholder="-- Chức danh khuyến khích --">
                        <option value="0" {{ !empty($get_title_recommend_model_id) && in_array(0, $get_title_recommend_model_id) ? 'selected' : '' }}>Chọn tất cả</option>
                        @foreach ($titles as $title)
                            <option value="{{ $title->id }}" {{ !empty($get_title_recommend_model_id) && in_array($title->id, $get_title_recommend_model_id) ? 'selected' : '' }}>{{ $title->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Nhóm đối tượng tham gia</label>
                </div>
                <div class="col-md-9">
                    <select name="training_object_id[]" id="training_object_id" class="form-control select2" data-placeholder="-- Nhóm đối tượng tham gia --" multiple>
                        @foreach ($training_objects as $training_object)
                            <option value="{{ $training_object->id }}" {{ !empty($get_training_object_id) && in_array($training_object->id, $get_training_object_id) ? 'selected' : '' }}>{{ $training_object->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- KHU VỰC ĐÀO TẠO --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Khu vực đào tạo <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="training_area_id[]" id="training_area_id" class="form-control select2" data-level="3" multiple data-placeholder="-- Khu vực đào tạo --" required>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" {{ !empty($training_area) && in_array($area->id, $training_area) ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.training_location')}} </label>
                </div>
                <div class="form-group col-sm-9 m-0" style="background: #efefef!important">
                    <div class="row">
                    <div class="col-md-4">
                        <select name="province" data-url="{{route('module.offline.filter.location')}}" class="select2 form-control">
                            <option value="">{{trans('backend.choose_province')}}</option>
                            @if($province)
                                @foreach($province as $item)
                                    <option value="{{$item->id}}" @if($item->id==$model->training_location_province) selected @endif> {{$item->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="district" class="select2 form-control">
                            <option value="">{{trans('backend.choose_district')}}</option>
                            @if($district)
                                @foreach($district as $item)
                                    <option value="{{$item->id}}" @if($item->id==$model->training_location_district) selected @endif> {{$item->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
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
            </div>

            {{-- LOẠI HÌNH ĐÀO TẠO --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.training_form')}}<span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="training_form_id" id="training_form_id" class="form-control select2" data-placeholder="{{trans('backend.training_form')}}" required>
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

            {{-- ĐƠN VỊ TỔ CHỨC --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Đơn vị tổ chức</label>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-4 col-md-3 pr-0">
                            <select name="training_unit_type" id="type_training_partner" class="type_training_partner select2">
                                <option value="" disabled selected>Đơn vị</option>
                                <option value="0" {{ $model->training_unit_type == 0 ? 'selected' : '' }}>Nội bộ</option>
                                <option value="1" {{ $model->training_unit_type == 1 ? 'selected' : '' }}>Bên ngoài</option>
                            </select>
                        </div>
                        <div class="col-8 col-md-9">
                            <div id="unit_type_training_partner">
                                <select name="training_unit[]" id="choose_unit_training_partner" class="form-control select2" data-placeholder="{{ trans('backend.unit') }}" multiple>
                                    <option value=""></option>
                                    @foreach($units as $item)
                                        <option value="{{ $item->id }}" {{ !empty($training_unit) && in_array($item->id, $training_unit) ? 'selected' : '' }}>{{$item->code}} - {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="training_partner">
                                <select name="training_unit[]" id="select_training_partner" class="form-control select2" data-placeholder="{{ trans('backend.unit') }}" multiple>
                                    @foreach ($training_partners as $tp)
                                        <option value="{{ $tp->id }}" {{ !empty($training_unit) && in_array($tp->id, $training_unit) ? 'selected' : '' }}>{{$tp->code}} - {{ $tp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ĐƠN VỊ PHỐI HỢP --}}
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Đơn vị phối hợp</label>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-4 col-md-3 pr-0">
                            <select name="training_partner_type" id="type_responsable" class="type_responsable select2">
                                <option value="" disabled selected></option>
                                <option value="0" {{ $model->training_partner_type == 0 ? 'selected' : '' }}>Nội bộ</option>
                                <option value="1" {{ $model->training_partner_type == 1 ? 'selected' : '' }}>Bên ngoài</option>
                            </select>
                        </div>
                        <div class="col-8 col-md-9">
                            <div id="unit_type_responsable">
                                <select name="training_partner_id[]" id="choose_unit_responsable" class="form-control select2" data-placeholder="{{ trans('backend.unit') }}" multiple>
                                    @foreach($units as $item)
                                        <option value="{{ $item->id }}" {{ !empty($training_partner) && in_array($item->id, $training_partner) ? 'selected' : '' }}>{{$item->code}} - {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="responsable">
                                <select name="training_partner_id[]" id="select_responsable" class="form-control select2" multiple>
                                    @foreach($training_partners as $tp)
                                        <option value="{{ $tp->id }}" {{ !empty($training_partner) && in_array($tp->id, $training_partner) ? 'selected' : '' }}>{{$tp->code}} - {{ $tp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Loại giảng viên</label>
                </div>
                <div class="col-md-9">
                    <select name="teacher_type_id" id="teacher_type_id" class="form-control load-teacher-type" data-placeholder="-- Loại giảng viên --" >
                    @if(isset($teacher_type))
                        <option value="{{ $teacher_type->id }}" selected> {{ $teacher_type->name }} </option>
                    @endif
                    </select>
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

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Khóa học dành cho <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="course_employee" id="course_employee" class="form-control" data-placeholder="-- Khóa học dành cho --" required>
                        <option value="0" selected>Chọn</option>
                        <option {{ $model->course_employee =='1' ? 'selected' : '' }} value="1"> CBNV tân tuyển</option>
                        <option {{ $model->course_employee =='2' ? 'selected' : '' }} value="2"> CBNV hiện hữu</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Khóa học thực hiện <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <select name="course_action" id="course_action" class="form-control" data-placeholder="-- Khóa học thực hiện --" required>
                        <option value="0" {{ empty($model->course_action) ? 'selected' : '' }}>Chọn</option>
                        <option {{ $model->course_action =='1' ? 'selected' : '' }} value="1"> Kế hoạch</option>
                        <option  {{ $model->course_action =='2' ? 'selected' : '' }} value="2"> Phát sinh</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="check-in-plan">Kế hoạch đào tạo tháng</label>
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
            </div>
            {{-- TÀI LIỆU --}}
            <div class="form-group row info_document_offline">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.document')}}</label>
                </div>
                <div class="col-md-9">
                    <div>
                        {{-- <a href="javascript:void(0)" id="select-document">{{trans('backend.choose_document')}}</a>
                        <div id="document-review">
                            @if($model->document)
                                {{ basename($model->document) }}
                            @endif
                        </div>
                        <input name="document" id="document-select" type="text" class="d-none" value="{{ $model->document }}"> --}}
                        <input type="file" name="document[]" class="myfrm form-control w-100 mb-1" multiple style="height:auto;">
                        @if (!empty($documents))
                            @foreach ($documents as $key => $document)
                                <input type="hidden" name="hidden_document[]" class="form-control w-100" id="hidden_document_{{$key}}" value="{{ $document }}">
                                <ul class="title_document" id="title_document_{{$key}}" class="m-2 w-100">
                                    <li class="name_document">{{ $document }} </li>
                                    <li onclick="deleteDocument({{ $key }})" class="delete_document">x</li>
                                </ul>
                            @endforeach
                        @endif
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

        </div>
        <div class="col-md-3">
            <div class="row row-acts-btn">
                <div class="col-sm-12">
                    <div class="btn-group act-btns">
                    @if($permission_save && $model->lock_course == 0 && !$user_invited)
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                    @endif
                        <a href="{{ route('module.offline.management') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{trans('backend.picture')}} (300 x 160)</label>
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
                <label>Thời lượng học</label>
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
            {{-- <div class="form-group">
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
            </div> --}}

            <div class="form-group" id="form-teacher">
                <label> {{ trans('backend.choose_teacher') }}</label>
                <div>
                    <select name="teacher_id" id="teacher_id" class="form-control">
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $model->teacher_id == $teacher->id ? 'selected' : '' }}> {{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- <div class="form-group">
                <label>{{trans('backend.assessment_after_the_course')}}</label>
                <div>
                    <select name="rating" id="rating" class="form-control select2" data-placeholder="-- {{trans('backend.assessment_after_the_course')}} --">
                        <option value=""></option>
                        <option value="1" {{ $model->rating == 1 ? 'selected' : '' }}> {{trans('backend.yes')}} </option>
                        <option value="0" {{ $model->rating == 0 ? 'selected' : '' }}> {{trans('backend.no')}} </option>
                    </select>
                </div>
            </div>
            <div id="form-template">
                <div class="form-group">
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
                </div>
                <div class="form-group">
                    <label>{{trans('backend.end_date')}}</label>
                    <input name="rating_end_date" type="text" class="form-control datepicker" placeholder="{{trans('backend.end_date')}}" autocomplete="off" value="{{ get_date($model->rating_end_date) }}">
                </div>
            </div> --}}
            <div class="form-group">
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

            <div class="form-group">
                <label class="form-check-label">
                    <input type="checkbox" class="" name="enter_student_cost" id="enter_student_cost" value="{{ $model->enter_student_cost }}" @if($model->enter_student_cost == 1) checked @endif >
                    Học viên tự nhập chi phí
                </label>
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
    function deleteDocument(key) {
        var delete_document = document.getElementById("hidden_document_"+key);
        var title_document = document.getElementById("title_document_"+key);
        delete_document.remove();
        title_document.remove();
    }

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
    });

    $('#enter_student_cost').on('click', function () {
        if ($(this).is(':checked')){
            $('#enter_student_cost').val(1);
        }else {
            $('#enter_student_cost').val(0);
        }
    });

    $('#title_join_id').on('change',function() {
        var get_value = $('#title_join_id').val();
        if (get_value.includes(0)) {
            $('#title_join_id').removeAttr('multiple');
            $('#title_join_id').val(0)
        } else {
            $('#title_join_id').attr('multiple','multiple');
        }
    });

    $('#title_recommend_id').on('change',function() {
        var get_value = $('#title_recommend_id').val();
        if (get_value.includes(0)) {
            $('#title_recommend_id').removeAttr('multiple');
            $('#title_recommend_id').val(0)
        } else {
            $('#title_recommend_id').attr('multiple','multiple');
        }
    });

    // SELECT ĐƠN VỊ TỔ CHỨC/PHỐI HỢP
    var training_partner_type = '<?php echo $model->training_unit_type ?>';
    var responsable_type = '<?php echo $model->training_partner_type ?>';
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

    // ĐƠN VỊ TỔ CHỨC
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

    // ĐƠN VỊ PHỐI HỢP
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
