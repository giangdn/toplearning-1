@extends('layouts.backend')

@section('page_title', 'Danh sách KPI')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i><span class="font-weight-bold">{{trans('backend.list')}} KPI</span></h2>
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
            <div class="col-md-10 ">
                <form class="form-inline form-search-user mb-3" id="form-search">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <input type="text" name="search" class="form-control" placeholder="{{ trans('backend.enter_code_name_employee') }}">
                    <input type="text" name="year" class="form-control is-number" placeholder="Năm">
                    <input type="text" name="quarter_1" class="form-control" placeholder="{{trans('backend.precious')}} 1">
                    <input type="text" name="quarter_2" class="form-control" placeholder="{{trans('backend.precious')}} 2">
                    <input type="text" name="quarter_3" class="form-control" placeholder="{{trans('backend.precious')}} 3">
                    <input type="text" name="quarter_4" class="form-control" placeholder="{{trans('backend.precious')}} 4">

                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-2 text-right">
                <div class="btn-group ">
                    <a class="btn btn-info" href="{{ download_template('mau_import_kpi.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                    <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                        <i class="fa fa-upload"></i> Import
                    </button>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="2%">#</th>
                    <th data-field="code">{{trans('backend.employee_code')}}</th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.employee_name') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="parent_name">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="year" data-align="center">Năm</th>
                    <th data-field="quarter_1" data-align="center">{{trans('backend.precious')}} 1</th>
                    <th data-field="quarter_2" data-align="center">{{trans('backend.precious')}} 2</th>
                    <th data-field="quarter_3" data-align="center">{{trans('backend.precious')}} 3</th>
                    <th data-field="quarter_4" data-align="center">{{trans('backend.precious')}} 4</th>
                    <th data-field="quarter_year" data-align="center">Quý cuối năm</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('module.potential.import_kpi') }}" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">IMPORT KPI</h5>
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
            return row.lastname + ' ' + row.firstname;
        }

        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.potential.kpi.getdata_kpi') }}',
        });
    </script>
@endsection
