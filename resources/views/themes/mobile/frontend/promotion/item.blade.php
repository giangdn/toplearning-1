<div class="list-group-item">
    <div class="row align-items-center">
        <div class="col-auto align-self-center pr-0 pl-0">
            <img src="{{ image_file($promotion->images) }}" alt="" class="avatar-100">
        </div>
        <div class="col pr-0">
            <form action="{{ route('module.front.promotion.get', ['id' => $promotion->id]) }}" method="post" class="form-ajax">
                @csrf
                <button type="submit" class="p-0" style="background: white; border: none; outline: none;">
                    <h6 class="@if($promotion_user && $promotion_user->point >= $promotion->point) font-weight-bold @else text-black-50 @endif text-left">{{ $promotion->name }}</h6>
                </button>
            </form>
            <p class="text-mute">
                {{ $promotion->group_name }}
                <br>
                {{ $promotion->point }}
                <img class="avatar-20 point" src="{{ asset('images/level/point.png') }}" alt="">
                <span class="float-right">
                    @lang('app.quantity'): <strong>{{ $promotion->amount }}</strong>
                </span>
                <br>
                @lang('app.end_date'): <b>{{ \Carbon\Carbon::parse($promotion->period)->format('d/m/Y') }}</b>
            </p>
        </div>
    </div>
</div>
