@extends('layouts.backend')

@section('page_title', 'Quản Lý Tài Liệu')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')

    <div role="main">

        <div class="row">
            <div class="col-md-7 form-inline">
                <form class="form-inline w-100 form-search mb-3" id="form-search">
                    <input type="text" name="search" class="form-control w-50" placeholder="Tìm kiếm tài liệu/Nguồn">
                    <div class="w-25">
                        <select name="category_id" class="form-control select2" data-placeholder="{{trans('backend.category')}}">
                            <option value=""></option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-5 text-right act-btns">
                <div class="pull-right">
                    @can('libraries-document-edit')
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
                        <a class="btn btn-info" href="{{ route('module.libraries.document.export') }}"><i class="fa fa-download"></i> Export</a>
                        @can('libraries-document-create')
                            <a href="{{ route('module.libraries.document.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('libraries-document-delete')
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
                    <th data-field="index" data-align="center" data-width="2%" data-formatter="index_formatter">STT</th>
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="name" data-width="25%" data-formatter="name_formatter">{{trans('backend.document_name')}}</th>
                    <th data-field="name_author" data-align="center" data-width="20%">Nguồn soạn thảo</th>
                    <th data-field="category_name">{{trans("backend.document_category")}}</th>
                    <th data-field="updated_at2" data-align="center" data-width="10%">{{trans('backend.last_updated')}}</th>
                    <th data-field="user_name" data-align="center" data-width="5%">{{trans('backend.update_by')}}</th>
                    <th data-field="status" data-align="center" data-width="5%" data-formatter="status_formatter">{{trans('backend.status')}}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">

        function index_formatter(value, row, index) {
            return (index+1);
        }

        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.name +'</a>';
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
            url: '{{ route('module.libraries.document.getdata') }}',
            remove_url: '{{ route('module.libraries.document.remove') }}'
        });

        var ajax_isopen_publish = "{{ route('module.document.ajax_isopen_publish') }}";

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
    </script>
@endsection




