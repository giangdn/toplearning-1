<div class="row">
    <div class="col-md-9">
    @if($permission_save)
        <form method="post" action="{{ route('module.offline.rating_level.save', ['course_id' => $model->id]) }}" class="form-ajax" id="form-rating-level" data-success="submit_success_rating_level">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Cấp độ đánh giá</label>
                </div>
                <div class="col-md-9">
                    <select name="level" id="" class="form-control select2" data-placeholder="Chọn cấp độ" required>
                        <option value=""></option>
                        <option value="1">Cấp độ 1</option>
                        <option value="2">Cấp độ 2</option>
                        <option value="3">Cấp độ 3</option>
                        <option value="4">Cấp độ 4</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Mẫu đánh giá</label>
                </div>
                <div class="col-md-9">
                    <select name="rating_template_id" id="rating_template_id" class="form-control select2" data-placeholder="Chọn mẫu đánh giá" required>
                        <option value=""></option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}"> {{ $template->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label>Tên đánh giá</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="rating_name" class="form-control" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label"></div>
                <div class="col-md-9">
                    @if($model->lock_course == 0)
                    <button type="submit" class="btn btn-primary" data-must-checked="false">
                        <i class="fa fa-save"></i> &nbsp;Thêm đánh giá
                    </button>
                    @endif
                </div>
            </div>
        </form>
    @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="text-right">
            @if(\Modules\Offline\Entities\OfflinePermission::saveCourse($model) && $model->lock_course == 0)
            <button id="delete-rating-level" class="btn btn-danger"><i class="fa fa-trash"></i> {{trans('backend.delete')}}</button>
            @endif
        </div>
        <p></p>
        <table class="tDefault table table-hover bootstrap-table" id="table-rating-level">
            <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-align="center" data-width="3%" data-formatter="stt_formatter">STT</th>
                    <th data-field="level" data-align="center">Cấp độ</th>
                    <th data-field="rating_name">Tên đánh giá</th>
                    <th data-field="rating_template">Mẫu đánh giá</th>
                    <th data-align="center" data-width="5%" data-formatter="rating_level_object_formatter">Đối tượng</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    function stt_formatter(value, row, index) {
        return (index + 1);
    }

    function rating_level_object_formatter(value, row, index) {
        return '<a href="javascript:void(0)" class="btn btn-primary load-modal" data-url="'+ row.modal_object_url +'"> <i class="fa fa-user"></i> </a>';
    }

    var table_rating_level = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.offline.rating_level.getData', ['course_id' => $model->id]) }}',
        remove_url: '{{ route('module.offline.rating_level.remove', ['course_id' => $model->id]) }}',
        detete_button: '#delete-rating-level',
        table: '#table-rating-level'
    });

    function submit_success_rating_level(form) {
        table_rating_level.refresh();
    }

    $('#rating_template_id').on('change', function () {
        var template_name = $('#rating_template_id option:selected').text();

        $('input[name=rating_name]').val(template_name);
    });
</script>
