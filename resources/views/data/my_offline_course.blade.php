<div class="fcrse_1">
    <a href="{{ route('module.online.detail_online', ['id' => $item->id]) }}" class="fcrse_img">
        <img src="{{ image_file($item->image) }}" alt="">
        <div class="course-overlay"></div>
    </a>
    <div class="fcrse_content">
        <div class="vdtodt">
            <span class="vdt14">First 2 days 22 hours</span>
        </div>
        <a href="#" class="crsedt145">{{ $item->name }}</a>
        <div class="allvperf">
            <div class="crse-perf-left">View</div>
            <div class="crse-perf-right">{{ $item->views }}</div>
        </div>
        <div class="allvperf">
            <div class="crse-perf-left">Purchased</div>
            <div class="crse-perf-right">150</div>
        </div>
        <div class="allvperf">
            <div class="crse-perf-left">Total Like</div>
            <div class="crse-perf-right">1k</div>
        </div>
        <div class="auth1lnkprce">
            <a href="{{ route('module.online.detail_online', ['id' => $item->id]) }}" class="cr1fot50">Detail</a>
            <a href="#" class="cr1fot50">See comments (875)</a>
            <a href="#" class="cr1fot50">See Reviews (105)</a>
        </div>
    </div>
</div>
