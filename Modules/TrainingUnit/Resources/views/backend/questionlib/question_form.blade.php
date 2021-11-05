@extends('layouts.backend')

@section('page_title', $page_title)

@section('header')

@endsection

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.training_unit.questionlib') }}"> Câu hỏi đề xuất</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.training_unit.questionlib.question', ['id'=> $category->id]) }}">{{ $category->name }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <form action="{{ route('module.training_unit.questionlib.save_question', ['id' => $category->id]) }}" method="post" class="form-ajax">
                        <input type="hidden" name="id" value="{{$model->id}}">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4 text-right">
                                <div class="btn-group act-btns">
                                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                                    <a href="{{ route('module.training_unit.questionlib.question', ['id'=> $category->id]) }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.question') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>Loại<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="type" id="type1" value="essay" {{ $model->type ? 'disabled' : '' }} {{ ($model->type == 'essay') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type1">Tự luận</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input required class="form-check-input" type="radio" name="type" id="type2" value="multiple-choise" {{ $model->type ? 'disabled' : '' }} {{ ($model->type == 'multiple-choise') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type2">Trắc nghiệm</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>Chọn<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="multiple" {{ $model->multiple == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="multiple">{{trans("backend.select_all")}}</label>
                                            <input type="hidden" name="multiple" class="check-multiple" value="{{ $model->multiple ? $model->multiple : '0' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{trans('backend.answer_question')}}</label>
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-3 text-right"> <a href="javascript:void(0)" class="btn btn-info" id="add-answer" {{ $model->type === 'essay' ? 'hidden' : '' }}> {{ trans('backend.add_answer_question') }}</a> </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label"></div>
                                    <div class="col-md-6" id="anwser-list">
                                        @if(isset($answers))
                                            @foreach($answers as $answer)
                                            <div class="anwser-item">
                                                <div class="row">
                                                    <input type="hidden" name="ans_id[]" class="ans-id" value="{{ $answer->id }}">
                                                    <div class="col-sm-11">
                                                        <input type="text" class="form-control" name="answer[]" value="{{ $answer->title }}" placeholder="{{trans('backend.answer_question')}}">
                                                        <input type="checkbox" class="correct-answer" {{ $answer->correct_answer == 1 ? 'checked' : '' }}> {{ trans('backend.correct_answer') }}
                                                        <input type="hidden" name="correct_answer[]" class="check-correct-answer" value="{{ $answer->correct_answer }}">
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <a href="javascript:void(0)" class="text-danger remove-anwser" data-ans="{{ $answer->id }}">Xóa</a>
                                                    </div>
                                                </div>
                                                <br>
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            var remove_answer = "{{ route('module.training_unit.questionlib.remove_question_answer', ['id' => $category->id]) }}";
        </script>
    </div>
    <template id="anwser-template">
        <div class="anwser-item">
            <div class="row">
                <input type="hidden" name="ans_id[]" class="ans-id" value="">
                <div class="col-sm-11">
                    <input type="text" class="form-control" name="answer[]" placeholder="{{trans('backend.answer_question')}}">

                    <input type="checkbox" class="correct-answer"> {{ trans('backend.correct_answer') }}

                    <input type="hidden" name="correct_answer[]" class="check-correct-answer" value="0">

                </div>
                <div class="col-sm-1">
                    <a href="javascript:void(0)" class="text-danger remove-anwser">Xóa</a>
                </div>
            </div>
            <br>
        </div>
    </template>

    <script type="text/javascript">

        var anwser_template = document.getElementById('anwser-template').innerHTML;
        $("#add-answer").on('click', function () {
            $("#anwser-list").append(anwser_template);
        });

        $('#anwser-list').on('click', '.remove-anwser', function(){
            $(this).closest('.anwser-item').remove();
            var ans_id = $(this).data('ans');

            $.ajax({
                url: remove_answer,
                type: 'post',
                data: {
                    ans_id: ans_id,
                },
            }).done(function(data) {

                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });

        $('#anwser-list').on('click', '.is-text', function(){

            if($(this).is(':checked')){
                $(this).closest('.anwser-item').find('.check-is-text').val(1);
            }else{
                $(this).closest('.anwser-item').find('.check-is-text').val(0);
            }
        });

        $('#anwser-list').on('click', '.correct-answer', function(){

            if($(this).is(':checked')){
                $(this).closest('.anwser-item').find('.check-correct-answer').val(1);
            }else{
                $(this).closest('.anwser-item').find('.check-correct-answer').val(0);
            }
        });

        $('#multiple').on('click', function(){

            if($(this).is(':checked')){
                $(this).closest('.form-check').find('.check-multiple').val(1);
            }else{
                $(this).closest('.form-check').find('.check-multiple').val(0);
            }
        });

        $('input[name=type]').on('change', function(){

            if($('#type1').is(':checked')){
                $('#add-answer').hide();
            }else{
                $('#add-answer').show();
            }

        });

        function replacement_template( template, data ){
            return template.replace(
                /{(\w*)}/g,
                function( m, key ){
                    return data.hasOwnProperty( key ) ? data[ key ] : "";
                }
            );
        }
    </script>
@stop
