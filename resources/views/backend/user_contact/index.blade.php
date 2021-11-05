{{-- @extends('layouts.backend')

@section('page_title', 'Người dùng liên hệ')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">Người dùng liên hệ</span>
        </h2>
    </div>
@endsection

@section('content') --}}
    <div role="main">
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline form-search-user w-100 mb-3" id="form-search">
                    <input type="text" class="form-control datepicker" name="search" value="" placeholder="-- Nhập ngày tạo --">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table" id="contact_table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="created_at" data-width="10%">Ngày tạo</th>
                    <th data-field="title">Tiêu đề</th>
                    <th data-field="content">Nội dung</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.user-contact.getdata') }}',
            remove_url: '{{ route('backend.user-contact.remove') }}'
        });
    </script>
{{-- @endsection --}}
