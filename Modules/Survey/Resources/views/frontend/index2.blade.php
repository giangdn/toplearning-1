@extends('layouts.app')

@section('page_title', 'Khảo sát')

@section('header')

{{--<link rel="stylesheet" href="{{ asset('styles/css/profile.css') }}">--}}
{{--<link rel="stylesheet" type="text/css" href="{{ asset('styles/css/prism.css') }}">--}}
{{--<link rel="stylesheet" type="text/css" href="{{ asset('styles/css/chosen.css') }}">--}}
{{--<script type="text/javascript" src="{{ asset('styles/js/chosen.jquery.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('styles/js/prism.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('styles/js/load_chosen.js') }}"></script>--}}
@endsection

@section('content')

<div class="container-fluid" id="trainingroadmap">
    <ol class="breadcrumb" style="background: white;margin-bottom: 0;" >
        <li>
            <a href="/"><i class="glyphicon glyphicon-home"></i> &nbsp;{{ trans('app.home') }}</a>
        </li>
        <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">»</li>
        <li>{{ trans('app.survey') }}</li>
    </ol>

    <div class="tPanel">
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-sortable="true" data-field="name">{{ trans('app.survey_name') }}</th>
                    <th data-field="date" data-align="center">{{ trans('app.time') }}</th>
                    <th data-field="count_ques" data-align="center">{{ trans('app.num_question') }}</th>
                    <th data-field="action" data-align="center" data-formatter="action_formatter">{{ trans('app.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script type="text/javascript">
    function action_formatter(value, row, index) {
        return row.survey_user == 1 ? '<a href="'+ row.get_survey_user +'">{{ trans('app.take_survey') }}</a>' : row.survey_user == 2 ? '<a href="'+ row.edit_survey_user +'">{{ trans('app.view_survey') }}</a>' : '<a href="'+ row.edit_survey_user +'">{{ trans('app.edit_survey') }}</a>';
    }

    var table = new LoadBootstrapTable({
        url: '{{ route('module.survey.get_data') }}',
        locale: '{{ data_locale('vi-VN', 'en-US') }}',
    });
</script>

@stop
