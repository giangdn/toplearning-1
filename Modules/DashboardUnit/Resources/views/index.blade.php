@extends('layouts.backend')

@section('page_title', trans('backend.dashboard'))
@section('header')
    <script src="{{asset('styles/vendor/chart/Chart.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('styles/js/google_chart.js')}}" type="text/javascript"></script>
    <script src="{{asset('styles/vendor/jqueryplugin/jquery.knob.min.js')}}" type="text/javascript"></script>
    <link rel="stylesheet" href="{{ asset('styles/vendor/ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/module/dashboard/css/dashboard.css') }}">
    <style>
        .select2-container--default .select2-selection--single,
        .select2-selection .select2-selection--single{
            padding: 2px 12px;
        }
    </style>
@endsection
@section('content')
    @if(!\App\Permission::isUnitManager())
    <div class="row">
        <div class="col-12 mb-3 menu_dashborad">
            <button type="button" class="btn btn-success ">
                <a href="{{ route('module.dashboard') }}">Tổng quan</a>
            </button>
            <button type="button" class="btn btn-success">
                <a href="{{ route('module.dashboard_unit') }}">Chi tiết</a>
            </button>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <form class="form-inline form-search mb-3" id="form-search">
                @for($i = 2; $i <= 2; $i++)
                    <div class="w-auto">
                        <select name="area" id="area-{{ $i }}" class="form-control load-area" data-placeholder="-- {{ $level_name_area($i)->name }} --" data-level="{{ $i }}" data-loadchild="area-{{ $i+1 }}" data-parent="0">
                            @if(isset($list_area_request[$i]))
                                <option value="{{ $list_area_request[$i]->id }}"> {{ $list_area_request[$i]->name }}</option>
                            @endif
                        </select>
                    </div>
                @endfor

                @for($i = 1; $i <= 3; $i++)
                    <div class="w-auto">
                        <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="-- ĐV cấp {{$i}} --" data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0">
                            @if(isset($list_unit_request[$i]))
                                <option value="{{ $list_unit_request[$i]->id }}"> {{ $list_unit_request[$i]->name }}</option>
                            @endif
                        </select>
                    </div>
                @endfor

                <div class="w-auto">
                    <select name="unit_type" class="form-control select2" data-placeholder="-- Loại đơn vị --">
                        <option value=""></option>
                        @foreach($unit_type as $type)
                            <option value="{{ $type->id }}" {{ isset($unit_type_request) && $unit_type_request == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-auto">
                    <input type="text" name="start_date" value="{{ isset($start_date_request) ? $start_date_request : '' }}" class="form-control datepicker w-100" placeholder="Từ ngày">
                </div>
                <div class="w-auto">
                    <input type="text" name="end_date" value="{{ isset($end_date_request) ? $end_date_request : '' }}" class="form-control datepicker w-100" placeholder="Đến ngày">
                </div>
                <div class="w-auto">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </div>
            </form>
        </div>
    </div>
    @php
        $area = request()->get('area');
        $unit = request()->get('unit');
        $unit_type = request()->get('unit_type');
        $start_date = request()->get('start_date');
        $end_date = request()->get('end_date');
    @endphp
    <div class="row">
        <div class="col pr-0 col-xs-6">
            <!-- small box -->
            <div class="small-box" style="background: #17a2b8;">
                <div class="inner text-white">
                    <h3>{{ $count_online_by_course }}</h3>
                    <p class="text-white">E-Learning</p>
                </div>
                <div class="icon">
                    <i class="fas fa-globe-americas"></i>
                </div>
            </div>
        </div>
        <div class="col pr-0 col-xs-6">
            <!-- small box -->
            <div class="small-box" style="background: #8b1409;">
                <div class="inner text-white">
                    <h3>{{ $count_offline_by_course }}</h3>
                    <p class="text-white">Tập trung</p>
                </div>
                <div class="icon">
                    <i class="fas fa-globe-americas"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col pr-0 col-xs-6">
            <!-- small box -->
            <div class="small-box" style="background: darkturquoise">
                <div class="inner" style="color: #8b1409;">
                    <h3>{{ $count_user_by_online_course }}</h3>
                    <p style="color: #8b1409;">CBNV Online</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col pr-0 col-xs-6">
            <!-- small box -->
            <div class="small-box" style="background: #FEF200">
                <div class="inner" style="color: #8b1409;">
                    <h3>{{ $count_user_by_offline_course }}</h3>
                    <p style="color: #8b1409;">CBNV Tập trung</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col pr-0 col-xs-6">
            <!-- small box -->
            <div class="small-box" style="background: #1988C8">
                <div class="inner text-white">
                    <h3>{{ $count_part_by_quiz }}</h3>
                    <p class="text-white">Ca thi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col col-xs-6">
            <!-- small box -->
            <div class="small-box" style="background: #0FA461">
                <div class="inner text-white">
                    <h3>{{ $count_user_by_quiz }}</h3>
                    <p class="text-white">Lượt CBNV thi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
    </div>

    <!-- Thống kê lớp theo loại hình đào tạo -->
    <div class="row mt-2">
        <div class="col-7">
            <div class="card">
                <div class="card-header bg-info row m-0">
                    <div class="col-10">
                        <h3 class="box-title text-white">Thống kê số lớp theo loại hình đào tạo</h3>
                    </div>
                    <div class="col-2 pull-right p-0">
                        <form action="{{ route('module.dashboard_unit.export_dashboard_training_form',['type' => 0]) }}" method="GET">
                            <input type="hidden" name="area_training_form" value="{{ $area }}">
                            <input type="hidden" name="unit_training_form" value="{{ $unit }}">
                            <input type="hidden" name="unit_type_training_form" value="{{ $unit_type }}">
                            <input type="hidden" name="start_date_training_form" value="{{ $start_date }}">
                            <input type="hidden" name="end_date_training_form" value="{{ $end_date }}">
                            <button class="btn btn-info" type="submit"><i class="fa fa-download"></i> Export</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="lineChartCourseByTrainingForm" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="box-title text-white">Thống kê số lớp theo loại hình đào tạo</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="pieChartCourseByTrainingForm" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê CBNV theo loại hình đào tạo -->
    <div class="row mt-2">
        <div class="col-7">
            <div class="card">
                <div class="card-header bg-primary row m-0">
                    <div class="col-10">
                        <h3 class="box-title text-white">Thống kê lượt CBNV theo loại hình đào tạo</h3>
                    </div>
                    <div class="col-2 pull-right p-0">
                        <form action="{{ route('module.dashboard_unit.export_dashboard_user_training_form') }}" method="GET">
                            <input type="hidden" name="area_user_training_form" value="{{ $area }}">
                            <input type="hidden" name="unit_user_training_form" value="{{ $unit }}">
                            <input type="hidden" name="unit_type_user_training_form" value="{{ $unit_type }}">
                            <input type="hidden" name="start_user_date_training_form" value="{{ $start_date }}">
                            <input type="hidden" name="end_user_date_training_form" value="{{ $end_date }}">
                            <button class="btn btn-info" type="submit"><i class="fa fa-download"></i> Export</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="lineChartUserByTrainingForm" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="box-title text-white">Thống kê lượt CBNV theo loại hình đào tạo</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="pieChartUserByTrainingForm" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê lớp theo Tân tuyển/Hiện Hữu -->
    <div class="row mt-2">
        <div class="col-7">
            <div class="card">
                <div class="card-header bg-secondary row m-0">
                    <div class="col-10">
                        <h3 class="box-title text-white">Thống kê số lớp Tân tuyển & Hiện hữu</h3>
                    </div>
                    <div class="col-2 pull-right p-0">
                        <form action="{{ route('module.dashboard_unit.export_dashboard_course_employee',['type' => 0]) }}" method="GET">
                            <input type="hidden" name="area_course_employee" value="{{ $area }}">
                            <input type="hidden" name="unit_course_employee" value="{{ $unit }}">
                            <input type="hidden" name="unit_type_course_employee" value="{{ $unit_type }}">
                            <input type="hidden" name="start_date_course_employee" value="{{ $start_date }}">
                            <input type="hidden" name="end_date_course_employee" value="{{ $end_date }}">
                            <button class="btn btn-info" type="submit"><i class="fa fa-download"></i> Export</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="lineChartCourseByCourseEmployee" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header bg-secondary">
                    <h3 class="box-title text-white">Thống kê số lớp Tân tuyển & Hiện hữu</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="pieChartCourseByCourseEmployee" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê CBNV theo Tân tuyển & Hiện hữu -->
    <div class="row mt-2">
        <div class="col-7">
            <div class="card">
                <div class="card-header bg-success row m-0">
                    <div class="col-10">
                        <h3 class="box-title text-white">Thống kê lượt CBNV Tân tuyển & Hiện hữu</h3>
                    </div>
                    <div class="col-2 pull-right p-0">
                        <form action="{{ route('module.dashboard_unit.export_dashboard_user_course_employee') }}" method="GET">
                            <input type="hidden" name="area_user_course_employee" value="{{ $area }}">
                            <input type="hidden" name="unit_user_course_employee" value="{{ $unit }}">
                            <input type="hidden" name="unit_type_user_course_employee" value="{{ $unit_type }}">
                            <input type="hidden" name="start_date_user_course_employee" value="{{ $start_date }}">
                            <input type="hidden" name="end_date_user_course_employee" value="{{ $end_date }}">
                            <button class="btn btn-info" type="submit"><i class="fa fa-download"></i> Export</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="lineChartUserByCourseEmployee" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="box-title text-white">Thống kê lượt CBNV Tân tuyển & Hiện hữu</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="pieChartUserByCourseEmployee" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê số ca thi theo loại kỳ thi -->
    <div class="row mt-2">
        <div class="col-7">
            <div class="card">
                <div class="card-header bg-danger row m-0">
                    <div class="col-10">
                        <h3 class="box-title text-white">Thống kê số ca thi theo loại kỳ thi</h3>
                    </div>
                    <div class="col-2 pull-right p-0">
                        <form action="{{ route('module.dashboard_unit.export_dashboard_quiz',['type' => 0]) }}" method="GET">
                            <input type="hidden" name="area_quiz" value="{{ $area }}">
                            <input type="hidden" name="unit_quiz" value="{{ $unit }}">
                            <input type="hidden" name="unit_type_quiz" value="{{ $unit_type }}">
                            <input type="hidden" name="start_date_quiz" value="{{ $start_date }}">
                            <input type="hidden" name="end_date_quiz" value="{{ $end_date }}">
                            <button class="btn btn-info" type="submit"><i class="fa fa-download"></i> Export</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="lineChartPartByQuizType" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header bg-danger">
                    <h3 class="box-title text-white">Thống kê số ca thi theo loại kỳ thi</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="pieChartPartByQuizType" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê CBNV thi theo loại kỳ thi -->
    <div class="row mt-2">
        <div class="col-7">
            <div class="card">
                <div class="card-header bg-warning row m-0">
                    <div class="col-10">
                        <h3 class="box-title text-white">Thống kê lượt CBNV thi theo loại kỳ thi</h3>
                    </div>
                    <div class="col-2 pull-right p-0">
                        <form action="{{ route('module.dashboard_unit.export_dashboard_user_quiz') }}" method="GET">
                            <input type="hidden" name="area_user_quiz" value="{{ $area }}">
                            <input type="hidden" name="unit_user_quiz" value="{{ $unit }}">
                            <input type="hidden" name="unit_type_user_quiz" value="{{ $unit_type }}">
                            <input type="hidden" name="start_date_user_quiz" value="{{ $start_date }}">
                            <input type="hidden" name="end_date_user_quiz" value="{{ $end_date }}">
                            <button class="btn btn-info" type="submit"><i class="fa fa-download"></i> Export</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="lineChartUserByQuizType" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="box-title text-white">Thống kê lượt CBNV thi theo loại kỳ thi</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div id="pieChartUserByQuizType" style="height:250px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        google.charts.load('current', {'packages':['corechart', 'line']});

        google.charts.setOnLoadCallback(lineChartCourseByTrainingForm);
        google.charts.setOnLoadCallback(pieChartCourseByTrainingForm);

        google.charts.setOnLoadCallback(lineChartUserByTrainingForm);
        google.charts.setOnLoadCallback(pieChartUserByTrainingForm);

        google.charts.setOnLoadCallback(lineChartCourseByCourseEmployee);
        google.charts.setOnLoadCallback(pieChartCourseByCourseEmployee);

        google.charts.setOnLoadCallback(lineChartUserByCourseEmployee);
        google.charts.setOnLoadCallback(pieChartUserByCourseEmployee);

        google.charts.setOnLoadCallback(lineChartPartByQuizType);
        google.charts.setOnLoadCallback(pieChartPartByQuizType);

        google.charts.setOnLoadCallback(lineChartUserByQuizType);
        google.charts.setOnLoadCallback(pieChartUserByQuizType);

        function lineChartCourseByTrainingForm() {
            let result = @json($lineChartCourseByTrainingForm);
            var data = google.visualization.arrayToDataTable(
                result['content']
            );
            var options = {
                lineWidth: 3,
                legend: {
                    position: 'right',
                    maxLines: 4,
                },
                chartArea:{
                    left: 30,
                },
                hAxis: {
                    title: 'Tháng',
                },
            };
            // var chart = new google.charts.Line(document.getElementById('lineChartCourseByTrainingForm'));
            // chart.draw(data, google.charts.Line.convertOptions(options));
            var chart = new google.visualization.LineChart(document.getElementById('lineChartCourseByTrainingForm'));
            chart.draw(data, options);
        }
        function pieChartCourseByTrainingForm() {
            var data = google.visualization.arrayToDataTable(@json($pieChartCourseByTrainingForm));
            var options = {
                title: '',
                pieHole: 0.4,
                pieSliceTextStyle: {
                    color: 'black',
                },
                legend: {position: 'top'},
                pieSliceText: 'value',
            };
            var chart = new google.visualization.PieChart(document.getElementById('pieChartCourseByTrainingForm'));
            chart.draw(data, options);
        }

        function lineChartUserByTrainingForm() {
            let result = @json($lineChartUserByTrainingForm);
            var data = google.visualization.arrayToDataTable(
                result['content']
            );

            var options = {
                // title: '',
                lineWidth: 3,
                legend: {
                    position: 'right',
                    maxLines: 4,
                },
                chartArea:{
                    // width: 320,
                    left: 30,
                },
                hAxis: {
                    title: 'Tháng'
                },
            };
            var chart = new google.visualization.LineChart(document.getElementById('lineChartUserByTrainingForm'));
            chart.draw(data, options);
        }
        function pieChartUserByTrainingForm() {
            var data = google.visualization.arrayToDataTable(@json($pieChartUserByTrainingForm));
            var options = {
                title: '',
                pieHole: 0.4,
                pieSliceTextStyle: {
                    color: 'black',
                },
                legend: {position: 'top'},
                pieSliceText: 'value',
            };
            var chart = new google.visualization.PieChart(document.getElementById('pieChartUserByTrainingForm'));
            chart.draw(data, options);
        }

        function lineChartCourseByCourseEmployee() {
            let result = @json($lineChartCourseByCourseEmployee);
            var data = google.visualization.arrayToDataTable(
                result['content']
            );

            var options = {
                lineWidth: 3,
                legend: {
                    position: 'right',
                    maxLines: 4,
                },
                chartArea:{
                    left: 30,
                },
                hAxis: {
                    title: 'Tháng'
                },
            };
            var chart = new google.visualization.LineChart(document.getElementById('lineChartCourseByCourseEmployee'));
            chart.draw(data, options);
        }
        function pieChartCourseByCourseEmployee() {
            var data = google.visualization.arrayToDataTable(@json($pieChartCourseByCourseEmployee));
            var options = {
                title: '',
                pieHole: 0.4,
                pieSliceTextStyle: {
                    color: 'black',
                },
                legend: {position: 'top'},
                pieSliceText: 'value',
            };
            var chart = new google.visualization.PieChart(document.getElementById('pieChartCourseByCourseEmployee'));
            chart.draw(data, options);
        }

        function lineChartUserByCourseEmployee() {
            let result = @json($lineChartUserByCourseEmployee);
            var data = google.visualization.arrayToDataTable(
                result['content']
            );

            var options = {
                // title: '',
                lineWidth: 3,
                legend: {
                    position: 'right',
                    maxLines: 4,
                },
                chartArea:{
                    // width: 320,
                    left: 30,
                },
                hAxis: {
                    title: 'Tháng'
                },
            };
            var chart = new google.visualization.LineChart(document.getElementById('lineChartUserByCourseEmployee'));
            chart.draw(data, options);
        }
        function pieChartUserByCourseEmployee() {
            var data = google.visualization.arrayToDataTable(@json($pieChartUserByCourseEmployee));
            var options = {
                title: '',
                pieHole: 0.4,
                pieSliceTextStyle: {
                    color: 'black',
                },
                legend: {position: 'top'},
                pieSliceText: 'value',
            };
            var chart = new google.visualization.PieChart(document.getElementById('pieChartUserByCourseEmployee'));
            chart.draw(data, options);
        }

        function lineChartPartByQuizType() {
            let result = @json($lineChartPartByQuizType);
            var data = google.visualization.arrayToDataTable(
                result['content']
            );

            var options = {
                lineWidth: 3,
                legend: {
                    position: 'right',
                    maxLines: 4,
                },
                chartArea:{
                    left: 30,
                },
                hAxis: {
                    title: 'Tháng'
                },
            };
            var chart = new google.visualization.LineChart(document.getElementById('lineChartPartByQuizType'));
            chart.draw(data, options);
        }
        function pieChartPartByQuizType() {
            var data = google.visualization.arrayToDataTable(@json($pieChartPartByQuizType));
            var options = {
                title: '',
                pieHole: 0.4,
                pieSliceTextStyle: {
                    color: 'black',
                },
                legend: {position: 'top'},
                pieSliceText: 'value',
            };
            var chart = new google.visualization.PieChart(document.getElementById('pieChartPartByQuizType'));
            chart.draw(data, options);
        }

        function lineChartUserByQuizType() {
            let result = @json($lineChartUserByQuizType);
            var data = google.visualization.arrayToDataTable(
                result['content']
            );

            var options = {
                // title: '',
                lineWidth: 3,
                legend: {
                    position: 'right',
                    maxLines: 4,
                },
                chartArea:{
                    // width: 320,
                    left: 30,
                },
                hAxis: {
                    title: 'Tháng'
                },
            };
            var chart = new google.visualization.LineChart(document.getElementById('lineChartUserByQuizType'));
            chart.draw(data, options);
        }
        function pieChartUserByQuizType() {
            var data = google.visualization.arrayToDataTable(@json($pieChartUserByQuizType));
            var options = {
                title: '',
                pieHole: 0.4,
                pieSliceTextStyle: {
                    color: 'black',
                },
                legend: {position: 'top'},
                pieSliceText: 'value',
            };
            var chart = new google.visualization.PieChart(document.getElementById('pieChartUserByQuizType'));
            chart.draw(data, options);
        }
    </script>
@endsection

