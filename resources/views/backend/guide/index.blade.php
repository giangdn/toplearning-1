@extends('layouts.backend')

@section('page_title', 'Quản lý hướng dẫn')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')

    <div role="main">
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline w-100" id="form-search">
                    <input type="text" class="form-control" name="search" value="" placeholder="Nhập tên">
                    <select name="type" id="type" class="form-control w-25">
                        <option value="" selected disabled>Thể loại</option>
                        <option value="1">File</option>
                        <option value="2">Video</option>
                        <option value="3">Bài viết</option>
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('guide-create')
                        <a href="{{ route('backend.guide.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('guide-delete')
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" id="guide_table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.name') }}</th>
                    <th data-field="type" data-formatter="type" data-align="center">Thể loại</th>
                    <th data-field="attach" data-formatter="attach">File/ Video/ Bài viết hướng dẫn</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +' </a>';
        }
        function type(value, row, index) {
            if (row.type == 2) {
                return '<span>Video</span>';
            } else if(row.type == 3) {
                return '<span>Bài viết</span>';
            } else {
                return '<span>File</span>';
            }
        }
        function attach(value, row, index) {
            if (row.type == 3) {
                return '<span class="guide_posts">'+ row.attach +'</span>'
            }
            return row.attach;
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.guide.getdata') }}',
            remove_url: '{{ route('backend.guide.remove') }}'
        });
    </script>
@endsection
