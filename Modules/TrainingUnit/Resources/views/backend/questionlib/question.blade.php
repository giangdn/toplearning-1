@extends('layouts.backend')

@section('page_title', 'Câu hỏi đề xuất')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.training_unit.questionlib') }}"> Câu hỏi đề xuất</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Câu hỏi: {{ $category->name }}</span>
        </h2>
    </div>
@endsection

@section('content')

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
                        <select name="type" class="form-control select2" data-placeholder="-- Loại câu hỏi --">
                            <option value=""></option>
                            <option value="multiple-choise">Trắc nghiệm</option>
                            <option value="essay">Tự luận</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{ download_template('mau_import_cau_hoi_de_xuat.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                        <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                    </div>
                    <p></p>
                    <div class="btn-group">
                        <button class="btn btn-success status" data-status="1">
                            <i class="fa fa-check-circle"></i> {{ trans('backend.approve') }}
                        </button>
                        <button class="btn btn-danger status" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> {{trans("backend.deny")}}
                        </button>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('module.training_unit.questionlib.question.create', ['id' => $category->id]) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter" data-width="3%" data-align="center">#</th>
                    <th data-field="state" data-checkbox="true" data-width="3%"></th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.question') }}</th>
                    <th data-field="type" data-formatter="type_formatter" data-align="center" data-width="20%">Loại câu hỏi</th>
                    <th data-field="type" data-formatter="type_formatter" data-align="center" data-width="10%">{{trans("backend.select_all")}}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="10%">{{trans('backend.status')}}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('module.training_unit.questionlib.import_question', ['id' => $category->id]) }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">IMPORT CÂU HỎI ĐỀ XUẤT</h5>
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

        function type_formatter(value, row, index) {
            return value == 'essay' ? 'Tự luận' : 'Trắc nghiệm';
        }

        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ value +'</a>';
        }

        function status_formatter(value, row, index) {
            return value == 1 ? '<span class="text-success">{{ trans("backend.approved") }}</span>' : (value == 2 ? '<span class="text-warning">{{ trans("backend.not_approved") }}</span>': '<span class="text-danger">{{ trans("backend.deny") }}</span>');
        }
        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_unit.questionlib.question.getdata', ['id' => $category->id]) }}',
            remove_url: '{{ route('module.training_unit.questionlib.remove_question', ['id' => $category->id]) }}'
        });

        var ajax_status = "{{ route('module.training_unit.questionlib.ajax_status', ['id' => $category->id]) }}";

        function success_submit(form) {
            $("#app-modal #myModal").modal('hide');
            table.refresh();
        }
    </script>
    <script src="{{ asset('styles/module/training_unit/js/question.js') }}"></script>
@endsection
