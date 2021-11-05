@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.competence'))

@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/capabilities/css/capabilities.css') }}">
@endsection

@section('content')
    <div class="container" id="course-review">
        @php
            $review_last = \Modules\Capabilities\Entities\CapabilitiesResult::getLastReviewUser($user->user_id);
            $course_standard = \Modules\Capabilities\Entities\CapabilitiesResult::getCourseStandard($user->user_id);
            $course_need_add = \Modules\Capabilities\Entities\CapabilitiesResult::getCourseNeedAdditional($user->user_id);
            $percent =  \Modules\Capabilities\Entities\CapabilitiesResult::getPercent($user->user_id);
        @endphp
        <div class="row">
            <div class="col-md-12">
                <h6 class="text-center pb-2">{{trans('backend.training_program')}}</h6>
                <div class="card shadow border-0 mb-3">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="control-label col-4"><b>{{trans('backend.fullname')}}</b></label>
                            <div class="col-8">
                                {{ $user->lastname .' '. $user->firstname .' ('. $user->code .')' }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-4"><b>{{ trans('backend.unit') }}</b></label>
                            <div class="col-8">
                                @if(isset($unit->name)) {{ $unit->name }} @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-4"><b>{{ trans('backend.title') }}</b></label>
                            <div class="col-8">
                                @if(isset($title->name)) {{ $title->name }} @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-4"><b>Trạng thái</b></label>
                            <div class="col-8">
                                @if($percent == 0)
                                    Đang lên lộ trình cải thiện
                                @elseif($percent == 100)
                                    Đã đáp ứng yêu cầu
                                @else
                                    Đang phát triển
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-4"><b>Ngày cập nhật đánh giá</b></label>
                            <div class="col-8">
                                {{ get_date($review_last->updated_at, 'd/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <p class="border-bottom">TIẾN TRÌNH ĐÀO TẠO THEO NĂNG LỰC</p>
                <div class="progress progress2">
                    <div class="progress-bar w-{{$percent}}" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}"
                         aria-valuemin="0" aria-valuemax="100">
                        {{ $percent }}%
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <p class="border-bottom mt-3">BIỂU ĐỒ PHÁT TRIỂN NĂNG LỰC DỰA THEO KHÓA HỌC</p>
                <div id="chart-capability"></div>
            </div>

            <div class="col-md-12">
                <h6 class="text-center mt-3">CHI TIẾT CÁC KHÓA HỌC THEO NĂNG LỰC CỦA BẠN</h6>

                <p>CHI TIẾT CÁC KHÓA HỌC THEO NĂNG LỰC CỦA CHỨC DANH HIỆN TẠI</p>
                <table class="tDefault table table-bordered table-review">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Tên năng lực yêu cầu</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($course_standard)
                        @foreach($course_standard as $key => $item)
                            @php
                                $result = \Modules\Capabilities\Entities\CapabilitiesResult::getCourseComplete($user->user_id, $item->course_id, $user->user_id);
                            @endphp
                            <tr>
                                <td>@if($result) <i class="fa fa-check text-success"></i> @endif</td>
                                <td class="text-left">
                                    {{ $item->capabilities_name .' - Cấp độ '. $item->standard_level .' ('. $item->capabilities_code . ')'  }} <br>
                                    <span class="text-info">NHÓM: {{ $item->group_name }}</span> <br>
                                    <span class="text-danger">KHÓA: {{ $item->course_name }}</span>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>

                <p>CHI TIẾT CÁC KHÓA HỌC CẦN BỔ SUNG CHO NĂNG LỰC HIỆN TẠI</p>
                <table class="tDefault table table-bordered table-review">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Tên năng lực yêu cầu</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($course_need_add)
                        @foreach($course_need_add as $key => $item)
                            @php
                                $result = \Modules\Capabilities\Entities\CapabilitiesResult::getCourseComplete($user->user_id, $item->course_id, $user->user_id);
                            @endphp
                            <tr>
                                <td>@if($result) <i class="fa fa-check text-success"></i> @endif</td>
                                <td class="text-left">
                                    {{ $item->capabilities_name .' - Cấp độ '. $item->standard_level .' ('. $item->capabilities_code . ')'  }} <br>
                                    <span class="text-info">NHÓM: {{ $item->group_name }}</span> <br>
                                    <span class="text-danger">KHÓA: {{ $item->course_name }}</span>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        /* charts */
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var jsonData = $.ajax({
                type: "POST",
                url: "{{ route('module.capabilities.review.user.chart_course', ['user_id' => $user->user_id]) }}",
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
                curveType: 'function',
                legend: {position: 'top'}
            };

            var chart = new google.visualization.LineChart(document.getElementById('chart-capability'));

            chart.draw(data, options);
        }
    </script>
@endsection
