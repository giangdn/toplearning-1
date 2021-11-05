<div>
    <div class="student_reviews">
        <div class="row m-0">
            <div class="col-12">
                <div class="review_right">
                    <div class="review_right_heading">
                        <h6>@lang('app.comment') ({{ $comments->count() }})</h6>
                    </div>
                </div>

                <div class="review_all120">
                    <form action="{{ route('themes.mobile.frontend.online.comment', ['course_id' => $item->id]) }}" method="post" class="form-comment form-ajax">
                        @csrf
                        <div class="form-group">
                            <textarea name="content" class="form-control" rows="5" id="content"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> @lang('app.send')</button>
                    </form>
                    <br>
                    <div>
                        @foreach($comments as $comment)
                            <div class="card shadow border-0 mt-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto pr-0">
                                            <img src="{{ \App\Profile::avatar(@$comment->user_id) }}" alt="" class="avatar avatar-50 no-shadow border-0">
                                        </div>
                                        <div class="col-auto align-self-center">
                                            <h6 class="font-weight-normal mb-1">{{ \App\Profile::fullname($comment->user_id) }}</h6>
                                            <p class="text-mute text-secondary">
                                                {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col align-self-center">
                                            {{ ucfirst($comment->content) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

