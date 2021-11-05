<nav class='greedy-nav'>
    <button><div class="hamburger"></div></button>
    <ul class='visible-links'>
        @foreach ($get_menu_child[0] as $key => $item)
            @if(!$item['permission'])
                @continue
            @endif
            @php
                if($name_url == $item['name_url']) {
                    session([$get_menu_child[1] => $item['url']]);
                    session()->save();
                }
            @endphp
            <li><a href="{{ $item['url'] }}" class="{{ $name_url == $item['name_url'] ? 'font-weight-bold border_breadcum' : '' }}">{{ $item['name'] }}</a></li>
        @endforeach
    </ul>
    <ul class='hidden-links hidden'></ul>
</nav>
@section('footer')
<script>
    var $nav = $('.greedy-nav');
    var $btn = $('.greedy-nav button');
    var $vlinks = $('.greedy-nav .visible-links');
    var $hlinks = $('.greedy-nav .hidden-links');

    $('.greedy-nav button').attr('style', 'background: '+ color +' !important');
    $( ".greedy-nav button" ).mouseover(function() {
        this.setAttribute('style', 'background: '+ get_hover_color +' !important');
    });
    $( ".greedy-nav button" ).mouseout(function() {
        this.setAttribute('style', 'background: '+ color +' !important');
    });

    var breaks = [];

    function updateNav() {
        var availableSpace = $btn.hasClass('hidden') ? $nav.width() : $nav.width() - $btn.width() - 30;
        if($vlinks.width() > availableSpace) {
            breaks.push($vlinks.width());
            $vlinks.children().last().prependTo($hlinks);

            if($btn.hasClass('hidden')) {
                $btn.removeClass('hidden');
            }

        } else {
            if(availableSpace > breaks[breaks.length-1]) {
                    $hlinks.children().first().appendTo($vlinks);
            breaks.pop();
            }

            if(breaks.length < 1) {
                $btn.addClass('hidden');
                $hlinks.addClass('hidden');
            }
        }

        $btn.attr("count", breaks.length);
        if($vlinks.width() > availableSpace) {
            updateNav();
        }
    }

    $(window).resize(function() {
        updateNav();
    });

    $btn.on('click', function() {
        $hlinks.toggleClass('hidden');
    });

    updateNav();
</script>
@endsection