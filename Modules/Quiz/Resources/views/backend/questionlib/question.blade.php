@extends('layouts.backend')

@section('page_title', 'Câu hỏi')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

<style>
    table video {
        width: 50%;
        height: auto;
    }

    table img {
        width: 50% !important;
        height: auto !important;
    }

    .bootstrap-table .fixed-table-container .fixed-table-body{
        height: auto !important;
    }
</style>

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('lamanager.quiz_manager') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.quiz.questionlib') }}">{{ trans('backend.questionlib') }}</a>
            <i class="uil uil-angle-right"></i>
            <span>{{ trans('backend.question') }}: {{ $category->name }}</span>
        </h2>
    </div>
    <div role="main">
        @if(isset($errors))

        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach

        @endif
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline" id="form-search">
                    <input type="text" name="search" class="form-control" placeholder="{{ trans('backend.enter_question') }}">
                    <div class="w-25">
                        <select name="type" class="form-control select2" data-placeholder="-- {{ trans('backend.kind_question') }} --">
                            <option value=""></option>
                            <option value="multiple-choise">{{trans("backend.multiple_choice")}}</option>
                            <option value="essay">{{trans("backend.essay")}}</option>
                            <option value="matching">{{trans("backend.matching_sentences")}}</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('quiz-question-create')
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{ download_template('mau_import_cau_hoi.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                        <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                    </div>
                    <p></p>
                    @endcan
                    @can('quiz-question-approve')
                    <div class="btn-group">
                        <button class="btn btn-success status" data-status="1">
                            <i class="fa fa-check-circle"></i> {{trans("backend.approve")}}
                        </button>
                        <button class="btn btn-danger status" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> {{trans('backend.deny')}}
                        </button>
                    </div>
                    @endcan
                    <div class="btn-group">
                        @can('quiz-question-create')
                        <a href="{{ route('module.quiz.questionlib.question.create', ['id' => $category->id]) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('quiz-question-delete')
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" data-page-list="[10, 25, 50, 100, all]">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter" data-width="3%" data-align="center">#</th>
                    <th data-field="state" data-checkbox="true" data-width="3%"></th>
                    <th data-field="name" data-formatter="name_formatter" data-width="30%">{{ trans('backend.titles') }}</th>
                    <th class="question_quiz" data-field="answers" data-formatter="answer_formatter">{{ trans('backend.question') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="10%">{{trans('backend.status')}}</th>
                    <th data-field="view_question" data-align="center" data-formatter="view_question_formatter" data-width="5%">Xem câu hỏi</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('module.quiz.questionlib.import_question', ['id' => $category->id]) }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">IMPORT {{ trans('backend.question') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1);
        }

        function name_formatter(value, row, index) {
            var html = '<a href="'+ row.edit_url +'"> <b>'+ value +'</b> </a> <br>';
                html += row.approved_by + ' ' + row.time_approved +'<br>';
                html += row.created_by +' '+row.created_time;
            return html;
        }

        function answer_formatter(value, row, index) {
            var html = row.text_type + '<br>';
            html += '<ul class="list-group">';
            $.each(row.answers, function (i,e){
                var class_success = (e.correct_answer == 1 || e.percent_answer > 0) ? "list-group-item-success" : "";
                var score = '';
                if (row.type == 'multiple-choise'){
                    if (row.multiple == 0){
                        score = e.correct_answer;
                    }else{
                        score = e.percent_answer +'%';
                    }
                }

                html += '<li class="list-group-item '+class_success+'">'+
                    (e.image_answer ? '<img src="'+ e.image_answer +'" alt="" class="w-25 img-responsive"> <br>' : '') +
                    (e.title ? e.title + (e.matching_answer ? ' '+ e.matching_answer : '') : '') +
                    (e.fill_in_correct_answer ? '<br>' + e.fill_in_correct_answer : '') +
                    '<span class="float-right">' + score + '</span> </li>';
            });
            html+="</ul>";
            return html;
        }

        function status_formatter(value, row, index) {
            return value == 1 ? '<span class="text-success">{{ trans("backend.approved") }}</span>' : (value == 2 ? '<span class="text-warning">{{ trans("backend.not_approved") }}</span>': '<span class="text-danger">{{ trans("backend.deny") }}</span>');
        }

        function view_question_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="btn btn-primary load-modal" data-url="'+ row.view_question +'"><i class="fa fa-eye"></i></a>';
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.questionlib.question.getdata', ['id' => $category->id]) }}',
            remove_url: '{{ route('module.quiz.questionlib.remove_question', ['id' => $category->id]) }}'
        });

        var ajax_status = "{{ route('module.quiz.questionlib.ajax_status', ['id' => $category->id]) }}";

        function success_submit(form) {
            $("#app-modal #myModal").modal('hide');
            table.refresh();
        }
    </script>
    <script src="{{ asset('styles/module/quiz/js/question.js') }}"></script>
@endsection
