<div id="modal-reset-pass" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form action="{{ route('auth.reset_pass') }}" method="post" id="form-reset-pass" enctype="multipart/form-data" class="form-ajax">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ data_locale('Lấy mật khẩu mới', 'Get a new password') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-4 control-label">
                            <label for="username">{{ trans('backend.user_name') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input name="username" id="username" type="text" class="form-control" value="" placeholder="{{ trans('backend.user_name') }}" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4 control-label">
                            <label for="username">Email</label>
                        </div>
                        <div class="col-md-8">
                            <input name="email" id="email" type="text" class="form-control" value="" placeholder="email" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary mr-2">{{ trans('app.send') }}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('app.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
