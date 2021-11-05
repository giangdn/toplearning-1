@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.forum'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-4">
                <button class="btn btn-danger mb-2" onclick="window.location.href='{{route('module.frontend.forums.form',['id' => $forum->id])}}'">@lang('app.send_new_posts')</button>
            </div>
            <div class="col-8 pl-0">
                <form method="get" class="input-group form-search border-0">
                    <input type="text" name="q" class="form-control" placeholder="{{ data_locale('Nhập tên bài viết', 'Enter topic name') }}" value="{{ request()->get('q') }}">
                    <button type="submit" class="btn btn-link text-white position-relative text-right">
                        <i class="material-icons vm">search</i>
                    </button>
                </form>
            </div>
        </div>
        <br>
        @foreach($forum_thread as $item)
            <div class="card shadow border-0 mb-1">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-3 pr-0">
                            <img src="{{ asset('themes/mobile/img/forum.png') }}" alt="" class="icons-raised avatar avatar-50 no-shadow border-0">
                        </div>
                        <div class="col-9 align-self-center">
                            <h6 class="font-weight-normal mb-1">
                                <a href="{{ route('module.frontend.forums.thread',['id' => $item->id])  }}" class="forum-item-title">
                                    {{ Str::limit($item->title) }}
                                </a>
                            </h6>
                            <p class="text-mute">
                                {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }} . {{ \App\Profile::fullname($item->updated_by ? $item->updated_by : $item->created_by) }}
                            </p>
                            <p class="text-mute text-secondary text-center">
                                <span class="row">
                                    <span class="col">
                                        {{ $item->views }} Views
                                    </span>
                                    <span class="col border-left">
                                        {{ $forum_threat_count($item->id) }} Comment
                                    </span>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="row">
            <div class="col-6">
                @if($forum_thread->previousPageUrl())
                    <a href="{{ $forum_thread->previousPageUrl() }}" class="bp_left">
                        <i class="material-icons">navigate_before</i> @lang('app.previous')
                    </a>
                @endif
            </div>
            <div class="col-6 text-right">
                @if($forum_thread->nextPageUrl())
                    <a href="{{ $forum_thread->nextPageUrl() }}" class="bp_right">
                        @lang('app.next') <i class="material-icons">navigate_next</i>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
