@extends('layouts.app')

@section('page_title', $item->name)

@section('content')
    <div class="warpper_emulation">
        <div class="container mb-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title"><i class="uil uil-apps"></i>
                            <a href="{{ route('frontend.emulation_program') }}"> Chương trình thi đua</a>
                            <i class="uil uil-angle-right"></i>
                            <span class="font-weight-bold">{{ $item->name }}</span>
                        </h2>
                    </div>
                </div>
            </div>
            <p></p>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="nav my-3 justify-content-center wrap-emulation" id="nav-tab" role="tablist">
                    <a class="progress-emulation active" id="nav-info-tab" data-toggle="tab" href="#nav-info" role="tab" aria-selected="true">Thông tin</a>
                    <a class="progress-emulation" id="nav-armorial-tab" data-toggle="tab" href="#nav-armorial" role="tab" aria-selected="false">Huy hiệu</a>
                    <a class="progress-emulation" id="nav-object-tab" data-toggle="tab" href="#nav-object" role="tab" aria-selected="true">Đối tượng</a>
                    <a class="progress-emulation" id="nav-condition-tab" data-toggle="tab" href="#nav-condition" role="tab" aria-selected="false">Điều kiện</a>
                    <a class="progress-emulation" id="nav-result-tab" data-toggle="tab" href="#nav-result" role="tab" aria-selected="false">Kết quả</a>
                </div>
            </div>
        </div>
        <div class="_215b17">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="course_tab_content">
                            <div class="tab-content" id="nav-tabContent">
    
                                {{-- Thông tin --}}
                                <div class="tab-pane fade show active" id="nav-info" role="tabpanel">
                                    <div class="_htg451 text-justify">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="preview_video">
                                                    <div class="row justify-content-center">
                                                        <div class="col-xl-4 col-lg-5 col-md-6">
                                                            <div class="preview_video">
                                                                <a href="#" class="fcrse_img" data-toggle="modal" data-target="#videoModal">
                                                                    <img src="{{ image_file($item->image) }}" alt="" height="auto">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-8 col-lg-7 col-md-6 detail">
                                                            <div class="_215b05">
                                                                <h2>{{ $item->name }}</h2>
                                                            </div>
                                                            <div class="_215b05">
                                                                <b>Mã chương trình:</b> {{ $item->code }}
                                                            </div>
                                                            <div class="_215b05">
                                                                <b>@lang('app.time'):</b> {{ get_date($item->time_start) }} @if($item->time_end) đến {{ get_date($item->time_end) }} @endif
                                                            </div>
                                                            <div class="_215b05">
                                                                {!! $item->description !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                                {{-- Huy hiệu --}}
                                <div class="tab-pane fade" id="nav-armorial" role="tabpanel">
                                    @foreach ($armorials as $armorial)
                                    <div class="_htg451 text-justify">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="preview_video">
                                                    <div class="row justify-content-center">
                                                        <div class="col-xl-4 col-lg-5 col-md-6">
                                                            <div class="preview_video">
                                                                <a href="#" class="fcrse_img" data-toggle="modal" data-target="#videoModal">
                                                                    <img src="{{ image_file($armorial->images) }}" alt="" height="auto">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-8 col-lg-7 col-md-6 detail">
                                                            <div class="_215b05">
                                                                <h2>{{ $armorial->name }}</h2>
                                                            </div>
                                                            <div class="_215b05">
                                                                <b>Điểm</b> {{ $armorial->min_score }}  đến {{ $armorial->max_score }} 
                                                            </div>
                                                            <div class="_215b05">
                                                                <b>Mô tả:</b> {!! $armorial->description !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
    
                                {{-- Đối tượng --}}
                                <div class="tab-pane fade" id="nav-object" role="tabpanel">
                                    <div class="_htg451 text-justify">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="preview_video">
                                                    <div class="row justify-content-center">
                                                        <div class="col-md-9">
                                                            <div class="form-group row">
                                                                <div class="col-sm-3 control-label">
                                                                    <label>{{ trans('backend.object_belong') }}</label>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="radio-inline"><input type="radio" name="object" value="1" checked> Đơn vị / Chức danh  </label>
                                                                    @foreach ($check_object_user as $value)
                                                                        @if ($value->user_id !== null)
                                                                            <label class="radio-inline"><input type="radio" name="object" value="3"> {{trans("backend.user")}} </label>
                                                                            @break
                                                                        @endif
                                                                    @endforeach                                                            
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12" id="form-object">
                                                            <div id="table-object">
                                                                <p></p>
                                                                <table class="tDefault table table-hover bootstrap-table" id="table-object-unit-title">
                                                                    <thead>
                                                                        <tr>
                                                                            <th data-field="unit_name"> {{ trans('backend.unit') }}</th>
                                                                            <th data-field="title_name">{{ trans('backend.title') }}</th>
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                            <div id="table-user-object">
                                                                <p></p>
                                                                <table class="tDefault table table-hover bootstrap-table2" id="table-user">
                                                                    <thead>
                                                                        <tr>
                                                                            <th data-field="profile_code" data-width="5%">{{ trans('backend.employee_code') }}</th>
                                                                            <th data-field="profile_name" data-width="25%">{{ trans('backend.employee_name') }}</th>
                                                                            <th data-field="email" data-width="20%">{{ trans('backend.employee_email') }}</th>
                                                                            <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                                                                            <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>                                                    
                                                                            <th data-field="title_name">{{ trans('backend.title') }}</th>
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>        
                                        </div> 
                                    </div>
                                </div>
    
                                {{-- Điều kiện --}}
                                <div class="tab-pane fade" id="nav-condition" role="tabpanel">
                                    @if (!$condition_courses_online->isEmpty())
                                        @foreach ($condition_courses_online as $condition_course_online)
                                        <div class="_htg451 text-justify">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="preview_video">
                                                        <div class="row justify-content-center">
                                                            <div class="col-xl-4 col-lg-5 col-md-6">
                                                                <div class="preview_video">
                                                                    <a href="{{route('module.online.detail_online', ['id' => $condition_course_online->course_id])}}" class="fcrse_img">
                                                                        <img src="{{ image_file($condition_course_online->image) }}" alt="" height="auto" style="object-fit: cover;">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-8 col-lg-7 col-md-6 detail">
                                                                <div class="_215b05">
                                                                    <h2>
                                                                        <a href="{{route('module.online.detail_online', ['id' => $condition_course_online->course_id])}}">
                                                                            {{ $condition_course_online->name }}
                                                                        </a> 
                                                                    </h2>
                                                                    <span class="_215b05">{{ \Illuminate\Support\Str::words($condition_course_online->description, 20) }}</span>
                                                                </div>
                                                                <div class="_215b05">
                                                                    <b>Mã Khóa học:</b> {{ $condition_course_online->code }}
                                                                </div>
                                                                <div class="_215b05">
                                                                    <b>@lang('app.time'):</b> {{ get_date($condition_course_online->start_date) }} @if($condition_course_online->end_date) đến {{ get_date($condition_course_online->end_date) }} @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        {{$condition_courses_online->links()}}
                                    @endif
                                    @if (!$condition_courses_offline->isEmpty())
                                        @foreach ($condition_courses_offline as $condition_course_offline)
                                        <div class="_htg451 text-justify">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="preview_video">
                                                        <div class="row justify-content-center">
                                                            <div class="col-xl-4 col-lg-5 col-md-6">
                                                                <div class="preview_video">
                                                                    <a href="{{route('module.offline.detail', ['id' => $condition_course_offline->course_id])}}" class="fcrse_img">
                                                                        <img src="{{ image_file($condition_course_offline->image) }}" alt="" height="auto" style="object-fit: cover;">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-8 col-lg-7 col-md-6 detail">
                                                                <div class="_215b05">
                                                                    <h2>
                                                                        <a href="{{route('module.offline.detail', ['id' => $condition_course_offline->course_id])}}">
                                                                            {{ $condition_course_offline->name }}
                                                                        </a> 
                                                                    </h2>
                                                                    <span class="_215b05">{{ \Illuminate\Support\Str::words($condition_course_offline->description, 20) }}</span>
                                                                </div>
                                                                <div class="_215b05">
                                                                    <b>Mã Khóa học:</b> {{ $condition_course_offline->code }}
                                                                </div>
                                                                <div class="_215b05">
                                                                    <b>@lang('app.time'):</b> {{ get_date($condition_course_offline->start_date) }} @if($condition_course_offline->end_date) đến {{ get_date($condition_course_offline->end_date) }} @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        {{$condition_courses_offline->links()}}
                                    @endif
                                    @if (!$condition_quizs->isEmpty())
                                        @foreach ($condition_quizs as $condition_quiz)
                                        @php
                                            $get_quiz_part_max = Modules\Quiz\Entities\QuizPart::where('quiz_id',$condition_quiz->id)->max('end_date');
                                            $get_quiz_part_min = Modules\Quiz\Entities\QuizPart::where('quiz_id',$condition_quiz->id)->min('start_date');
                                        @endphp
                                        <div class="_htg451 text-justify">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="preview_video">
                                                        <div class="row justify-content-center">
                                                            <div class="col-xl-4 col-lg-5 col-md-6">
                                                                <div class="preview_video">
                                                                    <a href="{{route('module.quiz')}}" class="fcrse_img">
                                                                        <img src="{{ image_file($condition_quiz->img) }}" alt="" height="auto">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-8 col-lg-7 col-md-6 detail">
                                                                <div class="_215b05">
                                                                    <h2>
                                                                        <a href="{{route('module.quiz')}}">
                                                                            {{ $condition_quiz->name }}
                                                                        </a> 
                                                                    </h2>
                                                                    <span class="_215b05">{{ \Illuminate\Support\Str::words($condition_quiz->description, 20) }}</span>
                                                                </div>
                                                                <div class="_215b05">
                                                                    <b>Mã Kỳ thi:</b> {{ $condition_quiz->code }}
                                                                </div>
                                                                <div class="_215b05">
                                                                    <b>@lang('app.time'):</b> {{ get_date($get_quiz_part_min) }} đến {{ get_date($get_quiz_part_max) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        {{$condition_quizs->links()}}
                                    @endif
                                </div>
    
                                {{-- KẾT QUẢ --}}
                                <div class="tab-pane fade" id="nav-result" role="tabpanel">
                                    <div class="_htg451 text-justify">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="preview_video">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ul class="get_armorial_result">
                                                                @if (!$armorials->isEmpty())
                                                                    @foreach ($armorials as $armorial)
                                                                    <li class="">
                                                                        <div class="armorial_image">
                                                                            <img class="mb-2" src="{{ image_file($armorial->images) }}" alt="" width="100px" height="100px" >
                                                                        </div>
                                                                        <p class="mb-0">{{$armorial->name}}</p>
                                                                        @php
                                                                            $count_armorial = App\EmulationUserGetArmorial::where('emulation_id',$item->id)->where('armorial_id',$armorial->id)->where('user_id','>',2)->count();
                                                                        @endphp
                                                                        <h2 class="m-0">{{$count_armorial}}</h2>
                                                                    </li>
                                                                    @endforeach
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <ul class="list-group list_armorial_user row mx-3">
                                                        @if (!$emulation_results->isEmpty())
                                                            @foreach ($emulation_results as $emulation_result)
                                                                <li class="list-group-item col-12">
                                                                    <div class="row">
                                                                        <div class="col-2">
                                                                            <img src="{{ \App\Profile::avatar($emulation_result->user_id) }}" alt="" width="100%" height="100px">
                                                                        </div>
                                                                        <div class="col-5">
                                                                            <p>{{$emulation_result->lastname}} {{$emulation_result->firstname}} - {{$emulation_result->code}}</p>
                                                                            <p>Đơn vị: {{$emulation_result->unit_name}}</p>
                                                                            <p>Chức danh: {{$emulation_result->title_name}}</p>
                                                                        </div>
                                                                        <div class="col-3">
                                                                            <p>Điểm: {{$emulation_result->sum_point}}</p>
                                                                        </div>
                                                                        @if ($emulation_result->armorial_images)
                                                                            <div class="col-2">
                                                                                <img src="{{ image_file($emulation_result->armorial_images) }}" alt="" width="100%" height="100px">
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>        
                                        </div> 
                                    </div>
                                </div>
    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.emulation_program.get_object', ['id' => $item->id]) }}',
            table: '#table-object-unit-title'
        });
        
        var table_user = new LoadBootstrapTable({
            url: '{{ route('backend.emulation_program.get_user_object', ['id' => $item->id]) }}',
            detete_button: '#delete-user',
            table: '#table-user'
        });
    </script>
    <script type="text/javascript">
        var object = $("input[name=object]").val();
        if (object == 1) {
            $("#object-add").show('slow');
            $("#object-unit").show('slow');
            $("#object-title").hide('slow');
            $("#object-user").hide('slow');
            $("#table-object").show('slow');
            $("#table-user-object").hide('slow');
        }
        else if (object == 2) {
            $("#object-add").show('slow');
            $("#object-unit").hide('slow');
            $("#object-title").show('slow');
            $("#object-user").hide('slow');
            $("#table-object").show('slow');
            $("#table-user-object").hide('slow');
        }
        else {
            $("#object-add").hide('slow');
            $("#object-unit").hide('slow');
            $("#object-title").hide('slow');
            $("#object-user").show('slow');
            $("#table-object").hide('slow');
            $("#table-user-object").show('slow');
        }
        
        $("input[name=object]").on('change', function () {
            var object = $(this).val();
            if (object == 1) {
                $("#object-add").show('slow');
                $("#object-unit").show('slow');
                $("#object-title").hide('slow');
                $("#object-user").hide('slow');
                $("#table-object").show('slow');
                $("#table-user-object").hide('slow');
                $("#title > option").prop("selected", "");
                $("#title").trigger("change");
                $('.title').val('');
                $("#checkbox").prop('checked', false);
            }
            else if (object == 2) {
                $("#object-add").show('slow');
                $("#object-unit").hide('slow');
                $("#object-title").show('slow');
                $("#object-user").hide('slow');
                $("#table-object").show('slow');
                $("#table-user-object").hide('slow');
            }
            else {
                $("#object-add").hide('slow');
                $("#object-unit").hide('slow');
                $("#object-title").hide('slow');
                $("#object-user").show('slow');
                $("#table-object").hide('slow');
                $("#table-user-object").show('slow');
                $("#title > option").prop("selected", "");
                $("#title").trigger("change");
                $('.title').val('');
                $("#checkbox").prop('checked', false);
            }
        });
    </script>
@endsection
