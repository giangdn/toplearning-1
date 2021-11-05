<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{trans('backend.info')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-md-5">{{trans('backend.employee_code')}}</label>
                    <div class="col-md-6">
                        {{ $user->code }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5">{{ trans('backend.employee_name') }}</label>
                    <div class="col-md-6">
                        {{ $user->full_name }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5">{{ trans('backend.title') }}</label>
                    <div class="col-md-6">
                        {{ $user->title_name }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5">{{ trans('backend.unit') }}</label>
                    <div class="col-md-6">
                        {{ $user->unit_name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

