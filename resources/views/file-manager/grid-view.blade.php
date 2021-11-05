@if((sizeof($files) > 0) || (sizeof($directories) > 0))

<div class="row">
  @foreach($items as $item)
  <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 img-row">
    <?php $item_id = $item->id; ?>
    <?php $item_name = $item->name; ?>
    <?php $thumb_src = $item->thumb; ?>
    <?php $item_path = $item->is_file ? $item->url : $item->path; ?>

    <div class="square clickable {{ $item->is_file ? '' : 'folder-item' }}" data-id="{{ $item_path }}"
           @if($item->is_file) onclick="useFile('{{ $item_path }}', '{{ $item->name }}')"
            @endif >
      @if($thumb_src)
      <img src="{{ $thumb_src }}">
      @else
      <i class="fa {{ $item->icon }} fa-5x"></i>
      @endif
    </div>

    <div class="caption text-center">

      <div class="btn-group title_lfm">
        <div class="row m-0 w-100">
          <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 p-1">
            <button type="button" data-id="{{ $item_path }}" title="{{$item_name}}" class="item_name btn btn-default btn-xs{{ $item->is_file ? '' : 'folder-item'}}" @if($item->is_file && $thumb_src) onclick="useFile('{{ $item_path }}', '{{ $item->name }}')" @endif >
              {{ $item_name }}
            </button>
          </div>
          <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 m-auto">
              <a href="javascript:rename('{{ $item->id }}','{{ $item->name }}','{{$item->type}}')" title="{{ Lang::get('lfm.menu-rename') }}">
                  <i class="fa fa-edit fa-fw"></i>
              </a>
            <a class="text-danger" href="javascript:trash('{{ $item_id }}', '{{ $item_name }}', '{{ $item->is_file }}')"><i class="fa
            fa-trash"></i></a>
          </div>
        </div>
      </div>
    </div>

  </div>
  @endforeach

</div>

@else
<p>{{ Lang::get('lfm.message-empty') }}</p>
@endif
