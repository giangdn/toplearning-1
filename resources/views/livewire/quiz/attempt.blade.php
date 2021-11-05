@php
    $answers = $question->answers;
@endphp
<div class="ques_item">
    <div class="ques_title">
        <span>{{ $question->name }}</span>
    </div>
    <div class="ui form">
        <div class="grouped fields">
            @if($question->type == "multiple-choise")
                @foreach($answers as $answer)
                <div class="field fltr-radio">
                    <div class="ui radio checkbox">
                        <input type="radio" name="example1" tabindex="0" class="hidden">
                        <label wire:click="chooseAnswer({{$answer->id}})">{{ $answer->title }}</label>
                    </div>
                </div>
                @endforeach
            @else

            @endif
        </div>
    </div>
</div>
