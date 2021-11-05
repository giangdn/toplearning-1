@extends('layouts.backend')

@section('page_title', 'Dữ liệu cũ')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12">
                <form class="form-inline form-search mb-3" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor

                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <input type="text" name="search" value="" class="form-control w-100" placeholder="Nhập mã/Tên nhân viên/ kỳ thi">
                    </div>
                    <div class="w-25">
                        <input type="text" name="start_date" value="" class="form-control w-100 datepicker" placeholder="Ngày bắt đầu">
                    </div>
                    <div class="w-25">
                        <input type="text" name="end_date" value="" class="form-control w-100 datepicker" placeholder="Ngày kết thúc">
                    </div>
                    <div class="w-25">
                        <select name="result" id="" class="select2 form-control" data-placeholder="-- Chọn kết quả --">
                            <option value=""></option>
                            <option value="Không đạt">Không đạt</option>
                            <option value="Đạt">Đạt</option>
                        </select>
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns" id="btn-quiz">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn btn-info" href="javascript:void(0)" id="export-result">
                            <i class="fa fa-download"></i> Export
                        </a>
                        <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-width="1%" data-checkbox="true"></th>
                    {{-- <th data-field="index" data-formatter="index_formatter" data-width="3%" data-align="center">STT</th> --}}
                    <th data-field="user_code" data-align="center">MNV</th>
                    <th data-field="user_name" data-formatter="name_formatter" data-align="center">Họ và Tên</th>
                    <th data-field="title" data-width="15%">Chức danh</th>
                    <th data-field="area" data-width="15%">Trực thuộc</th>
                    <th data-field="unit" data-width="7%" data-align="center">Đơn vị</th>
                    <th data-field="department" data-align="center">Phòng/Ban/TT</th>
                    <th data-field="phone" data-align="center">Điện thoại</th>
                    <th data-field="email" data-align="center" >Email</th>
                    <th data-field="quiz_code" data-align="center">Mã kỳ thi</th>
                    <th data-field="quiz_name" data-align="center" data-width="10%">Tên kỳ thi</th>
                    <th data-field="start_date" data-align="center">Thời gian bắt đầu</th>
                    <th data-field="end_date" data-align="center">Thời gian Kết thúc</th>
                    <th data-field="score_essay" data-align="center">Điểm thi trắc nghiệm</th>
                    <th data-field="score_multiple_choice" data-align="center">Điểm thi tự luận</th>
                    <th data-field="result" data-align="center">Kết quả</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.quiz.data_old_quiz.import') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT Dữ liệu cũ</h5>
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
            return '<a href="'+ row.edit_url +'">'+ row.user_name +'</a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.get_data_old_quiz') }}',
            remove_url: '{{ route('module.quiz.data_old_quiz.remove') }}'
        });

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        $('#export-result').on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('module.quiz.data_old_quiz.export') }}?'+form_search;
        })
    </script>
@endsection
