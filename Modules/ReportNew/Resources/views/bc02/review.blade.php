<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC02">
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
                    <label>{{ trans('backend.quiz') }}</label>
                </div>
                <div class="col-md-6 type">
                    <select name="quiz_id" class="form-control select2" data-placeholder="-- {{ trans('backend.quiz') }} --">
                        <option value=""></option>
                        @if($quiz)
                            @foreach($quiz as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
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
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{route('module.report_new.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th data-align="center" data-formatter="index_formatter">STT</th>
                <th data-field="quiz_name">Tên kỳ thi</th>
                <th data-field="role_name">Phòng phụ trách</th>
                <th data-field="type_name">Loại hình thi</th>
                <th data-field="quiz_template">Đề thi</th>
                <th data-field="full_name">Họ tên</th>
                <th data-field="user_code">MSNV</th>
                <th data-field="area_name">Khu vực</th>
                <th data-field="unit_name">Đơn vị trực tiếp</th>
                <th data-field="unit_parent_name">Đơn vị quản lý</th>
                <th data-field="title_name">{{ trans('backend.title') }}</th>
                <th data-field="email">Email</th>
                <th data-field="start_date" data-align="center">{{trans("backend.time_start")}}</th>
                <th data-field="execution_time" data-align="center">{{trans("backend.execution_time")}}</th>
                <th data-field="sumgrades" data-align="center" data-width="5%">{{ trans('backend.score') .'/10' }}</th>
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
