@extends('layouts.backend')

@section('page_title', $page_title)

@section('header')
    <style>
        /*#input-category .form-control{
            border: unset;
        }*/
        #input-category .btn-remove,
        #input-category th .btn-remove-col-matrix,
        #input-category th .btn-remove-row-matrix{
            display: none;
        }

        #input-category th:hover .btn-remove-col-matrix{
            display: block;
        }

        #input-category .item-category .input-group:hover .btn-remove,
        #input-category .item-question .input-group:hover .btn-remove,
        #input-category .item-answer .input-group:hover .btn-remove,
        #input-category th:hover .btn-remove-row-matrix{
            display: flex;
        }
    </style>
@endsection

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.survey.index') }}">{{trans('backend.survey')}}</a> <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.survey.template') }}">{{trans('backend.survey_form')}}</a> <i class="uil uil-angle-right"></i>
        <span class="">{{ $page_title }}</span>
    </h2>
</div>
<div role="main">
    <form method="post" action="{{ route('module.survey.template.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['survey-template-create', 'survey-template-edit'])
                    <button type="submit" class="btn btn-primary" id="save-template" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.survey.template') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                </div>
            </div>
        </div>
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
                            <div class="form-group row">
                                <div class="col-sm-1 control-label">
                                    <label>{{trans('backend.form_name')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="input-category">
                            @if(isset($categories))
                                @foreach($categories as $cate_key => $category)
                                    <div class="item-category mt-2" data-cate_key="{{ $cate_key }}">
                                        <div class="card">
                                            <div class="card-header bg-info">
                                                <input name="category_id[]" type="hidden" value="{{ $category->id }}">
                                                <div class="input-group">
                                                    <textarea name="category_name[]" class="form-control" placeholder="-- Đề mục --" required>{{ $category->name }}</textarea>
                                                    <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-category" data-cate_id="{{ $category->id }}"><i class="fa fa-trash"></i></a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="input-question-{{ $cate_key }}">
                                                    @php
                                                        $questions = $category->questions;
                                                    @endphp
                                                    @if(isset($questions))
                                                        @foreach($questions as $ques_key => $question)
                                                            <div class="item-question" data-ques_key="{{ $ques_key }}">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <div class="row px-0">
                                                                            <div class="col-10">
                                                                                <input name="question_id[{{ $cate_key }}][]" type="hidden" value="{{ $question->id }}">

                                                                                <div class="input-group">
                                                                                    <textarea name="question_code[{{ $cate_key }}][]" class="p-1 w-5 question_code" placeholder="-- Mã câu hỏi  {{ $ques_key + 1 }} --">{{ $question->code }}</textarea>
                                                                                    <textarea name="question_name[{{ $cate_key }}][]" class="form-control" placeholder="-- Câu hỏi  {{ $ques_key + 1 }} --" required>{{ $question->name }}</textarea>
                                                                                    <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-question" data-ques_id="{{ $question->id }}"><i class="fa fa-trash"></i></a>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-2 d-flex align-items-center">
                                                                                @if(isset($model->id))
                                                                                    <input type="hidden" name="type[{{ $cate_key }}][{{ $ques_key }}]" value="{{ $question->type }}">
                                                                                @endif
                                                                                <select @if(isset($model->id)) disabled @else name="type[{{ $cate_key }}][{{ $ques_key }}]" @endif class="form-control select2" data-placeholder="Chọn loại câu hỏi">
                                                                                    <option value=""></option>
                                                                                    <option value="choice" {{ $question->type == 'choice' ? 'selected' : '' }}> Trắc nghiệm</option>
                                                                                    <option value="essay" {{ $question->type == 'essay' ? 'selected' : '' }}> Tự luận</option>
                                                                                    <option value="text" {{ $question->type == 'text' ? 'selected' : '' }}> Nhập text</option>
                                                                                    <option value="matrix" {{ $question->type == 'matrix' ? 'selected' : '' }}> Ma trận</option>
                                                                                    <option value="matrix_text" {{ $question->type == 'matrix_text' ? 'selected' : '' }}> Ma trận (Nhập text)</option>
                                                                                    <option value="dropdown" {{ $question->type == 'dropdown' ? 'selected' : '' }}> Lựa chọn</option>
                                                                                    <option value="sort" {{ $question->type == 'sort' ? 'selected' : '' }}> Sắp xếp</option>
                                                                                    <option value="percent" {{ $question->type == 'percent' ? 'selected' : '' }}> Phần trăm</option>
                                                                                    <option value="number" {{ $question->type == 'number' ? 'selected' : '' }}> Nhập số</option>
                                                                                    <option value="time" {{ $question->type == 'time' ? 'selected' : '' }}> Thời gian</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        @if($question->type == 'essay')
                                                                            <textarea class="form-control" placeholder="Nội dung" readonly></textarea>
                                                                        @endif
                                                                        @if($question->type == 'time')
                                                                            <div class="input-group mb-3">
                                                                                <input type="text" class="form-control" placeholder="Ngày/Tháng/Năm" aria-describedby="basic-addon2" readonly>
                                                                                <div class="input-group-append">
                                                                                    <span class="input-group-text" id="basic-addon2"> <i class="fa fa-clock"></i></span>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                        @if(in_array($question->type, ['matrix', 'matrix_text']))
                                                                            @php
                                                                                $answers_row = $question->answers->where('is_row', '=', 1);
                                                                                $answers_col = $question->answers->where('is_row', '=', 0);
                                                                                $answer_row_col = $question->answers->where('is_row', '=', 10)->first();
                                                                            @endphp
                                                                                <div class="form-group row px-0">
                                                                                    <div class="col-6">
                                                                                        <a class="" id="btn-question-answer-row" data-cate_key="{{ $cate_key }}" data-ques_key="{{ $ques_key }}">
                                                                                            <i class="fa fa-plus"></i> Thêm dòng
                                                                                        </a>
                                                                                    </div>
                                                                                    <div class="col-6 text-right">
                                                                                        <a class="" id="btn-question-answer-col" data-cate_key="{{ $cate_key }}" data-ques_key="{{ $ques_key }}">
                                                                                            <i class="fa fa-plus"></i> Thêm cột
                                                                                        </a>
                                                                                    </div>
                                                                                    <div class="col-12 mt-2">
                                                                                        <table class="table table-bordered" id="table-matrix-{{ $cate_key .'-'. $ques_key }}">
                                                                                            <tr class="matrix-row-title">
                                                                                                <th>
                                                                                                    <input name="is_row[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="10">
                                                                                                    <input name="answer_id[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="{{ isset($answer_row_col) ? $answer_row_col->id : '' }}">
                                                                                                    <div class="input-group mt-3">
                                                                                                        <textarea name="answer_code[{{ $cate_key }}][{{ $ques_key }}][]" rows="3" class="w-25 answer_code" data-cate_key="{{ $cate_key }}" data-ques_key="{{ $ques_key }}" placeholder="Mã tiêu đề">{{ isset($answer_row_col) ? $answer_row_col->code : '' }}</textarea>
                                                                                                        <textarea name="answer_name[{{ $cate_key }}][{{ $ques_key }}][]" class="form-control" placeholder="Tiêu đề">{{ isset($answer_row_col) ? $answer_row_col->name : '' }}</textarea>
                                                                                                    </div>
                                                                                                </th>
                                                                                                @if(isset($answers_col))
                                                                                                    @php
                                                                                                        $col_key = 0;
                                                                                                    @endphp
                                                                                                    @foreach($answers_col as $answer)
                                                                                                        <th class="matrix-col-item-{{ $cate_key .'-'. $ques_key }} col-item-{{ $col_key }}" data-ans_key="{{ $col_key }}">
                                                                                                            <input name="is_row[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="0">
                                                                                                            <input name="answer_id[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="{{ $answer->id }}">
                                                                                                            <div class="input-group">
                                                                                                                <textarea name="answer_code[{{ $cate_key }}][{{ $ques_key }}][]" class="form-control w-100 answer_code" data-cate_key="{{ $cate_key }}" data-ques_key="{{ $ques_key }}" placeholder="Mã tùy chọn">{{ $answer->code }}</textarea>

                                                                                                                <textarea name="answer_name[{{ $cate_key }}][{{ $ques_key }}][]" class="form-control w-100" placeholder="Tùy chọn">{{ $answer->name }}</textarea>

                                                                                                                <a href="javascript:void(0)" class="btn btn-remove-col-matrix text-center w-100" id="del-answer-col" data-ans_id="{{ $answer->id }}" data-ans_key="{{ $col_key }}"> <i class="fa fa-trash"></i> </a>
                                                                                                            </div>
                                                                                                        </th>

                                                                                                        @php
                                                                                                            $col_key += 1;
                                                                                                        @endphp
                                                                                                    @endforeach
                                                                                                    @php
                                                                                                        $col_key = 0;
                                                                                                    @endphp
                                                                                                @endif
                                                                                            </tr>
                                                                                            @if(isset($answers_row))
                                                                                                @php
                                                                                                    $row_key = 0;
                                                                                                @endphp
                                                                                                @foreach($answers_row as $answer)
                                                                                                    <tr class="matrix-row-content" data-ans_key="{{ $row_key }}">
                                                                                                        <th>
                                                                                                            <input name="is_row[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="1">
                                                                                                            <input name="answer_id[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="{{ $answer->id }}">
                                                                                                            <div class="input-group">
                                                                                                                <textarea name="answer_code[{{ $cate_key }}][{{ $ques_key }}][]" rows="3" class="w-25 answer_code" data-cate_key="{{ $cate_key }}" data-ques_key="{{ $ques_key }}" placeholder="Mã tùy chọn">{{ $answer->code }}</textarea>

                                                                                                                <textarea name="answer_name[{{ $cate_key }}][{{ $ques_key }}][]" class="form-control" placeholder="Tùy chọn">{{ $answer->name }}</textarea>

                                                                                                                <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-answer-row" data-ans_id="{{ $answer->id }}"> <i class="fa fa-trash"></i> </a>
                                                                                                            </div>
                                                                                                        </th>
                                                                                                        @if(isset($answers_col))
                                                                                                            @php
                                                                                                                $col_key = 0;
                                                                                                            @endphp
                                                                                                            @foreach($answers_col as $answer_col)
                                                                                                                @php
                                                                                                                    $matrix_anser_code = $question->answers_matrix->where('answer_row_id', '=', $answer->id)->where('answer_col_id', '=', $answer_col->id)->first();
                                                                                                                @endphp
                                                                                                                <th class="col-item-{{ $col_key }}">
                                                                                                                    <textarea name="answer_matrix_code[{{ $cate_key }}][{{ $ques_key }}][{{ $row_key }}][{{ $col_key }}]" class="form-control" placeholder="">{{ isset($matrix_anser_code) ? $matrix_anser_code->code : '' }}</textarea>
                                                                                                                </th>
                                                                                                                @php
                                                                                                                    $col_key += 1;
                                                                                                                @endphp
                                                                                                            @endforeach
                                                                                                            @php
                                                                                                                $col_key = 0;
                                                                                                            @endphp
                                                                                                        @endif
                                                                                                    </tr>

                                                                                                    @php
                                                                                                        $row_key += 1;
                                                                                                    @endphp
                                                                                                @endforeach

                                                                                                @php
                                                                                                    $row_key = 0;
                                                                                                @endphp
                                                                                            @endif
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                        @else
                                                                            @php
                                                                                $answers = $question->answers->where('is_row', '=', 1);
                                                                            @endphp
                                                                            <div class="input-question-{{ $cate_key }}-answer-{{ $ques_key }}">
                                                                                @if(isset($answers))
                                                                                    @foreach($answers as $ans_key => $answer)
                                                                                        <div class="item-answer" data-ans_key="{{ $ans_key }}">
                                                                                            <div class="form-group row px-0">
                                                                                                <div class="col-11">
                                                                                                    <input name="is_row[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="1">
                                                                                                    <input name="answer_id[{{ $cate_key }}][{{ $ques_key }}][]" type="hidden" value="{{ $answer->id }}">

                                                                                                    <div class="input-group">
                                                                                                        <textarea name="answer_code[{{ $cate_key }}][{{ $ques_key }}][]" class="p-1 w-5 answer_code" placeholder="-- Tùy chọn tiêu đề --">{{ $answer->code }}</textarea>
                                                                                                        <textarea name="answer_name[{{ $cate_key }}][{{ $ques_key }}][]" class="form-control" placeholder="-- Tùy chọn {{ $ans_key + 1 }} --">{{ $answer->name }}</textarea>
                                                                                                        <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-answer" data-ans_id="{{ $answer->id }}"> <i class="fa fa-trash"></i></a>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-1 p-0 d-flex align-items-center">
                                                                                                    <div class="form-check">
                                                                                                        <input name="is_text[{{ $cate_key }}][{{ $ques_key }}][{{ $ans_key }}]" value="{{ $answer->is_text }}" {{ $answer->is_text == 1 ? 'checked' : '' }} id="check-answer{{ $cate_key }}{{ $ques_key }}{{ $ans_key }}" type="checkbox" class="form-check-input check-answer">
                                                                                                        <label class="form-check-label" for="check-answer{{ $cate_key }}{{ $ques_key }}{{ $ans_key }}">
                                                                                                            Nhập text
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                @endif
                                                                            </div>

                                                                            @if(!in_array($question->type, ['essay', 'time']))
                                                                            <a class="" id="btn-question-answer" data-cate_key="{{ $cate_key }}" data-ques_key="{{ $ques_key }}">
                                                                                <i class="fa fa-plus"></i> Thêm tùy chọn
                                                                            </a>
                                                                            @endif
                                                                        @endif

                                                                        <hr>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input check-multiples" name="multiple[{{ $cate_key }}][{{ $ques_key }}]" value="{{ $question->multiple }}" {{ $question->multiple == 1 ? 'checked' : '' }} type="checkbox" id="check-multiples{{ $cate_key }}{{ $ques_key }}">
                                                                            <label class="form-check-label" for="check-multiples{{ $cate_key }}{{ $ques_key }}">
                                                                                Chọn nhiều
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <a class="btn btn-success mt-2" id="btn-question" data-cate_key="{{ $cate_key }}"><i class="fa fa-plus-circle"></i> Thêm câu hỏi</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="col-md-12 mt-2">
                            <a class="btn btn-info" id="btn-category"><i class="fa fa-plus-circle"></i> Đề mục</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <template id="category-template">
        <div class="item-category mt-2" data-cate_key="{cate_key}">
            <div class="card">
                <div class="card-header bg-info">
                    <input name="category_id[]" type="hidden" value="">

                    <div class="input-group">
                        <textarea name="category_name[]" class="form-control" placeholder="-- Đề mục --" required></textarea>
                        <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-category" data-cate_id=""><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="input-question-{cate_key}"></div>
                </div>
                <div class="card-footer">
                    <a class="btn btn-success mt-2" id="btn-question" data-cate_key="{cate_key}"><i class="fa fa-plus-circle"></i> Thêm câu hỏi</a>
                </div>
            </div>
        </div>
    </template>

    <template id="question-template">
        <div class="item-question" data-ques_key="{ques_key}">
            <div class="card">
                <div class="card-header">
                    <div class="row px-0">
                        <div class="col-10">
                            <input name="question_id[{cate_key}][]" type="hidden" value="">

                            <div class="input-group">
                                <textarea name="question_code[{cate_key}][]" class="p-1 w-5 question_code" data-cate_key="{cate_key}" placeholder="Mã câu hỏi  {index_question}"></textarea>
                                <textarea name="question_name[{cate_key}][]" class="form-control" placeholder="-- Câu hỏi  {index_question} --" required></textarea>
                                <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-question" data-ques_id=""><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                        <div class="col-2 d-flex align-items-center">
                            <select name="type[{cate_key}][{ques_key}]" id="ques_type_{cate_key}_{ques_key}" class="form-control select2 ques_type" data-placeholder="" data-cate_key="{cate_key}" data-ques_key="{ques_key}">
                                <option value="" readonly>Chọn loại câu hỏi</option>
                                <option value="choice" selected>Trắc nghiệm</option>
                                <option value="essay">Tự luận</option>
                                <option value="text">Nhập text</option>
                                <option value="matrix">Ma trận</option>
                                <option value="matrix_text">Ma trận (Nhập text)</option>
                                <option value="dropdown">Lựa chọn</option>
                                <option value="sort">Sắp xếp</option>
                                <option value="percent">Phần trăm</option>
                                <option value="number">Nhập số</option>
                                <option value="time">Thời gian</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="input-question-{cate_key}-answer-{ques_key}">
                        <div class="item-answer" data-ans_key="0">
                            <div class="form-group row px-0">
                                <div class="col-11">
                                    <input name="is_row[{cate_key}][{ques_key}][]" type="hidden" value="1">
                                    <input name="answer_id[{cate_key}][{ques_key}][]" type="hidden" value="">

                                    <div class="input-group">
                                        <textarea name="answer_code[{cate_key}][{ques_key}][]" class="p-1 w-5 answer_code" data-cate_key="{cate_key}" data-ques_key="{ques_key}" placeholder="Mã tùy chọn 1"></textarea>
                                        <textarea name="answer_name[{cate_key}][{ques_key}][]" class="form-control" placeholder="-- Tùy chọn 1 --"></textarea>
                                        <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-answer" data-ans_id=""> <i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                                <div class="col-1 p-0 d-flex align-items-center">
                                    <div class="form-check">
                                        <input name="is_text[{cate_key}][{ques_key}][0]" value="0" id="check-answer{cate_key}{ques_key}0" type="checkbox" class="form-check-input check-answer">
                                        <label class="form-check-label" for="check-answer{cate_key}{ques_key}0">
                                            Nhập text
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="btn-question-answer-matrix">
                        <div class="form-group row px-0">
                            <div class="col-6">
                                <a class="" id="btn-question-answer-row" data-cate_key="{cate_key}" data-ques_key="{ques_key}">
                                    <i class="fa fa-plus"></i> Thêm dòng
                                </a>
                            </div>
                            <div class="col-6 text-right">
                                <a class="" id="btn-question-answer-col" data-cate_key="{cate_key}" data-ques_key="{ques_key}">
                                    <i class="fa fa-plus"></i> Thêm cột
                                </a>
                            </div>
                        </div>
                        <p class="table-matrix-{cate_key}-{ques_key}">

                        </p>
                    </div>

                    <a class="" id="btn-question-answer" data-cate_key="{cate_key}" data-ques_key="{ques_key}">
                        <i class="fa fa-plus"></i> Thêm tùy chọn
                    </a>

                    <hr>
                    <div class="form-check">
                        <input class="form-check-input check-multiples" name="multiple[{cate_key}][{ques_key}]" value="0" type="checkbox" id="check-multiples{cate_key}{ques_key}">
                        <label class="form-check-label" for="check-multiples{cate_key}{ques_key}">
                            Chọn nhiều
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="answer-template">
        <div class="item-answer" data-ans_key="{ans_key}">
            <div class="form-group row px-0">
                <div class="col-11">
                    <input name="is_row[{cate_key}][{ques_key}][]" type="hidden" value="1">
                    <input name="answer_id[{cate_key}][{ques_key}][]" type="hidden" value="">

                    <div class="input-group">
                        <textarea name="answer_code[{cate_key}][{ques_key}][]" class="p-1 w-5 answer_code" data-cate_key="{cate_key}" data-ques_key="{ques_key}" placeholder="Mã tùy chọn {index_answer}"></textarea>
                        <textarea name="answer_name[{cate_key}][{ques_key}][]" class="form-control" placeholder="-- Tùy chọn {index_answer} --"></textarea>
                        <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-answer" data-ans_id=""> <i class="fa fa-trash"></i></a>
                    </div>
                </div>
                <div class="col-1 p-0 d-flex align-items-center">
                    <div class="form-check">
                        <input name="is_text[{cate_key}][{ques_key}][{ans_key}]" value="0" id="check-answer{cate_key}{ques_key}{ans_key}" type="checkbox" class="form-check-input check-answer">
                        <label class="form-check-label" for="check-answer{cate_key}{ques_key}{ans_key}">
                            Nhập text
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="table-matrix-template">
        <table class="table table-bordered" id="table-matrix-{cate_key}-{ques_key}">
            <tr class="matrix-row-title">
                <th>
                    <input name="is_row[{cate_key}][{ques_key}][]" type="hidden" value="10">
                    <input name="answer_id[{cate_key}][{ques_key}][]" type="hidden" value="">
                    <div class="input-group mt-3">
                        <textarea name="answer_code[{cate_key}][{ques_key}][]" rows="3" class="w-25 answer_code" data-cate_key="{cate_key}" data-ques_key="{ques_key}" placeholder="Mã tiêu đề"></textarea>
                        <textarea name="answer_name[{cate_key}][{ques_key}][]" class="form-control" placeholder="Tiêu đề"></textarea>
                    </div>
                </th>
                @for($i = 0; $i < 4; $i++)
                    <th class="matrix-col-item-{cate_key}-{ques_key} col-item-{{ $i }}" data-ans_key="{{ $i }}">
                        <input name="is_row[{cate_key}][{ques_key}][]" type="hidden" value="0">
                        <input name="answer_id[{cate_key}][{ques_key}][]" type="hidden" value="">
                        <div class="input-group">
                            <textarea name="answer_code[{cate_key}][{ques_key}][]" class="form-control w-100 answer_code" data-cate_key="{cate_key}" data-ques_key="{ques_key}" placeholder="Mã tùy chọn"></textarea>
                            <textarea name="answer_name[{cate_key}][{ques_key}][]" class="form-control w-100" placeholder="Tùy chọn"></textarea>
                            <a href="javascript:void(0)" class="btn btn-remove-col-matrix text-center w-100" id="del-answer-col" data-ans_id="" data-ans_key="{{ $i }}"> <i class="fa fa-trash"></i> </a>
                        </div>
                    </th>
                @endfor
            </tr>
            <tr class="matrix-row-content" data-ans_key="0">
                <th>
                    <input name="is_row[{cate_key}][{ques_key}][]" type="hidden" value="1">
                    <input name="answer_id[{cate_key}][{ques_key}][]" type="hidden" value="">
                    <div class="input-group">
                        <textarea name="answer_code[{cate_key}][{ques_key}][]" rows="3" class="w-25 answer_code" data-cate_key="{cate_key}" data-ques_key="{ques_key}" placeholder="Mã tùy chọn"></textarea>
                        <textarea name="answer_name[{cate_key}][{ques_key}][]" class="form-control" placeholder="Tùy chọn"></textarea>
                        <a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-answer-row" data-ans_id=""> <i class="fa fa-trash"></i> </a>
                    </div>
                </th>
                @for($i = 0; $i < 4; $i++)
                    <th class="col-item-{{ $i }}">
                        <textarea name="answer_matrix_code[{cate_key}][{ques_key}][0][{{ $i }}]" class="form-control" placeholder=""></textarea>
                    </th>
                @endfor
            </tr>
        </table>
    </template>
</div>

<script>
    var remove_category = '{{ route('module.survey.template.remove_category') }}';
    var remove_question = '{{ route('module.survey.template.remove_question') }}';
    var remove_answer = '{{ route('module.survey.template.remove_answer') }}';
</script>
<script src="{{ asset('styles/module/survey/js/template.js')}}"></script>
@stop
