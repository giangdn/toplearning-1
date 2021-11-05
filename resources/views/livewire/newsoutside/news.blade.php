<x-layouts.app_outside>
    @php
        $date_now = date('Y-m-d H:s');
    @endphp
    @if (!$news->isEmpty())
        @foreach ($news as $new)
            <div class="row my-3 get_news_category">
                <div class="col-4 pr-0">
                    <a href="{{ route('detail_home_outside',['id' => $new->id, 'type' => $type]) }}">
                        <img class="w-100" src="{{ image_file($new->image) }}" alt="" height="auto" style="object-fit: cover">
                    </a>
                </div>
                <div class="col-8 new_category">
                    <div class="news_category_title">
                        <a href="{{ route('detail_home_outside',['id' => $new->id, 'type' => $type]) }}">
                            <h6 class="mb-2">{{ $new->title }}
                                @if ($date_now < $new->date_setup_icon)
                                    .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                @endif
                            </h6>
                        </a>
                    </div>
                    <div class="new_category_description">
                        {{ $new->description }}
                    </div>                                    
                </div>
            </div>
        @endforeach
        {{-- {{ $news->links() }} --}}
    @endif
</x-layouts.app_outside>
