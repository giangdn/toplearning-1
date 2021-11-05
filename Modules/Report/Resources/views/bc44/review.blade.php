<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC44">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.date_from')}}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="from_date" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.date_to')}}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="to_date" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>Phòng phụ trách</label>
                </div>
                <div class="col-md-6 type">
                    <select class="form-control select2" name="role_id" data-placeholder="Phòng phụ trách">
                        @if($role)
                            <option value=""></option>
                            @foreach($role as $item)
                                <option value="{{ $item->id }}">{{ $item->description }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.quiz_type') }}</label>
                </div>
                <div class="col-md-6 type">
                    <select name="quiz_type" id="quiz_type" class="form-control load-quiz-type" data-placeholder="-- {{ trans('backend.quiz_type') }} --">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <button type="submit" id="btnSearch" class="btn btn-primary">{{trans('backend.view_report')}}</button>
                    <button id="btnExport" class="btn btn-primary" name="btnExport">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export excel
                    </button>
                </div>
            </div>

        </div>
    </div>
</form>
<br>
<div class="table-responsive">
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{route('module.report.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th rowspan="2" data-formatter="index_formatter" data-align="center">STT</th>
                <th colspan="5" data-align="center">Thông tin kỳ thi</th>
                <th colspan="3" data-align="center">{{ trans('backend.time') }}</th>
                <th colspan="3" data-align="center">Số lượng thí sinh</th>
                <th rowspan="2" data-align="center" data-field="score_average">Điểm trung bình</th>
                <th colspan="6" data-align="center">Số lượng thí sinh nằm trong khung điểm</th>
            </tr>
            <tr class="tbl-heading">
                <th data-align="center" data-field="quiz_name">Tên kỳ thi</th>
                <th data-align="center" data-field="role_name">Phòng phụ trách</th>
                <th data-align="center" data-field="type_name">Loại hình thi</th>
                <th data-align="center" data-field="quiz_template">Đề thi</th>
                <th data-align="center" data-field="num_question">SL câu hỏi</th>
                <th data-align="center" data-field="limit_time">Thời lượng</th>
                <th data-align="center" data-field="start_date">Thời gian bắt đầu</th>
                <th data-align="center" data-field="end_date">Thời gian kết thúc</th>
                <th data-align="center" data-field="num_register">SL thí sinh đã đăng ký</th>
                <th data-align="center" data-field="num_doquiz">SL thí sinh thực tế thi</th>
                <th data-align="center" data-field="num_absent">Vắng thi</th>
                <th data-align="center" data-field="score_03">[0đ - 3đ)</th>
                <th data-align="center" data-field="score_35">[3đ - 5đ)</th>
                <th data-align="center" data-field="score_57">[5đ - 7đ)</th>
                <th data-align="center" data-field="score_78">[7đ - 8đ)</th>
                <th data-align="center" data-field="score_89">[8đ - 9đ)</th>
                <th data-align="center" data-field="score_910">[9đ - 10đ]</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

</script>
<script src="{{asset('styles/module/report/js/bc44.js')}}" type="text/javascript"></script>
