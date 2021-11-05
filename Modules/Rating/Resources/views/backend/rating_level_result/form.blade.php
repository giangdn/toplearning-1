@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ $course_type == 1 ? route('module.online.management') : route('module.offline.management') }}">{{ trans("backend.course") }} {{ $course_type == 1 ? 'online' : 'tập trung' }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ $course_type == 1 ? route('module.online.edit', ['id' => $course_id]) : route('module.offline.edit', ['id' => $course_id]) }}">{{ $page_title }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.rating_level.result.list_course_register', [$course_id, $course_type]) }}">Kết quả đánh giá cấp độ</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.rating_level.result.index', [$course_id, $course_type, $user_id]) }}">{{ $full_name }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Bài đánh giá</span>
        </h2>
    </div>
@endsection

@section('content')

<div role="main">
    <div class="tPanel">
        <div class="tab-content">
            <div id="base" class="tab-pane active">
                <div class="form-group row">
                    <div class="col-12">
                        @foreach($rating_course_categories as $cate_key => $category)
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
                                                    <textarea rows="3" placeholder="{{ trans('backend.content') }}">{{ $question->answer_essay }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($question->type == 'dropdown')
                                        <div class="ui form survey ml-5">
                                            <div class="grouped fields item-answer">
                                                <select class="form-control select2" data-placeholder="Chọn đáp án">
                                                    <option value=""></option>
                                                    @foreach($question->answers as $ans_key => $answer)
                                                        <option value="{{ $answer->answer_id }}" {{ $question->answer_essay == $answer->answer_id ? 'selected' : '' }}>
                                                            {{ $answer->answer_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @elseif ($question->type == "time")
                                        <div class="ui form survey ml-5">
                                            <div class="grouped fields item-answer">
                                                <input class="form-control question-datepicker w-auto" type="text" placeholder="ngày/tháng/năm" autocomplete="off" value="{{ $question->answer_essay }}">
                                            </div>
                                        </div>
                                    @elseif (in_array($question->type, ['matrix','matrix_text']))
                                        <div class="ui form survey ml-5">
                                            <div class="grouped fields item-answer">
                                                @php
                                                    $answer_row_col = $question->answers->where('is_row', '=', 10)->first();
                                                    $rows = $question->answers->where('is_row', '=', 1);
                                                    $cols = $question->answers->where('is_row', '=', 0);
                                                @endphp
                                                <table class="tDefault table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>{{ isset($answer_row_col) ? $answer_row_col->answer_name : '#' }}</th>
                                                        @foreach($cols as $ans_key => $answer_col)
                                                            <th>{{ $answer_col->answer_name }}</th>
                                                        @endforeach
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($rows as $ans_row_key => $answer_row)
                                                        @php
                                                            $check_answer_matrix = $answer_row->check_answer_matrix ? json_decode($answer_row->check_answer_matrix) : [];
                                                            $answer_matrix = json_decode($answer_row->answer_matrix);
                                                        @endphp
                                                        <tr>
                                                            <th>{{ $answer_row->answer_name }}</th>
                                                            @foreach($cols as $ans_key => $answer_col)
                                                                <th class="text-center">
                                                                    @if($question->type == 'matrix')
                                                                        <input type="{{ $question->multiple != 1 ? 'radio' : 'checkbox' }}" tabindex="0" class="hidden" {{ in_array($answer_col->answer_id, $check_answer_matrix) ? 'checked' : '' }}>
                                                                    @else
                                                                        <textarea rows="1" class="form-control w-100">{{ isset($answer_matrix) ? $answer_matrix[$ans_key-1] : '' }}</textarea>
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
                                                    @if($question->type == 'sort')
                                                        <li class="field fltr-radio m-0">
                                                            <div class="ui">
                                                                <div class="form-inline mb-1">
                                                                    <span class="mr-1">{{ $answer->answer_name }}</span>
                                                                    <input type="text" class="answer-item-sort form-control w-5" value="{{ $answer->text_answer }}">
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif

                                                    @if($question->type == 'text')
                                                        <div class="field fltr-radio m-0">
                                                            <div class="ui">
                                                                <div class="input-group mb-1 d-flex align-items-center">
                                                                    <span class="mr-1">{{ $answer->answer_name }}</span>
                                                                    <textarea rows="1" class="form-control w-auto">{{ $answer->text_answer }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if(in_array($question->type, ['number', 'percent']))
                                                        <div class="field fltr-radio m-0">
                                                            <div class="ui">
                                                                <div class="form-inline mb-1">
                                                                    <span class="mr-1">{{ $answer->answer_name }}</span>
                                                                    <input type="number" class="form-control w-5" value="{{ $answer->text_answer }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($question->type == 'choice')
                                                        <div class="field fltr-radio m-0">
                                                            <div class="ui mb-2">
                                                                @if($question->multiple != 1)
                                                                    <input type="radio" tabindex="0" class="hidden" {{ $answer->is_check ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="checkbox" tabindex="0" class="hidden" {{ $answer->is_check ? 'checked' : '' }}>
                                                                @endif
                                                                <label for="is_check{{$answer->answer_id}}" class="mb-0">{{ $answer->answer_name }}</label>
                                                                @if($answer->is_text == 1)
                                                                    <input type="text" class="form-control" value="{{ $answer->text_answer }}">
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
