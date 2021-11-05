@php
    if ($item->type == 1){
        $url = route('module.libraries.book.detail', ['id' => $item->id]);
    }elseif ($item->type == 2){
        $url = route('module.libraries.ebook.detail', ['id' => $item->id]);
    }elseif($item->type == 3){
        $url = route('module.libraries.document.detail', ['id' => $item->id]);
    }elseif($item->type == 5){
        $url = route('module.libraries.audiobook.detail', ['id' => $item->id]);
    }else{
        $url = route('module.libraries.video.detail', ['id' => $item->id]);
    }
    $isRating = \Modules\Libraries\Entities\LibrariesRatting::where('libraries_id',$item->id)->where('user_id',auth()->id())->first();
    $get_object_libraries = \Modules\Libraries\Entities\LibrariesObject::where('libraries_id',$item->id)->whereNotNull('unit_id')->where('type', $item->type)->get();
    $check_unit = 0;
    if ( !$get_object_libraries->isEmpty() ) {
        foreach ($get_object_libraries as $get_object_librarie) {
            $unit_code = \App\Models\Categories\Unit::find($get_object_librarie->unit_id);
            $get_array_childs = \App\Models\Categories\Unit::getArrayChild($unit_code->code);
            if( in_array($get_unit->unit_id, $get_array_childs) || $get_unit->unit_id == $get_object_librarie->unit_id) {
                $check_unit = 1;
            }
        }  
    }    
@endphp
    @if ((!$get_object_libraries->isEmpty() && $check_unit == 1) || $get_object_libraries->isEmpty())
        <div class="col-lg-3 col-md-4 p-1">
            <div class="fcrse_1 library">
                <a href="{{ $url }}" class="fcrse_img">
                    <img alt="{{ $item->name }}" class="lazy" data-src="{{ image_library($item->image) }}">
                </a>
                <div class="crse_reviews count_ratting">
                    <i class="uil uil-star">{{ !empty($isRating) ? $isRating->ratting : 0 }}</i>
                </div>
                <div class="fcrse_content">
                    <div class="vdtodt">
                        <span class="vdt14">{{ $item->views }} <i class="fa fa-eye"></i></span>
                        <span class="vdt14">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
                        {{-- <span class="vdt14" id="view_like_{{ $item->id }}">{{ $item->like_libraries }} Lượt thích</span> --}}
                    </div>
                    <div class="div_name">
                        <a href="{{ $url }}" class="crse14s"><span>{{ $item->name }}</span></a>
                        <div class="full_name">
                            <span>{{ $item->name }}</span>
                        </div>
                    </div>
                    
                    <div class="author">
                        @if ($item->type == 3)
                            <span>Nguồn soạn thảo: {{ $item->name_author }}</span>
                        @else
                            <span>Tác giả: {{ $item->name_author }}</span>
                        @endif
                    </div>

                    <div class="ratting_start_libraries">
                        <span class="mr-1">Đánh giá: </span>
                        <div class="ratting_start">
                            @for ($i = 1; $i < 6; $i++)
                                <span class="rating-star rating_star_item_{{ $item->id }} ratting_{{ $item->id }}_{{$i}} rating_libraries
                                    @if(!$isRating) empty-star rating 
                                    @elseif(!empty($isRating) && $isRating->ratting >= $i) full-star
                                    @endif" 
                                    onclick="ratting({{ $item->id }},{{ $i }}, {{ !empty($isRating) ? $isRating->ratting : 0 }})" 
                                    onmouseover="hoverRatting({{ $item->id }}, {{ $i }}, {{ !empty($isRating) ? $isRating->ratting : 0 }})"
                                    onmouseout="outRatting({{ $item->id }}, {{ $i }}, {{ !empty($isRating) ? $isRating->ratting : 0 }})"
                                >
                                </span>
                            @endfor
                        </div>
                    </div>
                    
                    
                    {{-- @php
                        $profile = \Modules\Libraries\Entities\LikeLibraries::where('user_id',\Auth::id())->first();
                        if ($profile !== null) {
                            $get_profile_like_libraries = json_decode($profile->libraries_id);
                        }
                    @endphp
                    <div>
                        <a class="like mt-2" id="like_{{ $item->id }}" onclick="like( {{ $item->id }} )">
                            @if (!empty($get_profile_like_libraries) && in_array($item->id,$get_profile_like_libraries))
                                <span style="color: blue"><i class="fas fa-thumbs-up"></i> Thích</span>
                            @else
                                <span><i class="far fa-thumbs-up"></i> Thích</span>
                            @endif
                        </a>
                    </div> --}}
                </div>
            </div>
        </div>
    @endif

<script>
    function like(id) {
        $.ajax({
            url: "{{ route('module.frontend.like') }}",
            type: 'post',
            data: {
                id: id,
            }
        }).done(function(data) {
            console.log(data);
            if (data.check_like == 1) {
                $('#like_'+id).html('<span style="color: blue"><i class="fas fa-thumbs-up"></i> Thích</span>');
            } else {
                $('#like_'+id).html('<i class="far fa-thumbs-up"></i> Thích');
            }
            $('#view_like_'+id).html(data.view_like + ' Lượt thích');
            return false;
        }).fail(function(data) {
            show_message('{{ trans('lageneral.data_error ') }}', 'error');
            return false;
        });
    }
</script>
