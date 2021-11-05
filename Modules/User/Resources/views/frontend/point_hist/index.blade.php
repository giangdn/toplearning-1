<div class="sa4d25">
    <div class="container-fluid">
        <div class="row">
            <div class ="col-md-12">
                <h4>@lang('app.history_point')</h4>
                <table class="tDefault table table-bordered bootstrap-table">
                    <thead>
                    <tr>
                        <th data-field="index" data-class="text-center" data-formatter="index_formatter" data-width="30px">STT</th>
                        <th data-field="content">@lang('app.content')</th>
                        <th data-field="createdate" data-width="200px" data-align="center">@lang('app.day')</th>
                        <th data-field="point" data-width="100px" data-class="text-center" data-formatter="point_formatter">@lang('app.score')</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
    function point_formatter(value, row, index) {
        if (row.promotion) {
            return '- '+value;
        } else {
            return '+ '+value;
        }
        
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('frontend.user.point_hist.getData') }}',
    });

</script>
