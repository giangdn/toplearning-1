<form method="post" action="{{ route('module.survey.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ $model->id }}">
    <div class="row">
        <div class="col-md-8">
        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group act-btns">
                @canany(['survey-create', 'survey-edit'])
                <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{trans('backend.save')}}</button>
                @endcanany
                <a href="{{ route('module.survey.index') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{trans('backend.cancel')}}</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.survey_name')}} <span class="text-danger">*</span> </label>
                </div>
                <div class="col-md-9">
                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.picture')}} (300 x 160)</label>
                </div>
                <div class="col-md-4">
                    <a href="javascript:void(0)" id="select-image">{{trans('backend.choose_picture')}}</a>
                    <div id="image-review" >
                        @if($model->image)
                            <img class="w-100" src="{{ image_file($model->image) }}" alt="">
                        @endif
                    </div>
                    <input name="image" id="image-select" type="text" class="d-none" value="{{ $model->image ? $model->image : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 control-label">{{trans('backend.time')}} <span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <div>
                        <input name="start_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('backend.start')}}" autocomplete="off" value="{{ get_date($model->start_date) }}">
                        <select name="start_hour" id="start_hour" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0 ; $i <= 23 ; $i++)
                                @php $ii = $i < 10 ? '0'.$i : $i @endphp
                                <option value="{{$ii}}" {{ get_date($model->start_date, 'H') == $ii ? 'selected' : '' }}>{{$ii}}</option>
                            @endfor
                        </select>
                        giờ
                        <select name="start_min" id="start_min" class="form-control d-inline-block  w-25 date-custom">
                            @for($i = 0; $i <= 59; $i += 1)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}" {{ get_date($model->start_date, 'i') == $ii ? 'selected' : '' }}>{{$ii}}</option>
                            @endfor
                        </select>
                        phút
                    </div>
                    <div>
                        <input name="end_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('backend.over')}}" autocomplete="off" value="{{ get_date($model->end_date) }}">
                        <select name="end_hour" id="end_hour" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0 ; $i <= 23 ; $i++)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}" {{ get_date($model->end_date, 'H') == $ii ? 'selected' : '' }}>{{$ii}}</option>
                            @endfor
                        </select>
                        giờ
                        <select name="end_min" id="end_min" class="form-control d-inline-block w-25 date-custom">
                            @for($i = 0; $i <= 59; $i += 1)
                                @php $ii = $i < 10 ? '0'. $i : $i @endphp
                                <option value="{{$ii}}" {{ get_date($model->end_date, 'i') == $ii ? 'selected' : '' }}>{{$ii}}</option>
                            @endfor
                        </select>
                        phút
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="template_id">{{trans('backend.survey_form')}} <span class="text-danger">*</span> </label>
                </div>
                <div class="col-md-9">
                    @if($survey_templates)
                        @if(isset($surver_user))
                            <input type="hidden" name="template_id" value="{{ $model->template_id }}">
                        @endif

                    <select class="form-control select2" name="template_id" id="template_id" data-placeholder="-- {{trans('backend.choose_survey_form')}} --"  {{ isset($surver_user) ? 'disabled' : '' }}>
                        <option value=""></option>
                        @foreach($survey_templates as $survey_template)
                            <option value="{{ $survey_template->id }}" {{ $model->template_id == $survey_template->id ? 'selected' : '' }}>
                                {{ $survey_template->name }}
                            </option>
                        @endforeach
                    </select>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.another_suggestion')}}</label>
                </div>
                <div class="col-sm-6">
                    <label class="radio-inline"><input type="radio" name="more_suggestions" value="1" @if($model->more_suggestions == 1) checked @endif>{{trans('backend.enable')}}</label>
                    <label class="radio-inline"><input type="radio" name="more_suggestions" value="0" @if($model->more_suggestions == 0) checked @endif>{{trans('backend.disable')}}</label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.status')}}</label>
                </div>
                <div class="col-md-6">
                    <label class="radio-inline"><input type="radio" name="status" value="1" @if($model->status == 1) checked @endif>{{trans('backend.enable')}}</label>
                    <label class="radio-inline"><input type="radio" name="status" value="0" @if($model->status == 0) checked @endif>{{trans('backend.disable')}}</label>
                </div>
            </div>
            {{--<div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Mẫu tuỳ chỉnh</label>
                </div>
                <div class="col-md-9">
                    <textarea type="text" class="form-control" name="custom_template" rows="5"> {{ $model->custom_template }} </textarea>
                </div>
            </div>--}}
        </div>
    </div>
</form>
<script>
    $("#select-image").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review").html('<img class="w-100" src="' + path + '">');
            $("#image-select").val(path);
        });
    });
</script>
