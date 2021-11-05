@extends('layouts.backend')

@section('page_title', 'Câu hỏi đề xuất')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">Câu hỏi đề xuất</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">
        <div class="row">
            <div class="col-md-8 form-inline">
                <form class="form-inline" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('backend.enter_category') }}">
                    <div class="w-25">
                    <select name="parent_id" class="form-control select2" data-placeholder="-- {{ trans('backend.parent_category') }} --">
                        <option value=""></option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        <a href="javascript:void(0)" class="btn btn-primary load-modal" data-url="{{ route('module.training_unit.questionlib.get_modal') }}"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</a>
                        <button class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-field="index" data-formatter="index_formatter" data-width="3%" data-align="center">#</th>
                    <th data-field="state" data-checkbox="true" data-width="3%"></th>
                    <th data-field="name" data-formatter="name_formatter" data-width="30%">Danh mục câu hỏi</th>
                    <th data-field="parent_name">{{ trans('backend.parent_category') }}</th>
                    <th data-field="created_by2"> {{ trans('backend.created_by') }}</th>
                    <th data-field="quantity" data-width="10%" data-align="center">Số lượng câu hỏi</th>
                    <th data-field="question" data-width="10%" data-align="center" data-formatter="question_formatter">{{ trans('backend.question') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index+1);
        }

        function name_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="edit-item" data-id="'+ row.id +'">'+ value +'</a>';
        }

        function question_formatter(value, row, index) {
            return '<a href="'+ row.question_url +'"><i class="fa fa-cogs"></i></a>';
        }


        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.training_unit.questionlib.getdata_category') }}',
            remove_url: '{{ route('module.training_unit.questionlib.remove_category') }}'
        });

        function success_submit(form) {
            $("#app-modal #myModal").modal('hide');
            table.refresh();
        }

        $("div[role=main]").on('click', '.edit-item', function () {
            let item = $(this);
            let oldtext = item.html();
            let id = item.data('id');
            item.html('<i class="fa fa-spinner fa-spin"></i> Vui lòng chờ...');

            $.ajax({
                type: 'POST',
                url: '{{ route('module.training_unit.questionlib.get_modal') }}',
                dataType: 'html',
                data: {
                    'id': id,
                },
            }).done(function(data) {
                item.html(oldtext);
                $("#app-modal").html(data);
                $("#app-modal #myModal").modal();
            }).fail(function(data) {
                item.html(oldtext);
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        });
    </script>
@endsection
