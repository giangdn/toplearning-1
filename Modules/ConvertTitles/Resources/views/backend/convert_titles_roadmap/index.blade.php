@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.trainingroadmap.list') }}">{{trans('backend.list_roadmap')}}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.convert_titles.roadmap.list_title') }}">{{trans('backend.title_conversion_program')}}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
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
            <div class="col-md-6">
                <form class="form-inline" id="form-search">
                    <div class="w-25">
                        <select name="subject_name" class="form-control load-subject" data-placeholder="--Học phần --"></select>
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    @can('convert-titles-roadmap-create')
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{ download_template('mau_import_chuong_trinh_khung_chuyen_doi_chuc_danh.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                        <button class="btn btn-info" id="import-plan" type="submit" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                        <a class="btn btn-info" href="javascript:void(0)" id="export-excel">
                            <i class="fa fa-download"></i> Export
                        </a>
                    </div>
                    @endcan
                    <div class="btn-group">
                        @can('convert-titles-roadmap-create')
                        <a  href="{{ route('module.convert_titles.roadmap.create', ['id' => $title_id]) }}" class="btn btn-primary" >
                            <i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}
                        </a>
                        @endcan
                        @can('convert-titles-roadmap-delete')
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="2%">#</th>
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="order"  data-align="center" data-width="2%"><i class="fa fa-floppy-o saveSortOrder"></i></th>
                    <th data-field="subject_code" >{{trans("backend.course_code")}}</th>
                    <th data-field="subject_name" >{{trans("backend.course_name")}}</th>
                    <th data-field="created_at2" data-align="center" >{{trans('backend.created_at')}}</th>
                    <th data-field="updated_at2" data-align="center" >{{trans('backend.edit_at')}}</th>
                    <th data-field="edit" data-width="5%" data-align="center" data-formatter="edit_formatter" >{{trans('backend.edit')}}</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('module.convert_titles.roadmap.import',['id'=>$title_id]) }}" method="post" class="form-ajax">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">IMPORT {{trans('backend.title_conversion_program')}}</h5>
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
        function edit_formatter(value, row, index) {
            return  '<a href="'+ row.edit_url +'" class="btn btn-info" style="cursor:pointer" ><i class="fa fa-edit"></i></a>';
        }
        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });
        $("#export-excel").on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('module.convert_titles.roadmap.export',['id' => $title_id]) }}?'+form_search;
        });
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.convert_titles.roadmap.getdata',['id' => $title_id]) }}',
            remove_url: '{{ route('module.convert_titles.roadmap.remove',['id'=> $title_id ]) }}'
        });
    </script>
@endsection
