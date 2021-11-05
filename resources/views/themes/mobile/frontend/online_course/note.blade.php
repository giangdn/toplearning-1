<div>
    <div class="student_reviews">
        <div class="row m-0">
            <div class="col-12">
                <div class="review_all120">
                    <form action="{{ route('themes.mobile.frontend.online.note', ['course_id' => $item->id]) }}" method="post" class="form-comment form-ajax">
                        @csrf
                        <div class="form-group">
                            <textarea name="note_content" class="form-control" rows="5" id="content"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> @lang('app.send')</button>
                    </form>
                    <br>
                    <div>
                        @foreach($notes as $comment)
                            <div class="card shadow border-0 mt-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto pr-0">
                                            <img src="{{ $comment->user_type == 1 ? image_file($comment->avatar) : asset('images/image_default.jpg') }}" alt="" class="avatar avatar-50 no-shadow border-0">
                                        </div>
                                        <div class="col-auto align-self-center">
                                            <h6 class="font-weight-normal mb-1">{{ $comment->user_type == 1 ? $comment->fullname : $comment->name }}</h6>
                                            <p class="text-mute text-secondary">
                                                {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col align-self-center">
                                            {{ ucfirst($comment->note) }}
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

