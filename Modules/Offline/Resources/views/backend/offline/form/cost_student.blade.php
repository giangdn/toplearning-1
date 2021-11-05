<form action="{{ route('module.offline.save_commit_date', ['id' => $model->id]) }}" method="post" class="form-ajax" data-success="form_cost_student" id="main-cost-student">
    <div class="row">
        <div class="col-md-8">
            <label for="">{{trans('backend.unit')}}: </label><span>VNĐ</span>
        </div>
        <div class="col-md-4 text-right">
            @if($model->lock_course == 0)
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
            @endif
        </div>
    </div>
    <br>
    <table class="tDefault table table-hover">
        <thead>
            <tr>
                <th data-align="center" width="1%">STT</th>
                <th width="7%">{{ trans('backend.employee_code') }}</th>
                <th>{{ trans('backend.fullname') }}</th>
                <th>{{ trans('backend.title') }}</th>
                <th>{{ trans('backend.unit') }}</th>
                <th width="10%">{{ trans('backend.training_cost') }} (vnđ)</th>
                <th width="10%">{{ trans('backend.commitment_amount') }} (vnđ)</th>
                <th width="140px">{{ trans('backend.amount_not_reduced') }}</th>
                <th width="10%">{{ trans('backend.coimmitted_date') }}</th>
                <th width="5%">{{ trans('backend.other_costs') }}</th>
            </tr>
        </thead>
        <body>
        @php
            $sum_student_cost = 0;
            $sum_exemption = 0;
        @endphp
         @foreach ($registers as $key => $register)
             @php
                $total_student_cost = $student_cost($register->id);
                $sum_student_cost += $total_student_cost;

                $exemption_amount = $exemption($register->user_id, $model->id);
                $sum_exemption += $exemption_amount;

                $commit_amount = $register->commit_amount ? number_format(($register->commit_amount - $register->exemption_amount), 2) :  number_format(($total_actual_amount / $registers->count()) * ($model->coefficient ? $model->coefficient : 1) + $total_student_cost, 2);

             @endphp
            <tr>
                <input type="hidden" name="id[]" value="{{ $register->id }}">
                <input type="hidden" name="user_id[]" value="{{ $register->user_id }}">
                <input type="hidden" name="coefficient[]" value="{{ $register->coefficient ? $register->coefficient : ($model->coefficient ? $model->coefficient : 1) }}">
                <input type="hidden" name="course_cost[]" value="{{ $register->course_cost ? $register->course_cost : number_format($total_actual_amount / $registers->count(), 2) }}">
                <th class="text-center" >{{ ($key + 1) }}</th>
                <th>{{ $register->profile_code }}</th>
                <th>{{ $register->profile_lastname . ' ' . $register->profile_firstname }}</th>
                <th>{{ $register->title_name }}</th>
                <th>{{ $register->unit_name }}</th>
                <th class="text-right">{{ number_format($total_actual_amount / $registers->count(), 2) }}</th>
                <th class="text-right">
                    <input type="hidden" id="commit_amount_{{ $register->id }}" value="{{ number_format($register->commit_amount,2) }}">
                {{
                    number_format($register->commit_amount,2)
                }}
                </th>
                <th>
                    <div class="row">
                        <div class="col pl-4 pr-0">
                            <select class="form-control pl-0 pr-0" name="calculator[]" style="width: 38px">
                                <option value="+" {{$register->calculator=='+'?'selected':''}}> + </option>
                                <option value="-" {{$register->calculator=='-'?'selected':''}}> - </option>
                            </select>
                        </div>
                        <div class="col pl-0 pr-0">
                            <input type="text" min="0" style="width: 85px" class="form-control is-number text-right" name="exemption_amount[]" value="{{ number_format($register->exemption_amount, 2) }}">
                        </div>
                    </div>
                </th>
                <th class="text-center">
                    <input type="number" class="form-control text-right" value="{{ $register->commit_date }}" name="commit_date[]" />
                </th>
                <th style="text-align: center;"><a class="btn btn-info import-plan" data-regid="{{ $register->id }}"><i class="fa fa-file-text-o" aria-hidden="true"></i></a></th>
            </tr>
         @endforeach
        @if(count($registers) > 0)
            <tr>
                <th></th>
                <th class="font-weight-bold">Tổng</th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-right font-weight-bold">{{ number_format($total_actual_amount, 2) }}</th>
                <th class="text-right font-weight-bold">{{ number_format(
    $sum_exemption ? $sum_exemption :
    ((($total_actual_amount / $registers->count()) * ($model->coefficient ? $model->coefficient : 1) * $registers->count())+ $total_student_cost), 2
    ) }}</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        @endif
        </body>
    </table>
</form>

<script type="text/javascript">
    $('.import-plan').on('click', function() {
        $regid = $(this).data('regid');
        var get_commit_amount = $('#commit_amount_'+ $regid).val();
        $.ajax({
            type: 'POST',
            url: '{{ route('module.offline.modal_student_cost', ['id' => $model->id]) }}',
            dataType: 'html',
            data: {
                'regid': $(this).data('regid'),
                'get_commit_amount': get_commit_amount
            },
        }).done(function(data) {
            $("#app-modal").html(data);
            $("#app-modal #modal-student-cost").modal();

            return false;
        }).fail(function(data) {

            Swal.fire(
                '',
                '{{ trans('lageneral.data_error ') }}',
                'error'
            );
            return false;
        });
    });

    $('#month').on('change', function () {
        var fields = $("#main-cost-student").serialize();

        $.ajax({
            type: 'POST',
            url: '{{ route('module.offline.save_commit_date', ['id' => $model->id]) }}',
            dataType: 'html',
            data: fields,
        }).done(function(data) {
            form_cost_student();
            return false;
        }).fail(function(data) {

            Swal.fire(
                '',
                '{{ trans('lageneral.data_error ') }}',
                'error'
            );
            return false;
        });
    });

    function form_cost_student(form) {
        window.location = '';
    }

    $(document).ready(function () {
        var $form = $( "#main-cost-student" );
        var $input = $form.find( "input[name='exemption_amount[]']" );
        $input.on( "keyup", function( event ) {
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
