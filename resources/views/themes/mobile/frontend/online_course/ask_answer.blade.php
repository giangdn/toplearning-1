<div>
    <div class="student_reviews">
        <div class="row m-0">
            <div class="col-12">
                <div class="review_all120">
                    <form action="{{ route('themes.mobile.frontend.online.ask_answer', ['course_id' => $item->id]) }}" method="post" class="form-comment form-ajax">
                        @csrf
                        <div class="form-group">
                            <textarea name="ask_content" class="form-control" rows="5" id="content"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> @lang('app.send')</button>
                    </form>
                    <br>
                    <div>
                        @foreach($ask_answer as $comment)
                            <div class="card shadow border-0 mt-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto pr-0">
                                            <img src="{{ $comment->user_type_ask == 1 ? image_file($comment->avatar) : asset('images/image_default.jpg') }}" alt="" class="avatar avatar-50 no-shadow border-0">
                                        </div>
                                        <div class="col-auto align-self-center">
                                            <h6 class="font-weight-normal mb-1">{{ $comment->user_type_ask == 1 ? $comment->fullname : $comment->name }}</h6>
                                            <p class="text-mute text-secondary">
                                                {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col align-self-center">
                                            {{ ucfirst($comment->ask) }}
                                        </div>
                                    </div>
                                    @if ($comment->user_id_answer !== null)
                                        @php
                                            if ($comment->user_type_answer == 1){
                                                $get_user_answer = App\Profile::where('user_id', $comment->user_id_answer)
                                                ->first();
                                            }else{
                                                $get_user_answer = \Modules\Quiz\Entities\QuizUserSecondary::find
                                                ($comment->user_id_answer);
                                            }
                                        @endphp
                                        <div class="row align-items-center mt-1 ml-1 ">
                                            <div class="col-auto pr-0">
                                                <img src="{{ $comment->user_type_answer == 1 ? image_file($get_user_answer->avatar) : asset('images/image_default.jpg') }}" alt="" class="avatar avatar-50 no-shadow border-0">
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <h6 class="font-weight-normal mb-1">{{ $comment->user_type_answer == 1 ? $get_user_answer->lastname . ' ' .$get_user_answer->firstname : $comment->name }}</h6>
                                                <p class="text-mute text-secondary">
                                                    Trả lời: {{ ucfirst($comment->answer) }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

