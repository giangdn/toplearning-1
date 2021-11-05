@extends('layouts.app')

@section('page_title', $forum_category->title)

@section('header')
    <script src="{{ asset('vendor/ckeditor_4.16.2/ckeditor.js') }}" type="text/javascript" charset="utf-8"></script>
@endsection

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row forum-container">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    @php
                        $user = \App\Profile::whereUserId($forum_category->created_by)->first();
                    @endphp
                    <div class="ibox-content forum-container">
                        <h2 class="st_title">
                            <i class="uil uil-apps"></i>
                            <a href="{{ route('module.frontend.forums') }}">{{ trans('app.forum') }}</a>
                            <i class="uil uil-angle-right"></i>
                            <a href="{{ route('module.frontend.forums.topic',['id' => $forum_category->forum_id]) }}">{{ $forum_category->category->name }} </a>
                            <i class="uil uil-angle-right"></i>
                            <span class="font-weight-bold">{{ $forum_category->title }}</span>
                        </h2>
                        <h3 class="f_title">{{ $forum_category->title }}</h3>
                        <div class="topic-item">
                            <div class="row">
                                <div class="col-md-12">
                                    @if ($is_admin || $forum_category->created_by == auth()->id() )
                                        <div class="eps_dots more_dropdown">
                                            <a href="javascript:void(0)"><i class="uil uil-ellipsis-v"></i></a>
                                            <div class="dropdown-content">
                                                <span onclick="window.location.href='{{ route('module.frontend.forums.edit',$forum_category->id) }}'" ><i class="uil uil-clock-three"></i>@lang('app.edit')</span>
                                                <span class="remove-item" data-id="{{ $forum_category->id }}"><i class="uil uil-ban"></i>@lang('app.delete')</span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="forum-avatar">
                                        <img class="img-circle" src="{{ image_file($user->getAvatar()) }}"
                                             alt="{{ $user->getFullName() }}"
                                             data-toggle="tooltip"
                                             data-placement="top"
                                             title="{{ $user->getFullName() }}"/>
                                    </div>
                                    <a class="forum-item-title">{{ $user->getFullName() }}</a>
                                    <div class="forum-sub-title">{{ \Carbon\Carbon::parse($forum_category->created_at)->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="forum-content text-justify" onCopy="return false" onPaste="return false">
                                    {!! $forum_category->content !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row forum-container">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="ibox-content forum-container">
                        <h6><b>@lang('app.comment')</b> ({{ $comments->count() }})</h6>
                    </div>
                </div>
            </div>
            @php
                $number = 0;
            @endphp
            @foreach($comments as $item)
                @php
                    $user = \App\Profile::whereUserId($item->created_by)->first();
                    $number++;
                @endphp
                <div class="row forum-container" id="{{ $number }}">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="ibox-content forum-container">
                            <div class="topic-item">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($is_admin || $item->created_by == auth()->id() )
                                            <div class="eps_dots more_dropdown">
                                                <a href="javascript:void(0)"><i class="uil uil-ellipsis-v"></i></a>
                                                <div class="dropdown-content">
                                                    <span onclick="window.location.href='{{ route('module.frontend.forums.comment.edit',$item->id) }}'" ><i class="uil uil-clock-three"></i>@lang('app.edit')</span>
                                                    <span class="remove-comment" data-threadid="{{ $number }}" data-id="{{ $item->id }}"><i class="uil uil-ban"></i>@lang('app.delete')</span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="forum-avatar">
                                            <img class="img-circle" src="{{ image_file($user->getAvatar()) }}"
                                                 alt="{{ $user->getFullName() }}"
                                                 data-toggle="tooltip"
                                                 data-placement="top"
                                                 title="{{ $user->getFullName() }}"/>
                                        </div>
                                        <a class="forum-item-title">{{ $user->getFullName() }}</a>
                                        <div class="forum-sub-title">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</div>
                                        <a class="thread_number" href="#{{ $number }}">#{{ $number }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="forum-content text-justify" onCopy="return false" onPaste="return false">
                                        {!! $item->comment !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{ $comments->links() }}
            {{--comment--}}
            @php
                $user = \App\Profile::whereUserId(auth()->id())->first();
            @endphp
            <div class="row forum-container">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="ibox-content forum-container">
                        <div class="topic-item">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="forum-avatar">
                                        <img class="img-circle" src="{{ image_file($user->getAvatar()) }}"
                                             alt="{{ $user->getFullName() }}"
                                             data-toggle="tooltip"
                                             data-placement="top"
                                             title="{{ $user->getFullName() }}"/>
                                    </div>
                                    <a class="forum-item-title">{{ $user->getFullName() }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="forum-content">
                                    <form action="{{ route('module.frontend.forums.comment',['id' => $forum_category ]) }}" method="post" class="form-comment form-ajax">
                                        @csrf
                                        <textarea id="content" name="comment" onCopy="return false" onPaste="return false"></textarea>
                                        <br>
                                        <button type="submit" class="btn btn_adcart">{{ trans('app.send_comment') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        CKEDITOR.replace('content', {
            filebrowserImageBrowseUrl: '/filemanager?type=image',
            filebrowserBrowseUrl: '/filemanager?type=file',
            filebrowserUploadUrl : null, //disable upload tab
            filebrowserImageUploadUrl : null, //disable upload tab
            filebrowserFlashUploadUrl : null, //disable upload tab
        });
        $(".remove-comment").on('click', function(event) {
            event.preventDefault();
            var id = $(this).data('id');
            var threadid = $(this).data('threadid');
            var url = "{{ route('module.frontend.forums.delete', ['id'=> $forum_category->id]) }}";
            var q = "Bạn có chắc muốn xóa bình luận này";
            var data = {'id': id};
            var item = $('#'+threadid);

            if(confirm(q))
            {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function(response)
                    {
                        if(response == 'ok')
                        {
                            item.remove();
                        }
                    }
                });
            }
        });
        $(".remove-item").on('click', function (event) {
            event.preventDefault();
            var id = $(this).data('id');
            var url = "{{route('module.frontend.forums.deleteforum',['id'=> $forum_category->id])}}";
            var q = "Bạn có chắc muốn xóa bài viết này";
            var data = {'id': id};

            if (confirm(q)) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (q) {
                        if (q === "ok") {
                            window.location.href = '{{ route('module.frontend.forums.topic',['id' => $forum_category->forum_id]) }}'
                        }
                    }
                });
            }
        });
    </script>
@stop
