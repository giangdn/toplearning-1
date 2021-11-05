{{-- @extends('layouts.app') --}}
@extends('layouts.course_activity')

@section('page_title', $title)

@section('content')
    <link href="{{ asset('modules/online/css/embed.css?v=1') }}" rel="stylesheet">
    <div class="container-fluid">
        <div class="mt-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title"><i class="uil uil-apps"></i>
                            @lang('app.course')
                            <i class="uil uil-angle-right"></i>
                            <a href="{{ route('module.online') }}"> @lang('app.onl_course')</a>
                            <i class="uil uil-angle-right"></i>
                            <a href="{{ route('module.online.detail', ['id' => $course_id]) }}"> {{ $item->name }}</a>
                            <i class="uil uil-angle-right"></i>
                            <span class="font-weight-bold">Hoạt động</span>
                        </h2>
                    </div>
                </div>
            </div>
            <p></p>

            <iframe src="{{ $url }}" class="iframe-embed" id="iframe-embed-url" allowfullscreen="allowfullscreen" scrolling="no"></iframe>
        </div>
    </div>
    <script>
        $("#iframe-embed-url").on("load", function() {
            let head = $("#iframe-embed-url").contents().find("head");
            let css = `<style>.out_of_scorm {
                            border: 1px solid #1b4486;
                            border-radius: 10px;
                            padding: 10px;
                            text-align: center;
                            color: red;
                        }</style>`;
            $(head).append(css);
        });
    </script>
@endsection
