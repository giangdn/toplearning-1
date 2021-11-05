<header class="header">
    <div class="inner-header">
        <a href="{{ route('home_outside',['type' => 0]) }}">
            <div class="logo" style="background: url({{ image_file(\App\Config::getLogoOutside()) }}) no-repeat 50%/100%"></div>
        </a>
        
    </div>
    @if (session()->has('show_home_page') && session()->get('show_home_page') == 1 && !empty(session()->get('login')))
        <div class="group-right menu_top">
            <ul>
                <li class="has-sub m-0">
                    <a href="{{ route('module.news') }}" style="color: white;" class="no-action btn m-2" aria-label="button">
                        <mark>QUAY LẠI</mark>
                    </a>
                </li>
            </ul>
        </div>
    @else
        <div class="group-right menu_top">
            <ul>
                <li class="has-sub contact m-0">
                    <a href="{{ route('user_contact_outside') }}" style="color: white;" class="no-action" aria-label="button">
                        <mark>THÔNG TIN LIÊN HỆ</mark>
                    </a>
                </li>
                <li class="has-sub m-0">
                    <a href="{{ route('login') }}" style="color: white;" class="no-action" aria-label="button">
                        <mark>ĐĂNG NHẬP</mark>
                        <span class="icon" style="background: url({{ asset('images/user_tt.png') }}) no-repeat 50%/100%"></span>
                    </a>
                </li>
            </ul>
        </div>
    @endif
    
    <div class="second-menu menu_web" style="opacity: 1;">
        {{-- <span class="line-color"></span> --}}
        <div class="group-left">
            <ul>
                @php
                    $news_category_parent = \Modules\NewsOutside\Entities\NewsOutsideCategory::query()->orderBy('stt_sort_parent')->whereNull('parent_id')->get();
                @endphp
                <li class="li_logo_home">
                    <a href="{{ route('home_outside',['type' => 0]) }}">
                        <img src="{{asset('images/home_outside.png')}}" alt="">
                    </a>
                </li>
                @foreach($news_category_parent as $category_parent)
                    @php
                        $news_category_child = $category_parent->child;
                    @endphp
                <li class="has-sub">
                    <button class="no-action" aria-label="button">
                        {{-- <span class="icon" style="background: url({{ image_file($category_parent->icon) }}) no-repeat 50%/100%"></span> --}}
                        <a class="text-light" href="{{ route('module.frontend.news_outside', ['cate_id' => 0, 'parent_id' => $category_parent->id, 'type' => 0]) }}">
                            <mark>{{ $category_parent->name }}</mark>
                        </a>
                    </button>
                    <div class="sub-menu-drop" data-show="30">
                        <div class="mark-mobile">
                            <mark>{{ $category_parent->name }}</mark>
                        </div>
                        @foreach($news_category_child as $category_child)
                        <div class="has-child">
                            <a class="link-load" href="{{ route('module.frontend.news_outside', ['cate_id' => $category_child->id, 'parent_id' => $category_child->parent_id, 'type' => 1]) }}">
                                {{-- <span class="icon" style="background: url({{ image_file($category_child->icon) }}) no-repeat 50%/100%"></span> --}}
                                <mark>{{ $category_child->name }}</mark>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @if (session()->has('show_home_page') && session()->get('show_home_page') == 1 && !empty(session()->get('login')))
            <div class="group-right menu_bottom">
                <ul>
                    <li class="has-sub m-2">
                        <a href="{{ route('module.news') }}" style="color: white;" class="no-action" aria-label="button">
                            <mark>QUAY LẠI</mark>
                        </a>
                    </li>
                </ul>
            </div>
        @else
            <div class="group-right menu_bottom">
                <ul>
                    <li class="has-sub">
                        <a href="{{ route('login') }}" style="color: white;" class="no-action" aria-label="button">
                            <span class="icon" style="background: url({{ asset('images/user_tt.png') }}) no-repeat 50%/100%"></span>
                            <mark>ĐĂNG NHẬP</mark>
                        </a>
                    </li>
                </ul>
            </div>
        @endif
    </div>

    <div class="second-menu menu_mobile" style="opacity: 1;">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="{{ route('home_outside',['type' => 0]) }}">
                <img src="{{asset('images/home_outside.png')}}" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
              <ul class="navbar-nav">

                @foreach($news_category_parent as $category_parent)
                    @php
                        $news_category_child = $category_parent->child;
                    @endphp
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ $category_parent->name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            @foreach($news_category_child as $category_child)
                                <a class="dropdown-item" href="{{ route('module.frontend.news_outside', ['cate_id' => $category_child->id, 'parent_id' => $category_child->parent_id, 'type' => 1]) }}">
                                    {{ $category_child->name }}
                                </a>
                            @endforeach
                        </div>
                    </li>
                @endforeach
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                </li>
              </ul>
            </div>
          </nav>
    </div>

    <div class="overlay-banner"></div>
    <div class="overlay-menu"></div>
</header>
