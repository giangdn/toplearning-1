<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" >
                <h6 class="modal-title text-center" id="exampleModalLabel">
                    {{trans('backend.register_course_by_subject')}} <br> {{ $subject->name }}
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table  class="table table-bordered bootstrap-table-modal table-striped" id="table-modal-register-course-by-subject">
                        <thead>
                            <tr class="tbl-heading">
                                <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="5" style="vertical-align: middle;">#</th>
                                <th data-align="center" data-formatter="register_formatter">{{ trans('backend.register') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" value="{{$subject_id}}" id="subject_id"/>
                <button type="button" class="btn btn-info" data-dismiss="modal">{{ trans('backend.close') }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    function index_formatter(value, row, index) {
        return (index + 1);
    }
    function register_formatter(value, row, index) {
        var btn_register = '<button class="btn btn-primary btn-register" data-type="'+row.course_type+'" data-courseid="'+row.id+'">Đăng ký</button>';

        return row.name + '<br>' + row.code + '<br>' + 'Hình thức: ' + row.type + '<br>' + row.start_date + (row.end_date ? ' - ' + row.end_date : '') + '<br>' + btn_register;
    }
    $(document).on('click','.btn-register',function (e) {
        e.preventDefault();
        console.log($(this));
        let data = {};
        data.course_id = $(this).data('courseid');
        data.course_type = $(this).data('type');
        data.subject_id = $('#subject_id').val();
        let item = $(this);
        let oldtext = item.html();
        item.html('<i class="fa fa-spinner fa-spin"></i> Đang chờ');
        $.ajax({
            type: 'PUT',
            url: '{{ route('module.frontend.user.roadmap.register') }}',
            dataType: 'json',
            data
        }).done(function(data) {
            item.html(oldtext);
            show_message(data.message,data.status);
        }).fail(function(data) {
            item.html(oldtext);
            show_message('{{ trans('lageneral.data_error ') }}','error');
            return false;
        });
    });

    var table_modal_register_course_by_subject = new LoadBootstrapTable({
        table: '#table-modal-register-course-by-subject',
        locale: '{{ \App::getLocale() }}',
        url: '{{ route("module.frontend.user.roadmap.getCourseBySubject",[$subject_id]) }}',
    });
</script>
