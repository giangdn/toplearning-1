<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xem câu hỏi</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h5> {!! ($question->name) !!} </h5>
                    </div>
                    @php
                        $answer_text = range('a', 'z');
                    @endphp
                    <div class="col-12 mt-2">
                        @if($question->type == 'essay')
                            <input type="file" class="">
                            <textarea class="form-control" rows="5" placeholder="Nhập đáp án"></textarea>
                        @endif
                        @if($question->type == 'fill_in')
                            @foreach($answers as $ans_key => $answer)
                                <p>
                                    {!! $answer_text[$ans_key] .'. '. $answer->title !!}
                                    <textarea class="form-control" placeholder="Nhập đáp án"></textarea>
                                </p>
                            @endforeach
                        @endif
                        @if($question->type == 'fill_in_correct')
                            @foreach($answers as $ans_key => $answer)
                                <p>
                                    {!! $answer_text[$ans_key] .'. '. $answer->title !!}
                                    <textarea class="form-control" placeholder="Nhập đáp án"></textarea>
                                </p>
                            @endforeach
                        @endif
                        @if($question->type == 'multiple-choise')
                            @if($question->answer_horizontal != 0)
                                <div class="row">
                            @endif
                            @foreach($answers as $ans_key => $answer)
                                @if($question->answer_horizontal != 0)
                                    <div class="col-{{ 12/$question->answer_horizontal }} p-1">
                                @endif
                                    <p>
                                        <input type="{{ $question->multiple == 1 ? 'checkbox' : 'radio' }}">
                                        {!! $answer_text[$ans_key] . ( $answer->title ? '. '. $answer->title : '') !!} <br>
                                        @if($answer->image_answer)
                                            <img src="{{ image_file($answer->image_answer) }}" alt="" class="w-50 img-responsive">
                                        @endif
                                    </p>
                                @if($question->answer_horizontal != 0)
                                    </div>
                                @endif
                            @endforeach
                            @if($question->answer_horizontal != 0)
                                </div>
                            @endif
                        @endif
                        @if($question->type == 'matching')
                            @foreach($answers as $ans_key => $answer)
                                <p>
                                    {!! $answer_text[$ans_key] .'. '. $answer->title  !!}
                                    <select class="form-control">
                                        @foreach($answers as $ans_key => $answer)
                                            <option value="">{{ $answer->matching_answer }}</option>
                                        @endforeach
                                    </select>
                                </p>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans('backend.close') }}</button>
            </div>
        </div>
    </div>
</div>
