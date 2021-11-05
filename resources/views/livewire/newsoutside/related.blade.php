<x-layouts.app_outside>
    @if (!empty($get_related_news_outside))
        <div class="row mb-3">
            <div class="col-md-5 col-12"><h5><span>Tin tức liên quan</span></h5></div>
            <div class="search col-md-7 col-12">
                @php
                    $date = date('Y-m-d');
                    $date_now = date('Y-m-d H:i:s');
                @endphp
                <label for="">Xem theo ngày</label>
                <div class="date_search">
                    <input type="date" name="search" wire:model.lazy="search" id="search" class="form-control" value="{{$date}}">
                </div>
            </div>
        </div>
        @foreach ($get_related_news_outside as $get_related_new_outside)
            <div class="row mb-3 get_new">
                <div class="col-4">
                    <a href="{{ route('detail_home_outside',['id' => $get_related_new_outside->id, 'type' => $type]) }}">
                        <img class="w-100" src="{{ image_file($get_related_new_outside->image) }}" alt="" height="auto" style="object-fit: cover">
                    </a>
                </div>
                <div class="col-8">
                    <div class="hot_new_title">
                        <a href="{{ route('detail_home_outside',['id' => $get_related_new_outside->id, 'type' => $type]) }}">
                            <h5 class="mb-2">{{ $get_related_new_outside->title }}
                                @if ($date_now < $get_related_new_outside->date_setup_icon)
                                    .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="" width="50px" height="25px">
                                @endif
                            </h5>
                        </a>
                    </div>
                    <div class="hot_new_description">
                        {!! $get_related_new_outside->description !!}
                    </div>                                    
                </div>
            </div>
        @endforeach
        {{-- {{ $get_related_news_outside->links() }} --}}
    @endif
</x-layouts.app_outside>

