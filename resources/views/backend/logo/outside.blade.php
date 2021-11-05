@extends('layouts.backend')

@section('page_title', 'Quản lý logo')
@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/logo/css/logo.css') }}">
@endsection
@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('backend.setting') }} </a>
            <i class="uil uil-angle-right"></i> 
            <span class="font-weight-bold">Logo bên ngoài</span>
        </h2>
    </div>
@endsection

@section('content')
    <div id="logo">
        <div role="main">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6 text-center">
                    <form class="form-horizontal form-ajax" id="form-search" method="post" action="{{ route('backend.logo_outside.save') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="name" value="logo_outside">
                            <button type="button" class="image-picker btn btn-info" id="select-image">
                                <i class="fa fa-folder-open m-r-5"></i> {{ trans('backend.choose_logo') }}
                            </button>
                            ({{trans('backend.size')}}: 300x80)
                            <br>
                            <div id="image-review" class="mt-2 mb-2">
                                @if(isset($logo))
                                    <img src="{{ image_file($logo->value) }}" alt="" class="w-50">
                                @else
                                    <div class="single-image image-holder-wrapper clearfix">
                                        <div class="image-holder placeholder">
                                            <i class="far fa-image"></i>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <input name="image" id="image-select" type="text" class="d-none" value="{{ @$logo->value }}">
                        @can('logo-edit')
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                        @endcan
                    </form>

                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#select-image").on('click', function () {
                var lfm = function (options, cb) {
                    var route_prefix = '/filemanager';
                    window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                    window.SetUrl = cb;
                };

                lfm({type: 'image'}, function (url, path) {
                    $("#image-review").html('<img src="'+ path +'" class="w-50">');
                    $("#image-select").val(path);
                });
            });
        });
    </script>
@endsection

