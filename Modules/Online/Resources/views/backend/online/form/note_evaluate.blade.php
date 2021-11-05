<div role="main">
    <div class="row">
        <div class="col-md-8">
            <form class="form-inline form-search mb-3" id="form-search-note">
                <input type="text" name="search_note" value="" class="form-control" placeholder="Nhập tên">
                <button class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
            </form>
        </div>
        <div class="col-md-4 text-right act-btns">
            <div class="pull-right">
                <div class="btn-group">
                    {{-- <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button> --}}
                </div>
            </div>
        </div>
    </div>
    <br>

    <table class="tDefault table table-hover" id="table-note">
        <thead>
            <tr>
                <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="3%">STT</th>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="fullname">Tên học viên</th>
                <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                <th data-field="title_name">Chức danh</th>
                <th data-field="view_note" data-formatter="note" data-align="center">Ghi chép</th>
                <th data-field="view_evaluate" data-formatter="evaluate" data-align="center">Đánh giá</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1);
        }

        function note(value, row, index) {
            if (row.view_note) {
                return '<a target="_blank" href="'+ row.view_note +'"><i class="fa fa-eye"></a>';
            } else {
                return '-';
            }
        }

        function evaluate(value, row, index) {
            if (row.view_evaluate) {
                return '<a target="_blank" href="'+ row.view_evaluate +'"><i class="fa fa-eye"></a>';
            } else {
                return '-';
            }
        }

        var table_note = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.get_user_note_evaluate',['course_id' => $model->id]) }}',
            table: '#table-note',
            form_search: '#form-search-note',
        });
</script>
