@extends('layouts.backend')

@section('page_title', 'Kết quả')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.quiz') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.quiz.manager') }}">{{ trans('backend.quiz_list') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.quiz.edit', ['id' => $quiz_id]) }}">{{ $quiz_name->name }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.quiz.result', ['id' => $quiz_id]) }}">{{ trans('backend.result') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{trans('backend.picture_of')}} {{ $fullname }}</span>
        </h2>
    </div>
@endsection

@section('content')
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-sortable="true" data-formatter="stt_formatter" data-align="center" data-width="5%">STT</th>
                    <th data-field="image" data-formatter="image_formatter" data-width="15%">{{trans('backend.picture')}}</th>
                    <th data-field="time" data-align="center" data-width="10%">{{trans('backend.time')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function stt_formatter(value, row, index){
            return index + 1;
        }

        function image_formatter(value, row, index){
            return '<img src="'+ row.url_image+'" class="w-50" />';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.result.user.getdata_image', ['id' => $quiz_id, 'type' => $type, 'user_id' => $user_id]) }}',
        });

    </script>

@endsection
