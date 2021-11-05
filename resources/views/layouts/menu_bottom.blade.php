@php
    $get_notes = App\Note::where('type',0)->where('user_id',\Auth::id())->get();
    $date = date('Y-m-d H:i:s');
    // dd(session()->get('close_open_menu'));
@endphp
@if (!empty($get_notes))
    @foreach ($get_notes as $key => $get_note)
        @if ($date > $get_note->date_time && $get_note->date_time !== '1970-01-01 08:00:00')
            <input type="text" id="test" value="1">
            <div class="show_note" id="note_id_{{$get_note->id}}" onload="note()">
                <div class="close_show_note">
                    <button class="btn" type="button" onclick="closeShowNote({{$get_note->id}})">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                <div class="note">
                    <h3><img src="{{ asset('images/note.png') }}" alt=""></h3>
                </div>
                <div class="content_note">
                    <span>{{$get_note->content}}</span>
                </div>
                <div class="song">
                    <div class="player">
                        <audio class="audio" id="my_audio" autoplay controls>
                            <source src="{{ url('images/got-it-done.mp3') }}" type="audio/mpeg">
                            <source src="{{ url('images/got-it-done.mp3') }}" type="audio/ogg">
                        </audio>
                    </div>
                </div>
                <div class="pause noti_note" onclick="togglePlay()">
                    <i class="uil uil-bell"></i>
                </div>
            </div>
        @endif
    @endforeach
@endif

<div class="all_menu_bottom row m-0">
    <div id="create_suggest" class="col-2 menu_bottom">
        <i class='uil uil-comment-alt-exclamation' aria-hidden="true"></i> <span>Góp ý</span>
    </div>
    <a href="{{ route('frontend.contact') }}" id="contact_menu" class="col-3 menu_bottom">
        <i class='fas fa-comments' aria-hidden="true"></i> <span>Liên hệ</span>
    </a>
    <a href="{{ route('frontend.google.map') }}" id="map_menu" class="col-4 menu_bottom">
        <i class='fas fa-map-marker-alt' aria-hidden="true"></i> <span>Địa điểm đào tạo</span>
    </a>
    <div id="note_menu" class="col-2 menu_bottom">
        <i class="far fa-sticky-note"></i> <span>Ghi chú</span>
    </div>
    <div class="col-1 menu_bottom" id="close_menu">
        <div class="pull-right">
            <i class="fas fa-sort-down" aria-hidden="true"></i>
        </div>
    </div>
</div>

<div class="row all_menu_bottom button_menu_bottom m-0">
    <div class="col-12 col-xl-12 col-lg-12 col-md-12 text-right">
        <div class="pull-right">
            <div class="btn-group">
                <button class="btn btn-info" id="show_menu" type="button">
                    <i class="fas fa-sort-up"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL GÓP Ý --}}
<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form action="{{ route('module.suggest.save') }}" method="post" class="form-ajax">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class='uil uil-comment-alt-exclamation' aria-hidden="true"></i> {{ trans('app.add_suggest') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-3 label">
                            <label> {{ trans('app.name_suggest') }}</label>
                        </div>
                        <div class="col-md-9">
                            <input class="form-control" name="name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 label">
                            <label> {{ trans('app.content') }}</label>
                        </div>
                        <div class="col-md-9">
                            <textarea class="form-control" name="content" required></textarea>
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

{{-- MODAL GHI CHÚ --}}
<div class="modal fade" id="modal-create-note" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form action="{{ route('frontend.save.note') }}" method="post" class="form-ajax">
            @csrf
            <div class="modal-content modal_note_content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <img src="{{ asset('images/note.png') }}" alt="">
                        Thêm ghi chú</h5>
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
                                <button type="button" onclick="addNewNote()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> &nbsp;Thêm ghi chú</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 label">
                            <label>Thời gian thông báo</label>
                        </div>
                        <div class="col-md-9">
                            <input type="datetime-local" id="date_time" name="date_times[]" onkeydown="dateTime(e)">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3 label">
                            <label>Nội dung ghi chú</label>
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

<script>
    var close_open_menu = "{{ session()->get('close_open_menu') }}";
    if (close_open_menu && close_open_menu == 0 ) {
        $('.menu_bottom').css('display','none');
        $('.all_menu_bottom').css('display','none');
        $('.button_menu_bottom').css('display','block');
    } else {
        $('.menu_bottom').css('display','flex');
        $('.all_menu_bottom').css('display','flex');
        $('.button_menu_bottom').css('display','none');
    }
    function dateTime(e) {
        e.preventDefault();
    }
    $('#create_suggest').on('click', function() {
        $('#modal-create').modal();
    });

    $('#note_menu').on('click', function() {
        $('#modal-create-note').modal();
    });

    $('#close_menu').on('click', function() {
        $('.menu_bottom').css('display','none');
        $('.all_menu_bottom').css('display','none');
        $('.button_menu_bottom').css('display','block');
        $.ajax({
            url: "{{ route('frontend.close_open_menu_bottom') }}",
            type: 'post',
            data: {
                status: 0,
            }
        }).done(function(data) {
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });

    $('#show_menu').on('click', function() {
        $('.menu_bottom').css('display','flex');
        $('.all_menu_bottom').css('display','flex');
        $('.button_menu_bottom').css('display','none');
        $.ajax({
            url: "{{ route('frontend.close_open_menu_bottom') }}",
            type: 'post',
            data: {
                status: 1,
            }
        }).done(function(data) {
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });

    var clicks = 0;
    function addNewNote() {
        clicks += 1;
        $('#modal-body-note').append(`<div class="form-group row" id="date_time_`+clicks+`">
                                    <div class="col-md-3 label">
                                        <label>Thời gian thông báo</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="datetime-local" id="date_time" name="date_times[]" onkeydown="dateTime(e)">
                                    </div>
                                </div>
                                <div class="form-group row" id="content_`+clicks+`">
                                    <div class="col-md-3 label">
                                        <label>Nội dung ghi chú</label>
                                    </div>
                                    <div class="col-md-7">
                                        <textarea class="form-control" name="contents[]" required></textarea>
                                    </div>
                                    <div class="col-sm-2 control-label">
                                        <button type="button" onclick="closeAddNewNote(`+clicks+`)" class="btn">Xóa</button>
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
    $('#my_audio').css('display','none')
    function togglePlay() {
        var pause = document.querySelector(".pause");
        var audio = document.querySelector(".audio");
        audio.play();
    }
</script>
