@extends('layouts.backend')

@section('page_title', 'Quản Lý Mượn Sách')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12 form-inline">
                <form class="form-inline form-search mb-3 w-100" id="form-search">
                    <div class="w-25">
                        <input type="text" name="search" value="" class="form-control w-100" autocomplete="off" placeholder="{{trans('backend.search')}}">
                    </div>
                    <div class="w-25">
                        <input name="borrow_date" type="text" class="datepicker form-control w-100" placeholder="{{trans('backend.date_borrow')}}" autocomplete="off">
                    </div>
                    <div class="w-25">
                        <input name="pay_date" type="text" class="datepicker form-control w-100" placeholder="{{trans('backend.pay_day')}}" autocomplete="off">
                    </div>
                    <div class="w-25">
                        <select name="status" id="status" class="form-control select2" data-placeholder="{{trans('backend.status')}}">
                            <option value=""></option>
                            <option value="1">{{trans('backend.get_book_yet')}}</option>
                            <option value="2">{{trans('backend.borrowing_book')}}</option>
                            <option value="3">{{trans('backend.book_back')}}</option>
                        </select>
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{ route('module.libraries.book.register.export') }}">
                            <i class="fa fa-download"></i> Export
                        </a>
                        <button class="btn btn-success approve" data-status="1">
                            <i class="fa fa-check-circle"></i> {{trans('backend.approve')}}
                        </button>
                        <button class="btn btn-danger approve" data-status="0">
                            <i class="fa fa-exclamation-circle"></i> {{trans('backend.deny')}}
                        </button>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-primary status" data-status="2">
                            <i class="fa fa-check-circle"></i> &nbsp;{{trans('backend.get_books')}}
                        </button>
                        <button class="btn btn-warning status" data-status="3">
                            <i class="fa fa-exclamation-circle"></i> &nbsp;{{trans('backend.book_back')}}
                        </button>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr>
                <th data-field="index" data-align="center" data-width="2%" data-formatter="index_formatter">STT</th>
                <th data-field="check" data-checkbox="true" data-width="2%"></th>
                <th data-field="book_name" data-width="25%">{{trans('backend.book_name')}}</th>
                <th data-field="current_number" data-width="5%" data-align="center">Số lượng sách</th>
                <th data-field="quantity" data-width="5%" data-align="center">Số lượng sách mượn</th>
                <th data-field="full_name" data-align="center" data-width="10%">{{trans("backend.borrower")}}</th>
                <th data-field="unit_name" data-align="center" data-width="10%">{{ trans('backend.work_unit') }}</th>
                <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                <th data-field="title_name" data-align="center" data-width="10%">{{ trans('backend.title') }}</th>
                <th data-field="borrow_date" data-align="center" data-width="10%">{{trans("backend.date_borrow")}}</th>
                <th data-field="user_return_book" data-align="center" data-width="10%">{{trans("backend.pay_day")}}</th>
                <th data-field="pay_date" data-align="center" data-width="10%">Hạn trả</th>
                <th data-field="register_date" data-align="center" data-width="10%">{{trans("backend.date_register")}}</th>
                <th data-field="status" data-align="center" data-width="10%">{{trans('backend.status')}}</th>
            </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1);
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.libraries.book.register.getdata') }}',
            remove_url: '{{ route('module.libraries.book.register.remove') }}'
        });

    </script>
    <script src="{{ asset('styles/module/libraries/js/register_book.js') }}"></script>
@endsection




