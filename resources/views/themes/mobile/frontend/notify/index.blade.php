@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.notify'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 px-0">
                <div class="swiper-container notify-slide pt-1">
                    <div class="swiper-wrapper nav-pills mb-2 text-center" id="nav-tab" role="tablist">
                        <a class="swiper-slide nav-item nav-link active" id="nav-notify-new-tab" data-toggle="tab" href="#nav-notify-new" role="tab" aria-selected="true">{{ ucfirst(trans('app.newest')) }}</a>
                        <a class="swiper-slide nav-item nav-link" id="nav-notify-viewed-tab" data-toggle="tab" href="#nav-notify-viewed" role="tab" aria-selected="false">@lang('app.watched')</a>
                        <a class="swiper-slide nav-item nav-link" id="nav-notify-old-tab" data-toggle="tab" href="#nav-notify-old" role="tab" aria-selected="false">@lang('app.old')</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="notify_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="nav-notify-new" role="tabpanel">
                            <div class="list-group list-group-flush">
                                @if(count($notify_new) > 0)
                                    @foreach($notify_new as $notify)
                                        @include('themes.mobile.frontend.notify.item')
                                    @endforeach
                                @else
                                    <span class="text-center">@lang('app.no_notice')</span>
                                @endif
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-notify-viewed" role="tabpanel">
                            <div class="list-group list-group-flush">
                                @if(count($notify_viewed) > 0)
                                    @foreach($notify_viewed as $notify)
                                        @include('themes.mobile.frontend.notify.item')
                                    @endforeach
                                @else
                                    <span class="text-center">@lang('app.no_notice')</span>
                                @endif
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-notify-old" role="tabpanel">
                            <div class="list-group list-group-flush">
                                @if(count($notify_old) > 0)
                                    @foreach($notify_old as $notify)
                                        @include('themes.mobile.frontend.notify.item')
                                    @endforeach
                                @else
                                    <span class="text-center">@lang('app.no_notice')</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        $('#nav-tab').on('click', '.nav-item', function () {
            $('a[data-toggle="tab"]').removeClass('active');
        });

        $(document).ready(function(){
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab-notify', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab-notify');
            if(activeTab){
                $('a[data-toggle="tab"]').removeClass('active');
                $('#nav-tab a[href="' + activeTab + '"]').tab('show');
                $('#nav-tab a[href="' + activeTab + '"]').addClass('active');
            }
        });

        $('#nav-notify-new').on('click', '.btn-delete', function () {
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
        $('#nav-notify-viewed').on('click', '.btn-delete', function () {
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
        $('#nav-notify-old').on('click', '.btn-delete', function () {
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

        var swiper = new Swiper('.notify-slide', {
            slidesPerView: 3,
            spaceBetween: 0,
        });
    </script>
@endsection
