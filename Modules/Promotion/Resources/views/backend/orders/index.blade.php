@extends('layouts.backend')

@section('page_title', 'Lịch sử quà tặng')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder="{{ trans('backend.enter_gift_name') }}" />
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;@lang('app.search')</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                      {{--  <a href="{{ route('module.promotion.orders.buy') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>@lang('app.add_new')</a>--}}
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i>@lang('app.delete')</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table class="tDefault table table-hover bootstrap-table">
                <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="orders_id" data-align="center" data-formatter="orders_formatter">@lang('app.order_id')</th>
                    <th data-sortable="true" data-field="name">@lang('app.gift')</th>
                    <th data-field="user" data-align="center">@lang('app.buyer')</th>
                    <th data-field="status" data-align="center">@lang('app.status')</th>
                    <th data-field="created_at" data-align="center">@lang('app.date_of_purchase')</th>
                </tr>
                </thead>
            </table>
        </div>

    </div>

    <script type="text/javascript">
        function orders_formatter(value,row,index) {
            return '<a href="'+ row.edit_url +'">'+ row.orders_id+'</a>';
        }

        function image_formatter(value,row,index) {
            return '<img src="'+row.images+'" width="200px" height="150px">'
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.promotion.orders.buy.getdata') }}',
            remove_url: '{{ route('module.promotion.orders.buy.remove') }}'
        });

    </script>

@endsection
