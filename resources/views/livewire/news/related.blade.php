<x-layouts.app>
    @php
        $date_now = date('Y-m-d H:i:s');
    @endphp
    @if (!empty($get_related_news))
        <div class="row mb-3">
            <div class="col-md-5 col-12"><h4><span>Tin tức liên quan</span></h4></div>
            <div class="search col-md-7 col-12">
                @php
                    $date = date('Y-m-d');
                @endphp
                <label for="">Xem theo ngày</label>
                <div class="date_search">
                    <input type="date" name="search" wire:model.lazy="search" id="search" class="form-control" value="{{$date}}">
                </div>
            </div>
        </div>

        @foreach ($get_related_news as $get_related_new)
            <div class="row mb-3 get_new">
                <div class="col-5 col-md-4 pr-0">
                    <a href="{{ route('module.news.detail',['id' => $get_related_new->id]) }}">
                        <img class="w-100" src="{{ image_file($get_related_new->image) }}" alt="" height="auto" style="object-fit: cover">
                    </a>
                </div>
                <div class="col-7 col-md-8 new">
                    <div class="hot_new_title">
                        <a href="{{ route('module.news.detail',['id' => $get_related_new->id]) }}">
                            <h4 class="mb-2"> 
                                <strong>{{ $get_related_new->title }}
                                @if ($date_now < $get_related_new->date_setup_icon)
                                    .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="" width="50px" height="25px">
                                @endif
                                </strong>
                            </h4>
                        </a>
                    </div>
                    <div class="hot_new_description">
                        {!! $get_related_new->description !!}
                    </div>                                    
                </div>
            </div>
        @endforeach
        {{-- {{ $get_related_news->links() }} --}}
    @endif
</x-layouts.app>

