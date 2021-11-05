<form id="form_cost" action="{{ route('module.online.save_cost', ['id' => $model->id]) }}"
      method="post"
      class="form-ajax"
      data-success="form_cost">
    <div class="row">
        <div class="col-md-8">
            <label for="">{{trans('backend.unit')}}: </label><span>VNĐ</span>
        </div>
        @if($permission_save && $model->lock_course == 0)
        <div class="col-md-4 text-right">
            <button type="submit"
                    class="btn btn-primary"><i class="fa fa-save"></i>
                &nbsp;{{ trans('backend.save') }}</button>
        </div>
        @endif
    </div>
    <br>
    <table class="tDefault table table-hover">
        <thead>
            <tr>
                <th data-align="center" data-width="3%">STT</th>
                <th>{{trans('backend.cost')}}</th>
                <th>Loại chi phí</th>
                <th>{{trans('backend.provisional_amount')}}</th>
                <th>{{trans('backend.amount_paid')}}</th>
                <th>{{trans('backend.note')}}</th>
            </tr>
        </thead>
        <body>
            @foreach ($training_costs as $key => $training_cost)
            <tr>
                <input type="hidden" name="id[]" value="{{ $training_cost->id }}">
                <th data-align="center" data-width="3%">{{ ($key + 1) }}</th>
                <th>{{ $training_cost->name }}</th>
                @if ($training_cost->type == 1)
                    <th> Chi phí tổ chức </th>
                @endif
                @if ($training_cost->type == 2)
                    <th>Chi phí phòng đào tạo</th>
                @endif
                @if ($training_cost->type == 3)
                    <th>Chi phí đào tạo bên ngoài</th>
                @endif
                @if ($training_cost->type == 4)
                    <th>Chi phí giảng viên</th>
                @endif
                <th>
                    <input type="text"
                           name="plan_amount[]"
                           value="{{ (count($course_cost) != 0 && isset($course_cost[$key]))? number_format($course_cost[$key]->plan_amount, 0, '.', ',') : 0 }}"
                           class="form-control plan_amount is-number"
                           autocomplete="off">
                </th>
                <th>
                    <input type="text"
                           name="actual_amount[]"
                           value="{{ count($course_cost) != 0 && isset($course_cost[$key])  ? number_format($course_cost[$key]->actual_amount, 0) : 0 }}"
                           class="form-control actual_amount is-number"
                           autocomplete="off">
                </th>
                <th>
                    <input type="text"
                           name="note[]"
                           value="{{ count($course_cost) != 0 && isset($course_cost[$key]) ? $course_cost[$key]->notes : '' }}"
                           class="form-control">
                </th>
            </tr>
            @endforeach
            <tr>
                <th></th>
                <th>{{trans('backend.total')}}</th>
                <th></th>
                <th id="total_plan_amount">
                    {{ number_format($total_plan_amount, 0) . ' VNĐ'}}</th>
                <th id="total_actual_amount">
                    {{ number_format($total_actual_amount, 0) . ' VNĐ'}}</th>
                <th></th>
            </tr>
        </body>
    </table>

</form>
<script type="text/javascript">
    $('.plan_amount').on('change', function(){
        var plan_amount = $(".plan_amount").map(function(){
            return $(this).val().replace(/,/g , '');
        }).get();
        var total = 0;

        $.each(plan_amount, function(i, item) {
            if (item)
                total += parseFloat(item);

        });

        $("#total_plan_amount").html(total.toLocaleString() + " VNĐ");
    });

    $('.actual_amount').on('change', function(){
        var actual_amount = $(".actual_amount").map(function(){return $(this).val().replace(/,/g , '');}).get();
        var total = 0;

        $.each(actual_amount, function(i, item) {
            if (item)
                total += parseFloat(item);
        });

        $("#total_actual_amount").html(total.toLocaleString()  + " VNĐ");
    });

    function form_cost(form) {
        window.location = '';
    }

    $(document).ready(function () {
        var $form = $( "#form_cost" );
        var $plan_amount = $form.find( "input[name='plan_amount[]']" );
        var $actual_amount = $form.find( "input[name='actual_amount[]']" );
        $plan_amount.on( "keyup", function( event ) {
            var $this = $( this );
            // Get the value.
            var input = $this.val();
            var input = input.replace(/[\D\s\._\-]+/g, "");
            input = input ? parseInt( input, 10 ) : 0;

            $this.val( function() {
                return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );
            } );
        });

        $actual_amount.on( "keyup", function( event ) {
            var $this = $( this );
            // Get the value.
            var input = $this.val();
            var input = input.replace(/[\D\s\._\-]+/g, "");
            input = input ? parseInt( input, 10 ) : 0;

            $this.val( function() {
                return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );
            } );
        });
    });
</script>
