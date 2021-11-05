@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.libraries'))

@section('content')
    @php
        if ($type == 1){
            $title = 'SÁCH GIẤY';
            $title_new = 'Sách giấy mới nhất';
        }elseif ($type == 2){
            $title = 'SÁCH ĐIỆN TỬ';
            $title_new = 'Sách điện tử mới nhất';
        } elseif ($type == 3){
            $title = 'TÀI LIỆU';
            $title_new = 'Tái liệu mới nhất';
        } elseif ($type == 4){
            $title = 'VIDEO';
            $title_new = 'Video mới nhất';
        } else{
            $title = 'SÁCH NÓI';
            $title_new = 'Sách nói mới nhất';
        }
    @endphp
    <div class="container" id="libraries">
        <div class="row pt-2 pb-3 mx-0">
            <form action="{{ route('module.libraries.search') }}" method="GET" class="w-100">
                @if ($search && !$type)
                    <select name="type" class="select2 form-control w-100"  onchange="submit();">
                        <option value="" disabled selected>Thể loại</option>                                                    
                        <option value="1">Sách giấy</option>
                        <option value="2">Sách điện tử</option>
                        <option value="3">Tài liệu</option>
                        <option value="4">Video</option>
                        <option value="5">Sách nói</option>
                    </select>
                @else
                    <input type="hidden" name="type" value="{{ $type }}">
                    <select name="cate_id" class="select2 form-control"  onchange="submit()">
                        <option value="" disabled selected>Danh mục</option>                                                  
                        @foreach ($cates_search as $cate)
                            @php
                                $get_libraries_cate = \Modules\Libraries\Entities\Libraries::where('type',$type)->where('status',1)->where('category_parent','like','%'. $cate->name .'%')->get();
                            @endphp
                            @if (!$get_libraries_cate->isEmpty())
                                <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                            @endif
                        @endforeach
                    </select>
                @endif
                <input type="text" name="search" id="search" class="form-control w-100" placeholder="Nhập tên sách, tác giả.." onchange="searchLibraries()">
            </form>
        </div>
        <div class="row">
            @if ($search && !$type)
                <div class="col-12 news_item">
                    <div class="row m-0">
                        @foreach ($get_libraries as $get_library)
                            @php
                                if ($type == 1){
                                    $url = route('module.libraries.book.detail', ['id' => $get_library->id]);
                                }elseif ($type == 2){
                                    $url = route('module.libraries.ebook.detail', ['id' => $get_library->id]);
                                } elseif ($type == 3){
                                    $url = route('module.libraries.document.detail', ['id' => $get_library->id]);
                                } elseif ($type == 4){
                                    $url = route('module.libraries.video.detail', ['id' => $get_library->id]);
                                } else{
                                    $url = route('module.libraries.audiobook.detail', ['id' => $get_library->id]);
                                }
                            @endphp
                            <div class="col-4 p-1">
                                <a href="{{ $url }}">
                                    <img src="{{ image_file($get_library->image) }}" alt="" height="180px" width="100%">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="col-12 title_type_search">
                    <h5 class="text-center ">{{ $title }}</h5>
                </div>
                <div class="col-12">
                    <h5><span class="title_type">{{ $title_new }}</span></h5>
                </div>
                <div class="col-12 news_item">
                    <div class="row m-0">
                        @foreach ($get_news_libraries as $get_new_libraries)
                            @php
                                if ($type == 1){
                                    $url = route('module.libraries.book.detail', ['id' => $get_new_libraries->id]);
                                }elseif ($type == 2){
                                    $url = route('module.libraries.ebook.detail', ['id' => $get_new_libraries->id]);
                                } elseif ($type == 3){
                                    $url = route('module.libraries.document.detail', ['id' => $get_new_libraries->id]);
                                } elseif ($type == 4){
                                    $url = route('module.libraries.video.detail', ['id' => $get_new_libraries->id]);
                                } else{
                                    $url = route('module.libraries.audiobook.detail', ['id' => $get_new_libraries->id]);
                                }
                            @endphp
                            <div class="col-4 p-1">
                                <a href="{{ $url }}">
                                    <img src="{{ image_file($get_new_libraries->image) }}" alt="" height="180px" width="100%">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @if ($cate_id > 0)
                    <div class="col-12">
                        <h5><span class="title_type">{{ $get_cate_with_id->name }}</span></h5>
                    </div>
                    <div class="col-12">
                        <div class="row m-0">
                            @foreach ($cates_item as $item)
                                    @php
                                    if ($type == 1){
                                        $url = route('module.libraries.book.detail', ['id' => $item->id]);
                                    }elseif ($type == 2){
                                        $url = route('module.libraries.ebook.detail', ['id' => $item->id]);
                                    } elseif ($type == 3){
                                        $url = route('module.libraries.document.detail', ['id' => $item->id]);
                                    } elseif ($type == 4){
                                        $url = route('module.libraries.video.detail', ['id' => $item->id]);
                                    } else{
                                        $url = route('module.libraries.audiobook.detail', ['id' => $item->id]);
                                    }
                                @endphp
                                <div class="col-4 p-1">
                                    <a href="{{ $url }}">
                                        <img src="{{ image_file($item->image) }}" alt="" height="180px" width="100%">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>                
                @else
                    @if ($search)
                        <div class="col-12 news_item">
                            <div class="row m-0">
                                @foreach ($get_libraries as $get_library)
                                    @php
                                        if ($type == 1){
                                            $url = route('module.libraries.book.detail', ['id' => $get_library->id]);
                                        }elseif ($type == 2){
                                            $url = route('module.libraries.ebook.detail', ['id' => $get_library->id]);
                                        } elseif ($type == 3){
                                            $url = route('module.libraries.document.detail', ['id' => $get_library->id]);
                                        } elseif ($type == 4){
                                            $url = route('module.libraries.video.detail', ['id' => $get_library->id]);
                                        } else{
                                            $url = route('module.libraries.audiobook.detail', ['id' => $get_library->id]);
                                        }
                                    @endphp
                                    <div class="col-4 p-1">
                                        <a href="{{ $url }}">
                                            <img src="{{ image_file($get_library->image) }}" alt="" height="180px" width="100%">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        @foreach ($cates_search as $cate)
                            @php
                                $get_libraries_cate = \Modules\Libraries\Entities\Libraries::select('image','id')->where('type',$type)->where('status',1)->where('category_parent','like','%'. $cate->name .'%')->get()->take(6);
                            @endphp
                            @if (!$get_libraries_cate->isEmpty())
                                <div class="col-12 news_item">
                                    <div class="row m-0">
                                        <div class="col-12 pl-0">
                                            <h5><span class="title_type">{{ $cate->name }}</span></h5>
                                        </div>
                                        @foreach ($get_libraries_cate as $get_library_cate)
                                            @php
                                                if ($type == 1){
                                                    $url = route('module.libraries.book.detail', ['id' => $get_library_cate->id]);
                                                }elseif ($type == 2){
                                                    $url = route('module.libraries.ebook.detail', ['id' => $get_library_cate->id]);
                                                } elseif ($type == 3){
                                                    $url = route('module.libraries.document.detail', ['id' => $get_library_cate->id]);
                                                } elseif ($type == 4){
                                                    $url = route('module.libraries.video.detail', ['id' => $get_library_cate->id]);
                                                } else{
                                                    $url = route('module.libraries.audiobook.detail', ['id' => $get_library_cate->id]);
                                                }
                                            @endphp
                                            <div class="col-4 p-1">
                                                <a href="{{ $url }}">
                                                    <img src="{{ image_file($get_library_cate->image) }}" alt="" height="180px" width="100%">
                                                </a>
                                            </div>
                                        @endforeach
                                        @if (count($get_libraries_cate) == 6 )
                                            <div class="col-12 my-2">
                                                <a href="{{ route('module.libraries.search').'?type='.$type.'&cate_id='.$cate->id }}" class="see_more">
                                                    <p class="text-center">XEM THÊM</p>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                @endif
            @endif
        </div>
    </div>
@endsection
@section('footer')
<script type="text/javascript"> 
    function searchLibraries() {
        const elem = document.getElementById('search');
        if (elem === document.activeElement) {
            $('#form_search').submit();
        } 
    }
</script>
@endsection