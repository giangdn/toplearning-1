<div class="row">
    <div class="col-md-9">
        <form method="post" action="{{ route('module.promotion.save_setting') }}" class="form-ajax" id="form-promotion" data-success="submit_success">
            <input type="hidden" name="type" value="3">
            <input type="hidden" name="course_id" value="{{ $model->id }}">

            <div class="promotion-enable">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <h6>{{ trans('backend.scoring_method') }} :</h6>
                    </div>
                    <div class="col-md-9">
                        <div class="form-check form-check-inline">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input point-type" id="promotion_0" name="method" value="0">
                                <label class="custom-control-label" for="promotion_0">Hoàn thành kỳ thi</label>
                            </div>
                        </div>
                        <div class="form-check form-check-inline">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input point-type" id="promotion_1" name="method" value="1">
                                <label class="custom-control-label" for="promotion_1">{{ trans('backend.landmarks') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="promotion_0 box-hidden">
                    @php
                        $complete = \Modules\Promotion\Entities\PromotionCourseSetting::getPromotionCourseSetting($model->id, 3, 'complete');
                    @endphp
                    <div class="form-group row">
                        <div class="col-sm-3 control-label"></div>
                        <div class="col-md-9">
                            <input name="start_date" type="text" class="form-control w-25 d-inline-block datepicker" placeholder="Bắt đầu" autocomplete="off" value="{{ $complete && $complete->start_date ? get_date($complete->start_date) : '' }}">
                            <input name="end_date" type="text" class="form-control w-25 d-inline-block datepicker" placeholder="Kết thúc" autocomplete="off" value="{{ $complete && $complete->end_date ? get_date($complete->end_date) : '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label"></div>
                        <div class="col-md-4">
                            <input name="point_complete" type="text" class="form-control" placeholder="{{ trans('backend.bonus_points') }}" autocomplete="off" value="{{ $complete ? $complete->point : '' }}">
                        </div>
                    </div>
                    <div class="row row-acts-btn save">
                        <div class="col-sm-12">
                            <div class="btn-group act-btns">
                                <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                                <a href="{{ route('module.quiz.manager') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="promotion_1 box-hidden">
                    <div class="form-group row">
                        <div class="col-sm-3 control-label"></div>
                        <div class="col-md-9">
                            <input name="min_score" type="text" class="form-control w-25 d-inline-block" placeholder="Từ điểm" autocomplete="off" value="">
                            <input name="max_score" type="text" class="form-control w-25 d-inline-block" placeholder="Đến điểm" autocomplete="off" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label"></div>
                        <div class="col-md-4">
                            <input name="point_landmarks" type="text" class="form-control" placeholder="{{ trans('backend.bonus_points') }}" autocomplete="off" value="">
                        </div>
                    </div>
                    <div class="row row-acts-btn save">
                        <div class="col-sm-12">
                            <div class="btn-group act-btns">
                                @if($model->quiz_type != 1)
                                <button type="button" id="save-landmarks" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                                @endif
                                <a href="{{ route('module.quiz.manager') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row promotion-table box-hidden">
    <div class="col-md-12">
        <div class="text-right">
            <button id="delete-setting" class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
        </div>
        <p></p>
        <table class="tDefault table table-hover bootstrap-table" id="table-setting">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-align="center" data-width="3%" data-formatter="stt_formatter">STT</th>
                <th data-field="score" data-align="center">{{ trans('backend.landmarks') }}</th>
                <th data-field="point" data-align="center">{{ trans('backend.bonus_points') }}</th>
            </tr>
            </thead>
        </table>
    </div>
</div>


<script type="text/javascript">
    function stt_formatter(value, row, index) {
        return (index + 1);
    }

    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.promotion.get_setting', ['courseId' => $model->id, 'course_type' => 3, 'code' => 'landmarks']) }}',
        remove_url: '{{ route('module.promotion.delete_setting') }}',
        detete_button: '#delete-setting',
        table: '#table-setting'
    });
</script>

<script type="text/javascript">
    if ($("#promotion_0").is(":checked")) {
        $(".promotion_0").removeClass('box-hidden');
        $(".promotion-table").addClass('box-hidden');
        $(".promotion_1").addClass('box-hidden');
        $(".promotion_2").addClass('box-hidden');
    }

    if ($("#promotion_1").is(":checked")) {
        $(".promotion_0").addClass('box-hidden');
        $(".promotion-table").removeClass('box-hidden');
        $(".promotion_1").removeClass('box-hidden');
        $(".promotion_2").addClass('box-hidden');
    }

    if ($("#promotion_2").is(":checked")) {
        $(".promotion_0").addClass('box-hidden');
        $(".promotion-table").addClass('box-hidden');
        $(".promotion_1").addClass('box-hidden');
        $(".promotion_2").removeClass('box-hidden');
    }

    $(".point-type").on('change', function () {
        let type = parseInt($(this).val());
        if (type == 0) {
            $(".promotion_0").removeClass('box-hidden');
            $(".promotion-table").addClass('box-hidden');
            $(".promotion_1").addClass('box-hidden');
            $(".promotion_2").addClass('box-hidden');
        }
        if(type == 1){
            $(".promotion_0").addClass('box-hidden');
            $(".promotion-table").removeClass('box-hidden');
            $(".promotion_1").removeClass('box-hidden');
            $(".promotion_2").addClass('box-hidden');
        }
        if(type == 2){
            $(".promotion_0").addClass('box-hidden');
            $(".promotion-table").addClass('box-hidden');
            $(".promotion_1").addClass('box-hidden');
            $(".promotion_2").removeClass('box-hidden');
        }
    });

    $('#save-landmarks').on('click', function () {
        $.ajax({
            url: '{{ route('module.promotion.save_setting') }}',
            type: 'POST',
            data: $('#form-promotion').serialize(),
        }).done(function(result) {
            if (result.status=='success'){
                $('input[name=min_score]').val('');
                $('input[name=max_score]').val('');
                $('input[name=point_landmarks]').val('');
                $(table.table).bootstrapTable('refresh');
                return false;
            }
        }).fail(function(data) {
            return false;
        });
    });

    function submit_success(form) {
        $(table.table).bootstrapTable('refresh');
    }
</script>
