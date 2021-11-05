@extends('themes.mobile.layouts.app')

@section('page_title', 'Góp ý')

@section('content')
    <div class="container-fluid suggest-container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="ibox-content suggest-container mt-2">
                    <div class="comment-suggest">
                        <div class="card border-primary mb-3">
                            <div class="card-header text-white bg-primary">
                                {{ $suggest->name }}
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{ $suggest->content }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12" id="list-comment">
                                @if($comments)
                                    @foreach($comments as $comment)
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        {{ $profile->lastname . ' ' . $profile->firstname }} <br>
                                                        <div class="time-comment">
                                                            <time>{{ trans('app.time') .' '. get_date($comment->created_at, 'H:i:s d/m/Y') }}</time>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">{{ $comment->content }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea class="form-control content" name="content" rows="5" placeholder="{{ trans('app.content') }}"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-9">
                            </div>
                            <div class="col-md-3 text-right">
                                <button type="text" class="btn btn-primary" id="add-comment">{{ trans('app.comment') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        var item = '';
        $('#add-comment').on('click', function () {
            var content = $('.content').val();

            if (content.length > 0){
                $.ajax({
                    url: "{{ route('module.suggest.save_comment', ['id' => $suggest->id]) }}",
                    type: 'post',
                    data: {
                        content: content,
                    },
                }).done(function (data) {
                    $("textarea[name=content]").val('');

                    item += '<div class="card mb-3">' +
                        '<div class="card-header">' +
                        '<div class="row">' +
                        '<div class="col-md-6">' +
                        data.user +
                        '</div>' +
                        '<div class="col-md-6">' +
                        ' <div class="time-comment text-right">' +
                        '<time> {{trans("backend.time")}}: ' + data.created_at2 + '</time>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="card-body">' +
                        '<p class="card-text">' + data.content + '</p>' +
                        '</div>' +
                        '</div>';

                    $("#list-comment").append(item);

                    item = '';
                    return false;
                }).fail(function (data) {
                    show_message('Lỗi dữ liệu', 'error');
                    return false;
                });
            }else {
                show_message('Mời nhập nội dung', 'error');
                return false;
            }
        });
    </script>
@endsection
