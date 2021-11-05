@php
    $tree_child = \App\Models\Categories\Unit::where('parent_code', '=', $parent_code)->get();
@endphp
<ul>
    @foreach($tree_child as $item)
        @php
            $count_child = \App\Models\Categories\Unit::countChild($item->code);
        @endphp
    <li>
        <a href="javascript:void(0)" data-id="{{ $item->id }}" class="tree-item">
            @if($item->level == 4) <i class="uil uil-plus"></i> @else <i class="uil-minus uil"></i> @endif {{ $item->name .' ('. $count_child . ')' }}
        </a>
        <div id="list{{ $item->id }}">
            @if($item->level < 4)
                @include('backend.category.unit.tree_child', ['parent_code' => $item->code])
            @endif
        </div>
    </li>
    @endforeach
</ul>

