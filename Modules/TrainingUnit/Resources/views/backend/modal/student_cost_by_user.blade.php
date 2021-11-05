<div class="modal fade" id="modal-student-cost-by-user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('module.training_unit.approve_student_cost.approve', ['id' => $course_id]) }}" method="post" class="form-ajax" data-success="form_student_cost">
            <input type="hidden" name="regid" value="{{ $regid }}">
            <input type="hidden" name="status" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">CHI PHÍ HỌC VIÊN CỦA {{ $full_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="tDefault table table-hover" style="font-style: normal">
                        <thead>
                        <tr>
                            <th data-align="center" data-width="3%">STT</th>
                            <th>Loại chi phí</th>
                            <th>Số tiền</th>
                            <th>{{ trans('backend.note') }}</th>
                        </tr>
                        </thead>
                        <body>
                        @foreach ($student_costs as $key => $student_cost)
                            <tr>
                                <th data-align="center" data-width="3%">{{ ($key + 1) }}</th>
                                <th>{{ $student_cost->name }}</th>
                                <th>{{ count($register_cost) != 0 && isset($register_cost[$key]) ? number_format($register_cost[$key]->cost, 0) : '' }}</th>
                                <th>{{ count($register_cost) != 0 && isset($register_cost[$key]) ? $register_cost[$key]->note : ''}}</th>
                            </tr>
                        @endforeach
                        <tr>
                            <th></th>
                            <th>Tổng</th>
                            <th>{{ number_format($total_student_cost, 0) . ' VNĐ' }}</th>
                            <th></th>
                        </tr>
                        </body>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                    @if ($manager_approved != 1)
                        <button type="button" class="btn btn-success approve" data-status="1"><i class="fa fa-check-circle"></i> {{trans('backend.approve')}}</button>
                    @endif
                    @if ($manager_approved != 0)
                        <button type="button" class="btn btn-danger approve" data-status="0"><i class="fa fa-times-circle"></i> {{trans('backend.deny')}}</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $('.approve').on('click', function () {
        var status = $(this).data('status');

        $('input[name=status]').val(status).trigger('change');
        $(this).closest('form').submit();
    });

    function form_student_cost(form) {
        $("#app-modal #modal-student-cost-by-user").hide();
        window.location = '';
    }
</script>
