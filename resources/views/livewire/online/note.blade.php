<div>
    <div class="student_reviews">
        <div class="row">
            <div class="col-lg-12">
                <div class="review_all120">
                    <form wire:submit.prevent="comment">
                        <div class="form-group comment-box">
                            <textarea class="form-control" rows="5" id="note" wire:model.lazy="note_content"></textarea>
                            <input type="hidden" wire:model.lazy="note">
                            @error('content') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        <br>
                        <button type="submit" class="btn_adcart float-right"><i class="fa fa-save"></i> {{ trans('lacore.save') }}</button>
                    </form>

                    @foreach($comments as $comment)
                        <div class="review_item">
                            <div class="review_usr_dt">
                                <img src="{{ $comment->user_type == 1 ? image_file($comment->avatar) : asset('images/image_default.jpg') }}" alt="">
                                <div class="rv1458">
                                    <h4 class="tutor_name1">{{ $comment->user_type == 1 ? $comment->fullname :
                                    $comment->name }}</h4>
                                    <span class="time_145">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                            <p class="rvds10">{{ ucfirst($comment->note) }}</p>
                            @if ($comment->user_id == getUserId() && $comment->user_type == getUserType())
                                <div class="rpt100">
                                <span><a href="javascript:void(0)"
                                        onclick="confirm('@lang('app.are_you_sure')') || event.stopImmediatePropagation()"
                                        wire:click="deleteComment({{ $comment->id }})"
                                        class="report145">@lang('app.delete')</a></span>
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

