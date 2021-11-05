@extends('themes.mobile.layouts.app')

@section('page_title', 'Chương trình thi đua')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 px-0">
                <div class="shadow-sm pb-0">
                    @if(count($emulation_programs) > 0)
                        @foreach($emulation_programs as $key => $emulation_program)
                            <div class="card shadow-sm my-1 px-2" style="border-radius: unset;">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-4 p-0" id="laster-emulation_program">
                                            <a href="{{ route('frontend.emulation_program.detail',['id' => $emulation_program->id]) }}">
                                                <img src="{{ image_file($emulation_program->image) }}" alt="" class="w-100 border-0" style="height: 85px;object-fit: cover;">
                                            </a>
                                        </div>
                                        <div class="col pr-0 align-self-center">
                                            <div class="">
                                                <h6 class="font-weight-normal px-2 name_emulation">
                                                    <a href="{{ route('frontend.emulation_program.detail',['id' => $emulation_program->id]) }}">
                                                        {{ $emulation_program->name }}
                                                    </a>
                                                </h6>
                                                <p class="px-2 mb-0">Mã: {{$emulation_program->code}}</p>
                                                <p class="px-2 mb-0 time_emulation">
                                                    <span>{{\Carbon\Carbon::parse($emulation_program->time_start)->format('Y-m-d')}} </span> 
                                                    <span>đến</span> 
                                                    <span>{{\Carbon\Carbon::parse($emulation_program->time_end)->format('Y-m-d')}}</span>  
                                                </p>
                                                <span class="small float-right mr-2">
                                                    <a href="{{ route('frontend.emulation_program.detail',['id' => $emulation_program->id]) }}">
                                                        <i class="material-icons vm">arrow_forward</i>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="row">
                            <div class="col-6">
                                @if($emulation_programs->previousPageUrl())
                                    <a href="{{ $emulation_programs->previousPageUrl() }}" class="bp_left">
                                        <i class="material-icons">navigate_before</i> @lang('app.previous')
                                    </a>
                                @endif
                            </div>
                            <div class="col-6 text-right">
                                @if($emulation_programs->nextPageUrl())
                                    <a href="{{ $emulation_programs->nextPageUrl() }}" class="bp_right">
                                        @lang('app.next') <i class="material-icons">navigate_next</i>
                                    </a>
                                @endif
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
@endsection
@section('footer')
    
@endsection
