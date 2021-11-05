<div>
    <div class="form-group row">
        <div class="col-sm-3" style="vertical-align:middle">
            <label>{{trans('backend.time')}} <span class="text-danger"> * </span></label>
        </div>
        <div class="col-md-8">
            <span><input name="start_date" type="text" class="datepicker form-control d-inline-block w-25" placeholder="{{trans('backend.choose_start_date')}}" autocomplete="off" value="{{ get_date($model->start_date) }}"></span>
            <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
            <span><input name="end_date" type="text" class="datepicker form-control d-inline-block w-25" placeholder='{{trans("backend.choose_end_date")}}' autocomplete="off" value="{{ get_date($model->end_date) }}"></span>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3" style="vertical-align:middle"><label>{{ trans('backend.training_content') }} <span class="text-danger">*</span></label></div>
        <div class="col-md-3">
            <select name="subject_name" id="subject_select2" data-tags="true" class="form-control select2" data-placeholder="{{trans('backend.choose_subject')}}" >
                <option value=""></option>
                @foreach ($subject as $item)
                    @php
                        $arr_sub[] = $item->name;
                    @endphp
                    <option value="{{$item->name}}" {{ $model->subject_name == $item->name ? 'selected' : '' }}>{{$item->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" name="subject_name" class="form-control" id="subject_text" placeholder="{{trans('backend.document')}}" value="{{$model->subject_name}}">
        </div>
        <div class="col-md-3">
            <input type="checkbox" value="{{ in_array($model->subject_name, $arr_sub) ? 1 : 0 }}" id="check-subject">
            {{ trans('backend.enter_text') }}
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3" style="vertical-align:middle"><label>{{ trans('backend.object') }} <span class="text-danger">*</span></label></div>
        <div class="col-md-8">
            <select class="form-control select2" multiple name="title[]" id="title" data-placeholder="{{trans('backend.choose_title')}}">
                <option value=""></option>
                @foreach($title as $item)
                    <option {{ in_array($item->id,$model->title)? 'selected' : '' }} value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3" style="vertical-align:middle"><label>{{trans('backend.number_student')}} <span class="text-danger">*</span></label></div>
        <div class="col-md-8">
            <input type="number" name="amount" class="form-control" placeholder="{{ trans('backend.enter_number_student') }}" value="{{ $model->amount }}" />
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3" style="vertical-align:middle"><label>{{trans('backend.form')}} <span class="text-danger">*</span></label></div>
        <div class="col-md-8">
            <select class="form-control" name="type">
                <option value="">{{trans('backend.choose_form')}}</option>
                <option value="2" {{ $model->type == 2 ? 'selected' : '' }}>{{trans('backend.online')}}</option>
                <option value="3" {{ $model->type == 3 ? 'selected' : '' }}>{{trans('backend.offline')}}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3" style="vertical-align:middle"><label>{{ trans('backend.type') }}</label></div>
        <div class="col-md-8">
            <select class="form-control load-training-form" name="training_form" id="type" data-placeholder="{{ trans('backend.choose_type') }}">
                <option value=""></option>
                @if($training_form)
                    <option value="{{ $training_form->id }}" selected>{{ $training_form->name }}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3" style="vertical-align:middle"><label>{{trans('backend.locations')}}</label></div>
        <div class="col-md-8">
            <textarea class="form-control" name="address" rows="5">{{ $model->address }}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3" style="vertical-align:middle"><label>{{trans('backend.cost')}}</label></div>
        <div class="col-md-8">
            <input type="number" name="cost" class="form-control" placeholder="{{trans('backend.enter_cost')}}" value="{{ $model->cost }}" />
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3" style="vertical-align:middle"><label>{{trans('backend.timer')}}</label></div>
        <div class="col-md-8">
            <input type="number" name="duration" placeholder="{{ trans('backend.enter_number_session') }}" value="{{ $model->duration }}" class="form-control" />
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3" style="vertical-align:middle"><label>{{ trans('backend.teacher') }}</label></div>
        <div class="col-md-8">
            <input type="text" name="teacher" class="form-control" value="{{ $model->teacher }}" />
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3" style="vertical-align:middle"><label>{{ trans('backend.training_objectives') }}</label></div>
        <div class="col-md-8">
            <textarea class="form-control" name="purpose" rows="5">{{ $model->purpose }}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3" style="vertical-align:middle"><label>{{ trans('backend.topical_content') }}</label></div>
        <div class="col-md-8">
            <textarea class="form-control" name="content" rows="5">{{ $model->content }}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3" style="vertical-align:middle"><label>{{ trans('backend.attachments') }}</label></div>
        <div class="col-md-8">
            <a href="javascript:void(0)" id="select-attach">{{trans("backend.choose_file")}}</a>
            <div id="attach-review">
                @if($model->attach)
                    {{ basename($model->attach) }}
                @endif
            </div>
            <input name="attach" id="attach-select" type="text" class="d-none" value="{{ $model->attach }}">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3" style="vertical-align:middle"><label>{{ trans('backend.student_list') }}</label></div>
        <div class="col-md-8">
            <select class="form-control select2" multiple name="students[]" id="student" data-placeholder="{{trans('backend.choose_student')}}">
                <option value=""></option>
                @foreach($students as $item)
                    <option {{in_array($item->user_id, $model->students) ? 'selected' : '' }} value="{{ $item->user_id }}">{{ $item->full_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3" style="vertical-align:middle"><label>{{ trans('backend.note') }}</label></div>
        <div class="col-md-8">
            <textarea class="form-control" name="note" rows="5">{{ $model->note }}</textarea>
        </div>
    </div>
</div>
