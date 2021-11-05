@extends('layouts.app')

@section('page_title', 'Hướng dẫn')

@section('content')
<div class="sa4d25">
    <div class="container-fluid guide-container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="ibox-content guide-container">
                    <h2 class="st_title"><i class="uil uil-apps"></i><span class="font-weight-bold">{{ trans('app.user_manual') }}</span></h2>
                    <br>
                    @if ($guides)
                        @foreach ($guides as $guide)
                        <center>
                            <h3 class="mt-3">{{ $guide->name }}</h3>
                            <video width="70%" height="auto" controls>
                                <source src="{{ image_file($guide->attach) }}" type="video/mp4">
                            </video>
                        </center>
                        @endforeach
                    @endif
                    <div class="paginate_guide">
                        {{ $guides->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
@stop
