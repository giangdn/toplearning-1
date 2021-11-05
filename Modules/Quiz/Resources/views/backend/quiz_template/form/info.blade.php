<form method="post" action="{{ route('module.quiz_template.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <input type="hidden" name="is_unit" value="{{ $is_unit }}">
    <input type="hidden" name="created_by" value="{{ $model->created_by }}">

    <div class="row">
        <div class="col-md-9">

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Mã bộ đề <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="code" type="text" class="form-control" value="{{ $model->code }}" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Tên bộ đề <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="type_id">{{ trans('backend.quiz_type') }}</label>
                </div>
                <div class="col-md-9">
                    <select name="type_id" id="type_id" class="form-control load-quiz-type" data-placeholder="-- {{ trans('backend.quiz_type') }} --">
                        @if($quiz_type)
                            <option value="{{ $quiz_type->id }}">{{ $quiz_type->name }}</option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('backend.content') }} </label>
                </div>
                <div class="col-md-9">
                    <textarea name="description" type="text" class="form-control" rows="4">{{ ($model->description) ? $model->description : '' }}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.time_quiz')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="limit_time" required type="text" class="is-number w-75 form-control d-inline-block date-custom" autocomplete="off" value="{{ $model->limit_time }}"> {{trans('backend.minutes')}}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.benchmarks')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="pass_score" required type="text" class="is-number w-75 form-control d-inline-block date-custom" autocomplete="off" value="{{ $model->pass_score }}"> {{trans('backend.score')}}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.maximum_score')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="max_score" required type="text" class="is-number form-control w-75 d-inline-block date-custom" autocomplete="off" value="{{ $model->max_score }}"> {{trans('backend.score')}}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.number_question_page')}}<span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="questions_perpage" required type="text" class="is-number form-control w-75 d-inline-block date-custom" autocomplete="off" value="{{ $model->questions_perpage }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.required_webcam')}}</label>
                </div>
                <div class="col-md-6">
                    <input name="webcam_require" type="checkbox" value="{{ $model->webcam_require }}" @if($model->webcam_require == 1) checked @endif>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.number_shots')}}</label>
                </div>
                <div class="col-md-6">
                    <input name="times_shooting_webcam" type="text" value="{{ $model->times_shooting_webcam }}" class="form-control is-number w-75" @if($model->webcam_require != 1) disabled @endif>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.require_answer')}}</label>
                </div>
                <div class="col-md-6">
                    <input name="question_require" type="checkbox" value="{{ $model->question_require }}" @if($model->question_require == 1) checked @endif>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.number_replies')}}</label>
                </div>
                <div class="col-md-6">
                    <input name="times_shooting_question" type="text" value="{{ $model->times_shooting_question }}" class="form-control is-number w-75" @if($model->question_require != 1) disabled @endif>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-right">
                <div class="btn-group act-btns">
                    @can('quiz-template-edit')
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcan
                    <a href="{{ route('module.quiz_template.manager') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                </div>
            </div>
            <p></p>
            <div class="form-group">
                <label class="w-100">{{trans('backend.picture')}} (300 x 160) <a href="javascript:void(0)" id="select-image" class="float-right">{{trans('backend.choose_picture')}}</a></label>

                <div id="image-review">
                    @if($model->img)
                        <img src="{{ image_file($model->img) }}" alt="" class="w-50">
                    @endif
                </div>
                <input name="img" id="image-select" type="text" class="d-none" value="{{ $model->img }}">
            </div>

            <div class="form-group">
                <label for="exam_form">{{trans('backend.quiz_form')}} <span class="text-danger">*</span></label>
                <select name="quiz_type" id="quiz_type" class="form-control select2" data-placeholder="{{trans('backend.choose_form')}}" required>
                    <option value=""></option>
                    @if($is_unit != 1)
                    <option value="1" {{ $model->quiz_type == 1 ? 'selected' : '' }}> {{trans('backend.online')}}</option>
                    <option value="2" {{ $model->quiz_type == 2 ? 'selected' : '' }}> {{trans('backend.offline')}}</option>
                    @endif
                    <option value="3" {{ $model->quiz_type == 3 ? 'selected' : '' }}> {{trans('backend.independent_exam')}}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="shuffle_question">{{trans('backend.shuffled_question')}}</label>
                <select name="shuffle_question" id="shuffle_question" class="form-control select2-default">
                    <option value="0" {{ ($model->shuffle_question == 0) ? 'selected' : '' }}> {{trans("backend.disable")}}</option>
                    <option value="1" {{ ($model->shuffle_question == 1) ? 'selected' : '' }}> {{trans("backend.enable")}}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="shuffle_answers">{{trans('backend.shuffle_answer')}}</label>
                <select name="shuffle_answers" id="shuffle_answers" class="form-control select2-default">
                    <option value="0" {{ ($model->shuffle_answers == 0) ? 'selected' : '' }}> {{trans("backend.disable")}}</option>
                    <option value="1" {{ ($model->shuffle_answers == 1) ? 'selected' : '' }}> {{trans("backend.enable")}}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="max_attempts">{{trans('backend.number_test')}} <span class="text-danger">*</span></label>
                <select name="max_attempts" id="max_attempts" class="form-control select2-default">
                    <option value="0" {{ ($model->max_attempts == 0) ? 'selected' : '' }}> {{trans('backend.unlimited')}}</option>
                    @for($i = 1; $i <= 10; $i++)
                        @php $ii = $i < 10 ? '0'. $i : $i @endphp
                        <option value="{{$ii}}" {{ ($model->max_attempts == $ii) ? 'selected' : '' }}> {{$ii}}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="grade_methor">{{trans('backend.scoring_method')}} <span class="text-danger">*</span></label>
                <select name="grade_methor" id="grade_methor" class="form-control select2-default">
                    <option value="1" {{ ($model->grade_methor == 1) ? 'selected' : '' }}> {{trans('backend.highest_time')}}</option>
                    <option value="2" {{ ($model->grade_methor == 2) ? 'selected' : '' }}> {{trans('backend.medium_score')}}</option>
                    <option value="3" {{ ($model->grade_methor == 3) ? 'selected' : '' }}> {{trans('backend.first_time_score')}}</option>
                    <option value="4" {{ ($model->grade_methor == 4) ? 'selected' : '' }}> {{trans('backend.last_point')}}</option>
                </select>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="paper_exam" {{ $model->paper_exam == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="paper_exam">{{trans('backend.paper_exam')}}</label>
                        <input type="hidden" name="paper_exam" class="check-paper-exam" value="{{ $model->paper_exam ? $model->paper_exam : '0' }}">
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
