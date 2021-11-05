<div class="table-responsive mt-30">
    <table id="tablesubjectregister" class="table table-bordered bootstrap-table table-striped" >
        <thead>
            <tr>
                <th data-sortable="true" data-align="center" data-formatter="index_formatter" data-width="5%">STT</th>
                <th data-field="code" data-width="100">{{ trans('backend.subject_code') }}</th>
                <th data-field="subject" data-width="500">{{ trans('backend.subject') }}</th>
                <th data-field="created_date" data-width="180">{{ trans('backend.created_at') }}</th>
                <th data-field="status_name" data-width="150">{{ trans('backend.status') }}</th>
                <th data-field="status" data-formatter="cancel_formatter" data-width="150">Hủy</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
    function cancel_formatter(value, row, index) {
        if(row.status==1)
            return '<button class="btn btn-primary cancelRegister" data-id='+row.id+'>Hủy đăng ký</button>';
        else
            return '';
    }
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.frontend.user.subjectregister.getData') }}',
    });
    $(document).on('click','.cancelRegister',function (e) {
        e.preventDefault();
        Swal.fire({
            title: '',
            text: 'Bạn có muốn hủy đăng ký không ?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý!',
            cancelButtonText: 'Hủy!',
        }).then((result) => {
            if (result.value) {
                let data = {};
                data.id = $(this).data('id');
                let item = $(this);
                let oldtext = item.html();
                item.attr('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> Đang chờ');
                $.ajax({
                    type: 'PUT',
                    url: '{{ route('module.frontend.user.subjectregister.update') }}',
                    dataType: 'json',
                    data
                }).done(function(data) {
                    // item.remove();
                    table.refresh();
                    show_message(data.message,data.status);
                }).fail(function(data) {
                    item.attr('disabled',false).html(oldtext);
                    show_message('{{ trans('lageneral.data_error ') }}','error');
                    return false;
                });
            }
        });
    });


</script>
