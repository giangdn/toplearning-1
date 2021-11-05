@extends('themes.mobile.layouts.app')

@section('page_title', 'Chương trình thi đua')

@section('content')
    <div class="container detail_emulation">
        <div class="row">
            <div class="col-12 p-0">
                <img src="{{ image_file($item->image) }}" alt="" class="w-100 border-0" style="height: 200px;object-fit: cover;">
            </div>
            <div class="col-12">
                <div class="info_detail row pt-2">
                    <div class="detail_name col-12">
                        <h4>{{$item->name}}</h4>
                    </div>
                    <div class="time_detail col-12 mb-2">
                        <p>{{\Carbon\Carbon::parse($item->time_start)->format('Y-m-d')}} đến {{\Carbon\Carbon::parse($item->time_end)->format('Y-m-d')}}</p> 
                    </div>
                </div>
            </div>
            <div class="col-12 mb-2">
                <div class="description_emulation">
                    <strong>
                        {!! $item->description !!}
                    </strong>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="emulation_tabs">
                    <nav>
                        <div class="nav nav-pills mb-4 tab_crse justify-content-center wrap-emulation" id="nav-tab" role="tablist">
                            <a class="progress-emulation active" id="nav-armorial-tab" data-toggle="tab" href="#nav-armorial" role="tab"
                               aria-selected="true">Huy hiệu</a>
                            <a class="progress-emulation" id="nav-object-tab" data-toggle="tab" href="#nav-object" role="tab"
                               aria-selected="false">Đối tượng</a>
                            <a class="progress-emulation" id="nav-condition-tab" data-toggle="tab" href="#nav-condition" role="tab"
                               aria-selected="false">Điều kiện</a>
                            <a class="progress-emulation" id="nav-result-tab" data-toggle="tab" href="#nav-result" role="tab"
                               aria-selected="false">Kết quả</a>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="course_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        {{-- HUY HIỆU --}}
                        <div class="tab-pane fade show active" id="nav-armorial" role="tabpanel">
                            @if(count($armorials) > 0)
                                @foreach($armorials as $key => $armorial)
                                    <div class="card mb-2 px-2">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-4 p-0" id="laster-emulation_program">
                                                    <img src="{{ image_file($armorial->images) }}" alt="" class="w-100 border-0" style="height: 85px;object-fit: cover;">
                                                </div>
                                                <div class="col pr-0 align-self-center">
                                                    <div class="">
                                                        <h6 class="font-weight-normal px-2 name_emulation">
                                                            {{ $armorial->name }} - {{$armorial->code}}
                                                        </h6>
                                                        <p class="px-2 mb-0">Điểm: {{$armorial->min_score}} đến {{$armorial->max_score}}</p>
                                                        <p class="px-2 mb-0">Mô tả: {{$armorial->description}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <span class="">@lang('app.not_found')</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- ĐỐI TƯỢNG --}}
                        <div class="tab-pane fade" id="nav-object" role="tabpanel">
                            @if (!$check_object->isEmpty())
                                <div class="object_detail text-justify">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="preview_video">
                                                <div class="row justify-content-center">
                                                    <div class="col-md-9">
                                                        <div class="form-group row pl-2 pt-2">
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
                                                        <div id="table-object" class="px-2 pt-0">
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
                            @else
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <span class="">@lang('app.not_found')</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- ĐIỀU KIỆN --}}
                        <div class="tab-pane fade" id="nav-condition" role="tabpanel">
                            @if(count($condition_courses_online) > 0)
                                @foreach($condition_courses_online as $key => $condition_course_online)
                                    <div class="card mb-2 px-2">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-5 p-0" id="laster-emulation_program">
                                                    <a href="{{route('themes.mobile.frontend.online.detail',['course_id'=>$condition_course_online->course_id])}}">
                                                        <img src="{{ image_file($condition_course_online->image) }}" alt="" class="w-100 border-0" style="height: 85px;">
                                                    </a>
                                                </div>
                                                <div class="col-7 pl-1 pr-0 align-self-center">
                                                    <div class="">
                                                        <h6 class="font-weight-normal px-2 name_emulation">
                                                            <a href="{{route('themes.mobile.frontend.online.detail',['course_id'=>$condition_course_online->course_id])}}">
                                                                {{ $condition_course_online->name }}
                                                            </a>
                                                        </h6>
                                                        <p class="px-2 mb-0">Mã: {{$condition_course_online->code}}</p>
                                                        <p class="px-2 mb-0">Mô tả: {{ \Illuminate\Support\Str::words($condition_course_online->description, 8) }}</p>
                                                        <p class="px-2 mb-0">
                                                            <span>{{\Carbon\Carbon::parse($condition_course_online->start_date)->format('Y-m-d')}} </span> 
                                                            @if ($condition_course_online->end_date)
                                                                <span>đến</span> 
                                                                <span>{{\Carbon\Carbon::parse($condition_course_online->end_date)->format('Y-m-d')}}</span> 
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                {{$condition_courses_online->links()}}
                            @endif
                            @if(count($condition_courses_offline) > 0)
                                @foreach($condition_courses_offline as $key => $condition_course_offline)
                                    <div class="card mb-2 px-2">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-5 p-0" id="laster-emulation_program">
                                                    <a href="{{route('themes.mobile.frontend.offline.detail',['course_id'=>$condition_course_offline->course_id])}}">
                                                        <img src="{{ image_file($condition_course_offline->image) }}" alt="" class="w-100 border-0" style="height: 85px;">
                                                    </a>
                                                </div>
                                                <div class="col-7 pr-0 pl-1 align-self-center">
                                                    <div class="">
                                                        <h6 class="font-weight-normal px-2 name_emulation">
                                                            <a href="{{route('themes.mobile.frontend.offline.detail',['course_id'=>$condition_course_offline->course_id])}}">
                                                                {{ $condition_course_offline->name }}
                                                            </a>
                                                        </h6>
                                                        <p class="px-2 mb-0">Mã: {{$condition_course_offline->code}}</p>
                                                        <p class="px-2 mb-0">Mô tả: {{ \Illuminate\Support\Str::words($condition_course_offline->description, 8) }}</p>
                                                        <p class="px-2 mb-0">
                                                            <span>{{\Carbon\Carbon::parse($condition_course_offline->start_date)->format('Y-m-d')}} </span> 
                                                            <span>đến</span> 
                                                            <span>{{\Carbon\Carbon::parse($condition_course_offline->end_date)->format('Y-m-d')}}</span>  
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                {{$condition_courses_offline->links()}}
                            @endif
                            @if(count($condition_quizs) > 0)
                                @foreach($condition_quizs as $key => $condition_quiz)
                                    @php
                                        $get_quiz_part_max = Modules\Quiz\Entities\QuizPart::where('quiz_id',$condition_quiz->id)->max('end_date');
                                        $get_quiz_part_min = Modules\Quiz\Entities\QuizPart::where('quiz_id',$condition_quiz->id)->min('start_date');
                                    @endphp
                                    <div class="card mb-2 px-2">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-4 p-0" id="laster-emulation_program">
                                                    <a href="{{route('themes.mobile.frontend.offline.detail',['course_id'=>$condition_quiz->id])}}">
                                                        <img src="{{ image_file($condition_quiz->img) }}" alt="" class="w-100 border-0" style="height: 85px;object-fit: cover;">
                                                    </a>
                                                </div>
                                                <div class="col pr-0 align-self-center">
                                                    <div class="">
                                                        <h6 class="font-weight-normal px-2 name_emulation">
                                                            <a href="{{route('themes.mobile.frontend.offline.detail',['course_id'=>$condition_quiz->id])}}">
                                                                {{ $condition_quiz->name }}
                                                            </a>
                                                        </h6>
                                                        <p class="px-2 mb-0">Mã: {{$condition_quiz->code}}</p>
                                                        <p class="px-2 mb-0">Mô tả: {{ \Illuminate\Support\Str::words($condition_quiz->description, 8) }}</p>
                                                        <p class="px-2 mb-0">
                                                            <span>{{\Carbon\Carbon::parse($condition_quiz->get_quiz_part_min)->format('Y-m-d')}} </span> 
                                                            <span>đến</span> 
                                                            <span>{{\Carbon\Carbon::parse($condition_quiz->get_quiz_part_max)->format('Y-m-d')}}</span>  
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                {{$condition_courses_offline->links()}}
                            @endif
                            @if ($condition_courses_online->isEmpty() && $condition_courses_offline->isEmpty() && $condition_quizs->isEmpty())   
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <span class="">@lang('app.not_found')</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- KẾT QUẢ --}}
                        <div class="tab-pane fade" id="nav-result" role="tabpanel">
                            @if (!$emulation_results->isEmpty())
                                <div class="_htg451 text-justify">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="preview_video">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <ul class="get_armorial_result pl-0 mb-0">
                                                            @if (!$armorials->isEmpty())
                                                                @foreach ($armorials as $armorial)
                                                                <li class="">
                                                                    <div class="armorial_image">
                                                                        <img class="mb-2" src="{{ image_file($armorial->images) }}" alt="" width="65px" height="65px">
                                                                    </div>
                                                                    <p class="mb-0">{{$armorial->name}}</p>
                                                                    @php
                                                                        $count_armorial = App\EmulationUserGetArmorial::where('emulation_id',$item->id)->where('armorial_id',$armorial->id)->count();
                                                                    @endphp
                                                                    <h6 class="m-0">{{$count_armorial}}</h6>
                                                                </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                                <ul class="list-group list_armorial_user row mx-1">
                                                    @foreach ($emulation_results as $emulation_result)
                                                        <li class="list-group-item col-12 mb-2">
                                                            <div class="row">
                                                                <div class="col-3 user_avatar pr-0">
                                                                    <img src="{{ image_file($emulation_result->avatar) }}" alt="" width="100%" height="auto" style="object-fit: cover;">
                                                                </div>
                                                                <div class="col-6 info_user">
                                                                    <p class="px-2 mb-0">{{$emulation_result->lastname}} {{$emulation_result->firstname}}</p>
                                                                    <p class="px-2 mb-0">Mã: {{$emulation_result->code}}</p>
                                                                    <p class="px-2 mb-0">Đơn vị: {{$emulation_result->unit_name}}</p>
                                                                    <p class="px-2 mb-0">Chức danh: {{$emulation_result->title_name}}</p>
                                                                    <p class="px-2 mb-0">Điểm: {{$emulation_result->sum_point}}</p>
                                                                </div>
                                                                <div class="col-3 images_user_armorial pl-0">
                                                                    <img src="{{ image_file($emulation_result->armorial_images) }}" alt="" width="100%" height="auto" style="object-fit: cover;">
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>        
                                    </div> 
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <span class="">@lang('app.not_found')</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
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
