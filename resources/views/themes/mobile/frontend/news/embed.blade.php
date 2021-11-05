@extends('themes.mobile.layouts.app')

@section('page_title', 'Xem file')

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
            <iframe src="{{ $url }}" class="iframe-embed" allowfullscreen="allowfullscreen" scrolling="no"></iframe>
        </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        var i = 0;
        $('#autorenew').on('click', function () {
            i += 1;
            if (i % 2 != 0){
                $('.iframe-embed').css('width', height_screen+'px');
                $('.iframe-embed').css('height', width_screen+'px');
                $('.iframe-embed').css('transform', 'rotate(90deg)');
                $('.iframe-embed').css('margin', 'auto');
                $('.footer').css('display', 'none');
            }else{
                $('.iframe-embed').css('width', width_screen+'px');
                $('.iframe-embed').css('height', height_screen+'px');
                $('.iframe-embed').css('transform', 'rotate(0deg)');
                $('.iframe-embed').css('margin', 'unset');
                $('.footer').css('display', 'block');
            }
        });
    </script>
@endsection
