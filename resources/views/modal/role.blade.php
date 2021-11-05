
<div class="modal fade" id="modal-role" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-body">
                <div role="tabpanel">
                    <ul class="nav nav-pills mb-4" role="tablist">
                        <li role="presentation" class="nav-item"><a class="nav-link active" href="#browseTab" aria-controls="browseTab" role="tab" data-toggle="tab">Duyệt tệp tin</a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        <div role="tabpanel" class="tab-pane container active" id="browseTab">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-9">
                                        <form action="" method="post" class="form-inline role-form-search">
                                            <input type="text" name="q" class="form-control" placeholder="Tìm kiếm tệp tin">

                                            <select name="file_type" class="form-control">
                                                <option value="">-- Loại tệp --</option>
                                            </select>
                                            <select name="search_type" class="form-control">
                                                <option value="1">Trong thư mục hiện tại</option>
                                                <option value="2">Tất cả thư mục </option>
                                            </select>

                                            <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                                        </form>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button class="btn btn-success"><i class="fa fa-upload"></i> UPLOAD</button>
                                    </div>
                                </div>

                            </div>
                            <p></p>
                            <div class="container">
                                <div class="scrollpane">
                                    <div id="results" class="row"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="select-button"><i class="fa fa-check-circle"></i> Chọn</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{ trans('backend.close') }}</button>
            </div>

        </div>
    </div>
</div>
