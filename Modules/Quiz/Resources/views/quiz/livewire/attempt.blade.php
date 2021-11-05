<div>
    <div class="row" id="quiz-content" xmlns:wire="http://www.w3.org/1999/xhtml">

        <div class="col-md-3">
            {{--@include('quiz::quiz.component.sidebar')--}}
        </div>

        <div class="col-md-9 quiz-{{ $attempt->quiz_id }}">
            @if($current_page <= $total_page)
            <form id="form-question" wire:submit.prevent="submit">
                <div class="card">
                    <div class="card-header">
                        <div class="text-center mb-1 button-page">
                            <button type="submit" class="btn btn-info" @if($current_page - 1 <= 0) disabled @endif><i class="fa fa-mail-reply"></i> {{ trans('backend.back') }}</button> |
                            <button type="submit" class="btn btn-info">{{ trans('backend.next') }} <i class="fa fa-mail-forward"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="questions">
                            @foreach($questions as $question)
                                @livewire('quiz::livewire.attempt.question', ['question' => $question], key($question['id']))
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-center mt-1 button-page">
                            <button type="button" class="btn btn-info" @if($current_page - 1 <= 0) disabled @endif><i class="fa fa-mail-reply"></i> {{ trans('backend.back') }}</button> |
                            <button type="button" class="btn btn-info">{{ trans('backend.next') }} <i class="fa fa-mail-forward"></i></button>
                        </div>
                    </div>

                    <div id="loading"></div>
                </div>
            </form>
            @endif
            @if($current_page > $total_page)
            <form action="" method="post" class="form-ajax text-center">
                <div class="card">
                    <div class="card-header">
                        Chúc mừng bạn đã hoàn thành kỳ thi <b>{{ $quiz->name }}</b>
                    </div>
                    <div class="card-body">
                        @if(!$attempt_finish)
                            <p>Để nộp bài vui lòng nhấn nút <b>Nộp bài thi</b></p>
                            <p>Để xem lại bài thi vui lòng nhấn nút <b>Xem lại bài</b></p>
                        @else
                            <p>Bài thi của bạn đã được nộp, nhấn nút <b>Xem lại bài</b> để xem lại bài làm của mình</p>
                        @endif
                        <p></p>
                        <button type="button" class="btn btn-info" wire:click="backPage"><i class="fa fa-mail-reply"></i> Xem lại bài</button>
                        @if($attempt_finish)
                            <a href="{{ route('module.quiz.doquiz.index', [ 'quiz_id' => $quiz->id, 'part_id' => $part->id]) }}" class="btn btn-info"><i class="fa fa-mail-reply"></i> Trở về màn hình kỳ thi</a>
                        @endif
                        @if(!$attempt_finish)
                            <button class="btn btn-primary" wire:click="submit"><i class="fa fa-send-o"></i> Nộp bài thi</button>
                        @endif
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>