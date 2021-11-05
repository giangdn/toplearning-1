{{-- @extends('layouts.app') --}}
@extends('layouts.course_activity')

@section('page_title', $title)

@section('content')
    <div class="container-fluid" id="container_iframe">

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
                           {{-- <th data-field="status" data-align="center">{{ trans('app.status') }}</th>--}}
                            {{--<th data-field="review" data-align="center" data-formatter="review_formatter">{{ trans('app.review') }}</th>--}}
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <script type="text/javascript">
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
                            // window.location = result.redirect;
                            // window.open(result.redirect, '_blank');

                            $('#container_iframe').html(`<iframe src="`+result.redirect+`" class="iframe-embed w-100" style="height:900px" allowfullscreen="allowfullscreen" scrolling="no"></iframe>`);
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
    </div>
@endsection
