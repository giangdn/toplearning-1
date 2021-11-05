@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.forum'))

@section('content')
    @php
        $comments = $forum_category->comments;
        /*$comments = $comments->sortByDesc('created_at');*/
    @endphp
    <div class="container">
        <h6>{{ $forum_category->title }}</h6>
        <p class="text-justify">{!! $forum_category->content !!}</p>

        <h6>@lang('app.comment') ({{ $comments->count() }})</h6>
        <div id="list-comment">
            @if($comments)
            @foreach($comments as $comment)
                @php
                    $like_comment = \Modules\Forum\Entities\ForumUserLikeComment::checkLikeComment($forum_category->id, $comment->id, 1);
                    $dislike_comment = \Modules\Forum\Entities\ForumUserLikeComment::checkLikeComment($forum_category->id, $comment->id, 2);
                    $count_like_comment = \Modules\Forum\Entities\ForumUserLikeComment::countLikeOrDisLike($forum_category->id, $comment->id, 1);
                    $count_dislike_comment = \Modules\Forum\Entities\ForumUserLikeComment::countLikeOrDisLike($forum_category->id, $comment->id, 2);
                @endphp
                <div class="card shadow border-0 mt-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto pr-0">
                                <img src="{{ \App\Profile::avatar($comment->created_by) }}" alt="" class="avatar avatar-40 no-shadow border-0">
                            </div>
                            <div class="col-auto align-self-center">
                                <h6 class="font-weight-normal mb-1">{{ \App\Profile::fullname($comment->created_by) }}</h6>
                                <p class="text-mute text-secondary">
                                    {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col align-self-center text-justify">
                                {!! ucfirst($comment->comment) !!}
                            </div>
                        </div>
                        <div class="row" id="commit{{ $comment->id }}">
                            <span class="m-auto like-comment-video text-muted" data-comment_id="{{ $comment->id }}">
                                <i class="material-icons vm {{ $like_comment ? 'text-primary' : '' }}" style="font-size: 15px;">thumb_up</i>
                                <span class="like-comment-{{ $comment->id }}">{{ $count_like_comment }}</span>
                            </span>
                            <span class="m-auto dislike-comment-video text-muted" data-comment_id="{{ $comment->id }}">
                                <i class="material-icons vm {{ $dislike_comment ? 'text-primary' : '' }}" style="font-size: 15px;">thumb_down</i>
                                <span class="dislike-comment-{{ $comment->id }}">{{ $count_dislike_comment }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
            @endif
        </div>
        <br>
        <input type="color" class="avatar avatar-40 change-color" value="fff"> {{ data_locale('Chọn màu', 'Choose color') }}
        <div class="form-group">
            <textarea class="form-control" type="text" name="comment" id="comment" rows="5" placeholder="{{ trans('app.content') }}"></textarea>
        </div>
        <button type="button" class="btn btn-primary float-right" id="add-comment"><i class="fa fa-save"></i> @lang('app.send')</button>
        <br>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        $('.change-color').on('change', function () {
            var color = $(this).val();
            $('#comment').css('color', color);
        });

        var is_like_comment = 0;
        var is_dislike_comment = 0;
        var type_comment = '';
        $("#list-comment").on('click', '.like-comment-video', function () {
            var comment_id = $(this).data('comment_id');
            $('#commit' + comment_id).find('i').removeClass('text-primary');

            is_like_comment += 1;
            if (is_like_comment % 2 == 0) {
                type_comment = '';
                $(this).find('i').removeClass('text-primary');
            } else {
                type_comment = 'like';
                $(this).find('i').addClass('text-primary');
            }
            $.ajax({
                type: 'POST',
                url: '{{ route('module.frontend.forums.comment.like_dislike', ['id' => $forum_category->id]) }}',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'type': type_comment,
                    'comment_id': comment_id,
                }
            }).done(function (data) {
                $('.like-comment-' + data.comment_id).text(data.count_like_comment);
                $('.dislike-comment-' + data.comment_id).text(data.count_dislike_comment);
                return false;
            }).fail(function (data) {
                return false;
            });
        });

        $("#list-comment").on('click', '.dislike-comment-video', function () {
            var comment_id = $(this).data('comment_id');
            $('#commit' + comment_id).find('i').removeClass('text-primary');

            is_dislike_comment += 1;
            if (is_dislike_comment % 2 == 0) {
                type_comment = '';
                $(this).find('i').removeClass('text-primary');
            } else {
                type_comment = 'dislike';
                $(this).find('i').addClass('text-primary');
            }

            $.ajax({
                type: 'POST',
                url: '{{ route('module.frontend.forums.comment.like_dislike', ['id' => $forum_category->id]) }}',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'type': type_comment,
                    'comment_id': comment_id,
                }
            }).done(function (data) {
                $('.like-comment-' + data.comment_id).text(data.count_like_comment);
                $('.dislike-comment-' + data.comment_id).text(data.count_dislike_comment);
                return false;
            }).fail(function (data) {
                return false;
            });
        });

        /*$('#comment').emojioneArea({
            search: false,
            pickerPosition: "bottom"
        });*/

        var item = '';
        $('#add-comment').on('click', function () {
            var color = $('.change-color').val();
            var comment = '<p style="color: '+color+'">' + $('#comment').val() + '</p>';

            if($('#comment').val().length > 0){
                $.ajax({
                    url: "{{ route('module.frontend.forums.comment',['id'=>$forum_category->id]) }}",
                    type: 'post',
                    data: {
                        comment: comment,
                    },
                }).done(function (data) {
                    if (data.status == 'warning'){
                        show_message(data.message, data.status);
                        return false;
                    }

                    $("#comment").val('');

                    item += '<div class="card shadow border-0 mt-3">' +
                        '<div class="card-body">' +
                        '<div class="row align-items-center">' +
                        '<div class="col-auto pr-0">' +
                        '<img src="'+ data.img_user +'" alt="" class="avatar avatar-40 no-shadow border-0">' +
                        '</div>' +
                        '<div class="col-auto align-self-center">' +
                        '<h6 class="font-weight-normal mb-1">' + data.name_user + '</h6>' +
                        '<p class="text-mute text-secondary">' + data.time_created + '</p>' +
                        '</div>' +
                        '</div>' +
                        '<div class="row">' +
                        '<div class="col align-self-center">' + data.comment + '</div>' +
                        '</div>' +
                        '<div class="row" id="commit'+ data.comment_id +'">' +
                        '<span class="m-auto like-comment-video text-muted" data-comment_id="'+ data.comment_id +'">' +
                        '<i class="material-icons vm" style="font-size: 15px;">thumb_up</i>' +
                        '<span class="like-comment-'+ data.comment_id +'"></span>' +
                        '</span>' +
                        '<span class="m-auto dislike-comment-video text-muted" data-comment_id="'+ data.comment_id +'">' +
                        '<i class="material-icons vm" style="font-size: 15px;">thumb_down</i>' +
                        '<span class="dislike-comment-'+ data.comment_id +'"></span>' +
                        '</span>' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                    $("#list-comment").append(item);

                    item = '';
                    return false;
                }).fail(function (data) {
                    show_message('Lỗi dữ liệu', 'error');
                    return false;
                });
            }else{
                show_message('Mời nhập liệu', 'warning');
                return false;
            }
        });
    </script>
@endsection
