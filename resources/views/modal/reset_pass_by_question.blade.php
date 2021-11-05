<div id="modal-check-user-question" class="modal fade" role="dialog">
    <div class="modal-dialog">
        @php
            $random_keys = [];
            $arr = [
                'month' => 'Bạn sinh vào tháng mấy',
                'day' => 'Bạn sinh vào ngày mấy',
                'year' => 'Bạn sinh vào năm mấy',
                'join_company' => 'Ngày bạn vào làm là ngày nào',
                'phone' => 'Số điện thoại của bạn',
                'unit_code' => 'Lựa chọn Đơn vị trực tiếp bạn đang làm việc',
                'title_code' => 'Lựa chọn Chức danh của bạn',
            ];
            $random_keys = array_rand($arr, 1);
            $titles = \App\Models\Categories\Titles::where('status', '=', 1)->get();
            $unit = \App\Models\Categories\Unit::where('status', '=', 1)->get();
        @endphp
        <form id="check-user-question">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Mời bạn trả lời các câu hỏi sau:</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="key[]" value="code">
                    <input name="code" type="text" class="form-control" value="" placeholder="Mã số NV của bạn là gì" autocomplete="off" required>

                    <input type="hidden" name="key[]" value="identity_card">
                    <input name="identity_card" type="text" class="form-control" value="" placeholder="CMND của bạn" autocomplete="off" required>

                    @foreach($arr as $key => $item)
                        @if(in_array($key, [$random_keys]))
                            <input type="hidden" name="key[]" value="{{ $key }}">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    @if($key == 'unit_code')
                                        <select name="{{ $key }}" class="form-control select2" required data-placeholder="{{ $item }}">
                                            <option value=""></option>
                                            @foreach($unit as $item)
                                                <option value="{{ $item->code }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($key == 'title_code')
                                        <select name="{{ $key }}" class="form-control select2" required data-placeholder="{{ $item }}">
                                            <option value=""></option>
                                            @foreach($titles as $item)
                                                <option value="{{ $item->code }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input name="{{ $key }}" type="text" class="form-control {{ ($key == 'join_company') ? 'datepicker' : '' }}" value="" placeholder="{{ $item }}" autocomplete="off" required>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="check-user">Gửi</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('#check-user').on('click', function () {
        $.ajax({
            url: '{{ route('auth.check_user_question') }}',
            type: 'post',
            data: $('#check-user-question').serialize(),
        }).done(function (data) {
            if (data.status == 'error') {
                show_message(data.message, data.status);
            } else {
                Swal.fire({
                    title: 'Mời nhập lại password mới',
                    html:
                        '<form id="reset-pass-user-question">' +
                        '@csrf' +
                        '<input type="hidden" name="user_id" value="' + data.user_id + '"/>' +
                        '<div class="row">' +
                        '<div class="col-md-12">' +
                        '<input name="password" id="password" type="password" class="form-control" value="" placeholder="{{ trans('backend.pass') }}" autocomplete="off">' +
                        '</div>' +
                        '</div>' +
                        '</form>',
                    focusConfirm: false,
                    confirmButtonText: 'Ok',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('auth.reset_pass_user_question') }}',
                            type: 'post',
                            data: $('#reset-pass-user-question').serialize(),
                        }).done(function (data) {

                            show_message('Password đã thay đổi', 'success');
                            window.location = '';
                            return false;
                        }).fail(function (data) {
                            show_message('Lỗi dữ liệu', 'error');
                            return false;
                        });
                        return false;
                    }
                });
            }
            return false;
        }).fail(function (data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    });
</script>
