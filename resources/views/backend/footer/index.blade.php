@extends('layouts.backend')

@section('page_title', 'Quản lý footer')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.setting') }}
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Quản lý footer</span>
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
{{--            <div class="col-md-4 text-right act-btns">--}}
{{--                <div class="pull-right">--}}
{{--                    <div class="btn-group">--}}
{{--                        <a href="{{ route('backend.footer.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>--}}
{{--                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="name" data-formatter="name_formatter">{{trans('backend.titles')}}</th>
{{--                    <th data-field="status" data-formatter="status_formatter" data-width="10%" data-align="center">{{trans('backend.status')}}</th>--}}
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">' + row.name +'</a>';
        }

        function status_formatter(value, row, index) {
            if (value == 1) {
                return '<span class="text-success">{{trans("backend.enable")}}</span>';
            }
            return '<span class="text-danger">{{trans("backend.disable")}}</span>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.footer.getdata') }}',
            remove_url: '{{ route('backend.footer.remove') }}'
        });
    </script>
@endsection
