    <div class="row">
        <div class="col-md-9">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{ trans('backend.object_belong') }}</label>
                </div>
                <div class="col-md-6">
                    <label class="radio-inline"><input type="radio" name="object" value="1" checked> {{ trans('backend.unit') }} </label>
                    <label class="radio-inline"><input type="radio" name="object" value="2"> {{ trans('backend.title') }} </label>
                    <label class="radio-inline"><input type="radio" name="object" value="3"> {{trans("backend.user")}} </label>
                </div>
            </div>
        <form method="post" action="{{ route('module.notify_send.save_object', ['id' => $model->id]) }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data" data-success="submit_success">
            <div id="object-unit">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label> {{ trans('backend.unit') }} </label>
                    </div>
                    <div class="col-md-9">
                        <select name="unit_id[]" id="unit_id" class="form-control load-unit" data-placeholder="-- {{ trans('backend.choose_unit') }} --" multiple>

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
                        <select name="title_id[]" id="title_id" class="form-control load-title" data-placeholder="-- {{trans('backend.choose_title')}} --" multiple>

                        </select>
                    </div>
                </div>
            </div>
            <div id="object-table">
                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                            <button type="submit" class="btn btn-info"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}</button>
                    </div>
                </div>
            </div>
        </form>
            <div id="object-user">
                @if(isset($errors))

                    @foreach($errors as $error)
                        <div class="alert alert-danger">{!! $error !!}</div>
                    @endforeach

                @endif
                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        <a class="btn btn-info" href="{{ download_template('mau_import_nguoi_dung.xlsx') }}"><i class="fa fa-download"></i> {{ trans('backend.import_template') }}</a>
                        <button class="btn btn-info" id="import-plan" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="form-object">
            <div class="text-right">
                <button id="send-object" class="btn btn-primary"><i class="fa fa-send"></i> {{ trans('backend.send_notify') }}</button>
                <button id="delete-item" class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
            </div>
            <p></p>
            <table class="tDefault table table-hover bootstrap-table">
                <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="profile_code">{{ trans('backend.employee_code') }}</th>
                    <th data-field="profile_name">{{ trans('backend.employee_name') }}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                    <th data-field="title_name">{{ trans('backend.title') }}</th>
                    <th data-field="time_send" data-align="center">{{ trans('backend.date_send') }}</th>
                    <th data-field="send_by" data-align="center">{{ trans('backend.user_send') }}</th>
                    <th data-field="status" data-formatter="status_formeter" data-align="center">{{trans('backend.status')}}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>


<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('module.notify_send.import_object', ['id' => $model->id]) }}" method="post" class="form-ajax">
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
        url: '{{ route('module.notify_send.get_object', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.notify_send.remove_object', ['id' => $model->id]) }}'
    });

    function status_formeter(value, row, index) {
        return value == 1 ? '{{ trans("backend.sent") }}' : '{{ trans("backend.unsent") }}';
    }
</script>

<script type="text/javascript">

    function submit_success(form) {
        $("#object-title select[name=title_id\\[\\]]").val(null).trigger('change');
        $("#object-unit select[name=unit_id\\[\\]]").val(null).trigger('change');
        $(table.table).bootstrapTable('refresh');
    }

    $('#import-plan').on('click', function() {
        $('#modal-import').modal();
    });

    var object = $("input[name=object]").val();
    if (object == 1) {
        $("#object-table").show('slow');
        $("#object-unit").show('slow');
        $("#object-title").hide('slow');
        $("#object-user").hide('slow');
    }
    else if (object == 2) {
        $("#object-table").show('slow');
        $("#object-unit").hide('slow');
        $("#object-title").show('slow');
        $("#object-user").hide('slow');
    }
    else {
        $("#object-table").hide('slow');
        $("#object-unit").hide('slow');
        $("#object-title").hide('slow');
        $("#object-user").show('slow');
    }

    $("input[name=object]").on('change', function () {
        var object = $(this).val();
        if (object == 1) {
            $("#object-table").show('slow');
            $("#object-unit").show('slow');
            $("#object-title").hide('slow');
            $("#object-user").hide('slow');
            $("#object-title select[name=title_id\\[\\]]").val(null).trigger('change');
        }
        else if (object == 2) {
            $("#object-table").show('slow');
            $("#object-unit").hide('slow');
            $("#object-title").show('slow');
            $("#object-user").hide('slow');
            $("#object-unit select[name=unit_id\\[\\]]").val(null).trigger('change');
        }
        else {
            $("#object-table").hide('slow');
            $("#object-unit").hide('slow');
            $("#object-title").hide('slow');
            $("#object-user").show('slow');
            $("#object-title select[name=title_id\\[\\]]").val(null).trigger('change');
            $("#object-unit select[name=unit_id\\[\\]]").val(null).trigger('change');
        }
    });

    $('#send-object').on('click', function () {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();

        if (ids.length <= 0) {
            show_message('Vui lòng chọn ít nhất 1 đối tượng', 'error');
            return false;
        }

        $.ajax({
            url: "{{ route('module.notify_send.send_object', ['id' => $model->id]) }}",
            type: 'post',
            data: {
                ids: ids,
            }
        }).done(function(data) {
            show_message(data.message, data.status);
            table.refresh();
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });

</script>
