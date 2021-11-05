<div class="row menu_new_insdie">
    @php
        $news_category_parent = \Modules\News\Entities\NewsCategory::query()->orderBy('stt_sort_parent')->whereNull('parent_id')->get();
    @endphp
    <nav class="navbar navbar-expand-md navbar-light">
        <a class="navbar-brand pb-2" href="{{ route('module.news') }}">
            <img src="{{asset('images/home_outside.png')}}" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                @foreach ($news_category_parent as $new_category_parent)
                    @php
                        $news_category_child = $new_category_parent->child;
                    @endphp
                    <li class="nav-item dropdown">
                        <a class="nav-link" id="navbarDropdownMenuLink" href="{{ route('module.news.cate_new', ['parent_id' => $new_category_parent->id, 'id' => 0, 'type' => 0]) }}" aria-haspopup="true" aria-expanded="false"> {{ $new_category_parent->name }} </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            @foreach ($news_category_child as $new_category_child)
                                <li class="dropdown-submenu">
                                    <a class="dropdown-item" href="{{ route('module.news.cate_new', ['parent_id' => $new_category_parent->id, 'id' => $new_category_child->id,  'type' => 1]) }}">{{ $new_category_child->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
</div>
<script>
    if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
        $('#navbarDropdownMenuLink').attr("data-toggle", "dropdown");
    }else{
        $('#navbarDropdownMenuLink').attr("data-toggle", "");
    }
</script>