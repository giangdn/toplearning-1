@extends('layouts.backend')

@section('page_title', 'Chuyển đổi chức danh')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">{{trans('backend.convert_titles')}}</span>
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
            <div class="col-md-12 form-inline">
                <form class="form-inline w-100" id="form-search">
                    <div class="w-25">
                        <input type="text" name="search" value="" class="form-control w-100" placeholder="{{trans('backend.enter_name')}}">
                    </div>
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="unit" class="form-control load-unit" data-placeholder="-- {{ trans('backend.unit') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{ download_template('mau_import_chuyen_doi_chuc_danh.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                        <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                        <a class="btn btn-info" href="{{ route('module.convert_titles.export') }}">
                            <i class="fa fa-download"></i> Export
                        </a>
                    </div>
                    <div class="btn-group">
                        <a class="btn btn-warning" href="{{ route('module.convert_titles.reviews')  }}"><i class="fa fa-list-alt"></i> {{ trans('backend.evaluation_form') }}</a>
                        <a  href="{{ route('module.convert_titles.create') }}" class="btn btn-primary" >
                            <i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}
                        </a>
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="code" >{{ trans('backend.employee_code') }}</th>
                    <th data-field="name" data-formatter="name_formatter" >{{ trans('backend.employee_name') }}</th>
                    <th data-field="dob" data-align="center">{{trans('backend.year_of_birth')}}</th>
                    <th data-field="gender" data-formatter="gender_formatter" data-align="center">{{trans('backend.gender')}}</th>
                    <th data-field="title_name_1">{{trans('backend.original_title')}}</th>
                    <th data-field="unit_name_1">{{ trans('backend.unit') }}</th>
                    <th data-field="title_name_2">{{trans('backend.convert_titles')}}</th>
                    <th data-field="unit_name_2">{{ trans('backend.training_unit') }}</th>
                    <th data-field="unit_receive_name">{{ trans('backend.receivers') }}</th>
                    <th data-field="start_date" data-align="center">{{trans('backend.start_date')}}</th>
                    <th data-field="end_date" data-align="center">{{trans('backend.end_date')}}</th>
                    <th data-field="send_date" data-align="center">{{trans('backend.date_send_evaluate')}}</th>
                    <th data-field="note" data-align="center">{{ trans('backend.note') }}</th>
                    <th data-field="course" data-formatter="course_formatter" data-align="center">{{ trans('backend.course') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('module.convert_titles.import') }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">IMPORT NHÂN SỰ CHUYỂN ĐỔI CHỨC DANH</h5>
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

        function name_formatter(value, row, index) {
            return  '<a href="'+ row.edit_url +'"> '+ row.name +'</a>';
        }

        function gender_formatter(value, row, index) {
            return  row.gender == 1 ? '{{trans("backend.male")}}' : '{{trans("backend.female")}}';
        }

        function course_formatter(value, row, index) {
            return  '<a href="'+ row.course +'" class="btn btn-info"><i class="fa fa-eye"></i></a> <a href="'+ row.export +'" ' +
                'class="btn btn-primary"><i class="fa fa-download"></i></a>';
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.convert_titles.getdata') }}',
            remove_url: '{{ route('module.convert_titles.remove') }}'
        });
    </script>
@endsection
