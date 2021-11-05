<div id="userThird" class="modal fade " tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('auth.save_user_third') }}" method="post" id="form-create-user" enctype="multipart/form-data" class="form-ajax form-validate">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">@lang('app.register')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <div class="text-center">
                        <input type="text" name="username" class="form-control" placeholder="{{ trans('backend.user_name') }} (*)" required autocomplete="off">
                    </div>
                    <div class="text-center">
                        <input type="password" name="password" class="form-control" placeholder="{{ trans('backend.pass') }} (*)" required autocomplete="off">
                    </div>
                    <div class="text-center">
                        <input type="text" name="lastname" class="form-control" placeholder="{{ trans('backend.they_staff') }} (*)" required autocomplete="off">
                    </div>
                    <div class="text-center">
                        <input name="firstname" type="text" class="form-control" placeholder="{{ trans('backend.employee_name') }} (*)" required autocomplete="off">
                    </div>
                    <div class="text-center">
                        <input type="text" name="email" class="form-control" placeholder="Email"  autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary mr-2">@lang('app.save')</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('app.close')</button>
                </div>
            </form>
        </div>
    </div>
</div>
