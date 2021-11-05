@extends('layouts.backend')

@section('page_title', 'Thiết lập')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            Đánh giá hiệu quả đào tạo <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.rating_organization') }}">Tổ chức đánh giá</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.rating_organization.edit', ['id' => $rating_levels->id]) }}">{{ $rating_levels->name }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Thiết lập</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="{{ route('module.rating_organization.setting.save', ['rating_levels_id' => $rating_levels->id]) }}" class="form-ajax" id="form-rating-level" data-success="submit_success_rating_level">
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>Cấp độ đánh giá</label>
                        </div>
                        <div class="col-md-10">
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
                        <div class="col-sm-2 control-label">
                            <label>Tên đánh giá</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="rating_name" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>Đối tượng được đánh giá</label>
                        </div>
                        <div class="col-md-10">
                            <label class="radio-inline">
                                <input type="radio" name="object_rating" class="radio-object-rating" value="1"> Lớp học
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="object_rating" class="radio-object-rating" value="2"> Học viên
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2 control-label">
                            <label>Đối tượng đánh giá</label>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group" id="object_type_1">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="radio-inline">
                                            <input type="checkbox" name="object_type[]" value="1"> Học viên
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>Thời gian đánh giá </span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="time_type[1]" id="time_type_1" class="form-control select2" data-placeholder="Chọn thời gian">
                                                <option value=""></option>
                                                <option value="1"> Khoảng thời gian</option>
                                            </select>
                                        </span>
                                        <span>
                                            <input name="num_date[1]" type="text" class="form-control is-number" placeholder="Số ngày" value="">
                                        </span>
                                        <span>
                                            <input name="start_date[1]" type="text" class="datepicker form-control" placeholder="{{trans('backend.choose_start_date')}}" autocomplete="off" value="">
                                        </span>
                                        <span>
                                            <input name="end_date[1]" type="text" class="datepicker form-control" placeholder='{{trans("backend.choose_end_date")}}' autocomplete="off" value="">
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>Đối tượng xem </span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="object_view_rating[1]" class="form-control select2" data-placeholder="Chọn đối tượng xem đánh giá">
                                                <option value=""></option>
                                                <option value="0" selected> Không</option>
                                                <option value="1"> Học viên</option>
                                                <option value="2"> Trưởng đơn vị</option>
                                            </select>
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label>Học viên hoàn thành</label>
                                    </div>
                                    <div class="col-10 p-0">
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[1]" value="1"> Có
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[1]" value="0" checked> Không
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <select name="rating_template_id[1]" id="rating_template_1" class="form-control select2" data-placeholder="Chọn mẫu đánh giá">
                                            <option value=""></option>
                                            @foreach($templates as $template)
                                                <option value="{{ $template->id }}"> {{ $template->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2" id="object_type_2">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="radio-inline">
                                            <input type="checkbox" name="object_type[]" value="2"> Trưởng đơn vị
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>Thời gian đánh giá </span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="time_type[2]" id="time_type_2" class="form-control select2" data-placeholder="Chọn thời gian">
                                                <option value=""></option>
                                                <option value="1"> Khoảng thời gian</option>
                                            </select>
                                        </span>
                                        <span>
                                            <input name="num_date[2]" type="text" class="form-control is-number" placeholder="Số ngày" value="">
                                        </span>
                                        <span>
                                            <input name="start_date[2]" type="text" class="datepicker form-control" placeholder="{{trans('backend.choose_start_date')}}" autocomplete="off" value="">
                                        </span>
                                        <span>
                                            <input name="end_date[2]" type="text" class="datepicker form-control" placeholder='{{trans("backend.choose_end_date")}}' autocomplete="off" value="">
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>Đối tượng xem </span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="object_view_rating[2]" class="form-control select2" data-placeholder="Chọn đối tượng xem đánh giá">
                                                <option value=""></option>
                                                <option value="0" selected> Không</option>
                                                <option value="1"> Học viên</option>
                                                <option value="2"> Trưởng đơn vị</option>
                                            </select>
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label>Học viên hoàn thành</label>
                                    </div>
                                    <div class="col-10 p-0">
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[2]" value="1"> Có
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[2]" value="0" checked> Không
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <select name="rating_template_id[2]" id="rating_template_2" class="form-control select2" data-placeholder="Chọn mẫu đánh giá">
                                            <option value=""></option>
                                            @foreach($templates as $template)
                                                <option value="{{ $template->id }}"> {{ $template->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2" id="object_type_3">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="radio-inline">
                                            <input type="checkbox" name="object_type[]" value="3"> Đồng nghiệp
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>Số lượng </span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <input type="text" name="num_user[3]" class="form-control is-number" placeholder="Số lượng" value="">
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>Thời gian đánh giá </span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="time_type[3]" id="time_type_3" class="form-control select2" data-placeholder="Chọn thời gian">
                                                <option value=""></option>
                                                <option value="1"> Khoảng thời gian</option>
                                            </select>
                                        </span>
                                        <span>
                                            <input name="num_date[3]" type="text" class="form-control is-number" placeholder="Số ngày" value="">
                                        </span>
                                        <span>
                                            <input name="start_date[3]" type="text" class="datepicker form-control" placeholder="{{trans('backend.choose_start_date')}}" autocomplete="off" value="">
                                        </span>
                                        <span>
                                            <input name="end_date[3]" type="text" class="datepicker form-control" placeholder='{{trans("backend.choose_end_date")}}' autocomplete="off" value="">
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>Đối tượng xem </span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="object_view_rating[3]" class="form-control select2" data-placeholder="Chọn đối tượng xem đánh giá">
                                                <option value=""></option>
                                                <option value="0" selected> Không</option>
                                                <option value="1"> Học viên</option>
                                                <option value="2"> Trưởng đơn vị</option>
                                            </select>
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label>Học viên hoàn thành</label>
                                    </div>
                                    <div class="col-10 p-0">
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[3]" value="1"> Có
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[3]" value="0" checked> Không
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <select name="rating_template_id[3]" id="rating_template_3" class="form-control select2" data-placeholder="Chọn mẫu đánh giá">
                                            <option value=""></option>
                                            @foreach($templates as $template)
                                                <option value="{{ $template->id }}"> {{ $template->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-2" id="object_type_4">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="radio-inline">
                                            <input type="checkbox" name="object_type[]" value="4"> Khác
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <select name="user_id[4][]" id="user_id" class="form-control select2" data-placeholder="Chọn nhân viên" multiple>
                                            <option value=""></option>
                                            @foreach($profile as $item)
                                                <option value="{{ $item->user_id }}"> {{ $item->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>Thời gian đánh giá </span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="time_type[4]" id="time_type_4" class="form-control select2" data-placeholder="Chọn thời gian">
                                                <option value=""></option>
                                                <option value="1"> Khoảng thời gian</option>
                                            </select>
                                        </span>
                                        <span>
                                            <input name="num_date[4]" type="text" class="form-control is-number" placeholder="Số ngày" value="">
                                        </span>
                                        <span>
                                            <input name="start_date[4]" type="text" class="datepicker form-control" placeholder="{{trans('backend.choose_start_date')}}" autocomplete="off" value="">
                                        </span>
                                        <span>
                                            <input name="end_date[4]" type="text" class="datepicker form-control" placeholder='{{trans("backend.choose_end_date")}}' autocomplete="off" value="">
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <span>Đối tượng xem </span>
                                    </div>
                                    <div class="col-10 p-0 form-inline">
                                        <span>
                                            <select name="object_view_rating[4]" class="form-control select2" data-placeholder="Chọn đối tượng xem đánh giá">
                                                <option value=""></option>
                                                <option value="0" selected> Không</option>
                                                <option value="1"> Học viên</option>
                                                <option value="2"> Trưởng đơn vị</option>
                                            </select>
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label>Học viên hoàn thành</label>
                                    </div>
                                    <div class="col-10 p-0">
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[4]" value="1"> Có
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="user_completed[4]" value="0" checked> Không
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <select name="rating_template_id[4]" id="rating_template_4" class="form-control select2" data-placeholder="Chọn mẫu đánh giá">
                                            <option value=""></option>
                                            @foreach($templates as $template)
                                                <option value="{{ $template->id }}"> {{ $template->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-2 control-label"></div>
                        <div class="col-md-10">
                            <button type="submit" class="btn btn-primary" data-must-checked="false">
                                <i class="fa fa-save"></i> &nbsp;Thêm đánh giá
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <p></p>
        <div class="row">
            <div class="col-md-12">
                <table class="tDefault table table-hover bootstrap-table" id="table-rating-level">
                    <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-align="center" data-width="3%" data-formatter="stt_formatter">STT</th>
                        <th data-field="level" data-align="center">Cấp độ</th>
                        <th data-field="rating_name">Tên đánh giá</th>
                        <th data-field="rating_template">Mẫu đánh giá</th>
                        <th data-align="center" data-width="5%" data-formatter="rating_level_object_formatter">Đối tượng</th>
                        <th data-align="center" data-width="5%" data-formatter="remove_rating_level_formatter">Xóa</th>
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

            function remove_rating_level_formatter(value, row, index) {
                return '<a href="javascript:void(0)" class="btn btn-danger remove-rating-level" data-id="'+ row.id +'"> <i class="fa fa-trash"></i> </a>';
            }

            var table_rating_level = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: '{{ route('module.rating_organization.setting.getData', ['rating_levels_id' => $rating_levels->id]) }}',
                table: '#table-rating-level'
            });

            function submit_success_rating_level(form) {
                table_rating_level.refresh();
            }

            $('#table-rating-level').on('click', '.remove-rating-level', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: '',
                    text: "Bạn có chắc muốn xóa các mục đã chọn?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Đồng ý!',
                    cancelButtonText: 'Hủy!',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('module.rating_organization.setting.remove', ['rating_levels_id' => $rating_levels->id]) }}',
                            data : {
                                id : id,
                            }
                        }).done(function(data) {
                            table_rating_level.refresh();
                            return false;

                        }).fail(function(data) {

                            Swal.fire('Lỗi hệ thống', '', 'error');
                            return false;
                        });
                    }
                });

                return false;
            });

            $('.radio-object-rating').on('click', function () {
                var object_rating = $(this).val();
                if (object_rating == 1){
                    $('#object_type_1').show();
                    $('#object_type_2').hide();
                    $('#object_type_3').hide();
                    $('#object_type_4').show();

                    $('#object_type_2 input[name=object_type\\[\\]]').prop("checked", false);
                    $('#object_type_2 select[name=time_type\\[2\\]]').val('').trigger('change');
                    $('#object_type_2 input[name=num_date\\[2\\]]').val('');
                    $('#object_type_2 input[name=start_date\\[2\\]]').val('');
                    $('#object_type_2 input[name=end_date\\[2\\]]').val('');
                    $('#object_type_2 select[name=object_view_rating\\[2\\]]').val(0).trigger('change');
                    $('#object_type_2 select[name=rating_template_id\\[2\\]]').val('').trigger('change');
                    $('#object_type_2 input:radio[name=user_completed\\[2\\]]').filter('[value="0"]').attr('checked', true);

                    $('#object_type_3 input[name=object_type\\[\\]]').prop("checked", false);
                    $('#object_type_3 select[name=time_type\\[3\\]]').val('').trigger('change');
                    $('#object_type_3 input[name=num_user\\[3\\]]').val('');
                    $('#object_type_3 input[name=num_date\\[3\\]]').val('');
                    $('#object_type_3 input[name=start_date\\[3\\]]').val('');
                    $('#object_type_3 input[name=end_date\\[3\\]]').val('');
                    $('#object_type_3 select[name=object_view_rating\\[3\\]]').val(0).trigger('change');
                    $('#object_type_3 select[name=rating_template_id\\[3\\]]').val('').trigger('change');
                    $('#object_type_3 input:radio[name=user_completed\\[3\\]]').filter('[value="0"]').attr('checked', true);
                }
                if (object_rating == 2){
                    $('#object_type_1').show();
                    $('#object_type_2').show();
                    $('#object_type_3').show();
                    $('#object_type_4').show();
                }
            });

            $('#object_type_1 input[name=object_type\\[\\]]').on('click', function () {
                if(!$(this).is(':checked')){
                    $('#object_type_1 select[name=time_type\\[1\\]]').val('').trigger('change');
                    $('#object_type_1 input[name=num_date\\[1\\]]').val('');
                    $('#object_type_1 input[name=start_date\\[1\\]]').val('');
                    $('#object_type_1 input[name=end_date\\[1\\]]').val('');
                    $('#object_type_1 select[name=object_view_rating\\[1\\]]').val(0).trigger('change');
                    $('#object_type_1 select[name=rating_template_id\\[1\\]]').val('').trigger('change');
                    $('#object_type_1 input:radio[name=user_completed\\[1\\]]').filter('[value="0"]').attr('checked', true);
                    $('#object_type_1 #rating_template_1').prop('required', false);
                }else{
                    $('#object_type_1 #rating_template_1').prop('required', true);
                }
            });
            $('#object_type_2 input[name=object_type\\[\\]]').on('click', function () {
                if(!$(this).is(':checked')){
                    $('#object_type_2 select[name=time_type\\[2\\]]').val('').trigger('change');
                    $('#object_type_2 input[name=num_date\\[2\\]]').val('');
                    $('#object_type_2 input[name=start_date\\[2\\]]').val('');
                    $('#object_type_2 input[name=end_date\\[2\\]]').val('');
                    $('#object_type_2 select[name=object_view_rating\\[2\\]]').val(0).trigger('change');
                    $('#object_type_2 select[name=rating_template_id\\[2\\]]').val('').trigger('change');
                    $('#object_type_2 input:radio[name=user_completed\\[2\\]]').filter('[value="0"]').attr('checked', true);
                    $('#object_type_2 #rating_template_2').prop('required', false);
                }else{
                    $('#object_type_2 #rating_template_2').prop('required', true);
                }
            });
            $('#object_type_3 input[name=object_type\\[\\]]').on('click', function () {
                if(!$(this).is(':checked')){
                    $('#object_type_3 select[name=time_type\\[3\\]]').val('').trigger('change');
                    $('#object_type_3 input[name=num_user\\[3\\]]').val('');
                    $('#object_type_3 input[name=num_date\\[3\\]]').val('');
                    $('#object_type_3 input[name=start_date\\[3\\]]').val('');
                    $('#object_type_3 input[name=end_date\\[3\\]]').val('');
                    $('#object_type_3 select[name=object_view_rating\\[3\\]]').val(0).trigger('change');
                    $('#object_type_3 select[name=rating_template_id\\[3\\]]').val('').trigger('change');
                    $('#object_type_3 input:radio[name=user_completed\\[3\\]]').filter('[value="0"]').attr('checked', true);
                    $('#object_type_3 #rating_template_3').prop('required', false);
                }else{
                    $('#object_type_3 #rating_template_3').prop('required', true);
                }
            });
            $('#object_type_4 input[name=object_type\\[\\]]').on('click', function () {
                if(!$(this).is(':checked')){
                    $('#object_type_4 #user_id').val(' ').trigger('change');
                    $('#object_type_4 select[name=time_type\\[4\\]]').val('').trigger('change');
                    $('#object_type_4 input[name=num_date\\[4\\]]').val('');
                    $('#object_type_4 input[name=start_date\\[4\\]]').val('');
                    $('#object_type_4 input[name=end_date\\[4\\]]').val('');
                    $('#object_type_4 select[name=object_view_rating\\[4\\]]').val(0).trigger('change');
                    $('#object_type_4 select[name=rating_template_id\\[4\\]]').val('').trigger('change');
                    $('#object_type_4 input:radio[name=user_completed\\[4\\]]').filter('[value="0"]').attr('checked', true);
                    $('#object_type_4 #rating_template_4').prop('required', false);
                }else{
                    $('#object_type_4 #rating_template_4').prop('required', true);
                }
            });

            for (var i = 1; i <= 4; i++){
                $('input[name=num_date\\['+ i +'\\]]').hide();
                $('input[name=start_date\\['+ i +'\\]]').hide();
                $('input[name=end_date\\['+ i +'\\]]').hide();
            }

            $('#time_type_1').on('change', function () {
                var time_type = $('#time_type_1 option:selected').val();

                if (time_type == 1){
                    $('input[name=start_date\\[1\\]]').show();
                    $('input[name=end_date\\[1\\]]').show();

                    $('input[name=num_date\\[1\\]]').hide();
                    $('input[name=num_date\\[1\\]]').val('');
                }else if (time_type > 1){
                    $('input[name=start_date\\[1\\]]').hide();
                    $('input[name=end_date\\[1\\]]').hide();

                    $('input[name=start_date\\[1\\]]').val('');
                    $('input[name=end_date\\[1\\]]').val('');

                    $('input[name=num_date\\[1\\]]').show();
                }else{
                    $('input[name=num_date\\[1\\]]').hide();
                    $('input[name=start_date\\[1\\]]').hide();
                    $('input[name=end_date\\[1\\]]').hide();
                }
            });
            $('#time_type_2').on('change', function () {
                var time_type = $('#time_type_2 option:selected').val();

                if (time_type == 1){
                    $('input[name=start_date\\[2\\]]').show();
                    $('input[name=end_date\\[2\\]]').show();

                    $('input[name=num_date\\[2\\]]').hide();
                    $('input[name=num_date\\[2\\]]').val('');
                }else if (time_type > 1){
                    $('input[name=start_date\\[2\\]]').hide();
                    $('input[name=end_date\\[2\\]]').hide();

                    $('input[name=start_date\\[2\\]]').val('');
                    $('input[name=end_date\\[2\\]]').val('');

                    $('input[name=num_date\\[2\\]]').show();
                }else{
                    $('input[name=num_date\\[2\\]]').hide();
                    $('input[name=start_date\\[2\\]]').hide();
                    $('input[name=end_date\\[2\\]]').hide();
                }
            });
            $('#time_type_3').on('change', function () {
                var time_type = $('#time_type_3 option:selected').val();

                if (time_type == 1){
                    $('input[name=start_date\\[3\\]]').show();
                    $('input[name=end_date\\[3\\]]').show();

                    $('input[name=num_date\\[3\\]]').hide();
                    $('input[name=num_date\\[3\\]]').val('');
                }else if (time_type > 1){
                    $('input[name=start_date\\[3\\]]').hide();
                    $('input[name=end_date\\[3\\]]').hide();

                    $('input[name=start_date\\[3\\]]').val('');
                    $('input[name=end_date\\[3\\]]').val('');

                    $('input[name=num_date\\[3\\]]').show();
                }else{
                    $('input[name=num_date\\[3\\]]').hide();
                    $('input[name=start_date\\[3\\]]').hide();
                    $('input[name=end_date\\[3\\]]').hide();
                }
            });
            $('#time_type_4').on('change', function () {
                var time_type = $('#time_type_4 option:selected').val();

                if (time_type == 1){
                    $('input[name=start_date\\[4\\]]').show();
                    $('input[name=end_date\\[4\\]]').show();

                    $('input[name=num_date\\[4\\]]').hide();
                    $('input[name=num_date\\[4\\]]').val('');
                }else if (time_type > 1){
                    $('input[name=start_date\\[4\\]]').hide();
                    $('input[name=end_date\\[4\\]]').hide();

                    $('input[name=start_date\\[4\\]]').val('');
                    $('input[name=end_date\\[4\\]]').val('');

                    $('input[name=num_date\\[4\\]]').show();
                }else{
                    $('input[name=num_date\\[4\\]]').hide();
                    $('input[name=start_date\\[4\\]]').hide();
                    $('input[name=end_date\\[4\\]]').hide();
                }
            });
        </script>
    </div>
@stop
