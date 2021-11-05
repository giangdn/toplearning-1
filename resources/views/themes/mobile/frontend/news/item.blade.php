<div class="card shadow-sm mb-1" style="border-radius: unset;">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-4 p-0" id="laster-news">
                <img src="{{ image_file($news->image) }}" alt="" class="w-100 border-0" style="height: 85px;">
            </div>
            <div class="col pr-0 align-self-center">
                <a href="{{ route('module.news.detail', ['id' => $news->id]) }}" >
                    <h6>{{ sub_char($news->title, 15) }}</h6>
                </a>
                <div class="row">
                    <div class="col-auto pr-1">
                        <img src="{{ \App\Profile::avatar($user_id) }}" alt="" class="avatar avatar-30 mt-1">
                    </div>
                    <div class="col pl-1">
                        <span class="small">{{ \App\Profile::fullname($user_id) }}</span>
                        <p class="text-mute small">
                            {{ $news->views }} @lang('app.view') -
                            @if(\Carbon\Carbon::parse($time)->diffInDays() > 10)
                                {{ get_date($time) }}
                            @elseif(\Carbon\Carbon::parse($time)->diffInHours() > 24)
                                {{ \Carbon\Carbon::parse($time)->diffInDays() .' '. trans('app.day_ago') }}
                            @elseif(\Carbon\Carbon::parse($time)->diffInMinutes() > 30)
                                {{ \Carbon\Carbon::parse($time)->diffInHours() .' '. trans('app.hour_ago') }}
                            @else
                                {{ \Carbon\Carbon::parse($time)->diffForHumans() }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
