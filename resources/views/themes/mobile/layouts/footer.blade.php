@php
    $arr = ['frontend.home'];

    $routeName = Route::currentRouteName();
@endphp
@php
    $get_notes = App\Note::where('type',0)->where('user_id',\Auth::id())->get();
    $date = date('Y-m-d H:i:s');
@endphp
@if (!empty($get_notes))
    @foreach ($get_notes as $key => $get_note)
        @if ($date > $get_note->date_time && $get_note->date_time !== '1970-01-01 08:00:00')
            <div class="show_note_mobile" id="note_id_{{$get_note->id}}">
                <div class="close_show_note_mobile">
                    <button class="btn text-white" type="button" onclick="closeShowNote({{$get_note->id}})">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                <div class="note_mobile">
                    <h5>{{ data_locale('Ghi chú', 'Note') }}</h5>
                </div>
                <div class="content_note_mobile">
                    <span>{{$get_note->content}}</span>
                </div>
            </div>
        @endif
    @endforeach
@endif

<div class="footer">
    <div class="no-gutters">
        <div class="col-auto mx-auto">
            <div class="row no-gutters justify-content-center text-center">
                <div class="col-3">
                    <a href="{{ route('frontend.home') }}" class="btn btn-link-default {{ (isset($lay) && $lay == 'home') ? 'active' : '' }}">
                        @if(isset($lay) && $lay == 'home')
                            <img src="{{ asset('themes/mobile/img/home.png') }}" alt="">
                        @else
                            <img src="{{ asset('themes/mobile/img/home-first.png') }}" alt="">
                        @endif
                        <div class="small">Home</div>
                    </a>
                </div>
                @if(in_array($routeName, $arr))
                    <div class="col-3">
                        <button class="btn btn-link-default" onclick="noteMenu()">
                            <img src="{{ asset('themes/mobile/img/notepad.png') }}" alt="">
                            <div class="small">{{ data_locale('Ghi chú', 'Note') }}</div>
                        </button>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('module.suggest.index') }}" class="btn btn-link-default {{ (isset($lay) && $lay == 'suggest') ? 'active' : '' }}">
                            @if(isset($lay) && $lay == 'suggest')
                                <img src="{{ asset('themes/mobile/img/feedback_footer.png') }}" alt="">
                            @else
                                <img src="{{ asset('themes/mobile/img/feedback.png') }}" alt="">
                            @endif
                            <div class="small">@lang('app.suggest')</div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('themes.mobile.frontend.profile') }}" class="btn btn-link-default {{ (isset($lay) && $lay == 'profile') ? 'active' : '' }}">
                            @if(isset($lay) && $lay == 'profile')
                                <img src="{{ asset('themes/mobile/img/user.png') }}" alt="">
                            @else
                                <img src="{{ asset('themes/mobile/img/user_first.png') }}" alt="">
                            @endif
                            <div class="small">@lang('app.account')</div>
                        </a>
                    </div>
                @else
                    <div class="col-6"></div>
                    <div class="col-3">
                        <a href="javascript:void(0)" onclick="window.history.back(); return false;" class="btn btn-link-default text-center text-white pt-3" style="background: #8c110a">
                            <i class="material-icons md-24 vm font-weight-bold">navigate_before</i> <span>@lang('app.back')</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@section('modal')
{{-- MODAL GHI CHÚ --}}
<div class="modal fade" id="modal-create-note-mobile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form action="{{ route('frontend.save.note') }}" method="post" class="form-ajax">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ data_locale('Thêm ghi chú', 'Add note') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body-note">
                    <input type="hidden" name="type" value="0">
                    <div class="row">
                        <div class="col-md-8">
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="btn-group act-btns">
                                <button type="button" onclick="addNewNote()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> &nbsp;{{ data_locale('Thêm ghi chú', 'Add note') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 label">
                            <label>{{ data_locale('Thời gian thông báo', 'Notice time') }}</label>
                        </div>
                        <div class="col-md-9">
                            <input type="datetime-local" id="date_time" name="date_times[]">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 label">
                            <label>{{ data_locale('Nội dung ghi chú', 'Content') }}</label>
                        </div>
                        <div class="col-md-7">
                            <textarea class="form-control" name="contents[]" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ trans('app.save') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('app.close') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('footer')
<script>
    $(document).ready(function() {
        setTimeout(function(){
            $(".show_note_mobile").show('slow');
        }, 1000);
    });
    function noteMenu(){
        $('#modal-create-note-mobile').modal();
    }
    var clicks = 0;
    function addNewNote() {
        clicks += 1;
        $('#modal-body-note').append(`<div class="form-group row" id="date_time_`+clicks+`">
                                    <div class="col-md-3 label">
                                    </div>
                                    <div class="col-md-9">
                                        <input type="datetime-local" id="date_time" name="date_times[]">
                                    </div>
                                </div>
                                <div class="form-group row" id="content_`+clicks+`">
                                    <div class="col-md-3 label">
                                    </div>
                                    <div class="col-md-7">
                                        <textarea class="form-control" name="contents[]" required></textarea>
                                    </div>
                                    <div class="col-sm-2 control-label">
                                        <button type="button" onclick="closeAddNewNote(`+clicks+`)" class="btn text-white">Xóa</button>
                                    </div>
                                </div>`)
    }
    function closeAddNewNote(id) {
        $('#date_time_'+id).remove();
        $('#content_'+id).remove();
    }
    function closeShowNote(id) {
        $('#note_id_'+id).remove();
        $.ajax({
            type: 'POST',
            url: "{{ route('frontend.close.note') }}",
            data: {
                id: id,
            }
        }).done(function(data) {
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }
</script>
@endsection
