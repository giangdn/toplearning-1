@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.survey.index') }}">{{trans('backend.survey')}}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.survey.report.index', ['survey_id' => $survey->id]) }}">{{trans("backend.report")}}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

<div role="main">
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
                        <h5>{{trans('backend.info_survey')}}</h5>
                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label>{{trans('backend.survey_name')}}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" value="{{ $survey->name }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label>{{trans('backend.start_date')}}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" value="{{ get_date($survey->start_date) }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label>{{trans('backend.end_date')}}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" value="{{ get_date($survey->end_date) }}" disabled>
                            </div>
                        </div>
                        <h5>{{trans('backend.info_surveyor')}}</h5>
                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                {{trans('backend.employee_code')}}
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" value="{{ $user->code }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label>{{trans('backend.fullname')}}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" value="{{ $user->lastname . ' ' . $user->firstname }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label>{{ trans('backend.unit') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" value="{{ @$unit->name }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2 control-label">
                                <label>{{ trans('backend.title') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" value="{{ @$title->name }}" disabled>
                            </div>
                        </div>
                        <h5>{{trans('backend.answers')}}</h5>
                        <div class="form-group row">
                            <div class="col-md-12">
                                @foreach($survey_user_categories as $cate_key => $category)
                                    <div class="ques_item mb-3">
                                        <h3 class="mb-0">{{ Str::ucfirst($category->category_name) }}</h3>
                                        <hr class="mt-1">
                                    </div>
                                    @foreach ($category->questions as $ques_key => $question)
                                        <div class="ques_item mb-2">
                                            <div class="ques_title survey mb-1">
                                                <span>{{ ($ques_key + 1) .'. '. Str::ucfirst($question->question_name) }}</span>
                                            </div>
                                            @if ($question->type == "essay")
                                                <div class="ui search focus">
                                                    <div class="ui form swdh30 survey">
                                                        <div class="field">
                                                            <textarea rows="3" disabled>{{ $question->answer_essay }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($question->type == 'dropdown')
                                                <div class="ui form survey ml-5">
                                                    <div class="grouped fields item-answer">
                                                        <select class="form-control select2" data-placeholder="Chọn đáp án" disabled>
                                                            <option value=""></option>
                                                            @foreach($question->answers as $ans_key => $answer)
                                                                <option {{ $question->answer_essay == $answer->answer_id ? 'selected' : '' }}>
                                                                    {{ $answer->answer_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif ($question->type == "time")
                                                <div class="ui form survey ml-5">
                                                    <div class="grouped fields item-answer">
                                                        <input class="form-control w-auto" type="text" disabled value="{{ $question->answer_essay }}">
                                                    </div>
                                                </div>
                                            @elseif (in_array($question->type, ['matrix','matrix_text']))
                                                <div class="ui form survey ml-5">
                                                    <div class="grouped fields item-answer">
                                                        @php
                                                            $rows = $question->answers->where('is_row', '=', 1);
                                                            $cols = $question->answers->where('is_row', '=', 0);
                                                            $row_cols = $question->answers->where('is_row', '=', 10)->first();
                                                        @endphp
                                                        <table class="tDefault table">
                                                            <thead>
                                                            <tr>
                                                                <th>{{ isset($row_cols) ? $row_cols->answer_name : '#' }}</th>
                                                                @foreach($cols as $ans_key => $answer_col)
                                                                    <th>{{ $answer_col->answer_name }}</th>
                                                                @endforeach
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($rows as $ans_row_key => $answer_row)
                                                                @php
                                                                    $check_answer_matrix = isset($answer_row->check_answer_matrix) ? json_decode($answer_row->check_answer_matrix) : [];
                                                                    $answer_matrix = json_decode($answer_row->answer_matrix);
                                                                @endphp
                                                                <tr>
                                                                    <th>{{ $answer_row->answer_name }}</th>
                                                                    @foreach($cols as $ans_key => $answer_col)
                                                                        <th class="text-center">
                                                                            @if($question->type == 'matrix')
                                                                                <input type="{{ $question->multiple != 1 ? 'radio' : 'checkbox' }}" disabled {{ in_array($answer_col->answer_id, $check_answer_matrix) ? 'checked' : '' }}>
                                                                            @else
                                                                                <input type="text" class="form-control w-100" disabled value="{{ isset($answer_matrix) ? $answer_matrix[$ans_key - 1] : '' }}">
                                                                            @endif
                                                                        </th>
                                                                    @endforeach
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            @else
                                                <div class="ui form survey ml-5">
                                                    <div class="grouped fields item-answer">
                                                        @foreach($question->answers as $ans_key => $answer)
                                                            @if(in_array($question->type, ['text', 'sort', 'percent', 'number']))
                                                                <div class="field fltr-radio m-0">
                                                                    <div class="ui">
                                                                        <div class="input-group mb-1">
                                                                            <span class="mr-1">{{ $answer->answer_name }}</span>
                                                                            <input type="text" class="form-control w-auto" disabled value="{{ $answer->text_answer }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if($question->type == 'choice')
                                                                <div class="field fltr-radio m-0">
                                                                    <div class="ui mb-2">
                                                                        @if($question->multiple != 1)
                                                                            <input type="radio" disabled {{ $answer->is_check ? 'checked' : '' }}>
                                                                        @else
                                                                            <input type="checkbox" disabled {{ $answer->is_check ? 'checked' : '' }}>
                                                                        @endif
                                                                        {{ $answer->answer_name }}
                                                                        @if($answer->is_text == 1)
                                                                            <input type="text" class="form-control" disabled value="{{ $answer->text_answer }}">
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                            <div class="col-sm-12">
                                <div class="ques_item mb-3">
                                    <hr class="mt-1">
                                    <h3 class="mb-0">{{ trans('app.other_suggest') }}</h3>
                                </div>
                                <textarea class="form-control" disabled>{{ $survey_user->more_suggestions }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
