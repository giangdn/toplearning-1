@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ $type == 1 ? route('module.online.management') : route('module.offline.management') }}">{{ trans('backend.course') }} {{ $type == 1 ? 'offline' : 'tập trung' }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ $type == 1 ? route('module.online.edit', ['id' => $course_id]) : route('module.offline.edit', ['id' => $course_id]) }}">{{ $page_title }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.rating.result.index', ['course_id' => $course_id, 'type'=> $type]) }}">{{ trans('backend.result_of_evaluation') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $user_type ==1 ? $user->lastname .' '. $user->firstname : $user->name
            }}</span>
        </h2>
    </div>
@endsection

@section('content')

<div role="main">
    <div class="tPanel">
        <div class="tab-content">
            <div id="base" class="tab-pane active">
                <div class="row">
                    <div class="col-md-6">
                        <h5>{{trans('backend.course_info')}}</h5>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <b>{{ trans('backend.course_code') }}: </b> {{ $item->code }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <b>{{ trans('backend.course_name') }}: </b> {{ $item->name }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <b>{{ trans('backend.time') }}: </b> {{ get_date($item->start_date) .($item->end_date ? ' đến '. get_date($item->end_date) : '') }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <b>{{ trans('backend.register_deadline') }}: </b> {{ get_date($item->register_deadline) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>{{trans('backend.info_surveyor')}}</h5>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <b> {{ trans('backend.employee_code') }}: </b> {{ $user->code }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <b>{{ trans('backend.fullname') }}: </b> {{ $user_type == 1 ? $user->lastname .' '. $user->firstname : $user->name }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <b>{{ trans('backend.unit') }}: </b> @if($unit) {{ $unit->name }} @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <b>{{ trans('backend.title') }}: </b> @if($title) {{ $title->name }} @endif
                            </div>
                        </div>
                    </div>
                </div>

                <h5>{{trans('backend.answers')}}</h5>
                <div class="form-group row">
                    <div class="col-12">
                        @foreach($rating_course_categories as $cate_key => $category)
                            <input type="hidden" name="user_category_id[]" value="{{ $category->id }}">
                            <input type="hidden" name="category_id[]" value="{{ $category->category_id }}">
                            <input type="hidden" name="category_name[{{ $category->category_id }}]" value="{{ $category->category_name }}">

                            <div class="ques_item mb-3">
                                <h3 class="mb-0">{{ Str::ucfirst($category->category_name) }}</h3>
                                <hr class="mt-1">
                            </div>
                            @foreach ($category->questions as $ques_key => $question)
                                <input type="hidden" name="user_question_id[{{ $category->category_id }}][]" value="{{ $question->id }}">
                                <input type="hidden" name="question_id[{{ $category->category_id }}][]" value="{{ $question->question_id }}">
                                <input type="hidden" name="question_name[{{ $category->category_id }}][{{ $question->question_id }}]" value="{{ $question->question_name }}">
                                <input type="hidden" name="type[{{ $category->category_id }}][{{ $question->question_id }}]" value="{{ $question->type }}">
                                <input type="hidden" name="multiple[{{ $category->category_id }}][{{ $question->question_id }}]" value="{{ $question->multiple }}">

                                <div class="ques_item mb-2">
                                    <div class="ques_title survey mb-1">
                                        <span>{{ ($ques_key + 1) .'. '. Str::ucfirst($question->question_name) }}</span>
                                    </div>
                                    @if ($question->type == "essay")
                                        <div class="ui search focus">
                                            <div class="ui form swdh30 survey">
                                                <div class="field">
                                                    <textarea rows="3" name="answer_essay[{{ $category->category_id }}][{{ $question->question_id }}]" placeholder="{{ trans('backend.content') }}">{{ $question->answer_essay }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($question->type == 'dropdown')
                                        <div class="ui form survey ml-5">
                                            <div class="grouped fields item-answer">
                                                <select name="answer_essay[{{ $category->category_id }}][{{ $question->question_id }}]" class="form-control select2" data-placeholder="Chọn đáp án">
                                                    <option value=""></option>
                                                    @foreach($question->answers as $ans_key => $answer)
                                                        <option value="{{ $answer->answer_id }}" {{ $question->answer_essay == $answer->answer_id ? 'selected' : '' }}>
                                                            {{ $answer->answer_name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @foreach($question->answers as $ans_key => $answer)
                                                    <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->id }}">
                                                    <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->answer_id }}">
                                                    <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_name }}">
                                                    <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_text }}">
                                                    <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_row }}">
                                                @endforeach
                                            </div>
                                        </div>
                                    @elseif ($question->type == "time")
                                        <div class="ui form survey ml-5">
                                            <div class="grouped fields item-answer">
                                                <input name="answer_essay[{{ $category->category_id }}][{{ $question->question_id }}]" class="form-control question-datepicker w-auto" type="text" placeholder="ngày/tháng/năm" autocomplete="off" value="{{ $question->answer_essay }}">
                                            </div>
                                        </div>
                                    @elseif (in_array($question->type, ['matrix','matrix_text']))
                                        <div class="ui form survey ml-5">
                                            <div class="grouped fields item-answer">
                                                @php
                                                    $rows = $question->answers->where('is_row', '=', 1);
                                                    $cols = $question->answers->where('is_row', '=', 0);
                                                @endphp
                                                <table class="tDefault table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        @foreach($cols as $ans_key => $answer_col)
                                                            <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer_col->id }}">
                                                            <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer_col->answer_id }}">
                                                            <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_col->answer_id }}]" value="{{ $answer_col->answer_name }}">
                                                            <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_col->answer_id }}]" value="{{ $answer_col->is_text }}">
                                                            <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_col->answer_id }}]" value="{{ $answer_col->is_row }}">

                                                            <th>{{ $answer_col->answer_name }}</th>
                                                        @endforeach
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($rows as $ans_row_key => $answer_row)
                                                        <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer_row->id }}">
                                                        <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer_row->answer_id }}">
                                                        <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row->answer_id }}]" value="{{ $answer_row->answer_name }}">
                                                        <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row->answer_id }}]" value="{{ $answer_row->is_text }}">
                                                        <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row->answer_id }}]" value="{{ $answer_row->is_row }}">

                                                        @php
                                                            $check_answer_matrix = json_decode($answer_row->check_answer_matrix);
                                                            $answer_matrix = json_decode($answer_row->answer_matrix);
                                                        @endphp
                                                        <tr>
                                                            <th>{{ $answer_row->answer_name }}</th>
                                                            @foreach($cols as $ans_key => $answer_col)
                                                                <th class="text-center">
                                                                    @if($question->type == 'matrix')
                                                                        <input type="{{ $question->multiple != 1 ? 'radio' : 'checkbox' }}" name="check_answer_matrix[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row->answer_id }}][]" tabindex="0" class="hidden" value="{{ $answer_col->answer_id }}" {{ in_array($answer_col->answer_id, $check_answer_matrix) ? 'checked' : '' }}>
                                                                    @else
                                                                        <textarea rows="1" name="answer_matrix[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer_row->answer_id }}][]"  class="form-control w-100">{{ isset($answer_matrix) ? $answer_matrix[$ans_key] : '' }}</textarea>
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
                                            <ul class="grouped fields item-answer sortable_type_{{ $question->type }}">
                                                @foreach($question->answers as $ans_key => $answer)
                                                    <input type="hidden" name="user_answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->id }}">
                                                    <input type="hidden" name="answer_id[{{ $category->category_id }}][{{ $question->question_id }}][]" value="{{ $answer->answer_id }}">
                                                    <input type="hidden" name="answer_name[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->answer_name }}">
                                                    <input type="hidden" name="is_text[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_text }}">
                                                    <input type="hidden" name="is_row[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" value="{{ $answer->is_row }}">

                                                    @if($question->type == 'sort')
                                                        <li class="field fltr-radio m-0">
                                                            <div class="ui">
                                                                <div class="form-inline mb-1">
                                                                    <input type="text" name="text_answer[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" class="answer-item-sort form-control w-5" value="{{ $answer->text_answer }}">
                                                                    <span class="ml-1">{{ $answer->answer_name }}</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif

                                                    @if($question->type == 'text')
                                                        <div class="field fltr-radio m-0">
                                                            <div class="ui">
                                                                <div class="input-group mb-1 d-flex align-items-center">
                                                                    <span class="mr-1">{{ $answer->answer_name }}</span>
                                                                    <textarea rows="1" name="text_answer[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" class="form-control w-auto">{{ $answer->text_answer }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if(in_array($question->type, ['number', 'percent']))
                                                        <div class="field fltr-radio m-0">
                                                            <div class="ui">
                                                                <div class="form-inline mb-1">
                                                                    <span class="mr-1">{{ $answer->answer_name }}</span>
                                                                    <input type="number" name="text_answer[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" class="form-control w-5" value="{{ $answer->text_answer }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($question->type == 'choice')
                                                        <div class="field fltr-radio m-0">
                                                            <div class="ui mb-2">
                                                                @if($question->multiple != 1)
                                                                    <input type="radio" name="is_check[{{ $category->category_id }}][{{ $question->question_id }}]" id="is_check{{$answer->answer_id}}" tabindex="0" class="hidden" value="{{ $answer->answer_id }}" {{ $answer->is_check ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="checkbox" name="is_check[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" id="is_check{{$answer->answer_id}}" tabindex="0" class="hidden" value="{{ $answer->answer_id }}" {{ $answer->is_check ? 'checked' : '' }}>
                                                                @endif
                                                                <label for="is_check{{$answer->answer_id}}" class="mb-0">{{ $answer->answer_name }}</label>
                                                                @if($answer->is_text == 1)
                                                                    <input type="text" name="text_answer[{{ $category->category_id }}][{{ $question->question_id }}][{{ $answer->answer_id }}]" class="form-control" value="{{ $answer->text_answer }}">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
