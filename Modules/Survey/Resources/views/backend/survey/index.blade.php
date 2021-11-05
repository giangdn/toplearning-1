@extends('layouts.backend')

@section('page_title', 'Khảo sát')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')

    <div role="main">

        <div class="row">
            <div class="col-md-6">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder='{{trans("backend.enter_name_survey")}}'>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{trans('backend.search')}}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">
                    @can('survey-template')
                    <div class="btn-group">
                        <a href="{{ route('module.survey.template') }}" class="btn btn-info"><i class="fa fa-drivers-license"></i> {{trans('backend.survey_form')}}</a>
                    </div>
                    @endcan
                    @can('survey-status')
                    <div class="btn-group">
                        <button class="btn btn-primary publish" data-status="1">
                            <i class="fa fa-check-circle"></i> &nbsp;{{trans('backend.enable')}}
                        </button>
                        <button class="btn btn-warning publish" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{trans('backend.disable')}}
                        </button>
                    </div>
                    @endcan
                    <div class="btn-group">
                        @can('survey-create')
                        <a href="{{ route('module.survey.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{trans('backend.add_new')}}</a>
                        @endcan
                        @can('survey-delete')
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{trans('backend.delete')}}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{trans('backend.open')}}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{trans('backend.survey_name')}}</th>
                    <th data-field="date" data-align="center">{{trans('backend.time')}}</th>
                    <th data-field="count_ques" data-align="center">{{trans('backend.number_of_questions')}}</th>
                    <th data-field="count_survey" data-align="center" data-formatter="count_survey_formatter">{{trans('backend.join')}} / {{trans('backend.object')}}</th>
                    <th data-field="report" data-width="10%" data-align="center" data-formatter="report_formatter">{{trans('backend.report')}}</th>
                    <th data-field="review" data-formatter="review_formatter" data-align="center">Xem mẫu</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">

        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name+'</a>';
        }

        function count_survey_formatter(value, row, index) {
            return  row.count_survey_user + ' / ' + row.count_object;
        }

        function report_formatter(value, row, index) {
            var html = '';
            @can('survey-export-report')
                html += '<a href="'+ row.report_url +'" class="btn btn-primary"><i class="fa fa-download"></i> {{trans("backend.report_all")}}</a> <br> ';
            @endcan

            @can('survey-view-report')
                html += '<a href="'+ row.report_detail_url +'" class="btn btn-info"><i class="fa fa-list-ul"></i> {{trans("backend.detail_report")}}</a>';
            @endcan

            return html;
        }

        function status_formatter(value, row, index) {
            return value == 1 ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-exclamation-triangle text-warning"></i>';
        }

        function review_formatter(value, row, index) {
            return '<a href="'+ row.review +'" class="btn btn-info"> <i class="fa fa-eye"></i> </a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.survey.getdata') }}',
            remove_url: '{{ route('module.survey.remove') }}'
        });

        $('.publish').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var status = $(this).data('status');

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 khảo sát', 'error');
                return false;
            }

            $.ajax({
                url: "{{ route('module.survey.ajax_isopen_publish') }}",
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        });
    </script>

@endsection
