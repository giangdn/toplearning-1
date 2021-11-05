@extends('layouts.backend')

@section('page_title', trans('backend.setting_like'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div role="main" id="daily-training-category">
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('score-like-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                        @endcan
                        @can('score-like-delete')
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
                    <th data-field="check" data-checkbox="true" data-width="2%"></th>
                    <th data-field="from">{{ trans('backend.from') }}</th>
                    <th data-field="to">{{ trans('backend.to') }}</th>
                    <th data-field="score">{{ trans('backend.score') }}</th>
                    <th data-align="center" data-formatter="edit_formatter">{{ trans('backend.edit') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('backend.likes_from') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input name="from" type="text" placeholder="{{ trans('backend.enter_quantity') }}" min="1" class="form-control is-number" value="" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('backend.likes_to') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input name="to" type="text" placeholder="{{ trans('backend.enter_quantity') }}" class="form-control is-number" value="" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label>{{ trans('backend.score') }}</label>
                            </div>
                            <div class="col-md-4">
                                <input name="score" type="text" placeholder="{{ trans('backend.enter_score') }}" class="form-control is-number" value="" >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @canany(['score-like-create','score-like-edit'])
                            <button type="button" onclick="save(event)" class="btn btn-primary save">{{ trans('lacore.save ') }}</button>
                        @endcan
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script type="text/javascript">
        function edit_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')"><i class="uil uil-edit-alt"></i></a>' ;
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.daily_training.score_like.getdata') }}',
            remove_url: '{{ route('module.daily_training.score_like.remove') }}'
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Vui lòng chờ...');
            $.ajax({
                url: "{{ route('module.daily_training.score_like.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('Chỉnh sửa');
                $("input[name=id]").val(data.id);
                $("input[name=from]").val(data.from);
                $("input[name=to]").val(data.to);
                $("input[name=score]").val(data.score);
                $('#modal-popup').modal();
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        }

        function save(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
            $('.save').attr('disabled',true);

            var form = $('#form_save');
            var from =  $("input[name=from]").val();
            var id =  $("input[name=id]").val();
            var to =  $("input[name=to]").val();
            var score = $("input[name=score]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('module.daily_training.score_like.save') }}",
                type: 'post',
                data: {
                    'from': from,
                    'id': id,
                    'to': to,
                    'score' : score
                }
            }).done(function(data) {
                item.html(oldtext);
                $('.save').attr('disabled',false);
                if (data && data.status == 'success') {
                    $('#modal-popup').modal('hide');
                    show_message(data.message, data.status);
                    $(table.table).bootstrapTable('refresh');
                } else {
                    show_message(data.message, data.status);
                }
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        }

        function create() {
            $("input[name=from]").val('');
            $("input[name=id]").val('');
            $("input[name=to]").val('');
            $("input[name=score]").val('');
            $('#exampleModalLabel').html('Thêm mới');
            $('#modal-popup').modal();
        }
    </script>
@endsection
