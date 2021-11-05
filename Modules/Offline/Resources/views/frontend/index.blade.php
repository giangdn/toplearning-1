@extends('layouts.app')

@section('page_title', trans('backend.offline_course'))

@section('header')

@endsection

@section('content')
    @php
        $search_status = request()->get('status');
        $search_training_program = request()->get('training_program_id');
        $search_level_subject = request()->get('level_subject_id');
        $search_subject = request()->get('subject_id');
    @endphp
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="_14d25">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    <h2 class="st_title"><i class="uil uil-apps"></i>
                                        @lang('app.course')
                                        <i class="uil uil-angle-right"></i>
                                        <span class="font-weight-bold">@lang('backend.offline_course')</span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('module.offline.search') }}" method="GET" class="from_offline_courses">
                            <div class="row">
                                <div class="col-lg-4 col-md-4">
                                    <div class="mt-10 lbel25">
                                        <label>{{ trans('app.training_program') }}</label>
                                    </div>
                                    <select name="training_program_id" id="training_program_id" class="ui hj145 dropdown cntry152 prompt srch_explore load-training-program"
                                            data-placeholder="{{ trans('app.training_program') }}" onchange="submit()">
                                        @if(isset($training_program))
                                            <option value="{{ $training_program->id }}" selected> {{ $training_program->name }} </option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="mt-10 lbel25">
                                        <label>{{ trans('backend.levels') }}</label>
                                    </div>
                                    <select name="level_subject_id" id="level_subject_id" class="ui hj145 dropdown cntry152 prompt srch_explore load-level-subject"
                                            data-placeholder="{{ trans('backend.levels') }}" data-training-program="{{ $search_training_program }}" onchange="submit()">
                                        @if(isset($level_subject))
                                            <option value="{{ $level_subject->id }}" selected> {{ $level_subject->name }} </option>
                                        @endif
                                    </select>
                                </div>

                                <div class="col-lg-4 col-md-4">
                                    <div class="mt-10 lbel25">
                                        <label>Trạng thái</label>
                                    </div>
                                    <select name="status" id="status" class="select2 ui hj145 dropdown cntry152 prompt srch_explore">
                                        <option value="" selected disabled>Trạng thái</option>
                                        <option value="1" {{ isset($status) && $status == 1 ? 'selected' : ''}}>Đăng ký</option>
                                        <option value="2" {{ isset($status) && $status == 2 ? 'selected' : ''}}>Đang học</option>
                                        <option value="3" {{ isset($status) && $status == 3 ? 'selected' : ''}}>Chờ duyệt</option>
                                        <option value="4" {{ isset($status) && $status == 4 ? 'selected' : ''}}>Hoàn thành</option>
                                        <option value="5" {{ isset($status) && $status == 5 ? 'selected' : ''}}>Đã kết thúc</option>
                                    </select>
                                </div>
                                
                                <div class="col-lg-4 col-md-4">
                                    <div class="mt-10 lbel25">
                                        <label>{{ trans('app.document') }}</label>
                                    </div>
                                    <select name="subject_id" id="subject_id" class="ui hj145 dropdown cntry152 prompt srch_explore load-subject" data-level-subject="{{ $search_level_subject }}" data-training-program="{{ $search_training_program }}" data-placeholder="{{ trans('backend.document') }}" onchange="submit()">
                                        @if(isset($subject))
                                            <option value="{{ $subject->id }}" selected> {{ $subject->name }} </option>
                                        @endif
                                    </select>
                                </div>

                                <div class="col-lg-4 col-md-4">
                                    <div class="mt-10 lbel25">
                                        <label>@lang('app.start_date')</label>
                                    </div>
                                    <div class="ui search focus search_date_start">
                                        <div class="input-group">
                                            <div class="ui left input swdh11">
                                                <input class="prompt srch_explore datepicker" type="text" placeholder="{{ trans('app.start_date') }}" name="fromdate" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4">
                                    <div class="mt-10 lbel25">
                                        <label>@lang('app.end_date')</label>
                                    </div>
                                    <div class="ui search focus search_date_end">
                                        <div class="input-group">
                                            <div class="ui left input swdh11">
                                                <input class="prompt srch_explore datepicker" type="text" placeholder="{{ trans('app.end_date') }}" name="todate" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-md-4">
                                    <div class="mt-10 lbel25">
                                        <label>{{ trans('app.search') }}</label>
                                    </div>
                                    <div class="ui search focus">
                                        <div class="input-group mb-3">
                                            <div class="ui left input swdh11">
                                                <input class="prompt srch_explore" type="text" placeholder="{{ trans('app.search') .' '. trans('app.course') }}" name="q" autocomplete="off" onchange="submit();">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="_14d25">
                                    <div class="row">
                                        @if ($items->count() > 0)
                                            @foreach($items as $item)
                                                <div class="col-lg-3 col-md-4 pr-0">
                                                    @include('data.course_item',['type'=>2])
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="fcrse_1 mb-20">
                                                <div class="text-center">
                                                    <span>@lang('app.not_found')</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#status').on('change', function () {
            var status = $('#status option:selected').val();
            $("#subject_id").empty();
            $("#subject_id").data('status', status);
            $('#subject_id').trigger('change');
        });
    </script>
@stop
