<form action="{{ route('module.online.save_setting_score_percent', ['id' => $model->id]) }}" method="post" class="form-ajax" data-success="form_setting_percent">
    <div class="row">
        <div class="col-md-8"></div>
        @if($permission_save && $model->lock_course == 0)
            <div class="col-md-4 text-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp {{ trans('backend.save') }}</button>
            </div>
        @endif
    </div>
    <br>
    <table class="tDefault table table-hover bootstrap-table" id="table-setting-percent">
        <thead>
            <tr>
                <th data-field="name" data-sortable="true">Hoạt động</th>
                <th data-align="center" data-formatter="score_formatter" data-width="15%">Điểm</th>
                <th data-align="center" data-formatter="percent_formatter" data-width="15%">Trọng số (%)</th>
            </tr>
        </thead>
    </table>
</form>
<script type="text/javascript">
    var table_setting_percent = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.online.get_setting_percent', ['id' => $model->id]) }}',
        table: '#table-setting-percent',
    });

    function score_formatter(value, row, index) {
        return '<input name="score['+row.id+']" class="form-control" value="'+ row.score +'" '+ row.disabled +' />';
    }

    function percent_formatter(value, row, index) {
        return '<input name="percent['+row.id+']" class="form-control" value="'+ row.percent +'" />';
    }

    function form_setting_percent(form) {
        $(table_setting_percent.table).bootstrapTable('refresh');
    }
</script>
