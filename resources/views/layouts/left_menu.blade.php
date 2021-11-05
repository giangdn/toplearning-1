@php
    $tabs = Request::segment(1);
    $tabs_course = Request::segment(2);
    $user_type = \Modules\Quiz\Entities\Quiz::getUserType();
@endphp
<div class="search_button_vertical_frontend">
    <button type="button" id="toggleMenu" class="toggle_menu">
        <i class='uil uil-bars'></i>
    </button>
    <button id="collapse_menu" class="collapse_menu">
        <i class="uil uil-bars collapse_menu--icon "></i>
        <span class="collapse_menu--label"></span>
    </button>
</div>
<nav class="vertical_nav vertical-fontend">

    <div class="left_section menu_left menu-left-frontend" id="js-menu" >
        <div class="left_section left-menu-frontend">
            <ul>
                @if($user_type == 1)
                <li id="news_menu" class="menu--item menu--item__has_sub_menu">
                    <a href="{{ route('module.news') }}" class="menu--link @if ($tabs == "news")
                        active
                    @endif" title="@lang('app.news')" data-turbolinks="false">
                        <i class="far fa-newspaper menu--icon"></i>
                        <span class="menu--label">@lang('app.news')</span>
                    </a>
                </li>

                <li id="info_user" class="menu--item menu--item__has_sub_menu">
                    <a href="{{ route('module.frontend.user.info') }}" class="menu--link @if ($tabs == "user")
                        active
                    @endif" title="@lang('app.user_info')" data-turbolinks="false">
                        <i class="far fa-user menu--icon"></i>
                        <span class="menu--label">@lang('app.user_info')</span>
                    </a>
                </li>

                <li class="menu--item">
                    <a href="/" class="menu--link @if ($tabs == "")
                        active
                    @endif" title="Home">
                        <i class='uil uil-home-alt menu--icon'></i>
                        <span class="menu--label">Dashboard</span>
                    </a>
                </li>

                <li class="menu--item">
                    <a href="{{ route('frontend.calendar') }}" class="menu--link @if ($tabs == "calendar")
                        active
                    @endif" title="@lang('app.training_calendar')">
                        <i class="uil uil-calendar-alt menu--icon"></i>
                        <span class="menu--label">@lang('app.training_calendar')</span>
                    </a>
                </li>

                <li id="course_menu" class="menu--item">
                    <a href="{{ route('frontend.all_course',['type' => 0]) }}" class="menu--link @if ($tabs == "all-course" || $tabs == "all-course-search")
                        active
                    @endif" title="Khóa học" data-turbolinks="false">
                    <i class="fas fa-chalkboard menu--icon"></i>
                        <span class="menu--label">Khóa học</span>
                    </a>
                </li>

                @endif
                <li class="menu--item">
                    <a href="{{ route('module.quiz') }}" class="menu--link @if ($tabs == "quiz")
                        active
                    @endif" title="@lang('app.quiz')" data-turbolinks="false">
                        <i class='far fa-question-circle menu--icon'></i>
                        <span class="menu--label">@lang('app.quiz')</span>
                    </a>
                </li>
                @if($user_type == 1)
                <li class="menu--item">
                    <a href="{{ route('module.survey') }}" class="menu--link @if ($tabs == "survey")
                        active
                    @endif" title="@lang('app.survey')">
                        <i class="fab fa-wpforms menu--icon"></i>
                        <span class="menu--label">@lang('app.survey')</span>
                    </a>
                </li>

                {{-- <li class="menu--item">
                    <a href="{{ route('module.career_roadmap.frontend') }}" class="menu--link @if ($tabs == "career-roadmap")
                            active @endif" title="@lang('career.career_roadmap')">
                        <i class='fas fa-chart-line menu--icon'></i>
                        <span class="menu--label">@lang('career.career_roadmap')</span>
                    </a>
                </li> --}}

                {{--<li class="menu--item">
                    <a href="{{ route('frontend.plan_app') }}" class="menu--link @if ($tabs == "plan-app")
                        active
                    @endif" title="@lang('backend.plan_app')">
                        <i class="uil uil-edit-alt menu--icon"></i>
                        <span class="menu--label">@lang('backend.plan_app')</span>
                    </a>
                </li>--}}

                <li class="menu--item">
                    <a href="{{ route('module.rating_level') }}" class="menu--link @if ($tabs == "rating-level") active @endif" title="Đánh giá hiệu quả đào tạo" data-turbolinks="false">
                        <i class="uil uil-edit-alt menu--icon"></i>
                        <span class="menu--label">Đánh giá hiệu quả đào tạo</span>
                    </a>
                </li>

                <li class="menu--item">
                    <a href="{{ route('module.suggest.index') }}" class="menu--link @if ($tabs == "suggest")
                        active
                    @endif">
                        <i class='uil uil-comment-alt-exclamation menu--icon'></i>
                        <span class="menu--label">@lang('app.suggest')</span>
                    </a>
                </li>

                <li class="menu--item">
                    <a href="{{ route('frontend.note') }}" class="menu--link @if ($tabs == "note")
                        active
                    @endif">
                        <i class="far fa-sticky-note menu--icon"></i>
                        <span class="menu--label">Ghi chú</span>
                    </a>
                </li>

                @php
                    $last_review =  \Modules\Capabilities\Entities\CapabilitiesResult::getLastReviewUser(Auth::id());
                @endphp
                @if($last_review)
                <li class="menu--item">
                    <a href="{{ route('module.frontend.user.my_capabilities') }}" class="menu--link @if ($tabs == "my-capabilities")
                        active @endif" title="@lang('backend.capability')">
                        <i class='uil uil-list-ul menu--icon'></i>
                        <span class="menu--label">@lang('backend.capability')</span>
                    </a>
                </li>
                @endif

                <li class="menu--item">
                    <a href="{{ route('module.daily_training.frontend') }}" class="menu--link @if ($tabs == "daily-training")
                        active
                    @endif" title="@lang('backend.training_video')">
                        <i class="uil uil-video menu--icon"></i>
                        <span class="menu--label">@lang('backend.training_video')</span>
                    </a>
                </li>

                <li id="libary_menu" class="menu--item menu--item__has_sub_menu">
                    @php
                        $tabs_2 = Request::segment(2);
                    @endphp
                    <label class="menu--link @if( $tabs_2 == 'book' || $tabs_2 == 'ebook' || $tabs_2 == 'document' || $tabs_2 == 'video' || $tabs_2 == 'audiobook')
                        active
                        @endif"
                        title="@lang('app.libraries')"
                    >
                        <i class="fas fa-book menu--icon"></i>
                        <span class="menu--label">@lang('app.libraries')</span>
                        <i class="fa fa-chevron-down libary-fa-down"></i>
                        <i class="fa fa-chevron-right libary-fa-right"></i>
                    </label>

                    <ul class="sub_menu" onmouseover="hoverSubmenu('libary_menu')" onmouseout="outHover('libary_menu')">
                        <li class="sub_menu--item">
                            <a href="{{ route('module.frontend.libraries.book',['id' => 0]) }}" class="sub_menu--link @if($tabs_2 == 'book') active @endif">
                                <i class="fas fa-book menu_icon_child"></i>
                                @lang('app.book')
                            </a>
                        </li>
                        <li class="sub_menu--item">
                            <a href="{{ route('module.frontend.libraries.ebook',['id' => 0]) }}" class="sub_menu--link @if($tabs_2 == 'ebook') active @endif ">
                                <i class="fas fa-bookmark menu_icon_child"></i>
                                @lang('app.ebook')
                            </a>
                        </li>
                        <li class="sub_menu--item">
                            <a href="{{ route('module.frontend.libraries.document',['id' => 0]) }}" class="sub_menu--link @if($tabs_2 == 'document') active @endif ">
                                <i class="fas fa-passport menu_icon_child"></i>
                                @lang('app.document')
                            </a>
                        </li>
                        <li class="sub_menu--item">
                            <a href="{{ route('module.frontend.libraries.audiobook',['id' => 0]) }}" class="sub_menu--link @if($tabs_2 == 'audiobook') active @endif ">
                                <i class="fas fa-file-audio menu_icon_child"></i>
                                Sách nói
                            </a>
                        </li>
                        <li class="sub_menu--item">
                            <a href="{{ route('module.frontend.libraries.video',['id' => 0]) }}" class="sub_menu--link @if($tabs_2 == 'video') active @endif ">
                                <i class="fas fa-play-circle menu_icon_child"></i>
                                Video
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu--item">
                    <a href="{{ route('module.frontend.forums') }}" class="menu--link @if ($tabs == "forums")
                        active
                    @endif" title="@lang('app.forum')">
                        <i class='uil uil-layers menu--icon'></i>
                        <span class="menu--label">@lang('app.forum')</span>
                    </a>
                </li>

                {{-- CHƯƠNG TRÌNH THI ĐUA --}}
                <li class="menu--item">
                    <a href="{{ route('frontend.emulation_program') }}" class="menu--link @if ($tabs == "emulation-program")
                        active
                    @endif" title="Chương trình thi đua" data-turbolinks="false">
                        <i class="fas fa-award menu--icon"></i>
                        <span class="menu--label">Chương trình thi đua</span>
                    </a>
                </li>

                {{-- Thảo luận tình huống --}}
                <li class="menu--item">
                    <a href="{{ route('frontend.topic_situations') }}" class="menu--link @if ($tabs == "topic-situations")
                        active
                    @endif" title="Thảo luận tình huống" data-turbolinks="false">
                        <i class="fas fa-award menu--icon"></i>
                        <span class="menu--label">Xử lý tình huống</span>
                    </a>
                </li>

                <li class="menu--item">
                    <a href="{{ route('module.front.promotion') }}" class="menu--link @if ($tabs == "promotion")
                        active
                    @endif" title="@lang('app.promotion')">
                        <i class='fas fa-gift menu--icon'></i>
                        <span class="menu--label">@lang('app.promotion')</span>
                    </a>
                </li>

                <li class="menu--item">
                    <a href="{{ route('frontend.attendance') }}" class="menu--link @if ($tabs == "attendance")
                        active
                    @endif">
                        <i class="far fa-registered menu--icon"></i>
                        <span class="menu--label">@lang('app.attendance')</span>
                    </a>
                </li>

                {{--<li class="menu--item">
                    <a href="{{ route('module.training_action.list') }}" class="menu--link @if ($tabs == "training-action") active @endif" title="@lang('backend.training_action')">
                        <i class='uil uil-palette menu--icon'></i>
                        <span class="menu--label">@lang('backend.training_action')</span>
                    </a>
                </li>--}}

                @endif
            </ul>
        </div>
        @if($user_type == 1)
        <div class="left_section left-menu-frontend-2 pt-2 mb-5">
            <ul>
                <li id="guide" class="menu--item menu--item__has_sub_menu">
                    @php
                        $tabs_2 = Request::segment(2);
                    @endphp
                    <label class="menu--link @if( $tabs_2 == 'video-guide' || $tabs_2 == 'posts-guide' || $tabs_2 == 'pdf')
                        active
                        @endif"
                        title="@lang('app.guide')"
                    >
                        <i class='uil uil-book-alt menu--icon'></i>
                        <span class="menu--label">@lang('app.guide')</span>
                        <i class="fa fa-chevron-down guide-fa-down"></i>
                        <i class="fa fa-chevron-right guide-fa-right"></i>
                    </label>

                    <ul class="sub_menu" onmouseover="hoverSubmenu('guide')" onmouseout="outHover('guide')">
                        <li class="sub_menu--item">
                            <a href="{{ route('module.frontend.guide.video') }}" class="sub_menu--link @if($tabs_2 == 'video-guide') active @endif">
                                <i class="fas fa-file-video menu_icon_child"></i>
                                Video
                            </a>
                        </li>
                        <li class="sub_menu--item">
                            <a href="{{ route('module.frontend.guide.posts') }}" class="sub_menu--link @if($tabs_2 == 'posts-guide') active @endif ">
                                <i class="fas fa-file-alt menu_icon_child"></i>
                                Bài viết
                            </a>
                        </li>
                        <li class="sub_menu--item">
                            <a href="{{ route('frontend.guide.pdf') }}" class="sub_menu--link @if($tabs_2 == 'pdf') active @endif ">
                                <i class="fas fa-file-pdf menu_icon_child"></i>
                                PDF
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu--item">
                    <a href="{{ route('module.faq.frontend.index') }}" class="menu--link  @if ($tabs == "faq")
                        active
                    @endif" title="{{ trans('app.faq') }}">
                        <i class='far fa-comments menu--icon'></i>
                        <span class="menu--label">@lang('app.faq')</span>
                    </a>
                </li>

                <li class="menu--item">
                    @php
                        $user = \App\User::find(\Auth::id());
                    @endphp
                    <a href="http://social-network.toplearning.vn/login_el.php?username={{ $user->username }}&password={{ $user->password }}" target="_blank" class="menu--link" title="Mạng xã hội">
                        <i class='fab fa-battle-net menu--icon'></i>
                        <span class="menu--label">Mạng xã hội</span>
                    </a>
                </li>
                {{-- <li class="menu--item">
                    <a href="{{ route('frontend.contact') }}" class="menu--link  @if ($tabs == "contact")
                        active
                    @endif" title="Liên hệ">
                        <i class='far fa-comments menu--icon'></i>
                        <span class="menu--label">Liên hệ</span>
                    </a>
                </li>
                <li class="menu--item">
                    <a href="{{ route('frontend.google.map') }}" class="menu--link  @if ($tabs == "google-map")
                        active
                    @endif" title="Địa điểm đào tạo">
                        <i class='fas fa-map-marker-alt menu--icon' aria-hidden="true"></i>
                        <span class="menu--label">Địa điểm đào tạo</span>
                    </a>
                </li> --}}
            </ul>
    </div>
        @endif
    </div>
</nav>
<script>
    $(document).on("turbolinks:load", function() {
        $(".fa-chevron-right").hide()
        $( "#libary_menu" ).hover(function() {
            if ($('#libary_menu').is(':hover')) {
                if ($('.vertical-fontend').hasClass('vertical_nav__minify')) {
                    $(".vertical-fontend").css('min-width','200px');
                    $("#libary_menu .sub_menu").css('left','50px');
                } else {
                    $(".vertical-fontend").css('min-width','440px');
                    $("#libary_menu .sub_menu").css('left','240px');
                }
                $(".libary-fa-down").hide()
                $(".libary-fa-right").show()
            } else {
                $(".vertical-fontend").css('min-width','60px')
                $(".libary-fa-down").show()
                $(".libary-fa-right").hide()
            }
        });
        $( "#guide" ).hover(function() {
            if ($('#guide').is(':hover')) {
                if ($('.vertical-fontend').hasClass('vertical_nav__minify')) {
                    $(".vertical-fontend").css('min-width','200px');
                    $("#guide .sub_menu").css('left','50px');
                } else {
                    $(".vertical-fontend").css('min-width','440px');
                    $("#guide .sub_menu").css('left','240px');
                }
                $(".guide-fa-down").hide()
                $(".guide-fa-right").show()
            } else {
                $(".vertical-fontend").css('min-width','60px')
                $(".guide-fa-down").show()
                $(".guide-fa-right").hide()
            }
        });

        if ($('.sub_menu_news').hasClass('active')) {
            $('.label_news').addClass('active');
        } else {
            $('.label_news').removeClass('active');
        }
    });
    function hoverSubmenu (menu) {
        $('#'+ menu).css('background', get_hover_color);
        $('#'+ menu).find("label").css('color','#ffffff !important');
    }
    function outHover (menu) {
        $('#'+ menu).css('background','unset');
        $('#'+ menu).find("label").css('color','unset');
    }
</script>
