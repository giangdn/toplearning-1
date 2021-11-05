<div class="table-responsive mt-30">
    <table id="tableroadmap" class="table table-bordered bootstrap-table table-striped" style="table-layout: fixed">
        <thead>
        <tr class="tbl-heading">
            <th data-field="index" data-formatter="index_formatter" data-width="40" rowspan="2" style="vertical-align: middle;">#</th>
            <th data-field="training_program_code" data-width="120" rowspan="2" style="vertical-align: middle;">{{ trans('backend.training_program_code') }}</th>
            <th data-field="training_program_name" data-width="240" rowspan="2" style="vertical-align: middle;">{{ trans('backend.training_program_name') }}</th>
            <th data-field="subject_code" rowspan="2" data-width="120" style="vertical-align: middle;">{{ trans('backend.course_code') }}</th>
            <th data-field="subject_name" rowspan="2" data-width="340" >{{ trans('backend.course_name') }}</th>
            <th data-field="training_form" rowspan="2" data-width="230" >Hình thức đào tạo dự kiến</th>
            <th rowspan="2" data-field="process_type" data-width="130" data-align="center" style="vertical-align: middle;">{{ trans('app.training_form') }}</th>
            <th style="vertical-align: middle;text-align: center;" data-width="240" colspan="2" data-align="center">{{ trans('app.time_held') }}</th>
            <th colspan="2" data-width="200" style="text-align: center; vertical-align: middle;">{{ trans('backend.date_effect') }}</th>
            <th colspan="2" data-width="260" data-align="center"  >{{ trans('backend.result') }}</th>
            {{--<th rowspan="2" data-width="120" data-field="cert">{{ trans('backend.certificates') }}</th>--}}
            {{--<th rowspan="2" data-width="140" data-width-unit="px" data-field="status">{{ trans('app.status') }}</th>--}}
            <th rowspan="2" data-width="200" data-field="note">{{ trans('backend.note') }}</th>
        </tr>
        <tr class="tbl-heading">
            <th data-field="start_date" data-align="center">{{ trans('app.from_date') }}</th>
            <th data-field="end_date" data-align="center">{{ trans('app.to_date') }}</th>

            <th data-field="start_effect" data-align="center">{{ trans('app.from_date') }}</th>
            <th data-field="end_effect" data-align="center">{{ trans('app.to_date') }}</th>

            <th data-field="score" data-align="center" data-width="120">{{ trans('app.score') }}</th>
            <th data-field="result" data-align="center" data-width="120">{{ trans('backend.passed') }}</th>

        </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
    function course_type(value, row, index) {
        if (value==1)
            return '{{ trans('backend.online') }}';
        else if(value==2)
            return '{{ trans('backend.offline') }}';
        return '-';
    }
    function mergeRows(index, rowspan, field) {
        $('#tableroadmap').bootstrapTable('mergeCells', {
            index: index,
            field: field,
            rowspan: rowspan
        });
    }
    $(function () {
        $(document).on('click','.btnRegisterSubject',function (e) {
            e.preventDefault();
            Swal.fire({
                title: '',
                text: 'Chuyên đề này chưa có khóa học, bạn có muốn đăng ký tham gia chuyên đề này không ?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý!',
                cancelButtonText: 'Hủy!',
            }).then((result) => {
                if (result.value) {
                    let data = {};
                    data.subject_id = $(this).data('subject_id');
                    let item = $(this);
                    let oldtext = item.html();
                    item.attr('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> Đang chờ');
                    $.ajax({
                        type: 'PUT',
                        url: '{{ route('module.frontend.user.roadmap.register') }}',
                        dataType: 'json',
                        data
                    }).done(function(data) {
                        item.attr('disabled',false).html(oldtext);
                        show_message(data.message,data.status);
                    }).fail(function(data) {
                        item.attr('disabled',false).html(oldtext);
                        show_message('{{ trans('lageneral.data_error ') }}','error');
                        return false;
                    });
                }
            });

        });
        $('.bootstrap-table').on('load-success.bs.table', function (e, name, args) {
            var table = document.getElementById('tableroadmap');
            var rowLength = table.rows.length - 1;
            var rowspan = 1;
            var start = 2;
            var cells = table.rows[start].cells.length - 1;
            if(cells > 0){
                var row = table.rows[start].cells[2].innerHTML;
                var saveIndex = 0;
                var result ='';
                var $y=1;
                for (var i = start+1; i <= rowLength; i += 1) {
                    if (row == table.rows[i].cells[2].innerHTML) {
                        rowspan++;
                        result = table.rows[i].cells[13].innerHTML;
                        if(result=='Hoàn thành')
                            table.rows[i-1].cells[13].innerHTML='Hoàn thành';
                        mergeRows(saveIndex, rowspan,'training_program_code');
                        mergeRows(saveIndex, rowspan,'training_program_name');
                        mergeRows(saveIndex, rowspan,'status');
                    }else{
                        rowspan=1;
                        row = table.rows[i].cells[2].innerHTML;
                        saveIndex = $y;
                    }
                    $y++;
                }
            }

        });
    });
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.frontend.user.roadmap.getDataRoadmap') }}',
    });

</script>
