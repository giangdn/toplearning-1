<div class="ques_item">
    <div class="ques_title">
        <span>{!! $question->name !!}</span>
    </div>
    <div class="ui form">
        <div class="grouped fields">
            @if($question->type == "multiple-choise")
                @php
                    $alpha = "a";
                @endphp
                @foreach($question->answers as $answer)
                    @if($question->multiple == 1)
                        <div class="field fltr-radio">
                            <div class="ui checkbox {{ $answer->selected == 1 ? "checked" : "" }}">
                                <input type="checkbox" name="{{ $answer->id }}" tabindex="0" class="hidden" {{ $answer->selected == 1 ? "checked" : "" }}>
                                <label wire:click="chooseMulti({{ $answer->id }})">{{ $alpha }}.{{ $answer->title }}</label>
                            </div>
                        </div>
                    @else
                        <div class="field fltr-radio">
                            <div class="ui radio checkbox {{ $answer->selected == 1 ? "checked" : "" }}">
                                <input type="radio" name="answer_{{ $answer->id }}" tabindex="0" class="hidden" {{ $answer->selected == 1 ? "checked" : "" }}>
                                <label wire:click="chooseOne({{ $answer->id }},{{ $answer->question_id }})">{{ $alpha }}.{{ $answer->title }}</label>
                            </div>
                        </div>
                    @endif
                    @php
                        $alpha++;
                    @endphp
                @endforeach
            @else
                <div>
                    <div class="ui form swdh30">
                        <div class="field">
                            <textarea rows="5" wire:keydown="doEssay({{ $question->id }})" placeholder="Pls explain" wire:model.lazy="essay">{{ $question->text_essay }}</textarea>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
