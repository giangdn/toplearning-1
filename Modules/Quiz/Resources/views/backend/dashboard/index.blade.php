@extends('layouts.backend')

@section('page_title', 'Thống kê kỳ thi')

@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/dashboard/css/dashboard.css') }}">
    <style>
        .intro-icon {
            position: relative;
        }
        .info-exam {
            position: absolute;
            top: 60px;
            left: 33%;
            color: white;
            font-size: 25px;
            max-width: 120px;
            line-height: normal;
        }

        .circl-value {
            border: 2px solid;
            background: white;
            width: 60px;
            margin: 0 auto;
            text-align: center;
            height: 60px;
            border-color: #c32027;
            border-radius: 60px;
            position: absolute;
            bottom: -15px;
            left: 38%;
        }

        .number-value {
            padding-top: 10px;
            font-size: 23px;
            color: #c32027;
        }
    </style>
@endsection

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-lg-4 col-xs-4">
                <!-- small box -->
                <div class="small-box" style="background: #8b1409;">
                    <div class="inner text-white">
                        <h3>{{ $attempt_user }}</h3>
                        <p class="text-white">Lượt thi nội bộ</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-4">
                <!-- small box -->
                <div class="small-box" style="background: #FEF200">
                    <div class="inner" style="color: #8b1409;">
                        <h3>{{$attempt_user_second}}</h3>
                        <p style="color: #8b1409;">Lượt thi bên ngoài</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-4">
                <!-- small box -->
                <div class="small-box" style="background: #1988C8">
                    <div class="inner text-white">
                        <h3>{{$count_quiz}}</h3>
                        <p class="text-white">Số lượng kỳ thi</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <h2>Thống kê số lượng thí sinh</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form class="form-search-quiz-dashbroad" action="" id="form-search" autocomplete="off">
                    @csrf
                    <div class="form-group row my-2">
                        <div class="col-3 text-right">{{ trans('backend.time') }}</div>
                        <div class="col-9 div-date">
                            <input name="start_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('backend.start')}}" autocomplete="off" value="">
                            <input name="end_date" type="text" class="datepicker form-control w-25 d-inline-block date-custom" placeholder="{{trans('backend.over')}}" autocomplete="off" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">{{ trans('backend.type') }}</div>
                        <div class="col-6">
                            <select name="user_type" id="user_type" class="form-control d-inline-block w-25 select2" data-placeholder="Loại thí sinh">
                                <option value=""></option>
                                <option value="1">Thí sinh nội bộ</option>
                                <option value="2">Thí sinh bên ngoài</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">{{ trans('backend.title') }}</div>
                        <div class="col-6">
                            <select name="title" id="title" class="form-control load-title" data-placeholder="Chức danh">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">Tên kỳ thi</div>
                        <div class="col-6">
                            <select name="quiz" id="quiz" class="form-control select2 " data-placeholder="Tên kỳ thi">
                                <option value=""></option>
                                @foreach ($quizs as $quiz)
                                    <option value="{{ $quiz->id }}">{{ $quiz->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">Loại kỳ thi</div>
                        <div class="col-6">
                            <select name="quiz_type[]" id="quiz_type" class="form-control select2" data-placeholder="Loại kỳ thi" multiple>
                                <option value=""></option>
                                @foreach ($quiz_types as $quiz_type)
                                    <option value="{{ $quiz_type->id }}">{{ $quiz_type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right"></div>
                        <div class="col-3">
                            <button type="button" id="search" class="btn btn-info">{{ trans('backend.search') }}</button>
                            <button type="button" id="export" class="btn btn-info">Export</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 text-center">
                <h2>Số lượng thí sinh</h2>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 text-center">
                <div id="chartUser"></div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="{{ asset('js/charts-loader.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart']});

        $("#search").on('click', function (event) {
            google.charts.setOnLoadCallback(drawBasic);
        });

        function drawBasic() {
            var jsonData = $.ajax({
                type: "POST",
                url: "{{ route('module.quiz.dashboard.chart_user') }}",
                dataType: "json",
                async: false,
                data: $("#form-search").serialize()
            }).responseText;

            jsonData = JSON.parse(jsonData);
            var data = google.visualization.arrayToDataTable(jsonData);

            var options = {
                title: 'Thống kê',
            };

            var chart = new google.visualization.PieChart(document.getElementById('chartUser'));

            chart.draw(data, options);
        }

        $('#export').on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('module.quiz.dashboard.export') }}?'+form_search;
        });
    </script>
@endsection
