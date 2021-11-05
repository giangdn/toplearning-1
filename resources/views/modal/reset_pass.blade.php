<div id="modal-reset-pass" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form action="{{ route('auth.reset_pass') }}" method="post" id="form-reset-pass" enctype="multipart/form-data" class="form-ajax">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Lấy mật khẩu mới</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-4 control-label">
                            <label for="username">Tên đăng nhập</label>
                        </div>
                        <div class="col-md-8">
                            <input name="username" id="username" type="text" class="form-control" value="" placeholder="Tên đăng nhập" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4 control-label">
                            <label for="username">Email</label>
                        </div>
                        <div class="col-md-8">
                            <input name="email" id="email" type="text" class="form-control" value="" placeholder="email" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Gửi</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </form>
    </div>
</div>
