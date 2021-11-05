
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box" style="background: #8b1409;">
            <div class="inner text-white">
                <h3>{{ $total_online_course }}</h3>
                <p class="text-white">@lang('backend.online_course')</p>
            </div>
            <div class="icon">
                <i class="fas fa-globe-americas"></i>
            </div>
            @can('online-course')
            <a href="{{ route('module.online.management') }}" class="small-box-footer bg-white">View <i class="fa fa-arrow-circle-right"></i></a>
            @endcan
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box" style="background: #FEF200">
            <div class="inner" style="color: #8b1409;">
                <h3>{{$total_offline_course}}</h3>
                <p style="color: #8b1409;">@lang('backend.offline_course')</p>
            </div>
            <div class="icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            @can('offline-course')
            <a href="{{ route('module.offline.management') }}" class="small-box-footer bg-white">View <i class="fa fa-arrow-circle-right"></i></a>
            @endcan
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box" style="background: #1988C8">
            <div class="inner text-white">
                <h3>{{$total_users}}</h3>
                <p class="text-white">@lang('backend.user')</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            @can('user')
            <a href="{{ route('module.backend.user') }}" class="small-box-footer bg-white">View <i class="fa fa-arrow-circle-right"></i></a>
            @endcan
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box" style="background: #0FA461">
            <div class="inner text-white">
                <h3>{{$total_quiz}}</h3>
                <p class="text-white">@lang('backend.quiz')</p>
            </div>
            <div class="icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            @can('quiz')
            <a href="{{ route('module.quiz.manager') }}" class="small-box-footer bg-white">View <i class="fa fa-arrow-circle-right"></i></a>
            @endcan
        </div>
    </div>
    <!-- ./col -->
</div>
<div class="row">
    <div class="col-lg-7 ui-sortable">
        <!-- CHART User -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('backend.number_monthly_hits')</h3>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="lineChartUser" style="height:250px"></canvas>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-md-5">
        <!-- user realtime online -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('backend.browser_device')</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="chart-responsive">
                            <canvas id="pieChart" ></canvas>
                        </div>
                        <!-- ./chart-responsive -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 no-padding">
                        <ul class="chart-legend clearfix">
                            @php
                                $total_browser = collect($browser_statistic)->pluck('1')->sum();
                            @endphp
                            @foreach ($browser_statistic as $key=>$item)
                                <li><span class="{{$item[2]}}">{{$item[0]=='Internet Explorer'?'IE':$item[0]}} {{round($item[1]/$total_browser*100,2)}}%</span></li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
                @php
                    $device_category = collect($device_category);
                    $total_device = $device_category->pluck('1')->sum();
                @endphp
                <ul class="nav nav-pills nav-stacked">
                    <li>
                        <a href="#">@lang('backend.desktop') <span class="pull-right text-blue" ><i class="fa fa-desktop fa-2x"></i> {{$total_device>0? round($device_category->where('0','=','desktop')->first()[1]/$total_device*100,2):0}}%</span></a>
                    </li>
                    <li>
                        <a href="#">@lang('backend.mobile') <span class="pull-right" style="color:rgba(0,172,95,0.94)"><i class="fa fa-mobile-alt fa-2x"></i> {{$total_device>0?round($device_category->where('0','=','mobile')->first()[1]/$total_device*100,2):0}}%</span></a>
                    </li>
                    <li>
                        <a href="#">@lang('backend.tablet') <span class="pull-right " style="color:rgba(249,88,25,0.89)"><i class="fa fa-tablet-alt fa-2x"></i> {{$total_device>0?round($device_category->where('0','=','tablet')->first()[1]/$total_device*100,2):0}}%</span></a>
                    </li>
                </ul>
            </div>
            <!-- /.footer -->
        </div>
        <div class="online-realtime pt-2">
            {{$users_online}} online
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-9 connectedSortable ui-sortable">
        <!-- CHART khóa học -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('backend.course_statistics')</h3>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="lineChart" style="height:250px"></canvas>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-md-3">
        <p class="text-center">
            <strong>@lang('backend.course_statistics')</strong>
        </p>
        @if(isset($course_statistic))
        <div class="progress-group pt-2">
            <span class="progress-text">@lang('backend.course_held')</span>
            <span class="progress-number"><b>{{$course_statistic->course_held}}</b>/{{$course_statistic->course_total}}</span>
            <div class="progress sm">
                <div class="progress-bar progress-bar-aqua" style="width: {{$course_statistic->course_total>0?($course_statistic->course_held/$course_statistic->course_total*100):0}}%"></div>
            </div>
        </div>
        <!-- /.progress-group -->
        <div class="progress-group pt-2">
            <span class="progress-text">@lang('backend.course_not_held')</span>
            <span class="progress-number"><b>{{$course_statistic->course_not_held}}</b>/{{$course_statistic->course_total}}</span>

            <div class="progress sm">
                <div class="progress-bar progress-bar-red" style="width: {{$course_statistic->course_total>0?($course_statistic->course_not_held/$course_statistic->course_total*100):0}}%"></div>
            </div>
        </div>
        <!-- /.progress-group -->
        <div class="progress-group pt-2">
            <span class="progress-text">@lang('backend.course_canceled')</span>
            <span class="progress-number"><b>{{$course_statistic->course_deny}}</b>/{{$course_statistic->course_total}}</span>

            <div class="progress sm">
                <div class="progress-bar progress-bar-green" style="width: {{$course_statistic->course_total>0?($course_statistic->course_deny/$course_statistic->course_total*100):0}}%"></div>
            </div>
        </div>
        <!-- /.progress-group -->
        <div class="progress-group pt-2">
            <span class="progress-text">@lang('backend.course_pending_approval')</span>
            <span class="progress-number"><b>{{$course_statistic->course_pending}}</b>/{{$course_statistic->course_total}}</span>

            <div class="progress sm">
                <div class="progress-bar progress-bar-yellow" style="width: {{$course_statistic->course_total>0?($course_statistic->course_pending/$course_statistic->course_total*100):0}}%"></div>
            </div>
        </div>
        <!-- /.progress-group -->
        @endif
    </div>
</div>
<div class="row course_lastest">
    <div class="col-md-6">
        <!-- Khóa học online news -->
        <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('backend.latest_online_course')</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <ul class="products-list product-list-in-box">
                @foreach ($lastest_online_course as $item)
                <li class="item">
                    <div class="">
                        <a href="{{route('module.online.detail_online', ['id' => $item->id])}}" class="product-title">{{$item->name}}</a>
                        <span class="product-description">{{$item->created_at->diffForHumans()}} - @lang('backend.code'): {{$item->code}}</span>
                    </div>
                </li>
                @endforeach
                <!-- /.item -->
            </ul>
        </div>
        <!-- /.box-body -->
    </div>
    </div>
    <div class="col-md-6">
        <!-- Khóa học offline news -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('backend.latest_offline_course')</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="products-list product-list-in-box">
                    @foreach ($lastest_offline_course as $item)
                    <li class="item">
                        <div class=" ">
                            <a href="{{route('module.offline.detail', ['id' => $item->id])}}" class="product-title">{{$item->name}}</a>
                            <span class="product-description">{{$item->created_at->diffForHumans()}} - @lang('backend.code'): {{$item->code}}</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7 ui-sortable">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('backend.situation_organizing_exam')</h3>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="barChartQuiz" style="height:250px"></canvas>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-md-5">
        <!-- Kỳ thi -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('backend.latest_exam')</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="products-list product-list-in-box">
                    @foreach ($lastest_quiz as $item)
                    <li class="item">
                        <div class=" ">
                            <a href="{{route('module.quiz.edit', ['id' => $item->id])}}" class="product-title">{{$item->name}}</a>
                            <span class="product-description">{{$item->created_at->diffForHumans()}}</span>
                        </div>
                    </li>
                    @endforeach
                    <!-- /.item -->
                </ul>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-9 ui-sortable">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('backend.students_complete_course')</h3>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="stackedChartQuiz" style="height:250px"></canvas>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-md-3">
        <!-- Kỳ thi -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('backend.completion_rate')</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="col-md-12 text-center">
                    <input type="text" readonly class="knob" value="{{($rate_fail>0 || $rate_finish>0)? round($rate_finish/($rate_fail+$rate_finish)*100,0):0}}%" data-width="90" data-height="90" data-fgColor="#50c772" data-readonly="true">
                    <div class="knob-label">@lang('backend.completed')</div>
                </div>
                <div class="col-md-12 text-center">
                    <input type="text" readonly class="knob" value="{{($rate_fail>0 || $rate_finish>0) ?round($rate_fail/($rate_fail+$rate_finish)*100,0):0}}%" data-width="90" data-height="90" data-fgColor="#E25775" data-readonly="true">
                    <div class="knob-label">@lang('backend.incomplete')</div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>

{{-- THỐNG KÊ KHÓA HỌC ONLINE TRUY CẬP THEO TỪNG THÁNG --}}
<div class="row">
    <div class="col-lg-9 ui-sortable">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Thống kê truy cập khóa học Online</h3>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="statistic_access_online" style="height:250px"></canvas>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>

{{-- THỐNG KÊ TRUY CẬP TIN TỨC, VIDEO, HÌNH ẢNH --}}
<div class="row">
    <div class="col-lg-9 ui-sortable">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Thống kê truy cập tin tức, video, hình ảnh</h3>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="statistic_access_news" style="height:250px"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Số lượng</h3>
            </div>
            <div class="box-body">
                <div class="col-md-12 text-center">
                    <div class="row">
                        <div class="col-6">
                            <svg width="80" height="80">
                                <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_video_new}}</text>
                            </svg>
                            <div class="knob-label">Video</div>
                        </div>
                        <div class="col-6">
                            <svg width="80" height="80">
                                <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_image_new}}</text>
                            </svg>
                            <div class="knob-label">Hình ảnh</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <div class="row">
                        <div class="col-6">
                            <svg width="80" height="80">
                                <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_post_new}}</text>
                            </svg>
                            <div class="knob-label">Bài viết</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- THỐNG KÊ TRUY CẬP DIỄN ĐÀN --}}
    <div class="row">
    <div class="col-lg-9 ui-sortable">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Thống kê truy cập diễn đàn</h3>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="statistic_access_forums" style="height:250px"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Số lượng</h3>
            </div>
            <div class="box-body">
                <div class="col-md-12 text-center">
                    <div class="row">
                        <div class="col-6">
                            <svg width="80" height="80">
                                <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_forum}}</text>
                            </svg>
                            <div class="knob-label">Chủ đề</div>
                        </div>
                        <div class="col-6">
                            <svg width="80" height="80">
                                <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_forum_post}}</text>
                            </svg>
                            <div class="knob-label">Bài viết</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <div class="row">
                        <div class="col-6">
                            <svg width="80" height="80">
                                <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                <text class="convert_forum_comment" fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_forum_comment}}</text>
                            </svg>
                            <div class="knob-label">Bình luận</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- THỐNG KÊ TRUY CẬP, SỐ LƯỢNG TÀI LIỆU, EBOOK, AUDIO, SÁCH GIẤY, VIDEO --}}
<div class="row">
    <div class="col-lg-9 ui-sortable">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Thống kê truy cập tài liệu, ebook, sách giấy, audio, video</h3>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="statistic_access_libraries" style="height:250px"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Số lượng</h3>
            </div>
            <div class="box-body">
                <div class="col-md-12 text-center">
                    <div class="row">
                        <div class="col-6">
                            <svg width="80" height="80">
                                <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_video_libraries}}</text>
                            </svg>
                            <div class="knob-label">Video</div>
                        </div>
                        <div class="col-6">
                            <svg width="80" height="80">
                                <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_book_libraries}}</text>
                            </svg>
                            <div class="knob-label">Sách</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <div class="row">
                        <div class="col-6">
                            <svg width="80" height="80">
                                <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_document_libraries}}</text>
                            </svg>
                            <div class="knob-label">Tài liệu</div>
                        </div>
                        <div class="col-6">
                            <svg width="80" height="80">
                                <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_audio_libraries}}</text>
                            </svg>
                            <div class="knob-label">Sách nói</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <div class="row">
                        <div class="col-6">
                            <svg width="80" height="80">
                                <circle cx="40" cy="40" r="30" stroke="rgba(0,0,0,.1)" stroke-width="10" fill="white" />
                                <text fill="#000000" text-anchor="middle" font-size="18" x="50%" y="57%">{{$count_ebook_libraries}}</text>
                            </svg>
                            <div class="knob-label">Sách điện tử</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
