@extends('layouts.backend')

@section('page_title', trans('backend.training_partner'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title">
            <a href="{{ route('backend.category') }}">
                <i class="far fa-arrow-alt-circle-left"></i>
                {{ trans('backend.category') }}
            </a>
            <i class="uil uil-angle-right"></i>
            <span class="">{{ trans('backend.training_partner') }}</span>
        </h2>
    </div>
    <div role="main">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline form-search mb-3" id="form-search">
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ trans('backend.enter_code_name_partner') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>

                </form>
            </div>
            <div class="col-md-4 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @can('user-create')
                            <div class="btn-group">
                                <!-- <button class="btn btn-info" id="model-list-template-import"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</button> -->
                                <!-- <button class="btn btn-info" id="model-list-import"><i class="fa fa-upload"></i> Import</button> -->
                                <a class="btn btn-info" href="{{ route('backend.training_partner_export') }}"><i class="fa fa-download"></i> Export</a>
                            </div>
                        @endcan
                        @can('category-partner-create')
                            <button style="cursor: pointer;" onclick="create()" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                        @endcan
                        @can('category-partner-delete')
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
                    <th data-sortable="true" data-field="code" data-width="10%">{{ trans('backend.code') }}</th>
                    <th data-sortable="true" data-field="name" data-formatter="name_formatter">{{ trans('backend.partner_name') }}</th>
                    <th data-field="people" >{{ trans('backend.contact') }}</th>
                    <th data-field="address">{{ trans('backend.address') }}</th>
                    <th data-field="email">Email</th>
                    <th data-field="phone">{{ trans('backend.phone') }}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal right fade" id="modal-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="ajax-modal-popup" role="document">
            <form action="" method="post" class="form-ajax" id="form_save">
                <div class="modal-content">
                    <div class="modal-header">
                        {{-- <h5 class="modal-title" id="exampleModalLabel"></h5> --}}
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="btn-group act-btns">
                            @canany(['category-partner-create', 'category-partner-edit'])
                                <button type="button" onclick="save(event)" class="btn btn-primary save">{{ trans('lacore.save') }}</button>
                            @endcan
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                        </div>
                    </div>
                    <div class="modal-body" id="body_modal">
                        <input type="hidden" name="id" value="">
                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="code">{{ trans('backend.code') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="code" type="text" class="form-control" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="name">{{ trans('backend.contact_name') }}<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-7">
                                <input name="name" type="text" class="form-control" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="people">{{ trans('backend.contact') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input name="people" type="text" class="form-control" value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="address">{{ trans('backend.address') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input name="address" type="text" class="form-control" value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="email">Email</label>
                            </div>
                            <div class="col-md-7">
                                <input name="email" type="text" class="form-control" value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4 control-label">
                                <label for="phone">{{ trans('backend.phone') }}</label>
                            </div>
                            <div class="col-md-7">
                                <input name="phone" type="text" class="form-control" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a id="edit_'+ row.id +'" style="cursor: pointer;" onclick="edit('+ row.id +')">'+ row.name +'</a>' ;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('backend.category.training_partner.getdata') }}',
            remove_url: '{{ route('backend.category.training_partner.remove') }}'
        });

        function edit(id){
            let item = $('#edit_'+id);
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Vui lòng chờ...');
            $.ajax({
                url: "{{ route('backend.category.training_partner.edit') }}",
                type: 'post',
                data: {
                    id: id,
                }
            }).done(function(data) {
                item.html(oldtext);
                $('#exampleModalLabel').html('Chỉnh sửa ' + data.name);
                $("input[name=id]").val(data.id);
                $("input[name=code]").val(data.code);
                $("input[name=name]").val(data.name);
                $("input[name=people]").val(data.people);
                $("input[name=address]").val(data.address);
                $("input[name=email]").val(data.email);
                $("input[name=phone]").val(data.phone);
                $('#modal-popup').modal();
                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function save(event) {
            let item = $('.save');
            let oldtext = item.html();
            item.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
            $('.save').attr('disabled',true);

            var form = $('#form_save');
            var name =  $("input[name=name]").val();
            var id =  $("input[name=id]").val();
            var code =  $("input[name=code]").val();
            var people = $("input[name=people]").val();
            var address = $("input[name=address]").val();
            var email = $("input[name=email]").val();
            var phone = $("input[name=phone]").val();
            event.preventDefault();
            $.ajax({
                url: "{{ route('backend.category.training_partner.save') }}",
                type: 'post',
                data: {
                    'name': name,
                    'code': code,
                    'id': id,
                    'people': people,
                    'address': address,
                    'email': email,
                    'phone': phone,
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
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        }

        function create() {
            $("input[name=name]").val('');
            $("input[name=id]").val('');
            $("input[name=code]").val('');
            $("input[name=people]").val('');
            $("input[name=address]").val('');
            $("input[name=email]").val('');
            $("input[name=phone]").val('');
            $('#exampleModalLabel').html('Thêm đối tác');
            $('#modal-popup').modal();
        }
    </script>
@endsection
