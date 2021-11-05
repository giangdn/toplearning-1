<div role="main">
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('module.quiz_educate_plan.save_teacher', ['id' => $model->id]) }}" method="post" class="form-ajax">
                <div class="form-group row">
                    <label class="col-sm-3 control-label">{{ trans("backend.instructors_grade") }} <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select name="teachers[]" class="form-control load-teacher" data-placeholder="-- {{ trans('backend.choose_teacher') }} --" multiple>
                            <option value=""></option>
                            @if(isset($teachers))
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" selected>{{ $teacher->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

