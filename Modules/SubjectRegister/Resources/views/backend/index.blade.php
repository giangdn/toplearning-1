@extends('layouts.backend')

@section('page_title', trans('backend.subject_registered'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')

    <div role="main">
        @if(isset($notifications))
            @foreach($notifications as $notification)
                @if(@$notification->data['messages'])
                    @foreach($notification->data['messages'] as $message)
                        <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}: {!! $message !!}</div>
                    @endforeach
                @else
                    <div class="alert alert-{{ @$notification->data['status'] == 'success' ? 'success' : 'danger' }}">{{ @$notification->data['title'] }}</div>
                @endif
                @php
                    $notification->markAsRead();
                @endphp
            @endforeach
        @endif
        <div class="row">
            <div class="col-md-12">
                <form class="form-inline form-search mb-3 w-100" id="form-search">
                    @csrf
                    @for($i = 1; $i <= 5; $i++)
                        <div class="w-25">
                            <select name="unit" id="unit-{{ $i }}" class="form-control load-unit unit_search" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0"></select>
                        </div>
                    @endfor
                    <div class="w-25">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                    <div class="w-25">
                        <input type="text" name="search" value="" class="form-control w-100" placeholder="Tìm Tên/Mã nhân viên, khóa học">
                    </div>
                    <div class="w-25">
                        <button type="submit" id="btnsearch" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{trans('backend.search')}}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right">
                <div class="pull-right">
                    <div class="btn-group">
                        <form action="{{ route('backend.subjectregister.export') }}" method="get">
                            <input type="hidden" name="export_search" value="">
                            <input type="hidden" name="export_unit" value="">
                            <button class="btn btn-info" id="btnExport" type="submit"><i class="fa fa-download"></i> Export</button>
                        </form>
                    </div>                        
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-sortable="true" data-align="center" data-formatter="stt_formatter" data-width="5%">STT</th>
                    <th data-field="code" data-width="100">{{ trans('backend.subject_code') }}</th>
                    <th data-field="subject" data-width="400">{{ trans('backend.subject') }}</th>
                    <th data-field="user_code" data-width="200">{{ trans('backend.employee_code') }}</th>
                    <th data-field="full_name" data-width="200">{{ trans('backend.employee_name') }}</th>
                    <th data-field="title_name" data-width="200">{{ trans('backend.title') }}</th>
                    <th data-field="unit_name" data-width="200">{{ trans('backend.direct_units') }}</th>
                    <th data-field="parent_unit_name" data-width="200">{{ trans('backend.management_unit') }}</th>
                    <th data-field="created_date" data-width="180">{{ trans('backend.created_at') }}</th>
                    <th data-field="status" data-width="180">{{ trans('backend.status') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function stt_formatter(value, row, index) {
            return (index + 1);
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('subjectregister.index') }}',
        });

        $('#btnsearch').on('click', function() {
            var latest_value = $(".unit_search option:selected:last").val();
            if(latest_value) {
                $('input[name=export_unit]').val(latest_value);
            }
            var search = $('input[name=search]').val();
            $('input[name=export_search]').val(search);
        })
    </script>

@endsection
