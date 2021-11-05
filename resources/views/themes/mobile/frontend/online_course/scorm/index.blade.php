@extends('themes.mobile.layouts.app')

@section('page_title', $title)

@section('content')
    <div class="container-fluid">

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $title }}
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ trans('app.info') }}</h5>
                        <p class="card-text">{{ $activity->description }}</p>
                        <button type="button" class="btn btn-primary" id="go-scorm"><i class="fa fa-edit"></i> Vào bài học</button>
                    </div>
                </div>

                <p></p>
                <h4>Tóm tắt lịch sử</h4>
                <table class="tDefault table table-hover bootstrap-table table-bordered">
                    <thead>
                        <tr>
                            <th data-formatter="index_formatter" data-align="center">#</th>
                            <th data-field="start_date">{{ trans('app.start_date') }}</th>
                            <th data-field="end_date">{{ trans('app.end_date') }}</th>
                            <th data-field="grade" data-align="center">{{ trans('app.score') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script>
        $("#go-scorm").on('click', function () {
            let btn = $(this);
            let icon = btn.find('i').attr('class');

            btn.find('i').attr('class', 'fa fa-spinner fa-spin');
            btn.prop("disabled", true);

            $.ajax({
                type: "POST",
                url: "{{ route('module.online.scorm.play', [$course->id, $activity->id]) }}",
                dataType: 'json',
                data: {},
                success: function (result) {
                    btn.find('i').attr('class', icon);
                    btn.prop("disabled", false);

                    if (result.status == "success") {
                        window.location = result.redirect;
                        return false;
                    }

                    show_message(result.message, result.status);
                    return false;
                }
            });
        });

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.attempts', [$activity->id]) }}',
        });

        function index_formatter(value, row, index) {
            return (index+1)
        }
    </script>
@endsection
