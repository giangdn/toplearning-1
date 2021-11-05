@extends('layouts.backend')

@section('page_title', 'Mẫu khảo sát')

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
            <span class=""> {{trans('backend.survey_form')}}</span>
        </h2>
    </div>
    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder='{{trans("backend.enter_template_name")}}'>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('survey-template-create')
                        <a href="{{ route('module.survey.template.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('survey-template-delete')
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
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-formatter="name_formatter">{{trans('backend.form_name')}}</th>
                    <th data-field="created_by" data-formatter="created_by_formatter">{{ trans('backend.created_by') }}</th>
                    <th data-field="updated_by" data-formatter="updated_by_formatter">{{trans('backend.update_by')}}</th>
                    <th data-field="review" data-formatter="review_formatter" data-align="center">Xem mẫu</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a>';
        }

        function created_by_formatter(value, row, index) {
            return row.created_by;
        }

        function updated_by_formatter(value, row, index) {
            return row.updated_by;
        }

        function review_formatter(value, row, index) {
            return '<a href="'+ row.review +'" class="btn btn-info"> <i class="fa fa-eye"></i> </a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.survey.template.getdata') }}',
            remove_url: '{{ route('module.survey.template.remove') }}'
        });
    </script>
@endsection
