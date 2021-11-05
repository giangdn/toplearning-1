@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.head_of_department'))

@section('content')
    <div class="container">
        <h6>@lang('app.number_participants_completed_course')</h6>
        <div class="card shadow overflow-hidden p-1">
            <div id="all-courses"></div>
        </div>
    </div>

    <div class="container mt-2">
        <h6>@lang('app.number_new_employees_month')</h6>
        <div class="card shadow overflow-hidden p-1">
            <div id="user-new"></div>
        </div>
    </div>

    <div class="container mt-2">
        <h6>@lang('app.number_employees_each_course')</h6>
        <select name="course" id="course" class="form-control select2" data-placeholder="{{ data_locale('Chọn khóa học', 'Choose the course') }}">
            <option value=""></option>
            @foreach($courses as $course)
                <option value="{{ $course->course_id }}" data-type="{{ $course->course_type }}">{{ $course->name }}</option>
            @endforeach
        </select>

        <select name="month" id="month" class="form-control select2" data-placeholder="{{ data_locale('Chọn tháng', 'Choose month') }}">
            <option value=""></option>
            @for($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}">{{ trans('app.month') .' '. $i }}</option>
            @endfor
        </select>
        <p></p>
        <div class="card shadow overflow-hidden p-1">
            <div id="user-by-course"></div>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        /* charts */
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(AllCourses);
        google.charts.setOnLoadCallback(UserNew);

        function AllCourses() {
            var jsonData = $.ajax({
                type: "POST",
                url: "{{ route('themes.mobile.frontend.manager.chart_all_courses') }}",
                dataType: "json",
                async: false,
                data: {
                    "_token": "{{ csrf_token() }}",
                },
            }).responseText;

            jsonData = JSON.parse(jsonData);
            var data = google.visualization.arrayToDataTable(jsonData);

            var options = {
                title: '',
                bars: 'horizontal',
                legend: {
                    position: 'top'
                }
            };

            var chart = new google.charts.Bar(document.getElementById('all-courses'));

            chart.draw(data, options);
        }

        function UserNew() {
            var jsonData = $.ajax({
                type: "POST",
                url: "{{ route('themes.mobile.frontend.manager.chart_user_new') }}",
                dataType: "json",
                async: false,
                data: {
                    "_token": "{{ csrf_token() }}",
                },
            }).responseText;

            jsonData = JSON.parse(jsonData);
            var data = google.visualization.arrayToDataTable(jsonData);

            var options = {
                title: '',
                bars: 'horizontal',
                legend: {
                    position: 'top'
                }
            };

            var chart = new google.charts.Bar(document.getElementById('user-new'));

            chart.draw(data, options);
        }

        $('#course').on('change', function () {
           var course = $("#course option:selected").val();
           var type = $("#course option:selected").data('type');

            google.charts.load('current', {'packages':['bar']});
            google.charts.setOnLoadCallback(UserByCourse);

            function UserByCourse() {
                var jsonData = $.ajax({
                    type: "POST",
                    url: "{{ route('themes.mobile.frontend.manager.chart_user_by_course') }}",
                    dataType: "json",
                    async: false,
                    data: {
                        'course': course,
                        'type': type,
                        "_token": "{{ csrf_token() }}",
                    },
                }).responseText;

                jsonData = JSON.parse(jsonData);
                var data = google.visualization.arrayToDataTable(jsonData);

                var options = {
                    title: '',
                    bars: 'horizontal',
                    legend: {
                        position: 'top'
                    }
                };

                var chart = new google.charts.Bar(document.getElementById('user-by-course'));

                chart.draw(data, options);
            }
        });

        $('#month').on('change', function () {
            var course = $("#course option:selected").val();
            var type = $("#course option:selected").data('type');
            var month = $("#month option:selected").val();

            if (!course){
                $("#month option:selected").remove();
                show_message('Bạn chưa chọn khóa học', 'warning');
                return false;
            }else {
                google.charts.load('current', {'packages':['bar']});
                google.charts.setOnLoadCallback(UserByCourse);

                function UserByCourse() {
                    var jsonData = $.ajax({
                        type: "POST",
                        url: "{{ route('themes.mobile.frontend.manager.chart_user_by_course') }}",
                        dataType: "json",
                        async: false,
                        data: {
                            'course': course,
                            'type': type,
                            'month' : month,
                            "_token": "{{ csrf_token() }}",
                        },
                    }).responseText;

                    jsonData = JSON.parse(jsonData);
                    var data = google.visualization.arrayToDataTable(jsonData);

                    var options = {
                        title: '',
                        bars: 'horizontal',
                        legend: {
                            position: 'top'
                        }
                    };

                    var chart = new google.charts.Bar(document.getElementById('user-by-course'));

                    chart.draw(data, options);
                }
            }
        });
    </script>
@endsection
