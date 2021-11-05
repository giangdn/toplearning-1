<div class="modal fade" id="modal-student-cost-by-user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('module.offline.student_cost.save') }}" method="post" class="form-ajax" data-success="form_student_cost">
            <input type="hidden" name="regid" value="{{ $regid }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">CHI PHÍ HỌC VIÊN</h5>
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
                                <input type="hidden" name="cost_id[]" value="{{ $student_cost->id }}">
                                <th data-align="center" data-width="3%">{{ ($key + 1) }}</th>
                                <th>{{ $student_cost->name }}</th>
                                <th><input type="text" name="cost[]" value="{{ count($register_cost) != 0 && isset($register_cost[$key]) ? number_format($register_cost[$key]->cost, 0) : '' }}" class="form-control is-number" autocomplete="off" {{ (isset($register_cost[$key]) && $register_cost[$key]->manager_approved == 1) ? 'readonly' : '' }}></th>
                                <th><input type="text" name="note[]" value="{{ count($register_cost) != 0 && isset($register_cost[$key]) ? $register_cost[$key]->note : ''}}" class="form-control" {{ (isset($register_cost[$key]) && $register_cost[$key]->manager_approved == 1) ? 'readonly' : '' }}></th>
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
                    <button type="submit" class="btn btn-primary @if(\App\Permission::isUnitManager()) hidden @endif">{{trans('backend.save')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    function form_student_cost(form) {
        $("#app-modal #modal-student-cost-by-user").hide();
        window.location = '';
    }
</script>
