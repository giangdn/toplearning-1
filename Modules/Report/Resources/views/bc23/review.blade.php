<form name="frm" action="{{ route('module.report.review', ['id' => 'BC23']) }}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC23">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.training_program')}}</label>
                </div>
                <div class="col-md-6 type">
                    <select class="form-control select2" name="training_program" id="training_program" data-placeholder="{{trans('backend.choose_training_program')}}">
                        <option value=""></option>
                        @foreach ($training_programs as $training_program)
                            <option {{ isset($training_program_id) ? $training_program->id == $training_program_id ? 'selected' : '' : '' }} value="{{ $training_program->id }}"> {{ $training_program->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.date_from')}}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="from_date" id="from_date" class="form-control datepicker-date" value="{{ isset($from_date) ? $from_date : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.date_to')}}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="to_date" id="to_date" class="form-control datepicker-date" value="{{ isset($to_date) ? $to_date : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <button type="submit" id="btnSearch" class="btn btn-primary">{{trans('backend.view_report')}}</button>
                </div>
            </div>
        </div>
    </div>
</form>
<form name="frm2" id="form-export" action="{{ route('module.report.export') }}" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC23">
    <input type="hidden" name="training_program_id" value="{{ isset($training_program_id) ? $training_program_id : '' }}">
    <input type="hidden" name="from_date" value="{{ isset($from_date) ? $from_date : '' }}">
    <input type="hidden" name="to_date" value="{{ isset($to_date) ? $to_date : '' }}">

    <div class="row">
        <div class="col-md-9"></div>
        <div class="col-md-3 text-right">
            <button id="btnExport" class="btn btn-primary" name="btnExport">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export excel
            </button>
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
<div class="table-responsive">
    <table class="tDefault table table-hover table-bordered">
        <thead>
            <tr class="tbl-heading">
                <th data-align="center">#</th>
                <th>MSNV</th>
                <th>{{trans('backend.student')}}</th>
                <th>{{ trans('backend.unit') }}</th>
                <th>{{ trans('backend.title') }}</th>
                @if ($course)
                    @foreach ($course as $item)
                        <th>{{ $item->name }}</th>
                    @endforeach
                @endif
            </tr>
        </thead>
        <tbody>
            @if ($rows)
                @foreach ($rows as $key => $row)
                    @php
                        $profile = $user($row->user_id);
                    @endphp
                    <tr class="tbl-heading">
                        <th data-align="center">{{ $key + 1 }}</th>
                        <th>{{ $profile->code }}</th>
                        <th>{{ $profile->lastname . ' ' . $profile->firstname }}</th>
                        <th>{{ $profile->unit_name }}</th>
                        <th>{{ $profile->title_name }}</th>
                        @if ($course)
                            @foreach ($course as $item)
                                @php
                                    $course_score = $score($row->user_id, $item->id, $item->course_type);
                                @endphp
                                <th>
                                    {{ $course_score ? $course_score->score : '' }}
                                </th>
                            @endforeach
                        @endif
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
<script src="{{asset('styles/module/report/js/bc23.js')}}" type="text/javascript"></script>

