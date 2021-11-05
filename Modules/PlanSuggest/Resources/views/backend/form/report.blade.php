<div>
    <div class="form-group row">
        <div class="col-md-3" style="vertical-align:middle"><label>{{trans('backend.attachments')}}</label></div>
        <div class="col-md-8">
            <a href="javascript:void(0)" id="select-attach-report">{{trans("backend.choose_file")}}</a>
            <div id="attach-report-review">
                @if($model->attach_report)
                    {{ basename($model->attach_report) }}
                @endif
            </div>
            <input name="attach_report" id="attach-report-select" type="text" class="d-none" value="{{ $model->attach_report }}">
        </div>
    </div>
</div>
