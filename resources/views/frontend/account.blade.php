@extends('layouts.app')

@section('page_title', 'Thông tin tài khoản')

@section('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/profile.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/prism.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/chosen.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/account.css') }}">

@endsection

@section('content')

    <div class="container-fluid" id="trainingroadmap" style="background: white;">
        <ol class="breadcrumb" style="background: white;margin-bottom: 0;">
            <li>
                <a href="/"><i class="glyphicon glyphicon-home"></i> &nbsp;Trang chủ</a>
            </li>
            <li style="padding-left: 5px;
            color: #717171;
            padding-right: 5px;
            font-weight: 700;">&raquo;
            </li>
            <li>
                <span>Hồ sơ học viên</span>
            </li>
        </ol>

        <div class="courses_news" style="background: white;">
            <ul class="nav nav-pills mb-4 thongtin row" role="tablist" style="margin-left: 15px; margin-right: 15px;">
                <li class="nav-item active"><a class="nav-link" href="#information" aria-controls="information" role="tab" data-toggle="tab"
                                               data-history="index.php?_mod=ttc&_view=my&_page=2&tabs=information">Thông tin cá nhân</a></li>
                <li class="nav-item " role="presentation"><a class="nav-link" href="#trainingprocess" aria-controls="trainingprocess" role="tab" data-toggle="tab"
                                                             data-history="index.php?_mod=ttc&_view=profile&_page=2&tabs=trainingprocess">Quá trình đào tạo</a></li>
                <li class="nav-item " role="presentation"><a class="nav-link" href="#quiz" aria-controls="quiz" role="tab" data-toggle="tab"
                                                             data-history="index.php?_mod=ttc&_view=profile&_page=2&tabs=quiz">Kết quả thi</a></li>
                <!-- <li role="presentation" ><a href="#workprocess" aria-controls="workprocess" role="tab" data-toggle="tab" data-history="index.php?_mod=scb&_view=profile&_page=&tabs=workprocess">QUÁ TRÌNH CÔNG TÁC</a></li> -->
                <li class="nav-item " role="presentation"><a class="nav-link" href="#roadmap" aria-controls="roadmap" role="tab" data-toggle="tab"
                                                             data-history="index.php?_mod=ttc&_view=profile&_page=2&tabs=roadmap">Chương trình khung</a></li>
                <li class="nav-item " role="presentation"><a class="nav-link" href="#roadmap_all_titles" aria-controls="roadmap_all_titles" role="tab" data-toggle="tab"
                                                             data-history="index.php?_mod=ttc&_view=profile&_page=2&tabs=roadmap_all_titles">Chương trình khung chức danh khác</a></li>
                <!-- <li class="nav-item" role="presentation" ><a class="nav-link" href="#camket" aria-controls="camket" role="tab" data-toggle="tab" data-history="index.php?_mod=tcc&_view=p2&tabs=camket">Hợp đồng đào tạo</a></li> -->
            </ul>
            <div class="tab-content" style="font-size: 13px;">
                <div role="tabpanel" class="tab-pane active" id="information">

                    <p></p>
                    <div>
                        <div class="tab-content mod-scb"
                             style="padding-left: 50px;background: #f7f7f7; border: 1px solid #cdcdcd; box-shadow: 0 2px 2px -2px #ccc; position: relative;margin-bottom: 50px;">
                            <div class="row">
                                <div class="col-sm-2 text-center">
                                    <div style="/*background: #fff;
    margin-right: 20px;
    padding: 20px;*/">
                                        <div class="profile-image">
                                            <img src="https://dev-bridgestone.toplearning.vn/dev-bridgestonedatafile/ttc/profile/1530524069-22.jpg" style="width: 100px">
                                        </div>
                                        <div class="edit"><a href="javascript:void(0)" id="change-avatar">Đổi ảnh</a></div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="row">

                                        <div class="col-md-6 mod-scb-profile">
                                            <div class="form-group row">
                                                <div class="col-sm-4 control-label">
                                                    Họ và tên
                                                </div>
                                                <div class="col-sm-8 control-content">
                                                    {{ $user->firstname .' '. $user->lastname }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-md-offset-1 mod-scb-profile">
                                            <div class="form-group row">
                                                <div class="col-sm-5 control-label">
                                                    Ngày tháng năm sinh
                                                </div>
                                                <div class="col-sm-7 control-content">
                                                    {{ get_date($user->dob) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mod-scb-profile">
                                            <div class="form-group row">
                                                <div class="col-sm-4 control-label">
                                                {{trans('backend.employee_code')}}
                                                </div>
                                                <div class="col-sm-8 control-content">
                                                    {{ $user->code }}                    </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-md-offset-1 mod-scb-profile">
                                            <div class="form-group row">
                                                <div class="col-sm-5 control-label">
                                                    Giới tính
                                                </div>
                                                <div class="col-sm-7 control-content">
                                                    @if($user->gender == 1)
                                                        {{ 'Nam' }}
                                                    @else
                                                        {{ 'Nữ' }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mod-scb-profile">
                                            <div class="form-group row">
                                                <div class="col-sm-4 control-label">
                                                    Số điện thoại
                                                </div>
                                                <div class="col-sm-8 control-content">
                                                    {{ $user->phone }}                               </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mod-scb-profile">
                                            <div class="form-group row">
                                                <div class="col-sm-5 control-label">
                                                    Email
                                                </div>
                                                <div class="col-sm-7 control-content">
                                                    {{ $user->email }}                   </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mod-scb-profile">
                                            <div class="form-group row">
                                                <div class="col-sm-4 control-label">
                                                    Nhóm chức danh
                                                </div>
                                                <div class="col-sm-8 control-content">
                                                    @if(isset($title->name)) {{ $title->name }}    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-md-offset-1 mod-scb-profile">
                                            <div class="form-group row">
                                                <div class="col-sm-5 control-label">
                                                    Trạng thái
                                                </div>
                                                <div class="col-sm-7 control-content">

                                                    @switch($user->status)
                                                        @case(0) Nghỉ việc @break;
                                                        @case(1) Đang làm @break;
                                                        @case(2) Thử việc @break;
                                                        @case(3) Tạm hoãn @break;
                                                    @endswitch
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="modal-change-avatar" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <form action="" method="post" id="form-change-avatar" enctype="multipart/form-data">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Đổi ảnh đại diện</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <div class="show-demo">
                                            <img src="https://dev-bridgestone.toplearning.vn/dev-bridgestonedatafile/ttc/profile/1530524069-22.jpg" style="width: 100px">
                                        </div>
                                        <input type="file" name="selectavatar" accept="image/*">
                                        <br/><em>Kích thước đề nghị: 100x100px</em>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">{{ trans('lacore.save') }}</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('backend.close') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script type="text/javascript">
                        $("#change-avatar").on('click', function (event) {
                            event.preventDefault();
                            $("#modal-change-avatar").modal();
                            return false;
                        });

                        function readURL(input) {

                            if (input.files && input.files[0]) {
                                var reader = new FileReader();

                                reader.onload = function (e) {
                                    $('#modal-change-avatar .show-demo img').attr('src', e.target.result);
                                }

                                reader.readAsDataURL(input.files[0]);
                            }
                        }

                        $("input[name=selectavatar]").change(function () {
                            readURL(this);
                        });

                        $("#form-change-avatar").on('submit', function (event) {
                            event.preventDefault();
                            var btn = "#form-change-avatar button[type=submit]";
                            $(btn).prop('disabled', true);

                            $.ajax({
                                url: "/?_mod=ttc&_act=change_avatar_profile",
                                type: "POST",
                                data: new FormData(this),
                                contentType: false,
                                cache: false,
                                processData: false,
                                success: function (data) {
                                    $(btn).prop('disabled', false);
                                    $("#modal-change-avatar").modal('hide');
                                }
                            });
                            return false;
                        });
                    </script>
                </div>

                <div role="tabpanel" class="tab-pane " id="trainingprocess" style="padding: 10px 25px;">

                    <div class="tPanel" style="overflow-x:auto;">
                        <table id="dg" class="tDefault table table-hover table-bordered">
                            <thead>
                            <tr class="tbl-heading">
                                <th width="5%" rowspan="2" style="vertical-align: middle;">#</th>
                                <th rowspan="2" style="vertical-align: middle;">{{ trans('backend.course_code') }}</th>
                                <th rowspan="2" style="vertical-align: middle;">{{ trans('backend.course') }}</th>
                                <th rowspan="2" style="vertical-align: middle;">Chức danh</th>
                                <th rowspan="2" style="vertical-align: middle;">{{trans('backend.training_units')}}</th>
                                <th rowspan="2" style="vertical-align: middle;">{{trans('backend.training_program_form')}}</th>
                                <th colspan="2" style="vertical-align: middle;">{{trans('backend.time')}}</th>
                                <!--<th rowspan="2" style="vertical-align: middle;">Chi phí</th> -->
                                <th colspan="2" style="vertical-align: middle;">{{ trans('backend.result') }}</th>
                                <!--<th rowspan="2" style="vertical-align: middle; width: 80px;">Kỹ năng cần đạt</th>-->

                                <th rowspan="2" style="vertical-align: middle; width: 80px;">Chứng chỉ</th>
                            </tr>
                            <tr class="tbl-heading">
                                <th>{{trans('backend.date_from')}}</th>
                                <th>{{trans('backend.date_to')}}</th>
                                <th>{{ trans('backend.score') }}</th>
                                <th>{{ trans("backend.classification") }}</th>
                            </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div id="modal-skill" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg">

                            <div class="modal-content">
                                <form action="" method="post" id="form-skill">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h3>Các kỹ năng cần đạt</h3>
                                            </div>

                                            <div class="col-md-6">
                                                <select name="skill" id="skill" class="form-control">

                                                </select>
                                            </div>
                                        </div>

                                        <div id="modal-content" style="display: none;">
                                            <p></p>
                                            <div id="skill-info" class="row">
                                                <div class="col-md-4"><b>Điểm kỹ năng bạn đã đạt được</b> <span id="txt-score"></span></div>
                                                <div class="col-md-4"><b>Điểm kỹ năng cần phải đạt</b> <span id="txt-scorereach"></span></div>
                                                <div class="col-md-4"><b>Thời hạn để đạt</b> <span id="txt-deadline"></span> tháng</div>
                                            </div>
                                            <p></p>
                                            <p>Hãy đưa ra các định hướng của bạn để hoàn thành "<span class="modal-title"></span>" </p>

                                            <div class="row block-item" id="default-show">
                                                <div class="col-md-8">
                                                    <input type="text" name="title[]" class="form-control" placeholder="Định hướng"/>
                                                </div>

                                                <div class="col-md-4">
                                                    <input type="number" min="1" name="value[]" class="form-control" placeholder="Hoàn thành trong (tháng)"/>
                                                </div>
                                            </div>
                                            <div id="show-add"></div>

                                            <div class="row">
                                                <div class="col-md-9"></div>
                                                <div class="col-md-3"><a id="add-new" style="margin-top: 5px; float: right;" href="javascript:void(0)">{{trans('backend.add_new')}}</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" id="btn-modal-add" class="btn btn-primary">Gửi</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('backend.close') }}</button>
                                    </div>
                                    <input type="hidden" name="courseid" id="courseid" value=""/>
                                    <input type="hidden" name="type" id="type" value=""/>
                                </form>
                                <div id="show-error" style="display: none;">
                                    <div class="alert alert-info">Bạn không cần hoàn thành kỹ năng nào trong khóa học này</div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <script type="text/javascript">
                        $(".view-skill").on('click', function () {
                            var url = "/?_mod=ttc&_act=getskillsubject";
                            var subjectid = $(this).data('subject-id');
                            var subjectname = $(this).data('subject-name');
                            var courseid = $(this).data('course-id');
                            var coursetype = $(this).data('course-type');

                            var count_skill = 0;
                            $.post(url, {
                                'courseid': courseid,
                            }, function (d) {
                                var obj = jQuery.parseJSON(d);
                                var html = "<option value=\"0\">Chọn kỹ năng</option>";

                                $.each(obj, function (i, item) {
                                    var value = obj[i];
                                    html += "<option value=\"" + value.id + "\">" + value.name + "</option>";
                                    count_skill++;
                                });

                                $("#skill").html(html);
                            });

                            /*if(count_skill > 0)
                            {*/
                            $("#modal-content").hide();
                            $("#modal-skill .modal-title").html(subjectname);
                            $("#modal-skill #courseid").val(courseid);
                            $("#modal-skill #type").val(coursetype);
                            /*}
                            else
                            {
                                $("#form-skill").hide();
                                $("#show-error").show();
                            }*/

                            $("#modal-skill").modal();
                        });

                        $("#skill").on('change', function () {
                            $("#modal-content #show-add").html('');
                            var url = '/?_mod=ttc&_act=get_skill_users_orientation';
                            var skillid = $(this).val();
                            var courseid = $("#modal-skill #courseid").val();
                            var type = $("#modal-skill #type").val();

                            $.post('/?_mod=ttc&_act=get_course_skill_info', {'skillid': skillid, 'courseid': courseid, 'type': type}, function (data) {
                                if (data !== "error") {
                                    var objs = jQuery.parseJSON(data);
                                    $("#txt-score").html(objs.score);
                                    $("#txt-scorereach").html(objs.scorereach);
                                    $("#txt-deadline").html(objs.deadline);
                                }
                            });

                            $.post(url, {
                                'skillid': skillid,
                                'courseid': courseid,
                            }, function (d) {
                                if (d !== "no_result") {
                                    var html = '';
                                    var obj = jQuery.parseJSON(d);
                                    $.each(obj, function (i, item) {
                                        var value = obj[i];
                                        var sd = '<br />';
                                        html += (i !== 0 ? sd : '') + '<div class="row block-item" id="item-' + value.id + '"> <div class="col-md-8"> <input type="text" name="title[]" class="form-control" placeholder="Định hướng" value="' + value.title + '"/> </div> <div class="col-md-4"> <input type="number" min="1" name="value[]" class="form-control" placeholder="Hoàn thành trong (tháng)" value="' + value.value + '"/> </div> <div class="col-md-12" style="text-align: right;"><a href="javascript:void(0)" class="remove-block-item" style="color: red;" data-id="' + value.id + '">Xóa</a></div></div>';
                                    });
                                    $("#default-show").html('');
                                    $("#show-add").append(html);

                                    $(".remove-block-item").on('click', function () {
                                        var url = '/?_mod=ttc&_act=remove_item_skill_users_orientation';
                                        var itemid = $(this).data('id');
                                        $.post(url, {
                                            'itemid': itemid,
                                        }, function (d) {
                                            if (d === "ok") {
                                                $("#item-" + itemid).remove();
                                            }
                                        });
                                    });
                                }
                            });

                            $("#modal-content").show();
                        });

                        $("#add-new").on('click', function () {
                            var html = '<br /><div class="row block-item"> <div class="col-md-8"> <input type="text" name="title[]" class="form-control" placeholder="Định hướng"/> </div> <div class="col-md-4"> <input type="number" min="1" name="value[]" class="form-control" placeholder="Hoàn thành trong (tháng)"/> </div> </div>';
                            $("#show-add").append(html);
                        });

                        $("#form-skill").on('submit', function () {
                            var url = '/?_mod=ttc&_act=addskillsubject';
                            var btn = $("#btn-modal-add");
                            var data = $(this).serialize();
                            var skillid = $("#skill").val();

                            if (skillid === "0") {
                                flash_msg('Bạn chưa chọn kỹ năng', 'warning');
                                return false;
                            }

                            btn.attr("disabled", true);
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                success: function (data) {
                                    if (data === "ok") {
                                        btn.attr("disabled", false);
                                        flash_msg('Cập nhật thành công');
                                    }
                                }
                            });
                            return false;
                        });

                        function flash_msg(message, type) {
                            message = typeof message == 'undefined' ? ' ' : message;
                            var style = 'style = "display:none;z-index:16000;position:fixed;left:40%; top:40%;max-width:25em;min-width:15em;padding:15px;text-align:center;font-size:1.2em;box-shadow:2px 2px 3px gray;padding:15px;';
                            switch (type) {
                                case 'warning':
                                    style += 'border:1px solid #EC971F;color:#fff;background:#EC971F';
                                    break;
                                case 'error':
                                    style += 'border:1px solid #900;color:#fff;background:#F00';
                                    break;
                                default:
                                    style += 'border:1px solid #449D44;color:#fff;background:#449D44;';
                                    break
                            }
                            style += '"';
                            $('body').append('<div id="flash-msg" ' + style + ' >' + message + '</div>');
                            $('#flash-msg').fadeIn();
                            setTimeout(function () {
                                $('#flash-msg').remove()
                            }, 3000)
                        }
                    </script>
                </div>

                <div role="tabpanel" class="tab-pane " id="quiz" style="padding: 10px 25px;">
                    <form method="post" action="index.php?_mod=quiz&_view=quiz&_lay=dft&_page=2">
                        <!-- <div class="row header" style="margin-right: 0;">
                            <div class="col-md-5 col-sm-6 col-xs-12">
                                <div style="padding: 15px 15px 15px 0;">
                                    <div class="input-group">
                                        <input type="text" name="search" id="search" class="form-control" placeholder="Nhập tên bài thi để tìm kiếm" value="" aria-label="...">
                                          <div class="input-group-btn">
                                            <button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-search"></i> &nbsp; Tìm kiếm</button>
                                          </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 col-sm-6 col-xs-12">

                            </div>
                        </div> -->

                        <br/>
                        <div class="tPanel">


                            <table style="background: #fff; font-size:14px;" class="tDefault table table-hover table-bordered">
                                <thead>
                                <tr class="tbl-heading">
                                    <th width="3%">#</th>
                                    <th>Tên bài thi</th>
                                    <th width="100px">Ngày thi</th>
                                    <th width="100px">Giờ thi</th>
                                    <th width="120px">Thời gian thi</th>
                                    <th width="140px">Trạng thái</th>
                                    <th width="100px">Điểm</th>
                                    <th width="100px">{{ trans('backend.result') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="9"></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </form>
                </div>

                <div role="tabpanel" class="tab-pane " id="workprocess" style="padding: 10px 25px;">
                    <div class="tPanel">
                        <table id="dg" class="tDefault table table-hover">
                            <thead>
                            <tr class="tbl-heading">
                                <th width="40px;">#</th>
                                <th>{{trans('backend.date_from')}}</th>
                                <th width="200px;">Khối</th>
                                <th width="200px;">{{ trans('backend.unit') }}</th>
                                <th width="200px;">Chức vụ</th>
                                <th width="200px;">Nhóm chức danh</th>
                                <th width="150px;">Trạng thái</th>
                            </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>


                </div>

                <div role="tabpanel" class="tab-pane " id="roadmap" style="padding: 10px 25px;">
                    <div class="tPanel" style="overflow-x:auto;">
                        <table id="dg" class="tDefault table table-hover table-bordered bootstrap-table">
                            <thead>
                            <tr class="tbl-heading">
                                <th data-field="index" data-formatter="index_formatter" width="40px;" rowspan="2" style="vertical-align: middle;">#</th>
                                <th data-field="subject_code" rowspan="2" style="vertical-align: middle;">{{ trans('backend.course_code') }}</th>
                                <th data-field="subject_name" rowspan="2" style="vertical-align: middle;">{{ trans('backend.course') }}</th>
                                <th rowspan="2" style="vertical-align: middle;">{{trans('backend.training_units')}}</th>
                                <th rowspan="2" style="vertical-align: middle;">{{trans('backend.training_program_form')}}</th>
                                <th style="vertical-align: middle;text-align: center;" colspan="2">{{trans('backend.time')}}</th>
                                <th colspan="2" style="text-align: center; vertical-align: middle;">{{trans('backend.required_time_complete_course')}}</th>
                                <th rowspan="2" style="text-align: center; vertical-align: middle;">Thời gian hoàn thành khóa học</th>
                                <th style="vertical-align: middle;text-align: center;" colspan="2">{{ trans('backend.result') }}</th>
                            </tr>
                            <tr class="tbl-heading">
                                <th>{{trans('backend.date_from')}}</th>
                                <th>{{trans('backend.date_to')}}</th>
                                <th style="text-align: center; vertical-align: middle;">{{trans('backend.date_from')}}</th>
                                <th style="text-align: center; vertical-align: middle;">{{trans('backend.date_to')}}</th>
                                <th>{{ trans('backend.score') }}</th>
                                <th>{{ trans("backend.classification") }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane " id="roadmap_all_titles" style="padding: 10px 25px;">
                    <div class="tPanel" style="overflow-x:auto;">
                        <table id="dg" class="tDefault table table-hover table-bordered bootstrap-table2">
                            <thead>
                            <tr class="tbl-heading">
                                <th data-field="index" data-formatter="index_formatter" width="40px;" rowspan="2" style="vertical-align: middle;">#</th>
                                <th data-field="title_name" rowspan="2" style="vertical-align: middle;">Chức danh</th>
                                <th data-field="subject_code" rowspan="2" style="vertical-align: middle;">Mã học phần</th>
                                <th data-field="subject_name" rowspan="2" style="vertical-align: middle;">{{ trans('backend.course') }}</th>
                                <th rowspan="2" style="vertical-align: middle;">{{trans('backend.training_units')}}</th>
                                <th rowspan="2" style="vertical-align: middle;">{{trans('backend.training_program_form')}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>


            </div>

            <script type="text/javascript">
                $(document).ready(function () {
                    var windowsize = $(window).width();
                    if (windowsize < 992) {
                        $('.nav-item').addClass('col-md-6');
                        $('.nav-item').removeClass('col-md-3');

                    }

                    if (windowsize < 768) {
                        $('.thongtin').css('text-align', 'center');
                    }

                });

                function index_formatter(value, row, index) {
                    return (index + 1);
                };

                var table = new LoadBootstrapTable({
                    url: '{{ route('frontend.account.getdataTrainingRoadmap') }}',
                    table: '.bootstrap-table'
                });
                var table = new LoadBootstrapTable({
                    url: '{{ route('frontend.account.getdataTrainingRoadmapOther') }}',
                    table: '.bootstrap-table2'
                });

            </script>
@stop
