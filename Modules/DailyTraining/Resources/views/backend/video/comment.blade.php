@extends('layouts.backend')

@section('page_title', 'Training Video')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training_video') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.daily_training') }}">{{ trans('backend.video_category') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.daily_training.video', ['cate_id' => $cate_id]) }}">{{ $video->name }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.comment') }}</span>
        </h2>
    </div>
@endsection

@section('content')
<div role="main" id="daily-training-comment-video">
    <div class="student_reviews">
        <div class="row">
            <div class="col-lg-12">
                <h6>@lang('app.comment') ({{ $comments->count() }})</h6>

                <div class="review_all120">
                    @if($comments)
                        @foreach($comments as $comment)
                            <div class="card shadow border-0 mt-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto opts_account">
                                            <img src="{{ \App\Profile::avatar($comment->user_id) }}" alt="" class="">
                                        </div>
                                        <div class="col align-self-center">
                                            <h6 class="font-weight-normal mb-1">
                                                {{ \App\Profile::fullname($comment->user_id) }}
                                            </h6>
                                            <p class="text-mute text-secondary">
                                                {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="col text-right">
                                            <input type="checkbox" class="check-failed" {{ $comment->failed == 1 ? 'checked' : '' }} data-comment_id="{{ $comment->id }}"> Tiêu cực
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col align-self-center">
                                            {!! ucfirst($comment->content) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
    <script type="text/javascript">
        $(".check-failed").on('click', function () {
            var comment_id = $(this).data('comment_id');

            $.ajax({
                type: 'POST',
                url: '{{ route('module.daily_training.video.check_failed_comment', ['cate_id' => $cate_id, 'video_id' => $video->id]) }}',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'comment_id': comment_id,
                }
            }).done(function(data) {
                return false;
            }).fail(function(data) {
                return false;
            });
        });
    </script>
@endsection

