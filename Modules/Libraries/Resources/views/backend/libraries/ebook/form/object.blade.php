<form method="post" action="{{ route('module.libraries.ebook.save_object', ['id' => $model->id]) }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data" data-success="submit_success">
   <div class="row">
        <div class="col-12 mb-3">
            <p style="color: red">(Ghi chú: Mặc định không nhập Đối tượng, Chức danh, Nhân viên. Học viên đều có thể Xem và Tải nội dung)</p>
        </div>
        <div class="col-md-9">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('backend.object_belong') }}</label>
                </div>
                <div class="col-md-6">
                    <label class="radio-inline"><input type="radio" name="object" value="1" checked> {{ trans('backend.unit') }} </label>
                    <label class="radio-inline"><input type="radio" name="object" value="2"> {{ trans('backend.title') }} </label>
                    <label class="radio-inline"><input type="radio" name="object" value="3"> {{ trans('backend.user') }} </label>
                </div>
            </div>
       
            <div id="object-unit">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label> {{ trans('backend.unit') }} </label>
                    </div>
                    <div class="col-md-9">
                        <select name="unit_id[]" multiple {{--id="unit-2"--}} class="form-control load-unit" data-placeholder="-- {{ trans('backend.unit') }} --" {{--data-level="2"--}}></select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label> {{ trans('backend.permission') }} </label>
                    </div>
                    <div class="col-md-9">
                        <select name="status_unit" class="form-control select2" data-placeholder="{{ trans('backend.permission') }}">
                            <option value=""></option>
                            <option value="1">{{ trans('backend.view') }}</option>
                            <option value="2">{{ trans('backend.download') }}</option>
                            <option value="3">{{ trans('backend.download_view') }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="object-title">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label> {{ trans('backend.title') }} </label>
                    </div>
                    <div class="col-md-9">
                        <select id="title" class="form-control select2" multiple data-placeholder="-- {{ trans('backend.title') }} --">
                            @foreach($titles as $title)
                                <option value="{{ $title->id }}">{{ $title->name }}</option>
                            @endforeach
                        </select>
                        <input type="checkbox" id="checkbox" >{{ trans('backend.select_all') }}
                    </div>
                    <input type="hidden" name="title_id" class="form-control title" value="">
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label> {{ trans('backend.permission') }} </label>
                    </div>
                    <div class="col-md-9">
                        <select name="status_title" class="form-control select2" data-placeholder="{{ trans('backend.permission') }}">
                            <option value=""></option>
                            <option value="1">{{ trans('backend.view') }}</option>
                            <option value="2">{{ trans('backend.download') }}</option>
                            <option value="3">{{ trans('backend.download_view') }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="object-add">
                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-info"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                    </div>
                </div>
            </div>
        
            <div id="object-user">
                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        <a class="btn btn-info" href="{{ download_template('mau_import_nhan_vien_thu_vien.xlsx') }}"><i class="fa
                        fa-download"></i> {{trans('backend.import_template')}}</a>
                        <button class="btn btn-info" id="import-plan" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
    <div class="row">
        <div class="col-md-12" id="form-object">
            <div id="table-object">
                <div class="text-right">
                    <button id="delete-item" class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                </div>
                <p></p>
                <table class="tDefault table table-hover bootstrap-table">
                    <thead>
                        <tr>
                            <th data-field="state" data-checkbox="true"></th>
                            <th data-field="unit_name"> {{ trans('backend.unit') }}</th>
                            <th data-field="title_name">{{ trans('backend.title') }}</th>
                            <th data-field="status">{{ trans('backend.permission') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="table-user-object">
                <div class="text-right">
                    <button id="delete-user" class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                </div>
                <p></p>
                <table class="tDefault table table-hover bootstrap-table2" id="table-user">
                    <thead>
                        <tr>
                            <th data-field="state" data-checkbox="true"></th>
                            <th data-field="profile_code" data-width="5%">{{ trans('backend.employee_code') }}</th>
                            <th data-field="profile_name" data-width="25%">{{ trans('backend.employee_name') }}</th>
                            <th data-field="email" data-width="20%">{{ trans('backend.employee_email') }}</th>
                            <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                            <th data-field="parent">{{ trans('backend.unit_manager') }}</th>
                            <th data-field="title_name">{{ trans('backend.title') }}</th>
                            <th data-field="status">{{ trans('backend.permission') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('module.libraries.ebook.import_object', ['id' => $model->id]) }}" method="post" class="form-ajax">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">IMPORT {{ trans('backend.user') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.libraries.ebook.get_object', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.libraries.ebook.remove_object', ['id' => $model->id]) }}'
    });

    var table_user = new LoadBootstrapTable({
        url: '{{ route('module.libraries.ebook.get_user_object', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.libraries.ebook.remove_object', ['id' => $model->id]) }}',
        detete_button: '#delete-user',
        table: '#table-user'
    });
</script>

<script type="text/javascript">

    $('#title').on('change', function () {
        var title = $("#title option:selected").map(function(){return $(this).val();}).get();
        $('.title').val(title);
    });

    $("#checkbox").click(function(){
        if($("#checkbox").is(':checked') ){
            $("#title > option").prop("selected","selected");
            $("#title").trigger("change");

            var title = $("#title option:selected").map(function(){return $(this).val();}).get();
            $('.title').val(title);
        }else{
            $("#title > option").prop("selected", "");
            $("#title").trigger("change");
            $('.title').val('');
            $(table.table).bootstrapTable('refresh');
        }
    });

    function submit_success(form) {
        $("#title > option").prop("selected", "");
        $("#title").trigger("change");
        $('.title').val('');
        $("#checkbox").prop('checked', false);
        $("#object-unit select[name=unit_id\\[\\]]").val(null).trigger('change');
        $("#object-unit select[name=parent_id]").val(null).trigger('change');
        $("#object-unit select[name=status_unit]").val(null).trigger('change');
        $("#object-title select[name=status_title]").val(null).trigger('change');
        table.refresh();
        table_user.refresh();
    }

    $('#import-plan').on('click', function() {
        $('#modal-import').modal();
    });

    var object = $("input[name=object]").val();
    if (object == 1) {
        $("#object-add").show('slow');
        $("#object-unit").show('slow');
        $("#object-title").hide('slow');
        $("#object-user").hide('slow');
        $("#table-object").show('slow');
        $("#table-user-object").hide('slow');
    }
    else if (object == 2) {
        $("#object-add").show('slow');
        $("#object-unit").hide('slow');
        $("#object-title").show('slow');
        $("#object-user").hide('slow');
        $("#table-object").show('slow');
        $("#table-user-object").hide('slow');
    }
    else {
        $("#object-add").hide('slow');
        $("#object-unit").hide('slow');
        $("#object-title").hide('slow');
        $("#object-user").show('slow');
        $("#table-object").hide('slow');
        $("#table-user-object").show('slow');
    }

    $("input[name=object]").on('change', function () {
        var object = $(this).val();
        if (object == 1) {
            $("#object-add").show('slow');
            $("#object-unit").show('slow');
            $("#object-title").hide('slow');
            $("#object-user").hide('slow');
            $("#table-object").show('slow');
            $("#table-user-object").hide('slow');
            // $("#object-title select[name=title_id\\[\\]]").val(null).trigger('change');
            $("#title > option").prop("selected", "");
            $("#title").trigger("change");
            $('.title').val('');
            $("#checkbox").prop('checked', false);
        }
        else if (object == 2) {
            $("#object-add").show('slow');
            $("#object-unit").hide('slow');
            $("#object-title").show('slow');
            $("#object-user").hide('slow');
            $("#table-object").show('slow');
            $("#table-user-object").hide('slow');
            $("#object-unit select[name=unit_id\\[\\]]").val(null).trigger('change');
        }
        else {
            $("#object-add").hide('slow');
            $("#object-unit").hide('slow');
            $("#object-title").hide('slow');
            $("#object-user").show('slow');
            $("#table-object").hide('slow');
            $("#table-user-object").show('slow');
            // $("#object-title select[name=title_id\\[\\]]").val(null).trigger('change');
            $("#object-unit select[name=unit_id\\[\\]]").val(null).trigger('change');
            $("#title > option").prop("selected", "");
            $("#title").trigger("change");
            $('.title').val('');
            $("#checkbox").prop('checked', false);
        }
    });

</script>
