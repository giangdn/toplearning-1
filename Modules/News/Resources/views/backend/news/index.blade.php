@extends('layouts.backend')

@section('page_title', 'Quản Lý Bài Viết')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')

    <div role="main">

        <div class="row">
            <div class="col-md-12 form-inline">
                <form class="form-inline w-100" id="form-search">
                    <div class="w-25">
                        <select name="cate_id" id="cate_id" class="form-control select2" data-placeholder="Chọn danh mục"> 
                            <option value=""></option>
                            @foreach ($cates as $cate)
                                <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-25">
                        <input type="text" name="search" value="" class="form-control w-100" placeholder="{{trans('backend.search_post')}}">
                    </div>
                    <div class="w-25">
                        <input name="start_date" type="text" class="datepicker form-control w-100" placeholder="{{trans('backend.start_date')}}" autocomplete="off">
                    </div>
                    <div class="w-25">
                        <input name="end_date" type="text" class="datepicker form-control w-100" placeholder="{{trans('backend.end_date')}}" autocomplete="off">
                    </div>
                    <div class="w-25">
                        <select name="type" id="type" class="form-control select2"> 
                            <option value="" selected disabled>Thể loại</option>
                            <option value="1">Bài viết</option>
                            <option value="2">Video</option>
                            <option value="3">Hình ảnh</option>
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
                        @can('news-list-create')
                            <a href="{{ route('module.news.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        @endcan
                        @can('news-list-delete')
                            <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                        @endcan
                    </div>
                    <div class="btn-group">
                        @can('news-list-status')
                            <button class="btn btn-primary" onclick="changeStatus(0,1)" data-status="1">
                                <i class="fa fa-check-circle"></i> &nbsp; Bật
                            </button>
                            <button class="btn btn-warning" onclick="changeStatus(0,0)" data-status="0">
                                <i class="fa fa-exclamation-circle"></i> &nbsp; Tắt
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter">#</th>
                    <th data-field="check" data-checkbox="true"></th>
                    <th data-field="title" data-formatter="name_formatter">{{trans('backend.titles')}}</th>
                    <th data-field="type" data-formatter="type">Thể loại</th>
                    <th data-field="status" data-align="center" data-formatter="status_formatter">{{trans('backend.status')}}</th>
                    <th data-field="category_name" style="width:70px">{{ trans('backend.category') }}</th>
                    <th data-field="created_by" data-formatter="created_by_formatter">{{ trans('backend.writer') }}</th>
                    <th data-field="created_at2">{{trans('backend.created_at')}}</th>
                    <th data-field="updated_by"data-formatter="updated_by_formatter">{{ trans('backend.edited_by') }}</th>
                    <th data-field="updated_at2">{{trans('backend.edit_at')}}</th>
                    <th data-field="views">{{ trans('backend.views') }}</th>
                    <th data-field="like_new">Lượt thích</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">

        function index_formatter(value, row, index) {
            return (index+1);
        }
        function type(value, row, index) {
            if (row.type == 1) {
               return '<span >Bài viết</span>';
            } else if (row.type == 2) {
                return '<span>Video</span>';
            } else {
                return '<span>Hình ảnh</span>';
            }
        }
        function name_formatter(value, row, index) {
            return '<a href="'+ row.edit_url +'">'+ row.title +'</a>';
        }
        
        function status_formatter(value, row, index) {
            var status = row.status == 1 ? 'checked' : '';
            var html = `<div class="custom-control custom-switch">
                            <input type="checkbox" `+ status +` onclick="changeStatus(`+row.id+`)" class="custom-control-input" id="customSwitch_`+row.id+`">
                            <label class="custom-control-label" for="customSwitch_`+row.id+`"></label>
                        </div>`;
            return html;
        }

        function created_by_formatter(value, row, index) {
            return row.created_by;
        }
        function updated_by_formatter(value, row, index) {
            return row.updated_by;
        }
        // function action_formatter(value, row, index) {
        //     return '<i class="fa fa-eye"></i>';
        // }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.news.getdata') }}',
            remove_url: '{{ route('module.news.remove') }}'
        });

        var ajax_isopen_publish = "{{ route('module.news.ajax_isopen_publish') }}";

        function changeStatus(id,status) {
            if (id && !status) {
                var ids = id;
                var checked = $('#customSwitch_' + id).is(":checked");
                var status = checked == true ? 1 : 0;
            } else {
                var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
                if (ids.length <= 0) {
                    show_message('Vui lòng chọn ít nhất 1 bài viết', 'error');
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
    <script src="{{ asset('styles/module/news/js/news.js') }}"></script>
@endsection
