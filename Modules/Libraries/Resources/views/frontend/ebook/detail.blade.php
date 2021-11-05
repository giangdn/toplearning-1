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
                                    <a href="{{ route('module.frontend.libraries.ebook',['id' => 0]) }}">@lang('app.ebook')</a> <i class="uil uil-angle-right"></i>
                                    <span class="font-weight-bold">{{ $item->category ? $item->category->name : $item->name }}</span>
                                </h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="{{ !empty($related_libraries) ? 'col-md-9' : 'col-md-12' }} col-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="img-library">
                                            <img src="{{ image_library($item->image) }}" alt="" style="width: 100%;"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-30">
                                        <div class="library-container">
                                            <h1>{{ $item->name }}</h1>
                                            <div class="_ttl121_custom">
                                                <div class="_ttl123_custom">@lang('app.view') : <span>{{ $item->views }}</span>
                                                </div>
                                            </div>
                                            <div class="_ttl121_custom">
                                                <div class="_ttl123_custom" id="count_download">
                                                    @lang('app.download') : <span>{{ $item->download }}</span>
                                                </div>
                                            </div>
                                            <br>
                                            @php  
                                                $disabled_download = $check_status_libraries_obj;
                                                $disabled_view = $check_status_libraries_obj;                                
                                            @endphp
                                            <div class="_ttl121_custom">
                                                <div class="_ttl123_custom">
                                                    @if($item->attachment && $disabled_download != 1)
                                                        <a href="{{ $item->getLinkDownload() }}" class="btn btn_adcart" target="_blank" onclick="return downloadFile()"><i class="fa fa-download"></i> @lang('app.download')</a>
                                                    @endif

                                                    @if($item->isFilePdf() && $disabled_view != 2)
                                                        <a href="{{ route('module.libraries.view_pdf', ['id' => $item->id]) }}" target="_blank" class="btn btn_adcart click-view-doc {{$disabled_view}}" data-id="{{$item->id}}" ><i class="fa fa-eye"></i> Xem</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-md-12">
                                        <h2>@lang('app.description')</h2>
                                        <img class="line-title" src="{{ asset('images/line.svg') }}" alt="">
                                        <br>
                                        {!! $item->description !!}
                                    </div>
                                </div>
                            </div>
                            @if (!empty($related_libraries))
                                <div class="col-md-3 col-12">
                                    <div class="col-12 my-2 pl-0">
                                        <h3 class="related_title">
                                            <span>Sách cùng danh mục</span>
                                        </h3>
                                    </div>
                                    <div class="row mr-0 related_libraries">
                                        @foreach ($related_libraries as $related_library)
                                            <div class="col-12">
                                                <div class="img-library">
                                                    <a href="{{ route('module.libraries.ebook.detail', ['id' => $related_library->id]) }}">
                                                        <img src="{{ image_library($related_library->image) }}" alt="" width="100%" height="auto"/>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var download_url = "{{ route('module.frontend.download', ['id' => $item->id]) }}";
        function downloadFile(id) {
            $.ajax({
            url: download_url,
            type: 'get',
            data: {
                id: id,
            },
            }).done(function(data) {
                $('#count_download').html(`@lang('app.download') : <span>`+ data +`</span>`)
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        }
    </script>
@stop
