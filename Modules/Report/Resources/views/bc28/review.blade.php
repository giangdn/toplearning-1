<form name="frm2" id="form-export" action="{{ route('module.report.export') }}" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC28">

    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.survey_name')}}</label>
                </div>
                <div class="col-md-6 type">
                    <select class="form-control select2" name="survey_id" id="survey_id" data-placeholder="{{trans('backend.choose_survey')}}">
                        <option value=""></option>
                        @foreach ($survey as $item)
                            <option value="{{ $item->id }}"> {{ $item->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <button id="btnExport" class="btn btn-primary" name="btnExport">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export excel
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="{{asset('styles/module/report/js/bc28.js')}}" type="text/javascript"></script>
