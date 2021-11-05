@extends('layouts.backend')

@section('page_title', 'Danh mục video')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training_video') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.daily_training') }}">{{ trans('backend.video_category') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.permission') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main" id="daily-training-category">
        <div class="row">
            <div class="col-md-12 form-inline">
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
                        <input type="text" name="search" class="form-control w-100" placeholder="{{ data_locale('Nhập mã / tên nhân viên', 'Enter the staff name / code') }}">
                    </div>
                    <div class="w-25">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }} </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="pull-right float-right">
                    @can('daily-training-permission-save')
                        <button type="button" class="btn btn-primary btnSave" >{{ trans('backend.save') }}</button>
                    @endcan
                    <input type="hidden" value="{{ $cate_id }}" name="category">
                </div>
            </div>
        </div>
        <br>
        <table class="tDefault table table-hover bootstrap-table" id="table">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-sortable="true" data-width="10%" data-field="code" data-sort-name="a.code">{{ trans('backend.code') }}</th>
                    <th data-width="25%" data-field="full_name">{{ trans('backend.fullname') }}</th>
                    <th data-field="email">{{ trans('backend.employee_email') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                </tr>
            </thead>
        </table>
        
    </div>
    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: "{{ route('module.daily_training.user.getdata',['cate_id'=>$cate_id]) }}",
            remove_url: '{{ route('module.daily_training.remove') }}'
        });

        $(function() {
            $('.btnSave').on('click', function () {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                var category = $('input[name=category]').val();
                var btn = $(this),
                    btn_text = btn.html();
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
                if (ids.length <= 0) {
                    show_message('Vui lòng chọn người cần phân quyền', 'error');
                    btn.prop('disabled', false).html(btn_text);
                    return false;
                }

                $.ajax({
                    url: '{{ route('module.daily_training.user.save_permission') }}',
                    type: 'post',
                    dataType:'json',
                    data: {
                        ids: ids,
                        category: category
                    }
                }).done(function(data) {
                    btn.prop('disabled', false).html(btn_text);
                    show_message(data.message, data.status);
                    if(data.status=='success'){
                        $('#table').bootstrapTable(
                            'refresh', {
                                url: '{{ url('/admin-cp/daily-training/user/getdata')}}/'+category,
                            });
                    }

                }).fail(function(data) {
                    btn.prop('disabled', false).html(btn_text);
                    show_message('Lỗi hệ thống', 'error');
                    return false;
                });
            });
        })
    </script>
@endsection
