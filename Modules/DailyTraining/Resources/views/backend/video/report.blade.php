@extends('layouts.backend')

@section('page_title', 'Training Video')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training_video') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.daily_training') }}">{{ trans('backend.video_category') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.daily_training.video', ['cate_id' => $cate_id]) }}">{{ $video->name }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.statistic') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main" id="daily-training-video">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('backend.enter_code_name_viewer') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="fullname">{{ trans('backend.viewers') }}</th>
                    <th data-field="dob" data-align="center">{{ trans('backend.year_of_birth') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="time_view" data-align="center">{{ trans('backend.watch_time') }}</th>
                    <th data-field="device">{{ trans('backend.device') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.daily_training.video.report.getdata', ['cate_id' => $cate_id, 'video_id' => $video->id]) }}',
        });
    </script>
@endsection
