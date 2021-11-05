@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.library'))

@section('content')
    <div class="container" id="news-page-mobile">
        <div class="row">
            <div class="col-12 px-0">
                <div class="swiper-container libraries-mobile-slide pt-1">
                    <div class="swiper-wrapper nav-pills mb-2 text-center" id="nav-tab" role="tablist">
                        @foreach ($cate_libraries as $key => $cate_librarie)
                            <a class="libraries_cate_nab_tab swiper-slide nav-item nav-link {{ $cate_id == $cate_librarie->id ? 'active' : '' }}" id="nav-libraries-{{ $cate_librarie->id }}-tab" data-toggle="tab" href="#nav-libraries-{{ $cate_librarie->id }}" role="tab" aria-selected="{{ $key == 0 ? 'true' : 'false'}}">{{ $cate_librarie->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 px-0">
                <div class="news_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        @foreach ($cate_libraries as $key => $cate_librarie)
                            @php
                                $libraries = \Modules\Libraries\Entities\Libraries::where('category_parent','like','%'. $cate_librarie->name . '%')->where('type',$type)->get();
                                $cates_child = $cate_librarie->cateChild($cate_librarie->id, $type);
                            @endphp
                            <div class="tab-pane fade {{ $cate_id == $cate_librarie->id ? 'active show' : '' }} shadow-sm pb-0" id="nav-libraries-{{ $cate_librarie->id }}" role="tabpanel" style="background-color: unset; border-radius: unset">
                                <div class="col-12 p-1">
                                    <select name="cate_child_id" class="select2 form-control cate_child_id_{{$cate_librarie->id}}"  onchange="selectOption({{ $cate_librarie->id }})">
                                        <option value="" disabled selected>Danh mục con</option>                                                    
                                        @foreach ($cates_child as $cate_child)
                                            <option value="{{ $cate_child->id }}">{{ $cate_child->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if(count($libraries) > 0)
                                    @foreach ($libraries as $librarie)
                                        <div class="col-12 p-1">
                                            @include('themes.mobile.frontend.libraries.item')
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12 text-canter">
                                        @if ($type == 1)
                                            <h5 class="empty_libraries">Chưa có sách</h5>
                                        @elseif ($type == 2)
                                            <h5 class="empty_libraries">Chưa có Sách điện tử</h5>
                                        @elseif ($type == 3)
                                            <h5 class="empty_libraries">Chưa có Tài liệu</h5>
                                        @elseif ($type == 4)
                                            <h5 class="empty_libraries">Chưa có video</h5>
                                        @elseif ($type == 5)
                                            <h5 class="empty_libraries">Chưa có Sách nói</h5>
                                        @endif
                                        
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        $('#nav-tab').on('click', '.nav-item', function () {
            $('a[data-toggle="tab"]').removeClass('active');
        });

        var swiper = new Swiper('.libraries-mobile-slide', {
            slidesPerView: 3,
            spaceBetween: 0,
        });

        function selectOption(id){
            var cate_id = $('.cate_child_id_'+id).val();
            if(cate_id) {
                var url = "{{ route('module.libraries.cate', ['cate_id' => ':cate_id', 'type' => $type]) }}"
                url = url.replace(':cate_id', cate_id);
                window.location = url
            }
        }
    </script>
@endsection
