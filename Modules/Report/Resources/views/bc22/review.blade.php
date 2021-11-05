<form name="frm" action="{{route('module.report.review', ['id' => 'BC22'])}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC22">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>Từ ngày</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="from_date" class="form-control datepicker-date" value="{{ isset($from_date) ? $from_date : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>Đến ngày</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="to_date" class="form-control datepicker-date" value="{{ isset($to_date) ? $to_date : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>Chọn kỳ thi</label>
                </div>
                <div class="col-md-6 type">
                    <select class="form-control select2" name="quiz_id" data-placeholder="Chọn kỳ thi">
                        @if($quiz)
                            <option value=""></option>
                            @foreach($quiz as $item)
                                <option {{ isset($quiz_id) ? $item->id == $quiz_id ? 'selected' : '' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <button type="submit" id="btnSearch" class="btn btn-primary">Xem báo cáo</button>
                </div>
            </div>
        </div>
    </div>
</form>
<form name="frm2" id="form-export" action="{{ route('module.report.export') }}" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC22">
    <input type="hidden" name="quiz_id" value="{{ isset($quiz_id) ? $quiz_id : '' }}">
    <input type="hidden" name="from_date" value="{{ isset($from_date) ? $from_date : '' }}">
    <input type="hidden" name="to_date" value="{{ isset($to_date) ? $to_date : '' }}">

    <div class="row">
        <div class="col-md-9"></div>
        <div class="col-md-3 text-right">
            @if(isset($quiz_id) && count($ranks) > 0)
            <button id="btnExport" class="btn btn-primary" name="btnExport">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export excel
            </button>
            @endif
        </div>
    </div>
</form>
<br>
<style>
    .tLight>thead>tr>th, .tDefault>thead>tr>th{
        padding: 10px 8px;
    }
    table>tbody>tr>th{
        font-weight: normal;
    }
</style>
<br>
<div class="table-responsive">
    <table class="tDefault table table-hover table-bordered">
        <thead>
        <tr class="tbl-heading">
            <th rowspan="2">STT</th>
            <th rowspan="2">Chức danh</th>
            <th rowspan="2">Tham gia sát hạch</th>
            <th rowspan="2">Tỷ lệ</th>
            @if($ranks)
                @foreach($ranks as $rank)
                    <th colspan="2">
                        Loại {{ $rank->rank }} <br> {{ number_format($rank->score_max, 1) }} >= Điểm >= {{ number_format ($rank->score_min, 1) }}
                    </th>
                @endforeach
            @endif
        </tr>
        <tr class="tbl-heading">
            @if($ranks)
                @foreach($ranks as $rank)
                    <th>Số lượng</th>
                    <th>Tỷ lệ</th>
                @endforeach
            @endif
        </tr>
        </thead>
        <tbody>
        @if ($rows)
            @foreach ($rows as $key => $row)
                @php
                    $lists = $list($row->title_id, $quiz_id);
                    $results = $result($row->title_id, $quiz_id);
                @endphp
                <tr class="tbl-heading">
                    <th style="text-align: center;">{{ $key + 1 }}</th>
                    <th>{{ $row->title_name }}</th>
                    <th style="text-align: center;">{{ $lists }}</th>
                    <th style="text-align: center;">100 %</th>
                    @if($ranks)
                        @foreach($ranks as $rank)
                            @if($results)
                                @php
                                    $count_result = 0;
                                @endphp

                                @foreach ($results as $item)
                                    @if (isset($item->reexamine))
                                        @if ($item->reexamine >= $rank->score_min && $item->reexamine <= $rank->score_max)
                                            @php
                                                $count_result++;
                                            @endphp
                                        @endif
                                    @else
                                        @if ($item->grade >= $rank->score_min && $item->grade <= $rank->score_max)
                                            @php
                                                $count_result++;
                                            @endphp
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                            <th style="text-align: center;">{{ $count_result }}</th>
                            <th style="text-align: center;">{{ number_format(($count_result / ($lists > 0 ? $lists : 1)) * 100, 1) .' %' }}</th>
                        @endforeach
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@section('bottom')
    <script src="{{asset('styles/module/report/js/bc22.js')}}" type="text/javascript"></script>
@endsection
