@extends('layouts.app')

@section('page_title', 'Sơ đồ web')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/profile.css') }}">
@endsection

@section('content')
<div class="container-fluid" style="background-color: white;max-width: 100%;margin-top: 100px;">
    <div class="row">

        <div class="col-md-12">

            <div class="content-news tab-content" style="padding: 0 50px;min-height: 400px;">
                <p></p><p></p>
                <h2 style="text-align: center;">Sơ đồ web</h2>
                <div class="row">
                    <ul>
                        <li><a href="/">Trang chủ</a></li>
                        <li><a href="/?_mod=forum&_view=forum">Diễn đàn</a></li>
                        <li>
                            <a href="#">{{ trans('backend.course') }}</a>
                            <ul>
                                <li><a href="/?_mod=ttc&_view=ttc&_lay=online">Khóa học offline</a></li>
                                <li><a href="/?_mod=ttc&_view=ttc&_lay=offline">Khóa học tập trung</a></li>

                            </ul>
                        </li>
                        <li><a href="/?_mod=quiz&_view=quiz&_lay=dft">Thi trực tuyến</a></li>
                        <li><a href="/?_mod=library&_view=library&_lay=dft">Thư viện</a>
                            <ul>
                                <li><a href="/?_mod=library&_view=book&_lay=dft">Sách</a></li>

                                <li><a href="/?_mod=library&_view=ebook&_lay=dft">Ebook</a></li>

                                <li><a href="/?_mod=library&_view=document&_lay=dft">{{trans('backend.document')}}</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="col-md-2" style="">

            </div>
        </div>

    </div>
</div>
@stop
