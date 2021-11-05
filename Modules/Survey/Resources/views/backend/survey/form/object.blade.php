    <div class="row">
        <div class="col-md-9">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.object_belong')}}</label>
                </div>
                <div class="col-md-6">
                    <label class="radio-inline"><input type="radio" name="object" value="1" checked> {{trans('backend.unit')}} </label>
                    <label class="radio-inline"><input type="radio" name="object" value="2"> {{trans('backend.title')}} </label>
                    <label class="radio-inline"><input type="radio" name="object" value="3"> {{trans('backend.user')}} </label>
                </div>
            </div>
        <form method="post" action="{{ route('module.survey.save_object', ['id' => $model->id]) }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data" data-success="submit_success">
            <div id="object-unit">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label> {{trans('backend.unit')}} </label>
                    </div>
                    <div class="col-md-9">
                        <div id="tree-unit" class="tree">
                            @foreach($corporations as $item)
                                @php
                                    $count_child = \App\Models\Categories\Unit::countChild($item->code);
                                @endphp
                                <div class="item">
                                    <i class="uil uil-plus"></i> <input type="checkbox" name="unit_id[]" data-id="{{ $item->id }}" class="check-unit" value="{{ $item->id }}">
                                    <a href="javascript:void(0)" data-id="{{ $item->id }}" class="tree-item">{{ $item->name .' ('. $count_child . ')' }}</a>
                                </div>
                                <div id="list{{ $item->id }}"></div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div id="object-title">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label> {{trans('backend.title')}} </label>
                    </div>
                    <div class="col-md-9">
                        <select id="title_id" class="form-control select2" data-placeholder="-- {{trans('backend.choose_title')}} --" multiple>
                            @foreach($title as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <input type="checkbox" id="checkbox" >{{trans('backend.select_all')}}
                    </div>
                    <input type="hidden" name="title_id" class="form-control title" value="">
                </div>
            </div>
            @canany(['survey-create', 'survey-edit'])
            <div id="object-add">
                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-info"><i class="fa fa-plus-circle"></i> {{trans('backend.add_new')}}</button>
                    </div>
                </div>
            </div>
            @endcanany
        </form>
            @canany(['survey-create', 'survey-edit'])
            <div id="object-user">
                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        <a class="btn btn-info" href="{{ download_template('mau_import_nhan_vien_khao_sat.xlsx') }}"><i class="fa fa-download"></i> {{trans('backend.import_template')}}</a>
                        <button class="btn btn-info" id="import-plan" name="task" value="import">
                            <i class="fa fa-upload"></i> Import
                        </button>
                    </div>
                </div>
            </div>
            @endcanany
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="form-object">
            <div id="table-object">
                @canany(['survey-create', 'survey-edit'])
                <div class="text-right">
                    <button id="delete-item" class="btn btn-danger"><i class="fa fa-trash"></i> {{trans('backend.delete')}}</button>
                </div>
                @endcanany
                <p></p>
                <table class="tDefault table table-hover bootstrap-table">
                    <thead>
                        <tr>
                            <th data-field="state" data-checkbox="true"></th>
                            <th data-field="unit_name"> {{trans('backend.unit')}}</th>
                            <th data-field="title_name">{{trans('backend.title')}}</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="table-user-object">
                @canany(['survey-create', 'survey-edit'])
                <div class="text-right">
                    <button id="delete-user" class="btn btn-danger"><i class="fa fa-trash"></i> {{trans('backend.delete')}}</button>
                </div>
                @endcanany
                <p></p>
                <table class="tDefault table table-hover bootstrap-table2" id="table-user">
                    <thead>
                        <tr>
                            <th data-field="state" data-checkbox="true"></th>
                            <th data-field="profile_code" data-width="5%">{{trans('backend.employee_code')}}</th>
                            <th data-field="profile_name" data-width="25%">{{trans('backend.employee_name')}}</th>
                            <th data-field="email" data-width="20%">{{trans('backend.employee_email')}}</th>
                            <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                            <th data-field="parent_name">{{ trans('backend.unit_manager') }}</th>
                            <th data-field="title_name">{{trans('backend.title')}}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('module.survey.import_object', ['id' => $model->id]) }}" method="post" class="form-ajax">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">IMPORT NGƯỜI DÙNG</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="file" name="import_file" id="import_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('backend.close')}}</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.survey.get_object', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.survey.remove_object', ['id' => $model->id]) }}'
    });

    var table_user = new LoadBootstrapTable({
        url: '{{ route('module.survey.get_user_object', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.survey.remove_object', ['id' => $model->id]) }}',
        detete_button: '#delete-user',
        table: '#table-user'
    });
</script>

<script type="text/javascript">

    function submit_success(form) {
        $("#object-title select[id=title_id]").val(null).trigger('change');
        $('#list1').find('ul').remove();
        $('.check-unit').prop('checked', false);
        $("#checkbox").prop('checked', false);
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
            $("#object-title select[name=title_id\\[\\]]").val(null).trigger('change');
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
            $("#object-title select[name=title_id\\[\\]]").val(null).trigger('change');
        }
    });

    $('#title_id').on('change', function () {
        var title = $("#title_id option:selected").map(function(){return $(this).val();}).get();
        $('.title').val(title);
    });

    $("#checkbox").click(function(){
        if($("#checkbox").is(':checked') ){
            $("#title_id > option").prop("selected","selected");
            $("#title_id").trigger("change");

            var title = $("#title_id option:selected").map(function(){return $(this).val();}).get();
            $('.title').val(title);
        }else{
            $("#title_id > option").prop("selected", "");
            $("#title_id").trigger("change");
            $('.title').val('');
        }
    });

    var openedClass = 'uil-minus uil';
    var closedClass = 'uil uil-plus';

    $('#tree-unit').on('click', '.tree-item', function (e) {
        var id = $(this).data('id');

        if ($(this).closest('.item').find('i:first').hasClass(openedClass)){
            $('#list'+id).find('ul').remove();
        }else{
            $.ajax({
                type: 'POST',
                url: "{{ route('backend.category.unit.tree_folder.get_child') }}",
                dataType: 'json',
                data: {
                    id: id
                }
            }).done(function(data) {
                let rhtml = '';

                rhtml += '<ul>';
                $.each(data.childs, function (i, item){

                    rhtml += '<li>';
                    rhtml += '<div class="item">';
                    rhtml += '<i class="uil uil-plus"></i> <input type="checkbox" name="unit_id[]" class="check-unit" data-id="'+ item.id +'" value="'+ item.id +'"> ';
                    rhtml += '<a href="javascript:void(0)" data-id="'+ item.id +'" class="tree-item"> ' + item.name + ' (' + data.count_child[item.id] + ') </a>';
                    rhtml += '</div>';
                    rhtml += '<div id="list'+ item.id +'"></div>';
                    rhtml += '</li>';
                });
                rhtml += '</ul>';

                document.getElementById('list'+id).innerHTML = '';
                document.getElementById('list'+id).innerHTML = rhtml;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        }

        if (this == e.target) {
            var icon = $(this).closest('.item').children('i:first');
            icon.toggleClass(openedClass + " " + closedClass);
            $(this).children().children().toggle();
        }
    });

    $('#tree-unit').on('click', '.check-unit', function (e) {
        var id = $(this).data('id');

        if($(this).is(":checked")){
            console.log('a');
            $(this).prop('checked', true);
            $.ajax({
                type: 'POST',
                url: "{{ route('module.survey.get_child', ['id' => $model->id]) }}",
                dataType: 'json',
                data: {
                    id: id
                }
            }).done(function(data) {
                $.each(data.childs, function (i, item){
                    $('#list'+id).load(data.page_child[item.id]);
                });
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        }else if($(this).is(":not(:checked)")){
            console.log('b');
            $(this).prop('checked', false);
            $('#list'+id).find('.check-unit').attr('checked', false);
        }

        if (this == e.target) {
            var icon = $(this).closest('.item').children('i:first');
            icon.toggleClass(openedClass + " " + closedClass);
            $(this).children().children().toggle();
        }
    });
</script>
