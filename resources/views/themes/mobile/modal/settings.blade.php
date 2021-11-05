<div class="modal fade " id="settings" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header theme-header border-0">
                <h6 class="">@lang('app.language')</h6>
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>--}}
            </div>
            <div class="modal-body" style="border-top: 1px solid #dee2e6;">
                <div class="row">
                    <div class="col-6 text-center">
                        <img src="{{ asset('themes/mobile/img/vietnam.png') }}" alt="" class="avatar-40"> <a href="{{ route('change_language', ['language' => 'vi']) }}">Vietnamese</a>
                    </div>
                    <div class="col-6 text-center">
                        <img src="{{ asset('themes/mobile/img/english.png') }}" alt="" class="avatar-40"> <a href="{{ route('change_language', ['language' => 'en']) }}">English</a>
                    </div>
                </div>
            </div>
            {{--<div class="modal-footer">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 text-center align-self-center">
                            <button type="button" class="btn btn-info" data-dismiss="modal" aria-label="Close" style="float: unset">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>
    </div>
</div>
