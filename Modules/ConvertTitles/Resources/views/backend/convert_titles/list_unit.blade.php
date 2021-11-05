@extends('layouts.backend')

@section('page_title', 'Đơn vị quản lý chuyển đổi chức danh')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">{{ trans('backend.conversion_unit') }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main" id="list-unit">
        <div class="row">
            <div class="col-md-12">
                <form id="form-search">
                    <div class="form-row align-items-center">
                        <div class="col-sm-2 my-1">
                            <input type="text" name="search" value="" class="form-control" autocomplete="off" placeholder="{{ trans('backend.enter_code_name_employee') }}">
                        </div>

                        <div class="col-sm-3 my-1">
                            <select name="title" class="form-control load-title" data-placeholder="-- {{trans('backend.convert_titles')}}--"></select>
                        </div>

                        <div class="col-sm-2 my-1">
                            <input name="start_date" type="text" class="datepicker form-control" placeholder="{{trans('backend.start_date')}}" autocomplete="off">
                        </div>

                        <div class="col-sm-2 my-1">
                            <input name="end_date" type="text" class="datepicker form-control" placeholder="{{trans('backend.end_date')}}" autocomplete="off">
                        </div>

                        <div class="col-sm-2 my-1">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="code" >{{ trans('backend.employee_code') }}</th>
                    <th data-field="name">{{ trans('backend.employee_name') }}</th>
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
                    <th data-field="export" data-formatter="export_formatter" data-width="3%" data-align="center">{{trans('backend.export_results')}}</th>
                    <th data-field="file-review" data-align="center" data-formatter="file_review_formatter" data-width="5%">{{trans('backend.assessments')}}</th>
                    <th data-field="file" data-align="center" data-formatter="file_formatter" data-width="5%">{{trans('backend.evaluation_form')}}</th>
            </thead>
        </table>
    </div>
    <script type="text/javascript">

        function gender_formatter(value, row, index) {
            return  row.gender == 1 ? '{{trans("backend.male")}}' : '{{trans("backend.female")}}';
        }

        function file_review_formatter(value, row, index) {
            var html = '';
            @can('convert-titles-evaluate')
                html += '<div class="attemp btn-group"><a href="javascript:void(0)" class="select-file btn btn-primary '+ (row.link_download ? '' : 'disabled') +'"><i ' +
                'class="fa fa-upload"></i></a> <input type="hidden" data-id="'+ row.convert_titles_id +'" value="'+row.file+'" ' +
                'name="file" class="file-select"> <a href="'+ row.download_file_review +'" class="btn btn-primary '+ (row
                    .download_file_review ? '' : 'disabled') +'"> <i class="fa fa-download"></i></a></div>';
            @endcan

            return html;
        }

        function file_formatter(value, row, index) {
            var html = '';
            @can('convert-titles-evaluate')
                html += ' <a href="'+ row.link_download +'" title="'+ row.file_name +'" class="btn btn-primary '+ (row.link_download ? '' : 'disabled') +'"><i class="fa fa-download"></i></a>';
            @endcan

            return html;
        }

        function export_formatter(value, row, index) {
            return '<div class="temp btn-group"><a href="javascript:void(0)" data-id="' + row.user_id + '" class="export-excel btn btn-info"><i class="fa fa-download"></i></a></div>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.convert_titles.getdata.list_unit') }}',
        });

        $("#list-unit").on('click', '.export-excel', function () {
            let user_id = $(this).closest(".temp").find('.export-excel').data('id');

            window.location = '{{ route('module.convert_titles.list_unit.export_employees') }}?user_id='+user_id

        });

        $('#list-unit').on('click', '.select-file', function () {
            let item = $(this);
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'files'}, function (url, path) {
                var path2 =  path.split("/");
                item.closest(".attemp").find('.file-review').html(path2[path2.length - 1]);
                item.closest(".attemp").find('.file-select').val(path);
                var convert_titles_id = item.closest(".attemp").find('.file-select').data('id');

                $.ajax({
                    url: "{{ route('module.convert_titles.save_file') }}",
                    type: 'post',
                    data: {
                        convert_titles_id: convert_titles_id,
                        path : path,
                    }
                }).done(function(data) {
                    $(table.table).bootstrapTable('refresh');
                    return false;
                }).fail(function(data) {
                    show_message('{{ trans('lageneral.data_error ') }}', 'error');
                    return false;
                });

            });
        });
    </script>
@endsection
