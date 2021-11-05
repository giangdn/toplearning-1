@extends('layouts.backend')

@section('page_title', 'Chương trình thi đua')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12">
                <form id="form-search" class="mb-3">
                    <div class="form-row align-items-center">
                        <div class="col-sm-2 my-1">
                            <input type="text" name="search" value="" class="form-control" autocomplete="off" placeholder="Tên / Mã chương trình">
                        </div>
                        <div class="col-sm-2 my-1">
                            <input name="time_start" type="text" class="datepicker form-control" placeholder="{{ trans('backend.start_date') }}" autocomplete="off">
                        </div>
                        <div class="col-sm-2 my-1">
                            <input name="time_end" type="text" class="datepicker form-control" placeholder="{{ trans('backend.end_date') }}" autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-primary ml-2"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-right act-btns">
                <div class="pull-right">
                    @can('emulation-program-export')
                        <div class="btn-group">
                            <a class="btn btn-info" href="{{route('backend.emulation_program.export')}}"><i class="fa fa-download"></i> Export</a>
                        </div>
                    @endcan
                    @can('emulation-program-approved')
                        <div class="btn-group">
                            <button class="btn btn-success approve" data-status="1">
                                <i class="fa fa-check-circle"></i> {{ trans('backend.approve') }}
                            </button>
                            <button class="btn btn-danger approve" data-status="2">
                                <i class="fa fa-exclamation-circle"></i> {{ trans('backend.deny') }}
                            </button>
                        </div>
                    @endcan
                    @can('emulation-program-open')
                        <div class="btn-group">
                            <button class="btn btn-primary" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp;{{ trans('backend.enable') }}
                            </button>
                            <button class="btn btn-warning" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp;{{ trans('backend.disable') }}
                            </button>
                        </div>
                    @endcan
                    <div class="btn-group">
                        @can('emulation-program-create')
                            <a href="{{ route('backend.emulation_program.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('emulation-program-delete')
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
                    <th data-field="isopen" data-sortable="true" data-align="center" data-formatter="isopen_formatter" data-width="3%">{{ trans('backend.status') }}</th>
                    <th data-field="image" data-formatter="image_formatter" data-width="15%">Hình ảnh</th>
                    <th data-field="name">Tên chương trình</th>
                    <th data-field="code" data-align="center" data-width="10%">Mã chương trình</th>
                    <th data-formatter="date_formatter" data-align="center" data-width="18%">{{ trans('backend.time') }}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="10%">{{ trans('backend.approve') }}</th>
                    <th data-field="result" data-align="center" data-formatter="result_formatter" data-width="5%">Kết quả</th>
                    <th data-field="edit" data-align="center" data-formatter="edit_formatter" data-width="5%">Chỉnh sửa</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function date_formatter(value, row, index) {
            return row.time_start  + (row.time_end ? ' <i class="fa fa-arrow-right"></i> ' + row.time_end : ' ');
        }

        function image_formatter(value,row,index) {
            return '<img src="'+ row.image + '" width="100%" height="auto">'
        }

        function result_formatter(value,row,index) {
            return '<a href="'+ row.result_emulation +'"> <i class="fas fa-edit"></i></a>'
        }

        function edit_formatter(value,row,index) {
            return '<a href="'+ row.edit_url +'"> <i class="fas fa-edit"></i></a>'
        }

        function isopen_formatter(value, row, index) {
            var status = row.isopen == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        function status_formatter(value, row, index) {
            value = parseInt(value);
            switch (value) {
                case 0: return '<span class="text-danger">{{ trans("backend.not_approved") }}</span>';
                case 1: return '<span class="text-success">{{trans("backend.approve")}}</span>';
                case 2: return '<span class="text-warning">{{ trans("backend.deny") }}</span>';
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.emulation_program.getdata') }}',
            remove_url: '{{ route('backend.emulation_program.remove') }}'
        });

        var ajax_isopen_publish = "{{ route('backend.emulation_program.ajax_isopen_publish') }}";

        // BẬT / TẮT
        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('Vui lòng chọn ít nhất 1 khóa học', 'error');
                    return false;
                }
            }
            $.ajax({
                url: ajax_isopen_publish,
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                show_message(data.message, data.status);
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        };

        // DUYỆT
        $('.approve').on('click', function () {
            var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
            var status = $(this).data('status');

            if (ids.length <= 0) {
                show_message('Vui lòng chọn ít nhất 1 Chương trình', 'error');
                return false;
            }

            $.ajax({
                url: "{{ route('backend.emulation_program.approve') }}",
                type: 'post',
                data: {
                    ids: ids,
                    status: status
                }
            }).done(function(data) {
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        });
    </script>
@endsection
