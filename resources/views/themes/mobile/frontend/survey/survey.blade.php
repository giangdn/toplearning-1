@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.survey'))

@section('header')
    <style>
        .form-control, .input-group, .input-group > .form-control:last-child{
            border-radius: unset;
            border: none;
        }

         .sortable_type_sort li:hover{
             cursor: grabbing;
         }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card shadow border-0 mb-3 bg-primary">
            <div class="card-body">
                <div class="row align-items-center text-white">
                    <div class="col align-self-center border-right">
                        <h6 class="font-weight-normal mb-1">{{ $item->name }}</h6>
                    </div>
                    <div class="col-3 align-self-center text-right">
                        <strong>{{ $item->countQuestion() }}</strong> <img src="{{ asset('themes/mobile/img/faq.png') }}" alt="" class="avatar-40">
                    </div>
                </div>
            </div>
        </div>
        <form action="{{ route('module.survey.user.save') }}" method="post" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="survey_user_id" value="">
            <input type="hidden" name="survey_id" value="{{ $item->id }}">
            <input type="hidden" name="template_id" value="{{ $item->template_id }}">
            @php
                $categories = $template->category->where('survey_id', '=', $item->id);
            @endphp
            @foreach($categories as $cate_key => $category)
                <input type="hidden" name="user_category_id[]" value="">
                <input type="hidden" name="category_id[]" value="{{ $category->id }}">
                <input type="hidden" name="category_name[{{ $category->id }}]" value="{{ $category->name }}">

                <div class="item-category">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <h6>{{ $category->name }}</h6>
                            <hr class="mt-0 mb-1">
                        </div>
                    </div>
                    @php
                        $questions = $category->questions->where('survey_id', '=', $item->id);
                        $num_ques = 1;
                    @endphp
                    @foreach ($questions as $ques_key => $question)
                        <input type="hidden" name="user_question_id[{{ $category->id }}][]" value="">
                        <input type="hidden" name="question_id[{{ $category->id }}][]" value="{{ $question->id }}">
                        <input type="hidden" name="question_code[{{ $category->id }}][{{ $question->id }}]" value="{{ $question->code }}">
                        <input type="hidden" name="question_name[{{ $category->id }}][{{ $question->id }}]" value="{{ $question->name }}">
                        <input type="hidden" name="type[{{ $category->id }}][{{ $question->id }}]" value="{{ $question->type }}">
                        <input type="hidden" name="multiple[{{ $category->id }}][{{ $question->id }}]" value="{{ $question->multiple }}">

                        <div class="ques_item mb-2">
                            <div class="ques_title survey mb-1">
                                <span>{{ $num_ques .'. '. Str::ucfirst($question->name) }}</span>
                            </div>
                            @if ($question->type == "essay")
                                <div class="ui focus">
                                    <div class="ui form swdh30 survey">
                                        <div class="field">
                                            <textarea rows="3" class="w-100" name="answer_essay[{{ $category->id }}][{{ $question->id }}]" placeholder="{{ trans('backend.content') }}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            @elseif($question->type == 'dropdown')
                                <div class="ui form survey">
                                    <div class="grouped fields item-answer">
                                        <select name="answer_essay[{{ $category->id }}][{{ $question->id }}]" class="form-control select2" data-placeholder="Chọn đáp án">
                                            <option value=""></option>

                                            @foreach($question->answers->where('survey_id', '=', $item->id) as $ans_key => $answer)
                                                <option value="{{ $answer->id }}">{{ $answer->name }}</option>
                                            @endforeach
                                        </select>

                                        @foreach($question->answers->where('survey_id', '=', $item->id) as $ans_key => $answer)
                                            <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                            <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer->id }}">
                                            <input type="hidden" name="answer_code[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->code }}">
                                            <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->name }}">
                                            <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_text }}">
                                            <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_row }}">
                                        @endforeach
                                    </div>
                                </div>
                            @elseif ($question->type == "time")
                                <div class="ui form survey">
                                    <div class="grouped fields item-answer">
                                        <input name="answer_essay[{{ $category->id }}][{{ $question->id }}]" class="form-control question-datepicker w-auto" type="text" placeholder="ngày/tháng/năm" autocomplete="off">
                                    </div>
                                </div>
                            @elseif (in_array($question->type, ['matrix','matrix_text']))
                                <div class="ui form survey">
                                    <div class="grouped fields item-answer" style="overflow-x:auto;">
                                        @php
                                            $rows = $question->answers->where('survey_id', '=', $item->id)->where('is_row', '=', 1);
                                            $cols = $question->answers->where('survey_id', '=', $item->id)->where('is_row', '=', 0);
                                            $answer_row_col = $question->answers->where('survey_id', '=', $item->id)->where('is_row', '=', 10)->first();
                                        @endphp
                                        <table class="tDefault table table-bordered table-responsive">
                                            <thead>
                                            <tr>
                                                @if(isset($answer_row_col))
                                                    <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                                    <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer_row_col->id }}">
                                                    <input type="hidden" name="answer_code[{{ $category->id }}][{{ $question->id }}][{{ $answer_row_col->id }}]" value="{{ $answer_row_col->code }}">
                                                    <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer_row_col->id }}]" value="{{ $answer_row_col->name }}">
                                                    <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer_row_col->id }}]" value="{{ $answer_row_col->is_text }}">
                                                    <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer_row_col->id }}]" value="{{ $answer_row_col->is_row }}">

                                                    <th>{{ $answer_row_col->name }}</th>
                                                @else
                                                    <th>#</th>
                                                @endif
                                                @foreach($cols as $ans_key => $answer_col)
                                                    <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                                    <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer_col->id }}">
                                                    <input type="hidden" name="answer_code[{{ $category->id }}][{{ $question->id }}][{{ $answer_col->id }}]" value="{{ $answer_col->code }}">
                                                    <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer_col->id }}]" value="{{ $answer_col->name }}">
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
                                                <input type="hidden" name="answer_code[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}]" value="{{ $answer_row->code }}">
                                                <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}]" value="{{ $answer_row->name }}">
                                                <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}]" value="{{ $answer_row->is_text }}">
                                                <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}]" value="{{ $answer_row->is_row }}">

                                                <tr>
                                                    <th>{{ $answer_row->name }}</th>
                                                    @foreach($cols as $ans_key => $answer_col)
                                                        @php
                                                            $matrix_anser_code = $question->answers_matrix->where('survey_id', '=', $item->id)->where('answer_row_id', '=', $answer_row->id)->where('answer_col_id', '=', $answer_col->id)->first();
                                                        @endphp
                                                        @if(isset($matrix_anser_code))
                                                            <input type="hidden" name="answer_matrix_code[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}][{{ $answer_col->id }}]" value="{{ $matrix_anser_code->code }}">
                                                        @endif

                                                        <th class="text-center">
                                                            @if($question->type == 'matrix')
                                                                <input type="{{ $question->multiple != 1 ? 'radio' : 'checkbox' }}" name="check_answer_matrix[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}][]" tabindex="0" class="hidden" value="{{ $answer_col->id }}">
                                                            @else
                                                                <textarea rows="1" name="answer_matrix[{{ $category->id }}][{{ $question->id }}][{{ $answer_row->id }}][]"  class="form-control w-100"></textarea>
                                                            @endif
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            @elseif($question->type == 'sort')
                                @php
                                    $num_sort = 1;
                                @endphp
                                <div class="ui form survey">
                                    <div class="grouped fields item-answer sortable_type_sort">
                                        @foreach($question->answers->where('survey_id', '=', $item->id) as $ans_key => $answer)
                                            <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                            <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer->id }}">
                                            <input type="hidden" name="answer_code[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->code }}">
                                            <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->name }}">
                                            <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_text }}">
                                            <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_row }}">

                                            <div class="field fltr-radio m-0">
                                                <div class="ui">
                                                    <div class="form-inline mb-1">
                                                        <span class="mr-1">{{ $answer->name }}</span>
                                                        <input type="text" name="text_answer[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" class="answer-item-sort form-control w-5" value="{{ $num_sort }}">
                                                    </div>
                                                </div>
                                            </div>

                                            @php
                                                $num_sort += 1;
                                            @endphp
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="ui form survey">
                                    <div class="grouped fields item-answer">
                                        @foreach($question->answers->where('survey_id', '=', $item->id) as $ans_key => $answer)
                                            <input type="hidden" name="user_answer_id[{{ $category->id }}][{{ $question->id }}][]" value="">
                                            <input type="hidden" name="answer_id[{{ $category->id }}][{{ $question->id }}][]" value="{{ $answer->id }}">
                                            <input type="hidden" name="answer_code[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->code }}">
                                            <input type="hidden" name="answer_name[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->name }}">
                                            <input type="hidden" name="is_text[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_text }}">
                                            <input type="hidden" name="is_row[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" value="{{ $answer->is_row }}">

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
                                                            <input type="text" name="text_answer[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" class="form-control w-5">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($question->type == 'choice')
                                                <div class="field fltr-radio m-0">
                                                    <div class="ui mb-2">
                                                        @if($question->multiple != 1)
                                                            <input type="radio" name="is_check[{{ $category->id }}][{{ $question->id }}]" id="is_check{{$answer->id}}" tabindex="0" class="hidden" value="{{ $answer->id }}">
                                                        @else
                                                            <input type="checkbox" name="is_check[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" id="is_check{{$answer->id}}" tabindex="0" class="hidden" value="{{ $answer->id }}">
                                                        @endif
                                                        <label for="is_check{{$answer->id}}" class="mb-0">{{ $answer->name }}</label>
                                                        @if($answer->is_text == 1)
                                                            <input type="text" name="text_answer[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" class="form-control">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        @php
                            $num_ques += 1;
                        @endphp
                    @endforeach
                </div>
                <hr>
            @endforeach
            @if($item->more_suggestions)
                {{ trans('app.other_suggest') }}
                <div class="row">
                    <div class="col-sm-12">
                        <textarea class="w-100 border-0" name="more_suggestions" rows="5" placeholder="{{ trans('app.content') }}"></textarea>
                    </div>
                </div>
            @endif
            <div class="card-footer text-center">
                <a href="{{ route('module.survey') }}" class="btn btn-danger">{{ trans('app.close') }}</a>
                <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> {{ trans('app.save') }}</button>
                <button type="submit" id="send" class="btn btn-info"> <i class="fa fa-location-arrow"></i> {{ trans('app.send') }}</button>
                <input type="hidden" name="send" value="0">
            </div>
        </form>
    </div>
@endsection

@section('footer')
    <script>
        $('.question-datepicker').datetimepicker({
            locale:'vi',
            format: 'DD/MM/YYYY'
        });

        $('#send').on('click', function () {
            $('input[name=send]').val(1);
        });

        $(".sortable_type_sort").sortable({
            update : function () {
                $('input.answer-item-sort').each(function(idx) {
                    $(this).val(idx + 1);
                });
            }
        });

        $(".sortable_type_sort").disableSelection();
    </script>
@endsection
