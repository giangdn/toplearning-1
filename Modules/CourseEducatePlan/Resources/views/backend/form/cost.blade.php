<form action="{{ route('module.course_educate_plan.save_cost', ['id' => $model->id]) }}" method="post" class="form-ajax" data-success="form_cost">
    <div class="row">
        <div class="col-md-8">
            <label for="">{{ trans('backend.unit') }}:
            </label><span>VNĐ</span>
        </div>
        <div class="col-md-4 text-right">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;{{ trans('backend.save') }}</button>
        </div>
    </div>
    <br>
    <table class="tDefault table table-hover"
           id="table-cost">
        <thead>
            <tr>
                <th data-align="center" data-width="3%">STT</th>
                <th>{{ trans('backend.cost') }}</th>
                <th>{{ trans('backend.provisional_amount') }}
                </th>
                <th>{{ trans('backend.amount_paid') }}</th>
                <th>{{ trans('backend.note') }}</th>
            </tr>
        </thead>

        <body>

            @foreach ($training_costs as $key => $training_cost)
            <tr>
                <input type="hidden" name="id[]" value="{{ $training_cost->id }}">
                <th data-align="center" data-width="3%">{{ ($key + 1) }}</th>
                <th>{{ $training_cost->name }}</th>
                <th>
                    <input type="text" name="plan_amount[]" class="form-control plan_amount is-number" autocomplete="off"
                           value="{{ count($course_cost) != 0 && isset($course_cost[$key]) ? number_format($course_cost[$key]->plan_amount, 0) : 0 }}">
                </th>
                <th>
                    <input type="text" name="actual_amount[]" class="form-control actual_amount is-number" autocomplete="off"
                           value="{{ count($course_cost) != 0 && isset($course_cost[$key]) ? number_format($course_cost[$key]->actual_amount, 0) : 0 }}">
                </th>
                <th>
                    <input type="text" name="note[]" class="form-control"
                           value="{{ count($course_cost) != 0 && isset($course_cost[$key]) ? $course_cost[$key]->notes : '' }}">
                </th>
            </tr>
            @endforeach
            <tr>
                <th></th>
                <th>{{ trans('backend.total') }}</th>
                <th id="total_plan_amount">
                    {{ number_format($total_plan_amount, 0) . ' VNĐ' }}
                </th>
                <th id="total_actual_amount">
                    {{ number_format($total_actual_amount, 0) . ' VNĐ' }}
                </th>
                <th></th>
            </tr>
        </body>
    </table>

</form>
<script type="text/javascript">
    $('.plan_amount').on('change', function () {
        var plan_amount = $(".plan_amount").map(function () {
            return $(this).val().replace(/,/g, '');
        }).get();
        var total = 0;

        $.each(plan_amount, function (i, item) {
            if (item)
                total += parseInt(item);
        });

        $("#total_plan_amount").html(total.toLocaleString(undefined) +
            " VNĐ");
    });

    $('.actual_amount').on('change', function () {
        var actual_amount = $(".actual_amount").map(function () {
            return $(this).val().replace(/,/g, '');
        }).get();
        var total = 0;

        $.each(actual_amount, function (i, item) {
            if (item)
                total += parseInt(item);
        });

        $("#total_actual_amount").html(total.toLocaleString(undefined) +
            " VNĐ");
    });

    function form_cost(form) {
        window.location = '';
    }

</script>
