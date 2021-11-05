<div class="row mt-3">
    <div class="col-md-12">
        <form method="post" action="" id="form-condition">
            <div class="custom-control custom-checkbox">
                <input name="complaterating" type="checkbox" class="custom-control-input" id="complate-rating" value="1" @if(!$permission_save || $model->lock_course == 1) disabled @endif @if($condition->rating == 1) checked @endif>
                <label class="custom-control-label" for="complate-rating"><h5>{{ trans('backend.complete_evaluation') }}</h5></label>
            </div>

            <div class="custom-control custom-checkbox">
                <input name="orderby" type="checkbox" class="custom-control-input" id="orderby" value="1" @if(!$permission_save || $model->lock_course == 1) disabled @endif @if($condition->orderby == 1) checked @endif>
                <label class="custom-control-label" for="orderby"><h5>{{ trans('backend.complete_in_order') }}</h5></label>
            </div>

            @if($count_activities_quiz && $count_activities_quiz >= 2)
                <div class="custom-control">
                    <div class="form-inline row">
                        <div class="col-md-3">
                            <select name="grade_methor" id="grade_methor" class="form-control select2" data-placeholder="Chọn cách tính điểm" @if(!$permission_save || $model->lock_course == 1) disabled @endif >
                                <option value=""></option>
                                <option value="1" @if($condition->grade_methor == 1) selected @endif>Lần cao nhất</option>
                                <option value="2" @if($condition->grade_methor == 2) selected @endif> {{trans('backend.medium_score')}}</option>
                                <option value="3" @if($condition->grade_methor == 3) selected @endif>Lần thi cuối</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-3"></div>
            @if(isset($activities) && $activities)
                @php
                    $include = explode(',', $condition->activity);
                @endphp
                @foreach($activities as $activity)

                    <div class="custom-control custom-checkbox">
                        <input name="activity[]" type="checkbox" class="custom-control-input" id="activity-{{ $activity->id }}" value="{{ $activity->id }}" @if(!$permission_save || $model->lock_course == 1) disabled @endif @if(in_array($activity->id, $include)) checked @endif>
                        <label class="custom-control-label" for="activity-{{ $activity->id }}">
                            <h5>
                                <img src="{{ $activity->icon }}" class="iconlarge activityicon" role="presentation" aria-hidden="true">
                                <span class="instancename">{{ trans('backend.complete_act') }} <b>{{ $activity->name . ($activity->status == 0 ? ' (Đã ẩn)' : '') }}</b></span>
                            </h5>
                        </label>
                    </div>

                @endforeach
            @endif
        </form>
    </div>

    <script type="text/javascript">
        $('#form-condition').on('change', '#grade_methor', function () {
            var grade_methor = $('#grade_methor option:selected').val();
            $.ajax({
                type: 'POST',
                url: '{{ route('module.online.save_condition', ['id' => $model->id]) }}',
                dataType: 'json',
                data: {
                    grade_methor : grade_methor
                }
            }).done(function(data) {

                if (data.status !== "success") {
                    show_message('Không thể lưu cài đặt', 'error');
                    return false;
                }

                return false;
            }).fail(function(data) {
                return false;
            });
        });

        $("#form-condition input").on('change', function () {
            $.ajax({
                type: 'POST',
                url: '{{ route('module.online.save_condition', ['id' => $model->id]) }}',
                dataType: 'json',
                data: $("#form-condition").serialize()
            }).done(function(data) {

                if (data.status !== "success") {
                    show_message('Không thể lưu cài đặt', 'error');
                    return false;
                }

                return false;
            }).fail(function(data) {
                return false;
            });
        });
    </script>
</div>
