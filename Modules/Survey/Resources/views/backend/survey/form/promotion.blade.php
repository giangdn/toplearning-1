<div class="row">
    <div class="col-md-9">
        <form method="post" action="{{ route('module.promotion.save_setting') }}" class="form-ajax" id="form-promotion" data-success="submit_success">
                <input type="hidden" name="type" value="4">
                <input type="hidden" name="course_id" value="{{ $model->id }}">

                <div class="promotion-enable">
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <h6>{{ trans('backend.scoring_method') }} :</h6>
                        </div>
                        <div class="col-md-9">
                            <div class="form-check form-check-inline">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input point-type" id="promotion_0" name="method" value="0" checked>
                                    <label class="custom-control-label" for="promotion_0">Hoàn thành khảo sát</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="promotion_0">
                        @php
                            $complete = \Modules\Promotion\Entities\PromotionCourseSetting::getPromotionCourseSetting($model->id, 4, 'complete');
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
                    </div>
                </div>
                <div class="row row-acts-btn save">
                    <div class="col-sm-12">
                        <div class="btn-group act-btns">
                            <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                            <a href="{{ route('module.survey.index') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>
