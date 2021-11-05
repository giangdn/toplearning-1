<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ route('home_outside',['type' => 0]) }}">
        <img src="{{asset('images/home-page.png')}}" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <div class="group-left">
            <ul>
                @php
                    $news_category_parent = \Modules\NewsOutside\Entities\NewsOutsideCategory::query()->orderBy('stt_sort_parent', 'asc')->whereNull('parent_id')->get();
                @endphp
                @foreach($news_category_parent as $category_parent)
                    @php
                        $news_category_child = $category_parent->child;
                    @endphp
                <li class="has-sub">
                    <button class="" aria-label="button">
                        <mark>{{ $category_parent->name }}</mark>
                    </button>
                    <div class="sub-menu-drop" data-show="30">
                        <div class="mark-mobile">
                            <mark>{{ $category_parent->name }}</mark>
                        </div>
                        @foreach($news_category_child as $category_child)
                        <div class="has-child">
                            <a class="link-load" href="{{ route('module.frontend.news_outside', ['cate_id' => $category_child->id, 'parent_id' => $category_child->parent_id, 'type' => 1]) }}">
                                <mark>{{ $category_child->name }}</mark>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
      </div>
    </div>
</nav>