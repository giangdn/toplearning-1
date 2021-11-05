@extends('layouts.app')

@section('page_title', 'Hướng dẫn')

@section('content')
<div class="sa4d25">
    <div class="container-fluid guide-container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="ibox-content guide-container">
                    <h2 class="st_title"><i class="uil uil-apps">
                        </i><span class="font-weight-bold">{{ trans('app.user_manual') }}</span>
                    </h2>
                    <br>
                    @if ($guides)
                        @foreach ($guides as $guide)
                            <a href="{{ route('module.frontend.guide.post.detail',['id'=>$guide->id]) }}">
                                <div class="all_guide_posts">
                                    <h3 class="mb-0">{{ $guide->name }}</h3>
                                    <p>{!! $guide->attach !!}</p>
                                </div>
                            </a>
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
