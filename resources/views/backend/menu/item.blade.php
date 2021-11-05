@php
    $tabs = Request::segment(2);
    $tabs_3 = Request::segment(3);
@endphp
@foreach($items as $key => $item)
    @if(!$item['permission'])
        @continue
    @endif
    @php
        $url = '';
        if(session()->has($item['name_url'])) {
            $url = session()->get($item['name_url']);
        }
        $active = '';
        if (request()->is(get_uri($item['url']) . '/*') || request()->is(get_uri($item['url'])) || 
            (!empty($item['url_child']) && (in_array($tabs, $item['url_child']) || (in_array($tabs_3, $item['url_child']))))) {
                $active = 'active';
        }
        if ($item['name_url'] == 'menu_quiz' && $tabs == 'dashboard' && !$tabs_3) {
            $active = '';
        }
    @endphp
    <li class="menu--item">
        <a href="{{ $url ? $url : $item['url'] }}" class="menu--link {{ $active }}"
            title="{{ $item['name'] }}"
        >
            <i class="{{ $item['icon'] }}"></i>
            <span class="menu--label">{{ $item['name'] }}</span>
        </a>
    </li>
@endforeach
<script>
    // if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
    //     $('.fa-chevron-down').show();
    //     $('.fa-chevron-right').hide();
    // }else{
    //     $(document).ready(function(){
    //         $(".fa-chevron-right").hide();

    //         $( ".li_menu_sub" ).hover(function() {
    //             if ($('.li_menu_sub:hover').length > 0){
    //                 $(".vertical_nav").css('min-width','440px')
    //             } else {
    //                 $(".vertical_nav").css('min-width','0px')
    //             }
    //         });

    //         $('.li_menu_sub').click(function(){
    //             $('.li_menu_sub').removeClass('menu--subitens__opened');
    //         });
    //     })
    // }
</script>
