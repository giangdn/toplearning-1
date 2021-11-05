@php
    \App\Slider::addGlobalScope(new \App\Scopes\CompanyScope());
    $sliders = \App\Slider::where('type',1)->where('location',0)->where('status', 1)->get();
    $user_id = \Auth::id();
    $get_unit =  \App\ProfileView::where('user_id', $user_id)->first();
        // dd($sliders);
@endphp
<div class="header banner_logo w-100">
    <span class="line-color"></span>
    <div class="banner_home">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach ($sliders as $key => $slider)
                    @php
                        $check_unit = 0;
                        if (!empty($slider->object) && !empty($get_unit)) {
                            $check_objects = json_decode($slider->object);
                            foreach ($check_objects as $check_object) {
                                $unit_code = \App\Models\Categories\Unit::find($check_object);
                                $get_array_childs = \App\Models\Categories\Unit::getArrayChild($unit_code->code);
                                if( in_array($get_unit->unit_id, $get_array_childs) || ($get_unit->unit_id == $unit_code->id) ) {
                                    $check_unit = 1;
                                }
                            }
                        }
                    @endphp
                    @if ( (!empty($slider->object) && $check_unit == 1) || empty($slider->object))
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <img class="d-block w-100" src="{{ image_file($slider->image) }}" alt="" class="w-100">
                        </div>
                    @endif
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
    </div>
</div>
