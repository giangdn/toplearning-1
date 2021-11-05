@extends('layouts.backend')

@section('page_title', trans('backend.study_promotion_program'))

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
                    <input type="text" name="search" value="" class="form-control " placeholder="{{ trans('backend.enter_gift_name') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('promotion-create')
                            <button class="btn btn-primary" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp; Bật
                            </button>
                            <button class="btn btn-warning" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp; Tắt
                            </button>
                        @endcan
                    </div>
                    <div class="btn-group">
                        @can('promotion-create')
                            <a href="{{ route('module.promotion.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('promotion-delete')
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
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
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{trans('backend.status')}}</th>
                    <th data-field="code" data-align="center">{{ trans('backend.gift_code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('backend.gift_name') }}</th>
                    <th data-field="images" data-align="center" data-formatter="image_formatter">{{trans('backend.picture')}}</th>
                    <th data-field="point" data-align="center">{{ trans('backend.points_change') }}</th>
                    <th data-field="amount" data-align="center">{{ trans('backend.quantity') }}</th>
                    <th data-field="period" data-align="center">{{ trans('backend.duration') }}</th>
                    <th data-field="rules" data-align="center">{{ trans('backend.regulations') }}</th>
                    <th data-field="contact" data-align="center">{{ trans('backend.contacts') }}</th>
                    <th data-field="groupname" data-align="center">{{ trans('backend.category_group') }}</th>
                    <th data-field="created_by" data-align="center">{{ trans('backend.user_create') }}</th>
                    <th data-field="created_at" data-align="center">{{trans('backend.created_at')}}</th>
                    <th data-field="updated_by" data-align="center">{{trans('backend.user_updated')}}</th>
                    <th data-field="updated_at" data-align="center">{{trans('backend.date_updated')}}</th>
                </tr>
                </thead>
            </table>
        </div>

    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name+'</a>';
        }
        function image_formatter(value,row,index) {
            return '<img src="'+row.images+'" width="200px" height="150px">'
        }

        function status_formatter(value, row, index) {
            var status = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.promotion.getdata') }}',
            remove_url: '{{ route('module.promotion.remove') }}'
        });

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('Vui lòng chọn ít nhất 1 nhóm quà tặng', 'error');
                    return false;
                }
            }
            $.ajax({
                url: "{{ route('module.promotion.ajax_is_open') }}",
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
    </script>

@endsection
