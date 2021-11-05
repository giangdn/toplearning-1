<div>
    <div class="student_reviews">
        <div class="row">
            <div class="col-lg-12">
                <div class="review_all120">
                    <form wire:submit.prevent="comment">
                        <div class="form-group comment-box">
                            <textarea class="form-control" rows="5" id="ask" wire:model.lazy="ask_content"></textarea>
                            <input type="hidden" wire:model.lazy="ask_id">
                            @error('content') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        <br>
                        <button type="submit" class="btn_adcart float-right"><i class="fa fa-save"></i> @lang('app.send')</button>
                    </form>

                    @foreach($comments as $comment)
                        <div class="review_item">
                            <div class="review_usr_dt">
                                <img src="{{ $comment->user_type_ask == 1 ? image_file($comment->avatar) : asset('images/image_default.jpg') }}" alt="">
                                <div class="rv1458">
                                    <h4 class="tutor_name1">{{ $comment->user_type_ask == 1 ? $comment->fullname : $comment->name }}</h4>
                                    <span class="time_145">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                            <p class="rvds10">{{ ucfirst($comment->ask) }}</p>
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
                                <div class="review_usr_dt ml-5">
                                    <img src="{{ $comment->user_type_answer == 1 ? image_file($get_user_answer->avatar) : asset('images/image_default.jpg')
                                     }}" alt="">
                                    <div class="rv1458">
                                        <h4 class="tutor_name1">{{ $comment->user_type_answer == 1 ?
                                        $get_user_answer->lastname . ' ' .$get_user_answer->firstname : $comment->name
                                        }}</h4>
                                        {{-- <span class="time_145">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span> --}}
                                        <p class="rvds10">Trả lời: {{ ucfirst($comment->answer) }}</p>
                                    </div>
                                </div>

                            @endif
                        </div>
                    @endforeach
                    {{ $comments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

