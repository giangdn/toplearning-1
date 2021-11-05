<div>
    <div class="student_reviews">
        <div class="row">
            <div class="col-lg-5 p-0">
                <div class="reviews_left">
                    <h3>@lang('app.rating')</h3>
                    <div class="total_rating">
                        <div class="_rate001 rating_badge">{{ $avg_star }}</div>
                        <div class="rating-box">
                            @php
                                $isRating = \Modules\Offline\Entities\OfflineRating::getRating($course_id, auth()->id());
                            @endphp
                            
                            @for ($i = 1; $i < 6; $i++)
                                <span class="rating-star 
                                    @if(!$isRating) empty-star rating 
                                    @elseif($isRating && $isRating->num_star >= $i) full-star 
                                    @endif" data-value="{{ $i }}">
                                </span>
                            @endfor
                        </div>
                        <div class="_rate002">{{ $isRating ? "Bạn đã đánh giá" : "" }}</div>
                    </div>
                    <div class="_rate003">
                        <div class="_rate004">
                            @php
                                $star5 = \Modules\Offline\Entities\OfflineRating::getRatingValue($course_id,5);
                            @endphp
                            <div class="progress progress1">
                                <div class="progress-bar w-{{ $star5 }}" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="rating-box">
                                @for ($i = 0; $i < 5; $i++)
                                    <span class="rating-star full-star"></span>
                                @endfor
                            </div>
                            <div class="_rate002">{{ $star5 }}%</div>
                        </div>
                        <div class="_rate004">
                            <div class="progress progress1">
                                @php
                                    $star4 = \Modules\Offline\Entities\OfflineRating::getRatingValue($course_id,4);
                                @endphp
                                <div class="progress-bar w-{{ $star4 }}" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="rating-box">
                                @for ($i = 0; $i < 5; $i++)
                                    <span class="rating-star @if ($i < 4)
                                        full-star
                                    @else
                                        empty-star
                                    @endif "></span>
                                @endfor
                            </div>
                            <div class="_rate002">{{ $star4 }}%</div>
                        </div>
                        <div class="_rate004">
                            <div class="progress progress1">
                                @php
                                    $star3 = \Modules\Offline\Entities\OfflineRating::getRatingValue($course_id,3);
                                @endphp
                                <div class="progress-bar w-{{ $star3 }}" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="rating-box">
                                @for ($i = 0; $i < 5; $i++)
                                    <span class="rating-star @if ($i < 3)
                                        full-star
                                    @else
                                        empty-star
                                    @endif "></span>
                                @endfor
                            </div>
                            <div class="_rate002">{{ $star4 }}%</div>
                        </div>

                        <div class="_rate004">
                            <div class="progress progress1">
                                @php
                                    $star2 = \Modules\Offline\Entities\OfflineRating::getRatingValue($course_id,2);
                                @endphp
                                <div class="progress-bar w-{{ $star2 }}" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="rating-box">
                                @for ($i = 0; $i < 5; $i++)
                                    <span class="rating-star @if ($i < 2)
                                        full-star
                                    @else
                                        empty-star
                                    @endif "></span>
                                @endfor
                            </div>
                            <div class="_rate002">{{ $star2 }}%</div>
                        </div>
                        <div class="_rate004">
                            <div class="progress progress1">
                                @php
                                    $star1 = \Modules\Offline\Entities\OfflineRating::getRatingValue($course_id,1);
                                @endphp
                                <div class="progress-bar w-{{ $star1 }}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="rating-box">
                                @for ($i = 0; $i < 5; $i++)
                                    <span class="rating-star @if ($i < 1)
                                        full-star
                                    @else
                                        empty-star
                                    @endif "></span>
                                @endfor
                            </div>
                            <div class="_rate002">{{ $star1 }}%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="review_right">
                    <div class="review_right_heading 1">
                        <h3>@lang('app.comment') ({{ $comments->total() }})</h3>
                        <div class="review_search">
                            <input class="rv_srch" type="text" placeholder="Tìm bình luận ...">
                            <button class="rvsrch_btn"><i class='uil uil-search'></i></button>
                        </div>
                    </div>
                </div>

                <div class="review_all120">
                    <form wire:submit.prevent="comment">
                        <div class="form-group comment-box">
                            <textarea class="form-control" rows="5" id="comment" wire:model.lazy="content"></textarea>
                            <input type="hidden" wire:model.lazy="comment_id">
                            @error('content') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        <br>
                        <button type="submit" class="btn_adcart float-right"><i class="fa fa-save"></i> @lang('app.send')</button>
                    </form>

                    @foreach($comments as $comment)
                        <div class="review_item">
                            <div class="review_usr_dt">
                                <img src="{{ image_file($comment->avatar) }}" alt="">
                                <div class="rv1458">
                                    <h4 class="tutor_name1">{{ $comment->fullname }}</h4>
                                    <span class="time_145">
                                        {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                                        @if ($comment->created_at != $comment->updated_at)
                                            @lang('app.edited')
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <p class="rvds10">{{ ucfirst($comment->content) }}</p>
                            @if ($comment->user_id == auth()->id())
                                <div class="rpt100">
                                <span><a href="javascript:void(0)"
                                         onclick="confirm('@lang('app.are_you_sure')') || event.stopImmediatePropagation()"
                                         wire:click="deleteComment({{ $comment->id }})"
                                         class="report145">@lang('app.delete')</a></span>
                                    <span><a href="javascript:void(0)" wire:click="editComment({{ $comment->id }})" class="report145">@lang('app.edit')</a></span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <div class="row comment-paging">
                        <div class="col-4 offset-4">
                            {{ $comments->links() }}
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

