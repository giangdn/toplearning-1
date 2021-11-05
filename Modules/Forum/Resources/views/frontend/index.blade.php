@extends('layouts.app')

@section('page_title', 'Diễn đàn')

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title"><i class="uil uil-apps"></i>
                           <span class="font-weight-bold"> @lang('app.forum')</span>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 mt-2">
                    <form method="get" action="" id="form-search" class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="{{ data_locale('Nhập hashtag', 'Enter hashtag') }}" value="{{ request()->get('search') }}" onchange="submit();">
                    </form>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12">
                    @if($forum_thread_search)
                        <div class="row">
                            <div class="col-12 bg-white">
                                <div class="forum-sub-title">
                                    @foreach($forum_thread_search as $thread)
                                        @php
                                            $user = App\Profile::whereUserId($thread->updated_by ? $thread->updated_by : $thread->created_by)->first();
                                            $count_comment = \Modules\Forum\Entities\ForumComment::CountComment($thread->id);
                                            $lastComment = \Modules\Forum\Entities\ForumThread::getLastestComment($thread->id);
                                            $userCmt = \App\Profile::whereUserId(auth()->id())->first();

                                            $check_unit = 0;
                                            $get_unit_id_forums_cate = \Modules\Forum\Entities\ForumCategoryPermission::where('forum_cate_id',$thread->category_id)->get();
                                            if ( !$get_unit_id_forums_cate->isEmpty() ) {
                                                foreach ($get_unit_id_forums_cate as $get_unit_id_forum_cate) {
                                                    if( $profile_view->unit_id == $get_unit_id_forum_cate->unit_id) {
                                                        $check_unit = 1;
                                                    } else if ($profile_view->user_id == $get_unit_id_forum_cate->user_id) {
                                                        $check_unit = 1;
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if ( (!$get_unit_id_forums_cate->isEmpty() && $check_unit == 1) || $get_unit_id_forums_cate->isEmpty() || $is_admin)
                                            <div class="forum-item border-bottom">
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="forum-avatar">
                                                            <img class="img-circle" src="{{ image_file($user->getAvatar()) }}"
                                                                alt="{{ $user->getFullName() }}"
                                                                data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="{{ $user->getFullName() }}"/>
                                                        </div>
                                                        @if ($is_admin || $thread->updated_by == auth()->id() )
                                                            <div class="eps_dots more_dropdown tool">
                                                                <a href="javascript:void(0)"><i class="uil uil-ellipsis-v"></i></a>
                                                                <div class="dropdown-content">
                                                                    <span onclick="window.location.href='{{ route('module.frontend.forums.edit',$thread->id) }}'" ><i class="uil uil-clock-three"></i>@lang('app.edit')</span>
                                                                    <span class="remove-item" data-id="{{ $thread->id }}"><i class="uil uil-ban"></i>@lang('app.delete')</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <a href="{{route('module.frontend.forums.thread',['id' => $thread->id])}}" data-toggle="tooltip" data-placement="bottom" title="{{ $thread->title }}"
                                                        class="forum-item-title">{{ Str::limit($thread->title) }}</a>
                                                        <div class="forum-sub-title">{{ \Carbon\Carbon::parse($thread->created_at)->diffForHumans() }} . {{ $user->getFullName() }}</div>
                                                        <div class="forum-sub-title {{ request()->get('search') == $thread->hashtag ? 'text-primary' : '' }}">{{ $thread->hashtag }}</div>
                                                        <div class="forum-sub-title">{!! Str::words(strip_tags($thread->content), 10) !!}</div>
                                                    </div>
                                                    <div class="col-md-1 forum-info">
                                                        <span class="views-number">
                                                            {{ $thread->views }}
                                                        </span>
                                                        <div>
                                                            <small>@lang('app.view')</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 forum-info">
                                                        @if ($is_admin || $thread->updated_by == auth()->id() )
                                                            <div class="eps_dots more_dropdown">
                                                                <a href="#"><i class="uil uil-ellipsis-v"></i></a>
                                                                <div class="dropdown-content">
                                                                    <span onclick="window.location.href='{{ route('module.frontend.forums.edit',$thread->id) }}'" ><i class="uil uil-clock-three"></i>@lang('app.edit')</span>
                                                                    <span class="remove-item" data-id="{{ $thread->id }}"><i class="uil uil-ban"></i>@lang('app.delete')</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <span class="views-number">
                                                            {{ $count_comment }}
                                                        </span>
                                                        <div>
                                                            <small>@lang('app.comment')</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 forum-comment-box">
                                                        @if ($lastComment)
                                                            <p class="forum-avatar-box">
                                                                <a class="forum-avatar">
                                                                    <img class="img-comment" src="{{ image_file(\App\Profile::avatar($lastComment->created_by)) }}"
                                                                        alt="{{ \App\Profile::fullname($lastComment->created_by) }}"
                                                                        data-toggle="tooltip"
                                                                        data-placement="top"
                                                                        title="{{ \App\Profile::fullname($lastComment->created_by) }}"/>
                                                                </a>
                                                                <a href="{{route('module.frontend.forums.thread',['id' => $thread->id])}}"
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
                                                                <a href="{{route('module.frontend.forums.thread',['id' => $thread->id])}}"
                                                                data-toggle="tooltip"
                                                                data-placement="bottom"
                                                                title="{{ get_date($thread->created_at, 'H:i d/m/Y') }}"
                                                                class="forum-permalink">{{ get_date($thread->created_at, 'H:i d/m/Y') }}
                                                                </a>
                                                            </p>
                                                            <div class="forum-comment">{!! Str::words(strip_tags($thread->content),8) !!}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="ibox-content forum-container">
                            @foreach($forum_categories as $forum_category)
                                @php
                                    $check_unit = 0;
                                    $get_unit_id_forums_cate = \Modules\Forum\Entities\ForumCategoryPermission::where('forum_cate_id',$forum_category->id)->get();
                                    if ( !$get_unit_id_forums_cate->isEmpty() ) {
                                        foreach ($get_unit_id_forums_cate as $get_unit_id_forum_cate) {
                                            if( $profile_view->unit_id == $get_unit_id_forum_cate->unit_id) {
                                                $check_unit = 1;
                                            } else if ($profile_view->user_id == $get_unit_id_forum_cate->user_id) {
                                                $check_unit = 1;
                                            }
                                        }
                                    }
                                @endphp

                                @if ( (!$get_unit_id_forums_cate->isEmpty() && $check_unit == 1) || $get_unit_id_forums_cate->isEmpty() || $is_admin)
                                    <div class="forum-title opts_account pl-0">
                                        <h3><img src="{{ image_file($forum_category->icon)}}" alt="" class="ml-0">  {{ $forum_category->name }}</h3>
                                        @php
                                            $forums = $forum_category->topic()->orderBy('num_topic', 'DESC')->orderBy('num_comment', 'DESC')->where('status',1)->get();
                                        @endphp
                                    </div>
                                    @foreach($forums as $item)
                                        @php
                                            $forum_thread = \Modules\Forum\Entities\ForumThread::where('forum_id', $item->id)
                                            ->where('status',1)
                                            ->orderBy('views', 'DESC')
                                            ->orderBy('total_comment', 'DESC')
                                            ->limit(6)
                                            ->get();
                                        @endphp
                                        <div class="forum-item active border-bottom">
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <div class="forum-icon pl-2 ml-3 ">
                                                        @if($item->icon)
                                                            <img src="{{ image_file($item->icon) }}" alt="" class="" style="width: 32px; height: 32px;">
                                                        @else
                                                            <img src="{{ asset('themes/mobile/img/hologram.png') }}" alt="" style="width: 32px; height: 32px;">
                                                        @endif
                                                    </div>
                                                    <a href="{{ route('module.frontend.forums.topic', ['id' => $item->id])}}" class="forum-item-title">{{ $item->name }}</a>
                                                </div>
                                                <div class="col-md-1 forum-info">
                                                    <span class="views-number">
                                                        {{ $item->getTotalViews() ? $item->getTotalViews() : 0 }}
                                                    </span>
                                                    <div>
                                                        <small>Views</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 forum-info">
                                                    <span class="views-number">
                                                        {{ $item->thread->count() ? $item->thread->count() : 0 }}
                                                    </span>
                                                    <div>
                                                        <small>Topics</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 forum-info">
                                                    <span class="views-number">
                                                        {{ $item->getTotalComment() ? $item->getTotalComment() : 0 }}
                                                    </span>
                                                    <div>
                                                        <small>Comment</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="forum-sub-title">
                                                        @foreach($forum_thread as $thread)
                                                            @php
                                                                $user = App\Profile::whereUserId($thread->updated_by ? $thread->updated_by : $thread->created_by)->first();
                                                                $count_comment = \Modules\Forum\Entities\ForumComment::CountComment($thread->id);
                                                                $lastComment = \Modules\Forum\Entities\ForumThread::getLastestComment($thread->id);
                                                                $userCmt = \App\User::getProfileById(auth()->id());
                                                            @endphp
                                                            <div class="forum-item border-0">
                                                                <div class="row">
                                                                    <div class="col-md-7">
                                                                        <div class="forum-avatar">
                                                                            <img class="img-circle" src="{{ image_file($user->getAvatar()) }}"
                                                                                alt="{{ $user->getFullName() }}"
                                                                                data-toggle="tooltip"
                                                                                data-placement="top"
                                                                                title="{{ $user->getFullName() }}"/>
                                                                        </div>
                                                                        @if ($is_admin || $thread->updated_by == auth()->id() )
                                                                            <div class="eps_dots more_dropdown tool">
                                                                                <a href="javascript:void(0)"><i class="uil uil-ellipsis-v"></i></a>
                                                                                <div class="dropdown-content">
                                                                                    <span onclick="window.location.href='{{ route('module.frontend.forums.edit',$thread->id) }}'" ><i class="uil uil-clock-three"></i>@lang('app.edit')</span>
                                                                                    <span class="remove-item" data-id="{{ $thread->id }}"><i class="uil uil-ban"></i>@lang('app.delete')</span>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                        <a href="{{route('module.frontend.forums.thread',['id' => $thread->id])}}" data-toggle="tooltip" data-placement="bottom" title="{{ $thread->title }}"
                                                                        class="forum-item-title">{{ Str::limit($thread->title) }}</a>
                                                                        <div class="forum-sub-title">{{ \Carbon\Carbon::parse($thread->created_at)->diffForHumans() }} . {{ $user->getFullName() }}</div>
                                                                        <div class="forum-sub-title">{{ $thread->hashtag }}</div>
                                                                        <div class="forum-sub-title">{!! Str::words(strip_tags($thread->content), 10) !!}</div>
                                                                    </div>
                                                                    <div class="col-md-1 forum-info">
                                                                        <span class="views-number">
                                                                            {{ $thread->views }}
                                                                        </span>
                                                                        <div>
                                                                            <small>@lang('app.view')</small>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1 forum-info">
                                                                        @if ($is_admin || $thread->updated_by == auth()->id() )
                                                                            <div class="eps_dots more_dropdown">
                                                                                <a href="#"><i class="uil uil-ellipsis-v"></i></a>
                                                                                <div class="dropdown-content">
                                                                                    <span onclick="window.location.href='{{ route('module.frontend.forums.edit',$thread->id) }}'" ><i class="uil uil-clock-three"></i>@lang('app.edit')</span>
                                                                                    <span class="remove-item" data-id="{{ $thread->id }}"><i class="uil uil-ban"></i>@lang('app.delete')</span>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                        <span class="views-number">
                                                                            {{ $count_comment }}
                                                                        </span>
                                                                        <div>
                                                                            <small>@lang('app.comment')</small>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 forum-comment-box">
                                                                        @if ($lastComment)
                                                                            <p class="forum-avatar-box">
                                                                                <a class="forum-avatar">
                                                                                    <img class="img-comment" src="{{ image_file(\App\Profile::avatar($lastComment->created_by)) }}"
                                                                                        alt="{{ \App\Profile::fullname($lastComment->created_by) }}"
                                                                                        data-toggle="tooltip"
                                                                                        data-placement="top"
                                                                                        title="{{ \App\Profile::fullname($lastComment->created_by) }}"/>
                                                                                </a>
                                                                                <a href="{{route('module.frontend.forums.thread',['id' => $thread->id])}}"
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
                                                                                <a href="{{route('module.frontend.forums.thread',['id' => $thread->id])}}"
                                                                                data-toggle="tooltip"
                                                                                data-placement="bottom"
                                                                                title="{{ get_date($thread->created_at, 'H:i d/m/Y') }}"
                                                                                class="forum-permalink">{{ get_date($thread->created_at, 'H:i d/m/Y') }}
                                                                                </a>
                                                                            </p>
                                                                            <div class="forum-comment">{!! Str::words(strip_tags($thread->content),8) !!}</div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        @if(count($forum_thread) == 6)
                                                            <div class="row">
                                                                <div class="col-12 text-center">
                                                                    <a href="{{ route('module.frontend.forums.topic', ['id' => $item->id])}}" class="forum-item-title">
                                                                        <button class="btn btn-info">@lang('app.view_all')</button>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
