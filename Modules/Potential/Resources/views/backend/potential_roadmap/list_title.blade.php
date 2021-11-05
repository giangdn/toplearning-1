@extends('layouts.backend')

@section('page_title', 'Chương trình khung nhân sự tiềm năng')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.trainingroadmap.list') }}">{{trans("backend.list_roadmap")}}</a> <i class="uil uil-angle-right"></i> {{trans("backend.potential_resource")}}
        </h2>
    </div>
@endsection
@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline" id="form-search">
                    <div class="w-50">
                        <select name="title" class="form-control load-title" data-placeholder="--{{ trans('backend.title') }} --"></select>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter" data-align="center" data-width="2%">#</th>
                    <th data-field="code" >{{trans('backend.title_code')}}</th>
                    <th data-field="name" data-formatter="name_formatter" >{{trans('backend.title_name')}}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">

        function index_formatter(value, row, index) {
            return (index+1);
        }
        function name_formatter(value, row, index) {
            return '<a href="'+ row.title_url +'"> '+row.name+' </a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.potential.roadmap.getdata_title') }}',
        });
    </script>
@endsection
