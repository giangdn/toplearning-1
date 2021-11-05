<div>
    <div class="question-item" id="q-{{ $question['id'] }}" data-qid="{{ $question['id'] }}" xmlns:wire="http://www.w3.org/1999/xhtml">
        <input type="hidden" name="q[]" value="{{ $question['id'] }}">
        <div class="row">
            <div class="col-md-2">
                <div class="info">
                    <h3 class="no">{{ trans('backend.question') }} <span class="qno">{{ $question['index'] + 1 }}</span></h3>
                    <div class="questionflag editable"></div>
                </div>
            </div>

            <div class="col-md-10">
                <div class="content">
                    <div class="formulation clearfix">
                        <div class="qtext">
                            <b><span lang="DE">{{ $question['name'] }}</span></b>
                        </div>
                        <div class="ablock">
                            <div class="prompt">
                                @if($question['type'] == 'multiple-choise')
                                    @if ($question['multiple'] == 1)
                                        Chọn một hoặc nhiều đáp án:
                                    @else
                                        Chọn một đáp án:
                                    @endif
                                @endif
                            </div>
                            <div class="answer">

                                @if($question['type'] == 'matching')

                                    @if(isset($question['anwsers']))
                                        @foreach($question['anwsers'] as $index => $answer)
                                            <div class="r{{ $index }}">
                                                <input type="hidden" name="q_{{ $question['id'] }}[]" value="{{ $answer['id'] }}">
                                                <label class="m-l-1">
                                                    <span class="answernumber">{{ $answer['index_text'] }}. </span>
                                                    <span lang="VN">{{ $answer['title'] }}</span>
                                                </label>
                                                <select name="matching_{{ $question['id'] }}[{{ $answer['id'] }}]" class="selected-answer" data-answer="{{ $answer['id'] }}">
                                                    @if(isset($answer['answers']))
                                                        @foreach($answer['answers'] as $op)
                                                            <option value="{{ $op['matching_answer'] }}" >{{ $op['matching_answer'] }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        @endforeach
                                    @endif


                                @endif

                                @if($question['type'] == 'essay')
                                    <input id="qf_{{ $question['id'] }}" type="file" data-answer="{{ $question['id'] }}" class="selected-answer file-essay" accept=".xlsx">
                                    <div>{{ $question['file_essay'] ?? '' }}</div>
                                    <textarea class="form-control selected-answer" name="q_{{ $question['id'] }}[]" rows="5" data-answer="{{ $question['id'] }}">{{ $question['text_essay'] ?? '' }}</textarea>
                                @endif

                                @if($question['type'] == 'multiple-choise')

                                @if(isset($question['anwsers']))
                                    @foreach($question['anwsers'] as $index => $answer)
                                        <div class="r{{ $index }}">
                                            <label for="q{{ $question['id'] }}:choice{{ $index }}" class="m-l-1">
                                                <input type="{{ $question['multiple'] == 1 ? 'checkbox' : 'radio' }}" wire:model="selected" value="{{ $answer['id'] }}" id="q{{ $question['id'] }}:choice{{ $index }}" class="selected-answer" data-answer="{{ $answer['id'] }}">
                                                <span class="answernumber">{{ $answer['index_text'] }}. </span>
                                                <span lang="VN">{{ $answer['title'] }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                @endif

                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
