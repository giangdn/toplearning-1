@extends('layouts.backend')

@section('page_title', 'API')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-6">
            </div>
            <div class="col-md-6 text-right act-btns">
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-align="center" data-formatter="stt_formatter" data-width="50px">STT</th>
                    <th  data-field="name">{{ trans('backend.name') }}</th>
                    <th  data-formatter="status_formatter" data-field="error" data-align="center" data-width="120px">Trạng thái</th>
                    @can('api-manual-sync')
                    <th  data-formatter="sync_formatter" data-align="center" data-width="120px">Chạy đồng bộ</th>
                    @endcan
                    <th data-field="duration" data-width="160px">Khoảng thời gian</th>
                    <th data-field="updated_date" data-width="170px">{{ trans('backend.date_updated') }}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function stt_formatter(value, row, index) {
            return (index + 1);
        }
        function status_formatter(value, row, index) {
            if(value==0){
                return '<span class="text-success"> thành công</span>';
            }else if(value==1){
                return '<span class="text-danger"> Thất bại</span>';
            }else
                return '-';
        }
        function sync_formatter(value, row, index) {
            return `<a href="javascript:void(0)" class="sync_api" data-id="${row.id}"><i class="fas fa-sync"></i></a>`;
        }
        function name_formatter(value,row,index) {
            return '<a href="'+row.edit+'">'+row.description+'</a>'+'<br>'+'<span style="color:#888; font-size: .75em">'+row.command+'</span>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.manual-api') }}',
            delete_method: 'delete'
        });
        $(document).on('click','.sync_api',function () {
            let id = $(this).data('id');
            let btn = $(this);
            let current_icon = btn.find('i').attr('class');
            btn.find('i').attr('class', 'fa fa-spinner fa-spin');
            btn.prop("disabled", true);
            $.ajax({
                url: base_url +'/admin-cp/manual-api/sync-manual',
                type: 'post',
                data: {
                    id
                }
            }).done(function(data) {
                show_message(data.message, data.status);
                btn.find('i').attr('class', current_icon);
                btn.prop("disabled", false);
                $(table.table).bootstrapTable('refresh');
                return false;
            }).fail(function(data) {
                btn.find('i').attr('class', current_icon);
                btn.prop("disabled", false);
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        })
    </script>
@endsection
