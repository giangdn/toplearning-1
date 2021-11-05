<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Thêm chuyên đề cần hoàn thành</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <form action="{{ route('module.subjectcomplete.user.save', ['user_id' => $user_id]) }}" method="post" class="form-ajax" id="form-add-question" data-success="success_submit">
            <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-4 control-label">
                            <label>Chọn chuyên đề</label><span style="color:red"> * </span>
                        </div>
                        <div class="col-md-8">
                            <select name="subject" class="form-control subject select2" >
                                <option value=""></option>
                                @foreach ($subject as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4 control-label">
                            <label>Quá trình công tác</label>
                        </div>
                        <div class="col-md-8">
                            <select name="titles" class="form-control select2" >
                                @foreach($workingProcess as $item)
                                    <option value="{{$item->id}}">{{$item->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4 control-label">
                            <label>Ghi chú số QĐ</label><span style="color:red"> * </span>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="note" class="form-control" />
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary button-save"><i class="fa fa-plus-circle"></i> {{ trans('backend.save') }}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> {{ trans('backend.close') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(".subject").select2({
        allowClear: true,
        dropdownAutoWidth : true,
        width: '100%',
        placeholder: function(params) {
            return {
                id: null,
                text: params.placeholder,
            }
        },
    });

    $(".button-save2").on('click', function() {
        var ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
        let button = $(this);
        let icon = button.find('i').attr('class');

        button.find('i').attr('class', 'fa fa-spinner fa-spin');
        button.prop("disabled", true);

        $.ajax({
            type: 'POST',
            url: "{{ route('module.quiz.question.save_category_question', ['id' => $user_id]) }}",
            dataType: 'json',
            data: {
                ids: ids
            },
        }).done(function(data) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);

            table.refresh();
            $("#app-modal #myModal").modal('hide');
            window.location = "";
            return false;
        }).fail(function(data) {
            show_message('Chưa chọn câu hỏi', 'error');
            button.find('i').attr('class', icon);
            button.prop("disabled", false);
            return false;
        });

    });
    function success_submit(form) {
        $("#app-modal #myModal").modal('hide');
        table.refresh();
    }
</script>

