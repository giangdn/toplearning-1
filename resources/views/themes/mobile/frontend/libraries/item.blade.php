@php
    if ($librarie->type == 1){
         $url = route('module.libraries.book.detail', ['id' => $librarie->id]);
    }elseif ($librarie->type == 2){
         $url = route('module.libraries.ebook.detail', ['id' => $librarie->id]);
    } elseif ($librarie->type == 3){
        $url = route('module.libraries.document.detail', ['id' => $librarie->id]);
    } elseif ($librarie->type == 4){
        $url = route('module.libraries.video.detail', ['id' => $librarie->id]);
    } else{
        $url = route('module.libraries.audiobook.detail', ['id' => $librarie->id]);
    }
@endphp
<div class="card shadow border-0">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-6 p-1">
                <a href="{{ $url }}">
                    <img src="{{ image_file($librarie->image) }}" alt="" style="height: 220px;object-fit: cover" class="w-100 img-responsive">
                </a>
            </div>
            <div class="col-12 col-md-6 p-1 align-self-center">
                <p class="text-mute">
                    <span>{{ $librarie->views }} <i class="material-icons vm small">remove_red_eye</i></span>
                    <span class="float-right small">{{ \Carbon\Carbon::parse($librarie->created_at)->diffForHumans() }}</span>
                </p>
                <a href="{{ $url }}">
                    <p class="font-weight-normal">{{ sub_char($librarie->name, 8) }}</p>
                </a>
            </div>
        </div>
    </div>
</div>

