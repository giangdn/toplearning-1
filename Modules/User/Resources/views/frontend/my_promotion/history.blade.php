<div class="table-responsive mt-30">
    <table id="history" class="table bootstrap-table">
        <thead class="thead-s">
        <tr class="tbl-heading">
            <th width="5%" data-formatter="index_formatter">#</th>
            <th data-field="name">@lang('app.course')</th>
            <th data-field="point">@lang('app.score')</th>
            <th  data-field="type" data-formatter="course_type" data-align="center">@lang('app.type')</th>
            <th  data-field="createdat" data-align="center" >@lang('app.time')</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
    function course_type(value, row, index) {
        return value == 1 ? trans('backend.online') : trans('backend.offline');
    }
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.frontend.user.my_promotion.history') }}',
        locale: '{{ App::getLocale() }}',
    });
</script>
