@section('page_title', 'Danh mục hoạt động đào tạo')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a> / Danh mục hoạt động đào tạo
        </h2>
    </div>
@endsection

<div>
    <a wire:click="from" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Thêm mới</a>

    <button wire:click="delete">Delete Post</button>
</div>
