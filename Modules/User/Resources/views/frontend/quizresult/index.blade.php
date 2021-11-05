<div class="table-responsive mt-30">
    <table id="dg" class="table bootstrap-table">
        <thead class="thead-s">
        <tr class="tbl-heading">
            <th data-width="40px" data-formatter="index_formatter">#</th>
            <th data-field="code" data-width="80px">@lang('app.quiz_code')</th>
            <th data-field="name">@lang('app.quiz')</th>
            <th data-field="start_date" data-width="180px" data-align="center">@lang('app.start_date')</th>
            <th data-field="end_date" data-width="180px" data-align="center">@lang('app.end_date')</th>
            <th data-field="limit_time" data-width="150px" data-align="center">@lang('app.time_exam') ( @lang('app.min') )</th>
            <th data-align="center" data-width="80px" data-field="grade">@lang('app.score')</th>
            <th data-align="center" data-width="160px" data-field="result" data-formatter="result_formatter">@lang('app.result')</th>
        </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
    function result_formatter(value, row, index) {
        return value == 1 ? '{{trans("backend.finish")}}' : 'Chưa hoàn thành';
    }
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.frontend.user.quizresult.getData') }}',
        locale: '{{ App::getLocale() }}',
    });

</script>
