<div id="modal-check-user-question" class="modal fade" role="dialog">
    <div class="modal-dialog">
        @php
            $random_keys = [];
            $arr = [
                'month' => data_locale('Bạn sinh vào tháng mấy?', 'What month are you born?'),
                'day' => data_locale('Bạn sinh vào ngày mấy?', 'What date were you born?'),
                'year' => data_locale('Bạn sinh vào năm mấy?', 'What year were you born?'),
                'join_company' => data_locale('Ngày bạn vào làm là ngày nào?', 'The day you entered work?'),
                'phone' => data_locale('Số điện thoại của bạn?', 'Your phone number?'),
                'unit_code' => data_locale('Lựa chọn Đơn vị trực tiếp bạn đang làm việc', 'Choose the Direct Unit you are working with'),
                'title_code' => data_locale('Lựa chọn Chức danh của bạn', 'Choose your Title'),
            ];
            $random_keys = array_rand($arr, 1);
            $titles = \App\Models\Categories\Titles::where('status', '=', 1)->get();
            $unit = \App\Models\Categories\Unit::where('status', '=', 1)->get();
        @endphp
        <form id="check-user-question">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ data_locale('Mời bạn trả lời các câu hỏi sau:', 'Please answer the following questions:') }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="key[]" value="code">
                    <input name="code" type="text" class="form-control" value="" placeholder="{{ data_locale('Mã số NV của bạn là gì', 'Code What is your staff') }}" autocomplete="off" required>

                    <input type="hidden" name="key[]" value="identity_card">
                    <input name="identity_card" type="text" class="form-control" value="" placeholder="{{ data_locale('CMND của bạn', 'Your ID card') }}" autocomplete="off" required>

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
                    <button type="button" class="btn btn-primary mr-2" id="check-user">{{ trans('app.send') }}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('app.close') }}</button>
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
