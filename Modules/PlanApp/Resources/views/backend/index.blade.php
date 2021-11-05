@extends('layouts.backend')

@section('page_title', 'Mẫu đánh giá hiệu quả đào tạo')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <a href="{{route('backend.evaluationform.manager')}}"> {{ trans('backend.evaluation_form') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.evaluate_training_effectiveness') }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder="{{trans('backend.enter_code_name_block')}}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('plan-app-template-create')
                        <a href="{{ route('module.plan_app.template.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('plan-app-template-delete')
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true"></th>
                <th data-sortable="true" data-formatter="name_formatter" data-field="name" >{{ trans('backend.evaluate_training_effectiveness_form') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+row.name+'</a>';
        }
        // function detail_formatter(value, row, index) {
        //     return '<a href="'+ row.edit_url +'"><i class="fa fa-certificate"></i></a>';
        // }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.plan_app.template.getdata') }}',
            remove_url: '{{ route('module.plan_app.template.remove') }}'
        });

    </script>

@endsection
