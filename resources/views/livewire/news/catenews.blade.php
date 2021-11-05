<x-layouts.app>
    @php
        $date_now = date('Y-m-d H:s');
    @endphp
    @if (!$news->isEmpty())
        @foreach ($news as $new)
            <div class="row my-3 get_news_category">
                <div class="col-5 col-md-4 pr-0">
                    <a href="{{ route('module.news.detail',['id' => $new->id]) }}">
                        <img class="w-100" src="{{ image_file($new->image) }}" alt="" height="auto" style="object-fit: cover">
                    </a>
                </div>
                <div class="col-7 col-md-8 new_category">
                    <div class="news_category_title">
                        <a href="{{ route('module.news.detail',['id' => $new->id]) }}">
                            <h4 class="mb-2"><strong>{{ $new->title }}</strong>
                                @if ($date_now < $new->date_setup_icon)
                                    .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                @endif
                            </h4>
                        </a>
                    </div>
                    <div class="news_category_description">
                        {{ $new->description }}
                    </div>                                    
                </div>
            </div>
        @endforeach
        {{-- {{ $news->links() }} --}}
    @endif
</x-layouts.app>
