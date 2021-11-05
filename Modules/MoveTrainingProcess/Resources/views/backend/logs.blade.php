@extends('layouts.backend')

@section('page_title', trans('backend.move_training_process'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.movetrainingprocess.index') }}" >{{trans('backend.move_training_process')}}</a>
            <i class="uil uil-angle-right"></i>
            <span >{{ trans('movetrainingprocess::language.move_training_process_log') }}</span>
        </h2>
    </div>
    <div role="main">
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-sortable="true" data-align="center" data-formatter="stt_formatter" data-width="3%">STT</th>
                    <th data-field="action" >{{ trans('backend.action') }}</th>
                    <th data-field="full_name" data-formatter="fullname_formatter">{{ trans('backend.user_create') }}</th>
                    <th data-field="created_date" >{{ trans('backend.created_at') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function stt_formatter(value, row, index) {
            return (index + 1);
        }
        function fullname_formatter(value, row, index) {
            return  row.full_name + ' <b>(' + row.code + ')</b>';
        }


        function area_formatter(value, row, index) {
            return row.area_name ? row.area_name + ' <a href="javascript:void(0)" class="load-modal" data-url="'+row.area_url+'"> <i class="fa fa-info-circle"></i></a>' : '';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.movetrainingprocess.logs.getData') }}',
        });

    </script>
@endsection
