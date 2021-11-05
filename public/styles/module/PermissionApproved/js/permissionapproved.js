$(function () {
    $('#company, #model_approved').on('change', function (e) {
        //e.preventDefault();
        this.form.submit();
    });
    var table = new LoadBootstrapTable({
        locale,
        url,
        remove_url,
        'sort_name':'level',
        'sort_order': 'asc'
    });
    $(document).on('click','.edit-approved', function (e) {
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);
        var url = $(this).data('url');
        $.get(url, {}, function (result){
            $("#app-modal").html(result);
            $("#app-modal #modal-permission-approved").modal();
            load_user_select2();
            load_title_select2();
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
        },'html').fail(function(data) {
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
            return false;
        });

        var level = $.trim($(this).closest('tr').find('td:nth-child(4)').text());
        $('#level').val(level);
        $('#idapproved').val($(this).data('id'));
    });

    $('.add-approved').on('click', function (e) {
        e.preventDefault();
        $('#idapproved').val("");
        var unit_id = $('#unit_id').val(),
            model_approved = $('#model_approved').val();
        var  data = {
            'model_approved': model_approved,
            'unit_id': unit_id
        };
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);
        var url = $(this).data('url');
        $.get(url,data, function (result){
            $("#app-modal").html(result);
            $("#app-modal #modal-permission-approved").modal();
            load_user_select2();
            load_title_select2();
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
        },'html').fail(function(data) {
            btn.find('i').attr('class', current_icon);
            btn.prop("disabled", false);
            return false;
        });

        var nextrow = isNaN(parseInt($('#tbl-approved >tbody >tr:last-child').find('td:nth-child(4)').text())) ? 1 : parseInt($('#tbl-approved >tbody >tr:last-child').find('td:nth-child(4)').text()) + 1;

        $('#level').val(nextrow);
        $('#titles').empty();
        $('.modal-title').text("Thêm phê duyệt cấp " + nextrow);
    });

    $('.del-approved').on('click', function (e) {
        e.preventDefault();
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');

        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);
        var lastrow = $('.bootstrap-table >tbody >tr').length;
        var unit_id = $('#unit_id').val(),
            url = $(this).data('url'),
            data = {
                "model_approved": $('#model_approved').val(),
                'unit_id': unit_id
            };
        Swal.fire({
            title: '',
            text: 'Bạn có muốn xóa phê duyệt cấp ' + lastrow +' ?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý!',
            cancelButtonText: 'Hủy!',
        }).then((result) => {
            if (result.value) {
                $.delete(url, data, function (data) {
                    if (data.status === "success") {
                        table.refresh();
                    } else {
                        show_message(data.message, 'error');
                    }
                    btn.find('i').attr('class', current_icon);
                    btn.prop("disabled", false);
                }).fail(function(data) {
                    btn.find('i').attr('class', current_icon);
                    btn.prop("disabled", false);
                    return false;
                });

            }
            else {
                btn.find('i').attr('class', current_icon);
                btn.prop("disabled", false);
            }
        });
    });

    $(document).on('click',"#save-approved", function (event) {
        event.preventDefault();
        var objectLevel = $('#objectlevel').val(),
            unit_id = $('#unit_id').val(),
            id = $('#idapproved').val(),
            model_approved = $('#model_approved').val(),
            url = $(this).closest('form').attr('action'),
            titles = $("#titles").map(function () {
                return $(this).val();
            }).get(),
            employees = $('#employees').map(function (index, elem) {
                return $(this).val();
            }).get();
        if (employees.length <= 0 && titles.length <= 0 && objectLevel<=0) {
            show_message ('Vui lòng chọn chức danh hoặc nhân viên hoặc cấp duyệt', 'error');
            return false;
        }
        var  data = {
                'object_id': objectLevel,
                'titles': titles,
                'employees': employees,
                'model_approved': model_approved,
                'id': id,
                'unit_id': unit_id
            };
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);
        $.post(url, data, function (data) {
            if (data.status === "success") {
                show_message(data.message);
                $('#modal-permission-approved').modal('hide');
                btn.find('i').attr('class', current_icon);
                btn.prop("disabled", false);
                table.refresh();
            } else {
                show_message(data.message, 'error');
                btn.find('i').attr('class', current_icon);
                btn.prop("disabled", false);
            }
        });
    });
    $(document).on('click','#update-approved', function (event) {
        event.preventDefault();
        var $form = $(this).closest('form');
        var objectLevel = $('#objectlevel',$form).val(),
            unit_id = $('#unit_id').val(),
            id = $('#idapproved').val(),
            model_approved = $('#model_approved').val(),
            url = $(this).closest('form').attr('action'),
            titles = $("#titles", $form).map(function () {
                return $(this).val();
            }).get(),
            employees = $('#employees', $form).map(function (index, elem) {
                return $(this).val();
            }).get();

        if (employees.length <= 0 && titles.length <= 0 && objectLevel<=0) {
            show_message ('Vui lòng chọn chức danh hoặc nhân viên hoặc cấp duyệt', 'error');
            return false;
        }

        var  data = {
            'object_id': objectLevel,
            'titles': titles,
            'employees': employees,
            'model_approved': model_approved,
            'id': id,
            'unit_id': unit_id
        };
        let btn = $(this);
        let current_icon = btn.find('i').attr('class');
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');
        btn.prop("disabled", true);
        $.put(url, data, function (data) {
            if (data.status === "success") {
                show_message(data.message);
                $('#modal-permission-approved').modal('hide');
                btn.find('i').attr('class', current_icon);
                btn.prop("disabled", false);
                table.refresh();
            } else {
                show_message(data.message, 'error');
                btn.find('i').attr('class', current_icon);
                btn.prop("disabled", false);
            }
        });
        return false;
    });
});

