<div>
    <div class="student_comment_situation">
        <div class="row all_comment_situation">
            <div class="col-lg-12">
                <div class="review_all120">
                    @foreach($comments as $comment)
                        <div class="review_item">
                            <div class="review_usr_dt">
                                <img src="{{ image_file($comment->avatar) }}" alt="">
                                <div class="rv1458">
                                    <h4 class="tutor_name1">{{ $comment->fullname }}</h4>
                                    <ul>
                                        <li>
                                            <span class="time_145">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                                        </li>
                                        <li class="mr-1">
                                            <span class="time_145" id="view_like_{{ $comment->id }}">{{ $comment->like_comment }}</span>
                                        </li>
                                        <li class="like_comment" id="like_comment_situation_{{ $comment->id }}"  onclick="likeComment({{ $comment->id }})">
                                            @php
                                                $profile = \Modules\TopicSituations\Entities\LikeCommentSituation::where('user_id',\Auth::id())->whereNull('reply_comment_id')->first();
                                                if ($profile !== null) {
                                                    $get_profile_like_situation = json_decode($profile->comment_id);
                                                }
                                            @endphp
                                            @if (!empty($get_profile_like_situation) && in_array($comment->id, $get_profile_like_situation))
                                                <span style="color: blue"><i class="fas fa-thumbs-up"></i></span>
                                            @else
                                                <span><i class="far fa-thumbs-up"></i></span>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="comment_situation mt-2 row m-0">
                                @if ($comment->user_id == auth()->id())
                                    <div class="col-md-10 col-12 form-inline">
                                        <p class="rvds10">{{ ucfirst($comment->comment) }}</p>
                                    </div>
                                    <div class="rpt100 col-md-2 col-12 edit_delete">
                                        <span><a href="javascript:void(0)"
                                            onclick="confirm('@lang('app.are_you_sure')') || event.stopImmediatePropagation()"
                                            wire:click="deleteComment({{ $comment->id }})"
                                            class="report145">@lang('app.delete')</a></span>
                                        <span><a href="javascript:void(0)" wire:click="editComment({{ $comment->id }})" class="report145">@lang('app.edit')</a></span>
                                    </div>
                                @else
                                    <div class="col-md-10 col-10 form-inline pr-0">
                                        <p class="rvds10">{{ ucfirst($comment->comment) }}</p>
                                    </div>
                                    <div class="col-md-2 col-2 text-right act-btns">
                                        <div class="pull-right">
                                            <span style="cursor: pointer" onclick="reply({{ $comment->id }})">
                                                <i class="fas fa-comment"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="reply_comment_{{ $comment->id }}">
                                        <form class="row form_reply_comment" id="form_reply_{{ $comment->id }}" wire:submit.prevent="reply(Object.fromEntries(new FormData($event.target)))" style="display: none">
                                            <div class="form-group col-md-10">
                                                <textarea class="form-control" rows="2" id="text_{{ $comment->id }}" wire:model.lazy="reply_comment"></textarea>
                                                <input type="hidden" wire:model.lazy="reply_comment_id">
                                                <input type="hidden" name="get_comment_id" value="{{ $comment->id }}">
                                            </div>
                                            <div class="col-md-2 p-0">
                                                <button type="submit" class="btn_adcart submit_form_reply"><i class="fa fa-save"></i> @lang('app.send')</button>
                                            </div> 
                                        </form>
                                    </div>
                                @endif
                            </div>
                            @php
                                // $get_reply_comments = Modules\TopicSituations\Entities\ReplyCommentSituation::where('comment_id',$comment->id)->paginate(2, ['*'], 'comments');
                                $get_reply_comments = Modules\TopicSituations\Entities\ReplyCommentSituation::where('comment_id',$comment->id)->get();
                            @endphp
                            @if (!$get_reply_comments->isEmpty())
                                @foreach ($get_reply_comments as $get_reply_comment)
                                @php
                                    $get_user_avatar = App\Profile::where('user_id',$get_reply_comment->user_id)->first()
                                @endphp
                                <div class="all_reply">
                                    <div class="info_user_reply">
                                        <div class="review_usr_dt">
                                            <img src="{{ image_file($get_user_avatar->avatar) }}" width="50px" height="50px" alt="">
                                            <div class="rv1458">
                                                <h4 class="tutor_name1">{{ $get_user_avatar->lastname }} {{ $get_user_avatar->firstname }}</h4>
                                                <ul>
                                                    <li>
                                                        <span class="time_145">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                                                    </li>
                                                    <li class="mr-1">
                                                        <span class="time_145" id="view_like_reply_{{ $get_reply_comment->id }}">{{ $get_reply_comment->like }}</span>
                                                    </li>
                                                    <li class="like_comment" id="like_reply_comment_situation_{{ $get_reply_comment->id }}"  onclick="likeReplyComment({{ $get_reply_comment->id }})">
                                                        @php
                                                            $profile_reply = \Modules\TopicSituations\Entities\LikeCommentSituation::where('user_id',\Auth::id())->whereNull('comment_id')->first();
                                                            if ($profile_reply !== null) {
                                                                $get_profile_like_situation = json_decode($profile_reply->reply_comment_id);
                                                            }
                                                        @endphp
                                                        @if (!empty($get_profile_like_situation) && in_array($get_reply_comment->id, $get_profile_like_situation))
                                                            <span style="color: blue"><i class="fas fa-thumbs-up"></i></span>
                                                        @else
                                                            <span><i class="far fa-thumbs-up"></i></span>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="get_reply_comment">
                                        <p class="rvds10">{{ $get_reply_comment->comment }}</p>
                                    </div>
                                </div>
                            @endforeach
                            {{-- {{ $get_reply_comments->appends(['comments'=> $comments->currentPage()])->links() }} --}}
                            {{-- {{ $get_reply_comments->links() }} --}}
                            @endif
                        </div>
                    @endforeach
                    {{ $comments->links() }}
                    {{-- {{ $comments->appends(['get_reply_comments'=> $get_reply_comments->currentPage()])->links() }} --}}

                    <form wire:submit.prevent="comment">
                        <div class="form-group comment-box">
                            <textarea class="form-control" rows="5" id="comment" wire:model.lazy="comment"></textarea>
                            <input type="hidden" wire:model.lazy="comment_id">
                            @error('comment') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        <br>
                        <button type="submit" class="btn_adcart float-right"><i class="fa fa-save"></i> @lang('app.send')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function likeComment(id) {
        $.ajax({
            url: "{{ route('frontend.like.comment_situation') }}",
            type: 'post',
            data: {
                id: id,
            }
        }).done(function(data) {
            console.log(data);
            if (data.check_like == 1) {
                $('#like_comment_situation_'+id).html('<span style="color: blue"><i class="fas fa-thumbs-up"></i></span>');
            } else {
                $('#like_comment_situation_'+id).html('<i class="far fa-thumbs-up"></i>');
            }
            $('#view_like_'+id).html(data.view_like);
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }

    function likeReplyComment(id) {
        $.ajax({
            url: "{{ route('frontend.like.reply.comment_situation') }}",
            type: 'post',
            data: {
                id: id,
            }
        }).done(function(data) {
            console.log(data);
            if (data.check_like == 1) {
                $('#like_reply_comment_situation_'+id).html('<span style="color: blue"><i class="fas fa-thumbs-up"></i></span>');
            } else {
                $('#like_reply_comment_situation_'+id).html('<i class="far fa-thumbs-up"></i>');
            }
            $('#view_like_reply_'+id).html(data.view_like);
            return false;
        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }

    var action = 1;
    $(".submit_form_reply").click(function() {
        action = 1;
    });
    function reply(id) {
        $('#text_'+id).val('');
        if(action == 1) {
            $('#form_reply_'+id).css('display','flex');
            action = 2;
        } else {
            $('#form_reply_'+id).css('display','none');
            action = 1;
        }
    }
</script>

