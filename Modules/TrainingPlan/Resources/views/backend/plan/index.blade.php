{{-- @extends('layouts.backend')

@section('page_title', 'Kế hoạch đào tạo năm')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{trans('backend.training_plan')}}</span>
        </h2>
    </div>
@endsection

@section('content') --}}

    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control " placeholder="Nhập mã/ tên khối/ đơn vị">
                    <input type="text" name="year" value="" class="form-control yearPicker" placeholder="Năm">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{trans('backend.search')}}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    @can('training-plan-create')
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
                        @can('training-plan-create')
                            <a href="{{ route('module.training_plan.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{trans('backend.add_new')}}</a>
                        @endcan
                        @can('training-plan-delete')
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{trans('backend.delete')}}</button>
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
                    <th data-sortable="true" data-field="code" data-width="10%">{{trans('backend.plan_code')}}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{trans('backend.plan_name')}}</th>
                    <th data-field="unit_name">{{trans('backend.unit')}}</th>
                    <th data-field="year" data-align="center">{{trans('backend.year')}}</th>
                    <th data-width="1%" data-align="center" data-formatter="plan_detail_formatter">{{trans('backend.detail')}}</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter" data-width="5%">{{trans('backend.status')}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function plan_detail_formatter(value, row, index) {
            var html = '';

            @can('training-plan-detail')
                html += '<a href="'+ row.plan_url +'">' + 'Xem' + '</a>';
            @endcan

            return html;
        }
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name+'</a>';
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
            url: '{{ route('module.training_plan.getdata') }}',
            remove_url: '{{ route('module.training_plan.remove') }}'
        });

        var ajax_isopen_publish = "{{ route('module.training_plan.ajax_isopen_publish') }}";

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('{{ trans('lacourse.min_one_course ') }}', 'error');
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
    
        $('.yearPicker').datetimepicker({
            format      :   "YYYY",
            viewMode    :   "years", 
        });
    </script>

{{-- @endsection --}}
