@extends('layouts.app')

@section('page_title', 'Đánh giá sau khóa học')

@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/rating/css/rating.css') }}">
@endsection

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb" style="background: white;margin-bottom: 0;">
            <li>
                <a href="/"><i class="glyphicon glyphicon-home"></i> &nbsp;Trang chủ</a>
            </li>
            <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">»</li>
            <li>
                <a href="{{ $type == 1 ? route('module.online') : route('module.offline') }}"> {{ trans('backend.course') }} {{ $type == 1 ? 'Online' : 'Tập trung' }}</a>
            </li>
            <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">»</li>
            <li>
                <a href="{{ $type == 1 ? route('module.online.detail_online', ['id' => $item->id]) : route('module.offline.detail', ['id' => $item->id]) }}"> {{ $item->name }}</a>
            </li>
            <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">»</li>
            <li>{{ trans('backend.assessments') }}</li>
        </ol>

        <form action="{{ route('module.rating.save_rating_course') }}" method="post" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="rating_user_id" value="">
            <input type="hidden" name="course_type" value="{{ $type }}">
            <input type="hidden" name="course_id" value="{{ $item->id }}">
            <input type="hidden" name="template_id" value="{{ $item->template_id }}">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="text-center"><h5> {{trans("backend.assessment_after_the_course")}}</h5></div>
                </div>
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="control-label col-sm-5"><b>{{ trans('backend.course_code') }}:</b></label>
                                <div class="col-sm-7">
                                    {{ $item->code }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-5"><b>{{trans('backend.start_date')}}:</b></label>
                                <div class="col-sm-7">
                                    {{ get_date($item->start_date) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-5"><b>{{trans("backend.register_deadline")}}:</b></label>
                                <div class="col-sm-7">
                                    {{ get_date($item->register_deadline) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="control-label col-sm-5"><b>{{ trans('backend.course_name') }}:</b></label>
                                <div class="col-sm-7">
                                    {{ $item->name }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-5"><b>{{trans('backend.end_date')}}:</b></label>
                                <div class="col-sm-7">
                                    {{ get_date($item->end_date) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12" id="custom-template">
                            @foreach($template->category as $cate_key => $category)
                                <input type="hidden" name="user_category_id[]" value="">
                                <input type="hidden" name="category_id[]" value="{{ $category->id }}">
                                <input type="hidden" name="category_name[{{ $category->id }}]" value="{{ $category->name }}">

                                <div class="ques_item mb-3">
                                    <h3 class="mb-0">{{ Str::ucfirst($category->name) }}</h3>
                                    <hr class="mt-1">
                                </div>
                                @foreach ($category->questions as $ques_key => $question)
                                    <input type="hidden" name="user_question_id[{{ $category->id }}][]" value="">
                                    <input type="hidden" name="question_id[{{ $category->id }}][]" value="{{ $question->id }}">
                                    <input type="hidden" name="question_name[{{ $category->id }}][{{ $question->id }}]" value="{{ $question->name }}">
                                    <input type="hidden" name="type[{{ $category->id }}][{{ $question->id }}]" value="{{ $question->type }}">
                                    <input type="hidden" name="multiple[{{ $category->id }}][{{ $question->id }}]" value="{{ $question->multiple }}">

                                    <div class="ques_item mb-2">
                                        <div class="ques_title survey mb-1">
                                            <span>{{ ($ques_key + 1) .'. '. Str::ucfirst($question->name) }}</span>
                                        </div>
                                        @if ($question->type == "essay")
                                            <div class="ui search focus">
                                                <div class="ui form swdh30 survey">
                                                    <div class="field">
                                                        <textarea rows="3" name="answer_essay[{{ $category->id }}][{{ $question->id }}]" placeholder="{{ trans('backend.content') }}"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($question->type == 'dropdown')
                                            <div class="ui form survey ml-5">
                                                <div class="grouped fields item-answer">
                                                    <select name="answer_essay[{{ $category->id }}][{{ $question->id }}]" class="form-control select2" data-placeholder="Chọn đáp án">
                                                        <option value=""></option>
                                                        @foreach($question->answers as $ans_key => $answer)
                                                            <option value="{{ $answer->id }}">{{ $answer->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    @foreach($question->answers as $ans_key => $answer)
                                                        <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                                        <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer->id }}">
                                                        <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->name }}">
                                                        <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_text }}">
                                                        <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_row }}">
                                                    @endforeach
                                                </div>
                                            </div>
                                        @elseif ($question->type == "time")
                                            <div class="ui form survey ml-5">
                                                <div class="grouped fields item-answer">
                                                    <input name="answer_essay[{{ $category->id }}][{{ $question->id }}]" class="form-control question-datepicker w-auto" type="text"
                                                           placeholder="ngày/tháng/năm" autocomplete="off">
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
                                                                <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                                                <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer_col->id }}">
                                                                <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer_col->id }}]"
                                                                       value="{{ $answer_col->name }}">
                                                                <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer_col->id }}]" value="{{ $answer_col->is_text }}">
                                                                <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer_col->id }}]" value="{{ $answer_col->is_row }}">

                                                                <th>{{ $answer_col->name }}</th>
                                                            @endforeach
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($rows as $ans_row_key => $answer_row)
                                                            <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                                            <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer_row->id }}">
                                                            <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}]" value="{{ $answer_row->name }}">
                                                            <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}]" value="{{ $answer_row->is_text }}">
                                                            <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}]" value="{{ $answer_row->is_row }}">

                                                            <tr>
                                                                <th>{{ $answer_row->name }}</th>
                                                                @foreach($cols as $ans_key => $answer_col)
                                                                    <th class="text-center">
                                                                        @if($question->type == 'matrix')
                                                                            <input type="{{ $question->multiple != 1 ? 'radio' : 'checkbox' }}"
                                                                                   name="check_answer_matrix[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}][]" tabindex="0"
                                                                                   class="hidden" value="{{ $answer_col->id }}">
                                                                        @else
                                                                            <textarea rows="1" name="answer_matrix[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}][]"
                                                                                      class="form-control w-100"></textarea>
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
                                                        <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                                        <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer->id }}">
                                                        <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->name }}">
                                                        <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_text }}">
                                                        <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_row }}">

                                                        @if($question->type == 'sort')
                                                            <li class="field fltr-radio m-0">
                                                                <div class="ui">
                                                                    <div class="form-inline mb-1">
                                                                        <input type="text" name="text_answer[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]"
                                                                               class="answer-item-sort form-control w-5" value="{{ $ans_key + 1 }}">
                                                                        <span class="ml-1">{{ $answer->name }}</span>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endif

                                                        @if($question->type == 'text')
                                                            <div class="field fltr-radio m-0">
                                                                <div class="ui">
                                                                    <div class="input-group d-flex align-items-center mb-1">
                                                                        <span class="mr-1">{{ $answer->name }}</span>
                                                                        <textarea rows="1" name="text_answer[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" class="form-control w-auto"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if(in_array($question->type, ['number', 'percent']))
                                                            <div class="field fltr-radio m-0">
                                                                <div class="ui">
                                                                    <div class="form-inline mb-1">
                                                                        <span class="mr-1">{{ $answer->name }}</span>
                                                                        <input type="number" name="text_answer[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" class="form-control w-5">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if($question->type == 'choice')
                                                            <div class="field fltr-radio m-0">
                                                                <div class="ui mb-2">
                                                                    @if($question->multiple != 1)
                                                                        <input type="radio" name="is_check[{{ $category->id }}][{{ $question->id }}]" id="is_check{{$answer->id}}" tabindex="0"
                                                                               class="hidden" value="{{ $answer->id }}">
                                                                    @else
                                                                        <input type="checkbox" name="is_check[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" id="is_check{{$answer->id}}"
                                                                               tabindex="0" class="hidden" value="{{ $answer->id }}">
                                                                    @endif
                                                                    <label for="is_check{{$answer->id}}" class="mb-0">{{ $answer->name }}</label>
                                                                    @if($answer->is_text == 1)
                                                                        <input type="text" name="text_answer[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" class="form-control">
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
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">{{trans('backend.save')}}</button>
                    <button type="submit" id="send" class="btn btn-info"> {{trans("backend.sents")}} </button>
                    <input type="hidden" name="send" value="0">
                </div>
            </div>
            <p></p>
        </form>
    </div>
    <script>

        $('.question-datepicker').datetimepicker({
            locale:'vi',
            format: 'DD/MM/YYYY'
        });

        $(".sortable_type_sort").sortable({
            update : function () {
                $('input.answer-item-sort').each(function(idx) {
                    $(this).val(idx + 1);
                });
            }
        });

        $('#send').on('click', function () {
            $('input[name=send]').val(1);
        });
    </script>
@stop
