@extends('layouts.backend')

@section('page_title', 'Liên hệ')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.setting') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.google.map') }}">Địa điểm đào tạo</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Danh sách địa điểm đào tạo</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" class="form-control" name="search" value="">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('guide-delete')
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" id="contact_table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="title" data-width="25%" data-formatter="name_formatter">{{ trans('backend.name') }}</th>
                    <th data-field="description" data-formatter="description">Mô tả</th>
                    <th data-field="lat" data-width="15%">Vĩ độ</th>
                    <th data-field="lng" data-width="15%">Kinh độ</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.title +' </a>';
        }
        function description(value, row, index) {
            return '<span class="contact_posts">'+ row.description +'</span>'
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.google.map.getdata') }}',
            remove_url: '{{ route('backend.google.map.remove') }}'
        });
    </script>
@endsection
