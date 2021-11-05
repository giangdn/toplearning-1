<div class="quiz-block">
    <div class="card block-item" id="info-number-question">
        <div class="card-header">
            <span>{{ trans('backend.question') }}</span>: <span class="font-weight-bold"> <span id="num-question-selected">0</span>{{ '/'. count($questions) }}</span>
        </div>
        <div class="card-body">
        @if (!empty($questions))
            @foreach($questions as $index => $question)
                <a href="javascript:void(0)" class="btn btn-warning select-question
                @if(@$question['selected']) question-selected
                @endif" id="select-q{{ $question['id'] }}"
                data-quiz-page="{{ ceil(($question['index'] + 1) / $quiz->questions_perpage) }}"
                data-id="{{ $question['id'] }}"
                >
                    <span class="thispageholder"></span>
                    <span class="trafficlight"></span>
                    <span class="accesshide">{{ ($question['index'] + 1) }}</span>
                    <div class="@if (@$question['flag'] == 1) flag-item @endif">
                    </div>
                </a>
            @endforeach
        @endif
        </div>
    </div>

    @if(!$attempt_finish)
    <div class="card block-item">
        <div class="card-header">
            <span>{{trans('backend.time_quiz')}}</span>
        </div>
        <div class="card-body">
            <div id="clockdiv"></div>
        </div>
    </div>
    @php
      if(url_mobile()){
        $url_submit = route('module.quiz_mobile.doquiz.submit', ['quiz_id' => $quiz->id, 'part_id' => $part->id, 'attempt_id' => $attempt->id]);
      }else{
        $url_submit = route('module.quiz.doquiz.submit', ['quiz_id' => $quiz->id, 'part_id' => $part->id, 'attempt_id' => $attempt->id]);
      }
    @endphp
        <form action="{{ $url_submit }}" method="post" class="form-ajax text-center" data-success="submit_success">
            <div class="card">
                <div class="card-body">
                    @if(!$attempt_finish)
                        <button type="button" class="btn btn-primary send-quiz"><i class="fa fa-send-o"></i> Nộp bài thi</button>
                    @endif
                </div>
            </div>
        </form>
    @endif

    @if($quiz->webcam_require == 1 && !$attempt_finish)
    <div class="card block-item">
        <div class="card-header">
            <span>Webcam</span>
        </div>
        <div class="card-body text-center">
            <video id="video" width="250" height="110" autoplay></video>
            <canvas id="canvas" width="640" height="480" class="d-none"></canvas>
            <div id="error"></div>
        </div>
    </div>
    @endif

</div>

<script type="text/javascript">
    @if($attempt_finish)
        var countDownDate = null;
        var times_shooting_webcam = null;
        var times_shooting_question = null;
    @else
        var countDownDate = new Date("{{ date('D, d M y H:i:s', $attempt->timestart + 59 * intval($quiz->limit_time)) }}").getTime();
        var timeServer = new Date("{{ date('D, d M y H:i:s') }}");
        var startTime = new Date("{{ date('D, d M y H:i:s') }}");

        @if($quiz->times_shooting_webcam)
            var time_wecam = (countDownDate - startTime)/'{{ intval($quiz->times_shooting_webcam + 1) }}';
            var times_shooting_webcam = Math.floor(time_wecam/1000)*1000;
        @else
            var times_shooting_webcam = null;
        @endif

        @if($quiz->times_shooting_question)
            var time_question = (countDownDate - startTime)/'{{ intval($quiz->times_shooting_question + 1) }}';
            var times_shooting_question = Math.floor(time_question/1000)*1000;
        @else
            var times_shooting_question = null;
        @endif
    @endif

    @if($part->end_date)
        var enddate = new Date("{{ date('D, d M y H:i:s', strtotime($part->end_date) + 59) }}").getTime();
    @else
        var enddate = null;
    @endif


</script>
