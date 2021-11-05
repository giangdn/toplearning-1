<div role="main">
    <div class="row">
        <div class="col-md-8">
            <form class="form-inline form-search mb-3" id="form-search">
                <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('backend.enter_category') }}">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
            </form>
        </div>
        <div class="col-md-4 text-right act-btns">
            <div class="pull-right">
                <div class="btn-group">
                    @if($model->lock_course == 0)
                    <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <br>

    <table class="tDefault table table-hover bootstrap-table" id="table-library-file">
        <thead>
            <tr>
                <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="3%">STT</th>
                <th data-field="state" data-checkbox="true"></th>
                <th data-field="name" data-formatter="name_formatter">Danh sách file</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.uploadFile +'">'+ row.uploadName +'</a>';
        }

        function index_formatter(value, row, index) {
            return (index+1);
        }

        var table_library_file = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.get_data_library_file',['course_id' => $model->id]) }}',
            remove_url: '{{ route('module.offline.library_file_remove') }}',
            table: '#table-library-file',
        });
</script>
