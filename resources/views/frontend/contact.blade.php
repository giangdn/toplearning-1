@extends('layouts.app')

@section('page_title', 'Liên hệ')

@section('content')
    <div class="container-fluid guide-container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="ibox-content guide-container">
                    <h2 class="st_title"><i class="uil uil-apps">
                        </i><span class="font-weight-bold">Liên hệ</span>
                    </h2>
                    <br>
                    @if ($contacts)
                        @foreach ($contacts as $contact)
                            <a href="{{ route('frontend.contact.detail',['id'=>$contact->id]) }}">
                                <div class="all_guide_posts">
                                    <h3 class="mb-0">{{ $contact->name }}</h3>
                                    <p>{!! $contact->description !!}</p>
                                </div>
                            </a>
                        @endforeach
                    @endif
                    <div class="paginate_guide">
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
