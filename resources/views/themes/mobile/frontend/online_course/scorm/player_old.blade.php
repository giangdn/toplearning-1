@extends('themes.mobile.layouts.app')

@section('page_title', $title)

@section('header')
    <link href="{{ asset('modules/online/css/embed.css') }}" rel="stylesheet">
    <style>
        .iframe-embed{
            min-height: unset;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row w-100 h-100" style="position: fixed">
        <iframe id="iframe-embed-url" src="{{ route('module.online.scorm.player', [$course_id, $activity_id, $attempt_id]) }}" class="iframe-embed" allowfullscreen="allowfullscreen" scrolling="auto" onload="access()"></iframe>
        </div>
    </div>
<script>
    function access() {
        setTimeout(function(){
            var iframe = document.getElementById("iframe-embed-url");
            var innerDoc1 = iframe.contentDocument || iframe.contentWindow.document;
            var iframe2 = innerDoc1.getElementById('scorm_object');
            if(iframe2) {
                var innerDoc2 = iframe2.contentDocument || iframe2.contentWindow.document;
                var message_window_slide = innerDoc2.querySelector("#message-window-slide");
                var message_window_wrapper = innerDoc2.querySelector("#message-window-wrapper");
                var message_window_heading = innerDoc2.querySelector(".message-window-heading");
                if(message_window_slide && message_window_wrapper){
                    message_window_slide.style.setProperty('height', 'auto', 'important');
                    message_window_wrapper.style.setProperty('height', 'auto', 'important');
                    message_window_heading.style.fontSize = '58%';
                    message_window_heading.style.setProperty('padding', '7px', 'important');
                    message_window_heading.style.setProperty('font-size', '58%', 'important');
                }
            }
        },500);
    }
</script>
@endsection
