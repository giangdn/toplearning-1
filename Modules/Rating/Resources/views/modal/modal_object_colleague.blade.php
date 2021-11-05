<div class="modal fade modal-add-object-colleague" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="{{ route('module.rating_level.add_object_colleague', [$course_id, $course_type, $course_rating_level, $rating_user]) }}" class="form-ajax" id="form-rating-level-object-colleague" data-success="submit_success_rating_level_object_colleague">
                <div class="modal-header">
                    <h4 class="modal-title">Thêm đồng nghiệp</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>Chọn nhân viên đánh giá</label>
                        </div>
                        <div class="col-md-9">
                            <select name="user_id" id="user_id" class="form-control select2" data-placeholder="Chọn nhân viên">
                                <option value=""></option>
                                @foreach($profile_unit as $item)
                                    <option value="{{ $item->user_id }}"> {{ $item->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <button type="submit" class="btn btn-primary" id="add-object-rating-level">
                                <i class="fa fa-plus-circle"></i> {{ trans('backend.add_new') }}
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-right">
                                <button id="delete-rating-level-object-colleague" class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('backend.delete') }}</button>
                            </div>
                            <p></p>
                            <table class="tDefault table table-hover bootstrap-table" id="table-rating-level-object-colleague">
                                <thead>
                                <tr>
                                    <th data-field="state" data-checkbox="true"></th>
                                    <th data-field="full_name">Nhân viên đánh giá</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="closed" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-times-circle"></i> {{ trans('backend.close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var table_rating_level_object_colleague = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: '{{ route('module.rating_level.getdata_object_colleague', [$course_type, $course_rating_level, $rating_user]) }}',
        remove_url: '{{ route('module.rating_level.remove_object_colleague', [$course_type]) }}',
        detete_button: '#delete-rating-level-object-colleague',
        table: '#table-rating-level-object-colleague'
    });

    function submit_success_rating_level_object_colleague(form) {
        table_rating_level_object_colleague.refresh();
        $('#user_id').val('').trigger('change');
    }

    $('.select2').select2({
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
</script>
