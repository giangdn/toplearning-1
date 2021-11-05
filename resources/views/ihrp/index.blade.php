@extends('layouts.backend')

@section('page_title', 'IHRP')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>IHRP</h2>
    </div>
@endsection

@section('content')
    <div class="row">

        <div class="col-md-2">
            <div class="scb-category-icon">
                <a href="{{ route('backend.ihrp.template1') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('backend.ihrp.template1') }}">Khóa đào tạo</a>
            </div>
        </div>
        <div class="col-md-2">
            <div class="scb-category-icon">
                <a href="{{ route('backend.ihrp.template2') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('backend.ihrp.template2') }}">Chi tiết đào tạo</a>
            </div>
        </div>
        <div class="col-md-2">
            <div class="scb-category-icon">
                <a href="{{ route('backend.ihrp.template3') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('backend.ihrp.template3') }}">Cập nhật kết quả đào tạo</a>
            </div>
        </div>
    </div>
@endsection
