@extends('layouts.backend')

@section('page_title', 'Mẫu khảo sát')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.survey.index') }}">{{trans('backend.survey')}}</a> <i class="uil uil-angle-right"></i>
            @if(isset($type))
            <a href="{{ route('module.survey.template') }}">{{trans('backend.survey_form')}}</a> <i class="uil uil-angle-right"></i>
            @endif
            <span class="font-weight-bold">Bài khảo sát</span>
        </h2>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="fcrse_2">
        <div class="_14d25">
            <div class="row">
                <div class="col-lg-12 col-md-6">
                        <div class="certi_form mt-3">
                            <div class="all_ques_lest">
                                @foreach($template->category as $cate_key => $category)
                                    <div class="ques_item mb-3">
                                        <h3 class="mb-0">{{ Str::ucfirst($category->name) }}</h3>
                                        <hr class="mt-1">
                                    </div>
                                    @foreach ($category->questions as $ques_key => $question)
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
                                                    </div>
                                                </div>
                                            @elseif ($question->type == "time")
                                                <div class="ui form survey ml-5">
                                                    <div class="grouped fields item-answer">
                                                        <input name="answer_essay[{{ $category->id }}][{{ $question->id }}]" class="form-control question-datepicker w-auto" type="text" placeholder="ngày/tháng/năm" autocomplete="off">
                                                    </div>
                                                </div>
                                            @elseif (in_array($question->type, ['matrix','matrix_text']))
                                                <div class="ui form survey ml-5">
                                                    <div class="grouped fields item-answer">
                                                        @php
                                                            $rows = $question->answers->where('is_row', '=', 1);
                                                            $cols = $question->answers->where('is_row', '=', 0);
                                                            $answer_row_col = $question->answers->where('is_row', '=', 10)->first();
                                                        @endphp
                                                        <table class="tDefault table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>{{ isset($answer_row_col) ? $answer_row_col->name : '#' }}</th>
                                                                    @foreach($cols as $ans_key => $answer_col)
                                                                        <th>{{ $answer_col->name }}</th>
                                                                    @endforeach
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($rows as $ans_row_key => $answer_row)
                                                                <tr>
                                                                    <th>{{ $answer_row->name }}</th>
                                                                    @foreach($cols as $ans_key => $answer_col)
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
                                            @else
                                                <div class="ui form survey ml-5">
                                                    <ul class="grouped fields item-answer sortable_type_{{ $question->type }}">
                                                        @foreach($question->answers as $ans_key => $answer)
                                                            @if($question->type == 'sort')
                                                                <li class="field fltr-radio m-0">
                                                                    <div class="ui">
                                                                        <div class="form-inline mb-1">
                                                                            <span class="mr-1">{{ $answer->name }}</span>
                                                                            <input type="text" name="text_answer[{{ $category->id }}][{{ $question->id }}][{{ $answer->id }}]" class="answer-item-sort form-control w-5" value="{{ $ans_key + 1 }}">
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
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                            <hr>
                            @if(isset($item->more_suggestions))
                                {{ trans('app.other_suggest') }}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <textarea class="w-100 form-control" name="more_suggestions" rows="5" placeholder="{{ trans('app.content') }}"></textarea>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.question-datepicker').datetimepicker({
        locale:'vi',
        format: 'DD/MM/YYYY'
    });

    $('#send').on('click', function() {
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
@stop
