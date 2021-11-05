@extends('layouts.app')

@section('page_title', $forum->name)

@section('content')
    <div class="sa4d25">
        <div class="container-fluid forum-container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title"><i class="uil uil-apps"></i>
                            <a href="{{ route('module.frontend.forums') }}">{{ trans('app.forum') }}</a>
                            <i class="uil uil-angle-right"></i>
                            <span class="font-weight-bold">{{ $forum->name }}</span>
                        </h2>
                        <br>
                            <button class="btn subscribe-btn" onclick="window.location.href='{{route('module.frontend.forums.form',['id' => $forum->id])}}'">@lang('app.send_new_posts')</button>
                        <br>
                        @foreach($forum_thread as $item)
                            @php
                                $user = App\Profile::whereUserId($item->updated_by ? $item->updated_by : $item->created_by)->first();
                            @endphp
                            <div class="forum-item">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="forum-avatar">
                                            <img class="img-circle" src="{{ image_file($user->getAvatar()) }}"
                                                 alt="{{ $user->getFullName() }}"
                                                 data-toggle="tooltip"
                                                 data-placement="top"
                                                 title="{{ $user->getFullName() }}"/>
                                        </div>
                                        @if ($is_admin || $item->updated_by == auth()->id() )
                                            <div class="eps_dots more_dropdown tool">
                                                <a href="javascript:void(0)"><i class="uil uil-ellipsis-v"></i></a>
                                                <div class="dropdown-content">
                                                    <span onclick="window.location.href='{{ route('module.frontend.forums.edit',$item->id) }}'" ><i class="uil uil-clock-three"></i>@lang('app.edit')</span>
                                                    <span class="remove-item" data-id="{{ $item->id }}"><i class="uil uil-ban"></i>@lang('app.delete')</span>
                                                </div>
                                            </div>
                                        @endif
                                        <a href="{{route('module.frontend.forums.thread',['id' => $item->id])}}" data-toggle="tooltip" data-placement="bottom" title="{{ $item->title }}"
                                           class="forum-item-title">{{ Str::limit($item->title) }}</a>
                                        <div class="forum-sub-title">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }} . {{ $user->getFullName() }}</div>
                                        <div class="forum-sub-title">{{ $item->hashtag }}</div>
                                        <div class="forum-sub-title">{!! Str::words(strip_tags($item->content), 10) !!}</div>
                                    </div>
                                    <div class="col-md-1 forum-info">
                                        <span class="views-number">
                                            {{ $item->views }}
                                        </span>
                                        <div>
                                            <small>@lang('app.view')</small>
                                        </div>
                                    </div>
                                    <div class="col-md-1 forum-info">
                                        @if ($is_admin || $item->updated_by == auth()->id() )
                                            <div class="eps_dots more_dropdown">
                                                <a href="#"><i class="uil uil-ellipsis-v"></i></a>
                                                <div class="dropdown-content">
                                                    <span onclick="window.location.href='{{ route('module.frontend.forums.edit',$item->id) }}'" ><i class="uil uil-clock-three"></i>@lang('app.edit')</span>
                                                    <span class="remove-item" data-id="{{ $item->id }}"><i class="uil uil-ban"></i>@lang('app.delete')</span>
                                                </div>
                                            </div>
                                        @endif
                                        <span class="views-number">
                                            {{ $forum_threat_count($item->id) }}
                                        </span>
                                        <div>
                                            <small>@lang('app.comment')</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 forum-comment-box">
                                        @php
                                            $lastComment = \Modules\Forum\Entities\ForumThread::getLastestComment($item->id);
                                            $userCmt = \App\Profile::whereUserId(auth()->id())->first();
                                        @endphp
                                        @if ($lastComment)
                                            <p class="forum-avatar-box">
                                                <a class="forum-avatar">
                                                    <img class="img-comment" src="{{ image_file(\App\Profile::avatar($lastComment->created_by)) }}"
                                                         alt="{{ \App\Profile::fullname($lastComment->created_by) }}"
                                                         data-toggle="tooltip"
                                                         data-placement="top"
                                                         title="{{ \App\Profile::fullname($lastComment->created_by) }}"/>
                                                </a>
                                                <a href="{{route('module.frontend.forums.thread',['id' => $item->id])}}"
                                                   data-toggle="tooltip"
                                                   data-placement="bottom"
                                                   title="{{ get_date($lastComment->created_at, 'H:i d/m/Y') }}"
                                                   class="forum-permalink">{{ get_date($lastComment->created_at, 'H:i d/m/Y') }}
                                                </a>
                                            </p>
                                            <div class="forum-comment">{!! Str::words(strip_tags($lastComment->comment),8)  !!}</div>
                                        @else
                                            <p class="forum-avatar-box">
                                                <a class="forum-avatar">
                                                    <img class="img-comment" src="{{ image_file($user->getAvatar()) }}"
                                                         alt="{{ $user->getFullName() }}"
                                                         data-toggle="tooltip"
                                                         data-placement="bottom"
                                                         title="{{ $user->getFullName() }}"/>
                                                </a>
                                                <a href="{{route('module.frontend.forums.thread',['id' => $item->id])}}"
                                                   data-toggle="tooltip"
                                                   data-placement="bottom"
                                                   title="{{ get_date($item->created_at, 'H:i d/m/Y') }}"
                                                   class="forum-permalink">{{ get_date($item->created_at, 'H:i d/m/Y') }}
                                                </a>
                                            </p>
                                            <div class="forum-comment">{!! Str::words(strip_tags($item->content),8) !!}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        {{ $forum_thread->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(".remove-item").on('click', function (event) {
            event.preventDefault();
            var id = $(this).data('id');
            var url = "{{route('module.frontend.forums.deleteforum',['id'=> $forum->id])}}";
            var q = "Bạn có chắc muốn xóa bài viết này";
            var data = {'id': id};
            var item = $(this).parent('div').parent('div').parent('div').parent('div');

            if (confirm(q)) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (q) {
                        if (q === "ok") {
                            item.remove();
                        }
                    }
                });
            }
        });
    </script>
@stop
