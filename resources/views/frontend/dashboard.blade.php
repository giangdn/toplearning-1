@extends('layouts.app')

@section('page_title', 'Bảng Điều khiển')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/profile.css') }}">
<link href="{{ asset('styles/css/frontend/dashboard/css/dashboard.css') }}" rel="stylesheet">

<script type="text/javascript" src="{{ asset('styles/module/dashboard/js/d3.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('styles/module/dashboard/js/jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('styles/module/dashboard/js/3dcharts.js') }}"></script>
@endsection

@section('content')
<div class="container-fluid">
<script type="text/javascript">
$(document).ready(function() {

    function getRandom(min, max) {
        return Math.random() * (max - min) + min;
    }
    function getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min)) + min;
    }

    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

        function generateData() {
        var data = [{
                    "name": "Đang học",
                    "color": "yellow",
                    "value": 0                },{
                    "name": "Chưa học",
                    "color": "red",
                    "value": 94                },
                {
                    "name": "Đã hoàn thành",
                    "color": "green",
                    "value": 6                }];

        return data;
    };

    function createLegendItem(name, color, type) {
        type = type || 'circle';
        return '<li class="chart-legend_item"><span class="chart-legend_' + type + '" style="background: '+color+'"></span><span class="chart-legend_text">'+name+'</span></li>';
    };


    function drawChart() {
        var $chart_bar = $('.chart-canvas');
        if (!$chart_bar.length)return;
        var $container = $chart_bar.closest('.home-indicators_item');
        if (!$container.length)$container = $chart_bar.closest('ul');
        var $legend = $container.find('.chart-legend .home-indicators-legend_content');
        if (!$legend.length) $legend = $container.find('.chart-legend');
        $legend.empty();

        var chartData = generateData();

        // сортируем по убыванию, чтобы вывести легенду
        var legendData = chartData.slice(0).sort(function(a, b) {
            if (a.end_sum > b.end_sum) {
                return -1;
            } else if (a.end_sum < b.end_sum) {
                return 1;
            } else {
                return 0;
            }
        });

        $.each(legendData, function(index){
            var item = createLegendItem(this.name, this.color, 'circle');
            $legend.append(item);
        });

        initRoundChart3D({
            "dataObj": chartData
        });
    }

    drawChart();

    $('.button-update-chart').on('click', function(e) {
        e.preventDefault();

        drawChart();
    });
});    
</script>

<div class="" style="margin: 20px 0;">
    <div class="" style="">
        <div class="content-dashboard">

            <font class="title-ds"><img src="{{ asset('styles/images/icon-tnrm.png') }}" style="margin-bottom: 5px;"> Lộ trình học tập</font>

            <p class="text-center">Bạn còn <font class="l-text">11 khóa học</font> cần hoàn thành</p>

                        
                <div id="overlay" style="margin: auto 10px 0 25%;display: inline-block;float: left;">
                    <div id="progstat" style="width: 33%;"></div>
                    <div id="progress"></div>
                </div>
                
                <span style="display: inline-block;">33%</span>
            
            

            <div id="ht-ch">
                <i class="fa fa-square" style="color: green"></i> {{trans("backend.finish")}}  <i class="fa fa-square" style="color: white; border: 1px solid #6198c7; border-radius: 3px;"></i> Chưa học            </div>

            <font class="title-ds"><img src="{{ asset('styles/images/icon-tkc.png') }}" style="margin-bottom: 5px;"> Thống kê chung</font>

            <div class="row" style="margin-left: 0; margin-right: 0;">
                <div class="col-sm-12 col-md-5">
                    <div class="content-left">
                        Thống kê chung về tình trạng học tập của bạn                                                <div class="total-register">
                            Bạn đã <font class="l-text">ghi danh 17/56</font> Khóa học                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="chart">
                                    <div class="chart-canvas">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3" style="padding-top: 20px;font-size: 13px;">

                                <i class="st-danghoc fa fa-square"></i> Đang học <br />
                                <i class="st-chuahoc fa fa-square"></i> Chưa học <br />
                                <i class="st-dahoc fa fa-square"></i> Đã hoàn thành <br />
                            </div>
                        </div>
                        <h5 class="class-khm"><img src="{{ asset('styles/images/icon-khm.png') }}" style="margin-bottom: 5px;"> Khóa học mới</h5>
                        <table>
                            <thead>
                                <th width="40%" style="text-align: left;">Tên khóa học</th>
                                <th>{{trans('backend.start_date')}}</th>
                                <th>{{trans('backend.end_date')}}</th>
                                <th>Xem thêm</th>
                            </thead>
                            <tbody>
                                                            <tr>
                                    <td style="text-align: left;">Kiến thức về Lốp xe</td>
                                    <td>03-09-2018</td>
                                    <td>20-09-2018</td>
                                    <td><a href="/index.php?_mod=ttc&_view=ttc&_lay=onlinedetail&courseid=248">Xem thêm</a></td>
                                </tr>
                                                            <tr>
                                    <td style="text-align: left;">Dịch vụ khách hàng chuyên nghiệp</td>
                                    <td>23-08-2018</td>
                                    <td>30-11-2018</td>
                                    <td><a href="/index.php?_mod=ttc&_view=ttc&_lay=onlinedetail&courseid=247">Xem thêm</a></td>
                                </tr>
                                                            <tr>
                                    <td style="text-align: left;">Kiến thức về lốp xe về dong PSR</td>
                                    <td>01-09-2018</td>
                                    <td>24-09-2018</td>
                                    <td><a href="/index.php?_mod=ttc&_view=ttc&_lay=onlinedetail&courseid=246">Xem thêm</a></td>
                                </tr>
                                                        </tbody>
                        </table>

                        <div class="text-center">
                            <a href="/?_mod=ttc&_view=ttc&_lay=online" class="btn-view-more">Xem thêm</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-7">
                                        <div class="content-left">
                        <h5 class="class-khm" style="background: #6198c7"><img src="{{ asset('styles/images/dang-hoc.png') }}" style=""> Đang học (0 Khóa học)</h5>
                        <table>
                            <thead>
                                <th width="40%" style="text-align: left;">Tên khóa học</th>
                                <th>Loại khóa học</th>
                                <th>Vào học</th>
                                <th>Số ngày còn lại</th>
                                <th>Ngày hết hạn</th>
                            </thead>
                            <tbody>
                                <td colspan="5">Không có dữ liệu !</td>                            </tbody>
                        </table>

                        <div class="text-center">
                            <a href="/index.php?_mod=ttc&_view=my&_lay=dft" class="btn-view-more">Xem thêm</a>
                        </div>
                            <h5 class="class-khm" style="background: #507fa8;margin-top: 20px;"><img src="{{ asset('styles/images/chua-hoc.png') }}" style=""> Chưa học ( 3 Khóa học)</h5>
                        <table>
                            <thead>
                                <th width="40%" style="text-align: left;">Tên khóa học</th>
                                <th>Loại khóa học</th>
                                <th>Số ngày còn lại</th>
                                <th>Ngày hết hạn</th>
                            </thead>
                            <tbody>
                                                            <tr>
                                    <td style="text-align: left;">Phòng chống rửa tiền</td>
                                    <td style="text-align: center;">_</td>
                                    <td>0</td>
                                    <td>_</td>
                                </tr>
                                                            <tr>
                                    <td style="text-align: left;">Định giá TSĐB</td>
                                    <td style="text-align: center;">_</td>
                                    <td>0</td>
                                    <td>_</td>
                                </tr>
                                                        </tbody>
                        </table>

                        <div class="text-center">
                            <a href="/?_mod=ttc&_view=profile&_lay=dft&tabs=roadmap" class="btn-view-more">Xem thêm</a>
                        </div>
                            <h5 class="class-khm" style="background: #3e6281;margin-top: 20px;"><img src="{{ asset('styles/images/da-hoc.png') }}" style=""> Đã học (1 Khóa học)</h5>
                        <table>
                            <thead>
                                <th width="40%" style="text-align: left;">Tên khóa học</th>
                                <th>Loại khóa học</th>
                                <th>Ngày kết thúc</th>
                                <th>{{ trans('backend.result') }}</th>
                            </thead>
                            <tbody>
                                                            <tr>
                                    <td style="text-align: left;">Phòng chống rửa tiền</td>
                                    <td><i class="fa fa-globe"></i> </td>
                                    <td>16-05-2018</td>
                                    <td>Hoàn thành</td>
                                </tr>
                                                        </tbody>
                        </table>

                        <div class="text-center">
                            <a href="/index.php?_mod=ttc&_view=profile&_lay=dft&tabs=trainingprocess" class="btn-view-more">Xem thêm</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
@stop