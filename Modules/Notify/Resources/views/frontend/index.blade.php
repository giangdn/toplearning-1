@extends('layouts.app')

@section('page_title', 'HỆ THỐNG QUẢN LÝ ĐÀO TẠO E-LEARNING')

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title"><i class="uil uil-bell">
                            </i><span class="font-weight-bold">@lang('app.notify')</span>
                        </h2>
                    </div>
                </div>
            </div>
            <p></p>
            <div class="row search-course mt-3 pb-2">
                <div class="col-12 form-inline">
                    <form action="" method="get" class="form-inline" id="form-search">
                        {{ csrf_field() }}
                        <input type="text" name="search" value="" class="form-control search_text mr-1" placeholder="Nhập Tiêu đề thông báo">
                        <input name="start_date" type="text" class="datepicker form-control search_start_date mr-1" placeholder="{{trans('backend.start_date')}}" autocomplete="off">
                        <input name="end_date" type="text" class="datepicker form-control search_end_date mr-1" placeholder="{{trans('backend.end_date')}}" autocomplete="off">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="all_msg_bg" id="notify-new">
                        @if ($notify->count() > 0)
                            @foreach($notify as $note)
                                <div class="channel_my item all__noti5 p-2">
                                    <div class="profile_link">
                                        @if($note->important == 1)
                                            <i class="uil uil-star text-warning"></i>
                                        @endif
                                        <div class="pd_content w-100">
                                            <h6>
                                                <a href="{{ route('module.notify.view', ['id' => $note->id, 'type' => $note->type]) }}">
                                                    <i class="uil uil-bell" style="font-size: 17px"></i>
                                                    <span class="{{ $note->viewed == 1 ? 'text-blue-50' : 'text-blue-bold' }}">
                                                        {{ $note->subject }}
                                                    </span>
                                                </a>
                                            </h6>
                                            <span class="nm_time" style="margin-left: 25px">
                                                {{ \Illuminate\Support\Carbon::parse($note->created_at)->diffForHumans() }}
                                            </span>
                                        </div>
                                        <button class="btn btn-danger float-right btn-delete" data-id="{{ $note->id .'_'.$note->type }}">
                                            <i class="uil uil-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="channel_my item all__noti5">
                                <div class="profile_link">
                                    <div class="pd_content">
                                        <h6>@lang('app.no_notification')</h6>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#notify-new').on('click', '.btn-delete', function () {
            var ids = $(this).map(function(){return $(this).data('id');}).get();
            Swal.fire({
                title: '',
                text: 'Bạn chắc chắn muốn xoá thông báo này!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý!',
                cancelButtonText: 'Hủy!',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('module.notify.remove') }}",
                        dataType: 'json',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'ids': ids
                        },
                        success: function (result) {
                            if (result.status === "success") {
                                window.location = '';
                                return false;
                            }
                            else {
                                window.location = '';
                                return false;
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
