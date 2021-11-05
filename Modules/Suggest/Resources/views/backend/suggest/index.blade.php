@extends('layouts.backend')

@section('page_title', 'Quản lý góp ý')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')

    <div role="main">
        <div class="row">
            <div class="col-md-12">
                    <form class="form-inline form-search-user mb-3" id="form-search">
                        @for($i = 1; $i <= 5; $i++)
                            <div class="w-25">
                                <select name="unit" id="unit-{{ $i }}"
                                    class="form-control load-unit"
                                    data-placeholder="-- ĐV cấp {{$i}} --"
                                    data-level="{{ $i }}" data-loadchild="unit-{{ $i+1 }}" data-parent="0">
                                </select>
                            </div>
                        @endfor
                        <div class="w-25">
                            <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                        </div>
                        <div class="w-25">
                            <select name="title" class="form-control load-title" data-placeholder="-- {{ trans('backend.title') }} --"></select>
                        </div>

                        <div class="w-25">
                            <select name="status" class="form-control select2" data-placeholder="-- {{ trans('backend.status') }} --">
                                <option value=""></option>
                                <option value="0">{{ trans('backend.inactivity') }}</option>
                                <option value="1">{{ trans('backend.doing') }}</option>
                                <option value="2">{{ trans('backend.probationary') }}</option>
                                <option value="3">{{ trans('backend.pause') }}</option>
                            </select>
                        </div>

                        <div class="w-25">
                            <input type="text" name="search" class="form-control w-100" value="" placeholder="{{ trans('backend.enter_suggest') }}">
                        </div>
                        <div class="w-25">
                            <input name="start_date" type="text" class="datepicker form-control w-100" placeholder="{{ trans('backend.start_date') }}" autocomplete="off">
                        </div>
                        <div class="w-25">
                            <input name="end_date" type="text" class="datepicker form-control w-100" placeholder="{{ trans('backend.end_date') }}" autocomplete="off">
                        </div>
                        <div class="w-25">
                            <input type="text" name="search_code_name" class="form-control w-100" placeholder="{{ data_locale('Nhập mã / tên nhân viên', 'Enter the staff name / code') }}">
                        </div>
                        <div class="w-25">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="name" data-formatter="name_formatter">{{ trans('backend.suggestion') }}</th>
                    <th data-field="profile">{{ trans('backend.user') }}</th>
                    <th data-field="email">Email</th>
                    <th data-field="title_name" data-width="10%">{{ trans('backend.title') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="unit_manager" data-with="5%">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="created_at2">{{ trans('backend.created_at') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">

        function name_formatter(value, row, index) {
            return  '<a href="'+ row.edit_url +'">'+ row.name +'</a>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.suggest.getdata') }}',
        });
    </script>
@endsection
