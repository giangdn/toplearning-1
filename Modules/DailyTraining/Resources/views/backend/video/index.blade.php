@extends('layouts.backend')

@section('page_title', 'Training Video')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div role="main" id="daily-training-video">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('backend.enter_video') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('daily-training-video-acceept')
                            <button type="button" class="btn btn-success approve" data-status="1"><i class="fa fa-check-circle"></i> {{ trans('backend.approve') }}</button>
                            <button type="button" class="btn btn-danger approve" data-status="0"><i class="fa fa-times-circle"></i> {{ trans('backend.deny') }}</button>
                        @endcan
                    </div>
                    <div class="btn-group">
                        @can('daily-training-video-delete')
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
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="video" data-width="200" data-formatter="video_formatter">Video</th>
                    <th data-field="name">{{ trans('backend.video_name') }}</th>
                    <th data-field="view" data-align="center">{{ trans('backend.views') }}</th>
                    <th data-field="created_by">{{ trans('backend.poster') }}</th>
                    <th data-field="created_time" data-align="center">{{ trans('backend.post_time') }}</th>
                    <th data-field="user_approve">{{ trans('backend.reviewer') }}</th>
                    <th data-field="time_approve" data-align="center">{{ trans('backend.review_time') }}</th>
                    <th data-field="approve" data-formatter="approve_formatter" data-align="center">{{trans('backend.status')}}</th>
                    <th data-formatter="comment_formatter" data-align="center">{{ trans('backend.comment') }}</th>
                    <th data-formatter="report_formatter" data-align="center">{{ trans('backend.statistic') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function video_formatter(value, row, index) {
            return '<video width="200" height="100" controls><source src="'+row.video+'" type="video/mp4"></video>'
        }

        function comment_formatter(value, row, index) {
            return '<a href="'+row.view_comment+'"><i class="fa fa-comment"></i></a>';
        }

        function report_formatter(value, row, index) {
            return '<a href="'+row.view_report+'"><i class="fa fa-cogs"></i></a>';
        }

        function approve_formatter(value, row, index) {
            return (value == 2 ? '{{ trans("backend.pending") }}' : (value == 1 ? '{{ trans("backend.approve") }}' : '{{ trans("backend.deny") }}'));
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.daily_training.video.getdata', ['cate_id' => $cate_id]) }}',
            remove_url: '{{ route('module.daily_training.video.remove', ['cate_id' => $cate_id]) }}'
        });

        $(".approve").on('click', function () {
            let status = $(this).data('status');
            let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 video', 'error');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '{{ route('module.daily_training.video.approve', ['cate_id' => $cate_id]) }}',
                dataType: 'json',
                data: {
                    'ids': ids,
                    'status': status
                }
            }).done(function(data) {
                show_message(data.message, data.status);
                table.refresh();
                return false;
            }).fail(function(data) {
                return false;
            });
        });
    </script>
@endsection
