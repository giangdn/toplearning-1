<div class="search_button_vertical">
    <button type="button" id="toggleMenu" class="toggle_menu">
        <i class='uil uil-bars'></i>
    </button>
    <button id="collapse_menu" class="collapse_menu">
        <i class="uil uil-bars collapse_menu--icon "></i>
        <span class="collapse_menu--label"></span>
    </button>
</div>
<nav class="vertical_nav nav_backend">
    <div class="left_section menu_left menu_left_backend" id="js-menu" >
        <div class="left_section left_menu_backend">

            {!! \App\Helpers\MenuHelper\BackendMenuLeft::render() !!}

            {{--@if(Module::has('TrainingAction'))
            <li class="menu--item">
                <a href="{{ route('module.training_action') }}" class="menu--link {{ $tabs == 'module.training_action' ? 'hover-backend-menu' : '' }}" title="@lang('backend.training_action')">
                    <i class='uil uil-home-alt menu--icon'></i>
                    <span class="menu--label">@lang('backend.training_action')</span>
                </a>
            </li>
            @endif--}}
        </div>
    </div>
</nav>
