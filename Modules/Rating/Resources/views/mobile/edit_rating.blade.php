@extends('themes.mobile.layouts.app')

@section('page_title', 'Đánh giá sau khóa học')

@section('header')
<link rel="stylesheet" href="{{ asset('styles/module/rating/css/rating.css') }}">
@endsection

@section('content')
<div class="container-fluid">
   {{-- <ol class="breadcrumb" style="background: white;margin-bottom: 0;" >
        <li>
            <a href="/"><i class="glyphicon glyphicon-home"></i> &nbsp;Trang chủ</a>
        </li>
        <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">»</li>
        <li>
            <a href="{{ $type == 1 ? route('module.online') : route('module.offline') }}"> {{ trans('backend.course') }} {{ $type == 1 ? 'Offline' : 'Tập trung' }}</a>
        </li>
        <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">»</li>
        <li>
            <a href="{{ $type == 1 ? route('module.online.detail', ['id' => $item->id]) : route('module.offline.detail', ['id' => $item->id]) }}"> {{ $item->name }}</a>
        </li>
        <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">»</li>
        <li>{{ trans('backend.assessments') }}</li>
    </ol>--}}
    <form id="form-rating" action="{{ route('module.rating.save_rating_course') }}" method="post" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="course_id" value="{{ $item->id }}">
        <input type="hidden" name="template_id" value="{{ $item->template_id }}">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="text-center"><h5> ĐÁNH GIÁ SAU KHÓA HỌC</h5></div>
            </div>
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="control-label col-sm-5"><b>{{ trans('backend.course_code') }}:</b></label>
                            <div class="col-sm-7">
                                {{ $item->code }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-5"><b>{{trans('backend.start_date')}}:</b></label>
                            <div class="col-sm-7">
                                {{ get_date($item->start_date) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-5"><b>Hạn đăng ký:</b></label>
                            <div class="col-sm-7">
                                {{ get_date($item->register_deadline) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="control-label col-sm-5"><b>{{ trans('backend.course_name') }}:</b></label>
                            <div class="col-sm-7">
                                {{ $item->name }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-5"><b>{{trans('backend.end_date')}}:</b></label>
                            <div class="col-sm-7">
                                {{ get_date($item->end_date) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" id="custom-template">
                        @foreach($rating_course_categories as $cate_key => $category_template)
                            @php
                                $question = $rating_course_question($category_template->id);
                            @endphp
                            <input type="hidden" name="category_id[]" value="{{ $category_template->id }}">
                            <div class="item-category" data-position="{{ $cate_key }}">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="border" style="color: white; background: #0c8281; padding: 5px 5px; font-weight: bold; size: 1em">{{ $category_template->category_name }}</div>
                                    </div>
                                </div>
                                @foreach($question as $ques_key => $ques)
                                    @php
                                        $answer = $rating_course_answer($ques->id);
                                    @endphp
                                    <input type="hidden" name="question_id[{{ $category_template->id }}][]" value="{{ $ques->id }}">
                                    <div class="item-criteria" data-point= "{{ $ques_key }}">
                                        <div class="card">
                                            <div class="card-header border">
                                                {{ $ques->question_name }}
                                            </div>
                                            <div class="card-body border">
                                                @if($ques->type == 'essay')
                                                    <div class="item-answer-text-{{ $cate_key }}-{{ $ques_key }}">
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <textarea @if(isset($rating_course)) {{ $rating_course->send == 1 ? 'disabled' : '' }} @endif name="answer_essay[{{ $category_template->id }}][{{$ques->id}}]" rows="3" class="form-control">{{ $ques->answer_essay }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    @foreach($answer as $ans_key => $ans)
                                                        <input type="hidden" name="answer_id[{{ $category_template->id }}][{{$ques->id}}][]" value="{{ $ans->id }}">
                                                        <div class="item-answer" data-ans="{{ $ans->id }}" >
                                                            <div class="form-group row">
                                                                <div class="col-sm-12 custom-answer-rating" >
                                                                    @if($ques->multiple == 1)
                                                                        <input @if(isset($rating_course)) {{ $rating_course->send == 1 ? 'disabled' : '' }} @endif class="check-is-text" type="checkbox" name="is_check[{{ $category_template->id }}][{{ $ques->id }}][{{ $ans->id }}]" value="{{ $ans->is_check }}" {{ ($ans->is_check == 1) ? 'checked' : '' }}> {{ $ans->answer_name }}
                                                                    @else
                                                                        <input @if(isset($rating_course)) {{ $rating_course->send == 1 ? 'disabled' : '' }} @endif class="radio-is-text" type="radio" name="check[{{ $category_template->id }}][{{$ques->id}}]" value="{{ $ans->id }}" {{ ($ans->is_check == 1) ? 'checked' : '' }}> {{ $ans->answer_name }}
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12" >
                                                                <input @if(isset($rating_course)) {{ $rating_course->send == 1 ? 'disabled' : '' }} @endif type="text" name="text_answer[{{ $category_template->id }}][{{$ques->id}}][{{$ans->id}}]" value="{{ $ans->text_answer }}" class="form-control input_answer_text" {{ $ans->is_text == 1 ? '' : 'hidden' }}>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <p></p>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary" @if(isset($rating_course)) {{ $rating_course->send == 1 ? 'hidden' : '' }} @endif>{{trans('backend.save')}}</button>
                <button type="submit" id="send" class="btn btn-info" @if(isset($rating_course)) {{ $rating_course->send == 1 ? 'disabled' : '' }} @endif> {{ isset($rating_course) ? $rating_course->send == 1 ? 'Đã gửi' : 'Gửi' : 'Gửi'}}</button>
                <input type="hidden" name="send" value="0">
            </div>
        </div>
        <p></p>
    </form>
</div>
@stop
@section('footer')
    <script>
        $('.check-is-text').on('click', function(){

            $(this).parents('.item-answer').find('.input_answer_text').hide();

            if($(this).is(':checked')){
                $(this).val(1);
                $(this).parents('.item-answer').find('.input_answer_text').show();
            }else {
                $(this).val(0);
                $(this).parents('.item-answer').find('.input_answer_text').val('');
                $(this).parents('.item-answer').find('.input_answer_text').hide();
            }
        });

        $('.radio-is-text').on('click', function(){

            if($(this).is(':checked')){
                $(this).val();
                $(this).parents('.item-answer').find('.input_answer_text').show();
            }else {
                $(this).parents('.item-answer').find('.input_answer_text').val('');
                $(this).parents('.item-answer').find('.input_answer_text').hide();
            }

        });

        $('#send').on('click', function(){
            $('input[name=send]').val(1);
        })

    </script>
@endsection
