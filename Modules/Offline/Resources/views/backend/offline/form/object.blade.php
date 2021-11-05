<div class="row">
    <div class="col-md-9">
        <form method="post" action="{{ route('module.offline.save_object', ['id' => $model->id]) }}" class="form-ajax" id="form-object" data-success="submit_success_object">
            {{--<div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label></label>
                </div>

                <div class="col-md-9">
                    <div class="form-check form-check-inline">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input object-type" id="typeRadio1" name="object_type" value="1" checked>
                            <label class="custom-control-label" for="typeRadio1">@lang('app.title')</label>
                        </div>
                    </div>

                    <div class="form-check form-check-inline">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input object-type" id="typeRadio2" name="object_type" value="2">
                            <label class="custom-control-label" for="typeRadio2">{{trans('backend.unit')}}</label>
                        </div>
                    </div>
                </div>
            </div>--}}

            <div class="box-title">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label>{{trans('backend.choose_unit')}}</label>
                    </div>
                    <div class="col-md-9">
                        <div id="tree-unit" class="tree">
                            @foreach($corporations as $item)
                                @php
                                    $count_child = \App\Models\Categories\Unit::countChild($item->code);
                                @endphp
                                <div class="item">
                                    <i class="uil uil-plus"></i> <input type="checkbox" name="unit[]" data-id="{{ $item->id }}" class="check-unit" value="{{ $item->id }}">
                                    <a href="javascript:void(0)" data-id="{{ $item->id }}" class="tree-item">{{ $item->name .' ('. $count_child . ')' }}</a>
                                </div>
                                <div id="list{{ $item->id }}"></div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label>{{trans('backend.choose_title')}}</label>
                    </div>
                    <div class="col-md-9">
                        <select id="title" class="form-control select2" multiple data-placeholder="-- {{ trans('backend.title') }} --">
                            @foreach($titles as $title)
                                <option value="{{ $title->id }}">{{ $title->name }}</option>
                            @endforeach
                        </select>
                        <input type="checkbox" id="checkbox" >{{trans('backend.select_all')}}
                    </div>
                    <input type="hidden" name="title" class="form-control title" value="">
                </div>
            </div>

            {{--<div class="box-unit box-hidden">
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label>{{trans('backend.choose_unit')}}</label>
                    </div>
                    <div class="col-md-9">
                        <select name="unit[]" id="unit" class="form-control load-unit" multiple data-placeholder="-- Đơn vị --">
                        </select>
                    </div>
                </div>
            </div>--}}

            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>{{trans('backend.type_object')}}</label>
                </div>
                <div class="col-md-9">
                    <label class="radio-inline"><input type="radio" name="type" value="1">{{trans('backend.obligatory')}}</label>
                    <label class="radio-inline"><input type="radio" name="type" value="2">{{trans('backend.register')}}</label>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3 control-label"></div>
                <div class="col-md-9">
                    @if($model->lock_course == 0)
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.add_new') }}</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12" id="form-object">
        <div class="text-right">
            @if($model->lock_course == 0)
            <button id="delete-object" class="btn btn-danger"><i class="fa fa-trash"></i> {{trans('backend.delete')}}</button>
            @endif
        </div>
        <p></p>
        <table class="tDefault table table-hover" id="table-object">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-align="center" data-width="3%" data-formatter="stt_formatter">STT</th>
                    <th data-field="title_name">{{trans('backend.title')}}</th>
                    <th data-field="unit_name">{{ trans('backend.work_unit') }}</th>
                    <th data-field="unit_manager">{{ trans('backend.unit_manager') }}</th>
                    <th data-align="center" data-field="type" data-width="10%" data-formatter="type_formatter">{{trans('backend.type_object')}}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    function type_formatter(value, row, index) {
        return value == 1 ? '{{trans("backend.obligatory")}}' : '{{trans("backend.register")}}';
    }

    function stt_formatter(value, row, index) {
        return (index + 1);
    }
    var table_object = new LoadBootstrapTable({
        url: '{{ route('module.offline.get_object', ['id' => $model->id]) }}',
        remove_url: '{{ route('module.offline.remove_object', ['id' => $model->id]) }}',
        detete_button: '#delete-object',
        table: '#table-object'
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
            table.refresh();
        }
    });

    $(".object-type").on('change', function () {
        let type = parseInt($(this).val());
        if (type == 1) {
            $(".box-title").removeClass('box-hidden');
            $(".box-unit").addClass('box-hidden');
        }
        else {
            $(".box-unit").removeClass('box-hidden');
            $(".box-title").addClass('box-hidden');
        }
    });

    function submit_success_object(form) {
        $("#form-object #title").val(null).trigger('change');
        $("#form-object #unit").val(null).trigger('change');
        table_object.refresh();
    }

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
                    rhtml += '<i class="uil uil-plus"></i> <input type="checkbox" name="unit[]" class="check-unit" data-id="'+ item.id +'" value="'+ item.id +'"> ';
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
                url: "{{ route('module.offline.get_child', ['id' => $model->id]) }}",
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
