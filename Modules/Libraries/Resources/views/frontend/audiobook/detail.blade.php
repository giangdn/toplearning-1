@extends('layouts.app')

@section('page_title', $item->name)

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="fcrse_2">
                    <div class="_14d25 mb-5">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="st_title"><i class="uil uil-apps"></i>
                                    <a href="{{ route('module.libraries') }}">@lang('app.libraries')</a> <i class="uil uil-angle-right"></i>
                                    <a href="{{ route('module.frontend.libraries.audiobook',['id' => 0]) }}">Sách nói</a> <i class="uil uil-angle-right"></i>
                                    <span class="font-weight-bold">{{ $item->category ? $item->category->name : $item->name }}</span>
                                </h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="library-container row">
                                    <h1 class="col-9 pl-0">{{ $item->name }}</h1>
                                    <div class="_ttl121_custom col-3">
                                        <div class="_ttl123_custom">@lang('app.view') : <span>{{ $item->views }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="img-library" id="video">
                                    <audio class="w-100" controls id="audiobook_id">
                                        <source src="{{ $item->getLinkPlay() }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            </div>
                            <div class="col-md-5 another_audiobooks">
                                <input type="hidden" id="get_attachment_audiobook_{{$item->id}}" value="{{ upload_file($item->attachment)}}">
                                <div class="row wrraped_audiobook" onclick="selectVideo({{$item->id}})">
                                    <div class="col-12">
                                        <h3 class="name_audiobooks">{{ $item->name }}</h3>
                                    </div>
                                </div>
                                @if ($libraries_audiobooks)
                                    @foreach ($libraries_audiobooks as $libraries_audiobook)
                                        <input type="hidden" id="get_attachment_audiobook_{{$libraries_audiobook->id}}" value="{{ upload_file($libraries_audiobook->attachment)}}">
                                        <div class="row wrraped_audiobook" id="audiobook-{{$libraries_audiobook->id}}" onclick="selectVideo({{$libraries_audiobook->id}})">
                                            <div class="col-sm-12 col-md-12">
                                                <h3 class="name_audiobooks">{{$libraries_audiobook->name_audiobook}}</h3>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="row mt-10 ml-0">
                            <div class="col-md-12 description_library_video">
                                <h3><span>@lang('app.description')</span></h3>
                                {!! $item->description !!}
                            </div>
                        </div>
                        @if (!empty($related_libraries))
                            <div class="row mt-4 ml-0">
                                <div class="col-12 mb-2">
                                    <h3 class="related_video_title"><span>Sách nói cùng danh mục</span> </h3> 
                                </div>
                                @foreach ($related_libraries as $related_library)
                                    <div class="col-md-3 col-6 p-0">
                                        <div class="img-library">
                                            <a href="{{ route('module.libraries.audiobook.detail', ['id' => $related_library->id]) }}">
                                                <img src="{{ image_library($related_library->image) }}" alt="" width="100%" height="auto"/>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function selectVideo(id) {
            var get_attachment = $('#get_attachment_audiobook_'+id).val();
            $('#video').html(`<audio class="w-100" controls autoplay>
                                <source src="`+ get_attachment +`" type="audio/mpeg">
                              </audio>`);
            }
    </script>
@stop
