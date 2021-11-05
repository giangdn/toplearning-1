@extends('layouts.backend')

@section('page_title', trans('backend.subject_complete'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">{{trans('backend.subject_registered')}}</span>
        </h2>
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
            <div class="col-md-6">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder="{{trans('backend.merged_subject_search')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{trans('backend.search')}}</button>
                </form>
            </div>
            <div class="col-md-6 text-right act-btns">
                <div class="pull-right">

                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-sortable="true" data-align="center" data-formatter="stt_formatter" data-width="5%">STT</th>
                    <th data-field="code" data-width="100">{{ trans('backend.subject_code') }}</th>
                    <th data-field="subject" data-formatter="subject_formatter"  data-width="400">{{ trans('backend.subject') }}</th>
                    <th data-field="full_name" data-width="200">{{ trans('backend.employee_name') }}</th>
                    <th data-field="created_date" data-width="180">{{ trans('backend.created_at') }}</th>
                    <th data-field="status" data-width="120">{{ trans('backend.status') }}</th>
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
        $('#import-plan').on('click', function() {
            $('#modal-import').modal();
        });
    </script>

@endsection
