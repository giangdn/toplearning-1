@extends('layouts.backend')

@section('page_title', 'Ghi chú')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline form-search-user w-100 mb-3" id="form-search">
                    <div class="w-25">
                        <select name="unit" class="form-control load-unit" data-placeholder="-- {{ trans('backend.unit') }} --"></select>
                    </div>
                    <div class="w-25">
                        <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                    </div>
                    <input type="text" class="form-control" name="search" value="" placeholder="Nhập mã/ tên nhân viên">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" id="contact_table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="fullname" data-width="15%">{{ trans('backend.name') }}</th>
                    <th data-field="date_time" data-width="10%">Ngày tạo</th>
                    <th data-field="content">Ghi chú</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.note.getdata') }}',
        });
    </script>
@endsection
