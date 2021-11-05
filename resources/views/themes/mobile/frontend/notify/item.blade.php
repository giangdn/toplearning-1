<div class="list-group-item" >
    <a href="{{ route('themes.mobile.frontend.notify.detail', ['id' => $notify->id, 'type' => $notify->type]) }}">{{ $notify->subject }}</a>
    <span class="float-right vm btn-delete" data-id="{{ $notify->id .'_'.$notify->type }}">
        <i class="material-icons text-danger">delete</i>
    </span>
</div>
