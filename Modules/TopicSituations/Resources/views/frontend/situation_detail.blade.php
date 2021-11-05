@extends('layouts.app')

@section('page_title', 'Xử lý tình huống')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="ibox-content forum-container">
                <h2 class="st_title">
                    <i class="uil uil-apps"></i>
                    <a href="{{ route('frontend.topic_situations') }}">Xử lý tình huống</a>
                    <i class="uil uil-angle-right"></i>
                    <a href="{{ route('frontend.get.situations',['id' => $topic->id]) }}">{{$topic->name}}</a>
                    <i class="uil uil-angle-right"></i>
                    <span class="font-weight-bold">{{ $situation->name }}</span>
                </h2>
            </div>
        </div>
    </div>
    <div class="row m-0 detail_situation">
        <div class="col-md-12">
            <div class="wrapped_detail">
                <ul class="situation_view_like_time">
                    <li>
                        <p>{{ $situation->view }} <i class="fas fa-eye"></i></p>
                    </li>
                    <li>
                        <p>{{ $situation->like }} Lượt thích</p>
                    </li>
                    <li>
                        <p>Ngày tạo: {{ \Carbon\Carbon::parse($situation->created_at)->format('H:s d/m/Y') }}</p>
                    </li>
                </ul>
                <div class="situation_name pb-1 pt-2">
                    <h3>{{ $situation->name }}</h3>
                </div>
                <div class="situation_code py-1">
                    <span>Mã: {{ $situation->code }}</span>
                </div>
                
            </div>
        </div>
        <div class="col-12 text-justify">
            <div class="situation_description mt-2">
                <span>Mô tả</span>
                {!! $situation->description !!}
            </div>
        </div>
        <div class="col-12">
            @livewire('situation.comment', ['topic_id' => $topic->id, 'situation_id' => $situation->id])
        </div>
    </div>
</div>
@stop
