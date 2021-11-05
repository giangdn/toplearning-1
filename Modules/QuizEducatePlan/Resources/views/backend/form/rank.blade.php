<div role="main">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <label class="col-sm-3 control-label">{{ trans("backend.classification") }} <span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="rank" value="">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 control-label">{{ trans('backend.score') }} <span class="text-danger">*</span></label>

                <div class="col-sm-2">
                    <input type="text" class="form-control is-number" name="score_min" value="">
                </div>
                {{ trans("backend.to") }}
                <div class="col-sm-2">
                    <input type="text" class="form-control is-number" name="score_max" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3"></div>
                <div class="col-sm-3">
                    <button type="button" class="btn btn-primary save-rank"><i class="fa fa-plus-circle"></i> {{ trans("backend.add_new") }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="tDefault table table-hover" id="table-rank">
                <thead>
                    <tr>
                        <th data-field="rank" data-align="center">{{ trans("backend.classification") }}</th>
                        <th data-field="score_min" data-align="center">{{ trans("backend.scores_from") }}</th>
                        <th data-field="score_max" data-align="center">{{ trans("backend.scores_to") }}</th>
                        <th data-field="action" data-formatter="action_formatter" data-align="center" data-width="5%">{{trans('backend.action')}}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    function action_formatter(value, row, index) {
        return '<a href="javascript:void(0)" class="remove-item" data-id="'+ row.id +'"><i class="fa fa-trash text-danger"></i></a>';
    }

    var table2 = new LoadBootstrapTable({
        url: '{{ route('module.quiz_educate_plan.edit.getrank', ["idsg"=>$idsg, 'id' => $model->id]) }}',
        remove_url: '{{ route('module.quiz_educate_plan.edit.removerank', ["idsg"=>$idsg, 'id' => $model->id]) }}',
        table: '#table-rank'
    });

    $('.save-rank').on('click', function() {
        let button = $(this);
        let icon = button.find('i').attr('class');

        button.find('i').attr('class', 'fa fa-spinner fa-spin');
        button.prop("disabled", true);

        var rank = $('input[name=rank]').val();
        var score_min = $('input[name=score_min]').val();
        var score_max = $('input[name=score_max]').val();

        if (!score_min) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            show_message('Điểm tối thiếu không được trống', 'error');
            return false;
        }
        if (!score_max) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            show_message('Điểm tối đa không được trống', 'error');
            return false;
        }
        if (!rank) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            show_message('Xếp loại không được để trống', 'error');
            return false;
        }
        $.ajax({
            url: '{{ route('module.quiz_educate_plan.edit.saverank', ["idsg"=>$idsg, 'id' => $model->id])}}',
            type: 'post',
            data: {
                rank: rank,
                score_min : score_min,
                score_max : score_max,
            },
        }).done(function(data) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            if(data.status == 'error'){
                show_message(data.message, 'error');
                return false;
            }else{
                show_message('Thêm thành công', 'success');
                $(table2.table).bootstrapTable('refresh');

                $('input[name=rank]').val('');
                $('input[name=score_min]').val('');
                $('input[name=score_max]').val('');
                return false;
            }

        }).fail(function(data) {
            setTimeout(function(){
                button.find('i').attr('class', icon);
                button.prop("disabled", false);
            }, 500);
            show_message('Lỗi hệ thống', 'error');
            return false;
        });
    });
</script>
