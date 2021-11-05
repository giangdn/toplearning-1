
var table = $('.bootstrap-table');
var btnDelete = $("#delete-item");
btnDelete.prop('disabled', true);
var removeQuestion = (typeof remove_question !== 'undefined') ? remove_question : 'Bạn có chắc muốn xóa các mục đã chọn?';

table.bootstrapTable({
    locale: 'vi-VN',
    sidePagination: 'server',
    pagination: true,
    sortOrder: 'desc',
    toggle: 'table',
    search: false,
    pageSize: 20,
    idField: (typeof field_id !== 'undefined') ? field_id : 'id',
    queryParams: function (params) {
        let field_search = $("#form-search").serializeArray();
        $.each(field_search, function (i, item) {
            params[item.name] = item.value;
        });
        return params;
    }
});

table.on('check.bs.table uncheck.bs.table ' +
    'check-all.bs.table uncheck-all.bs.table', () => {
    btnDelete.prop('disabled', !table.bootstrapTable('getSelections').length);
});

$("#form-search").on('submit', function () {
    table.bootstrapTable('refresh');
    return false;
});

btnDelete.on('click', function () {
    let ids = $("input[name=btSelectItem]:checked").map(function(){return $(this).val();}).get();
    if (!confirm(removeQuestion)) {
        return false;
    }

    $.ajax({
        type: "POST",
        url: remove_url,
        dataType: 'json',
        data: {
            'ids': ids
        },
        success: function (result) {
            if (result.status === "success") {
                show_message(result.message, result.status);
                table.bootstrapTable('refresh');
                return false;
            }
            else {
                return false;
            }
        }
    });

    return false;
});

table.on('click', '.remove-item', function () {
    let ids = [$(this).data('id')];
    if (!confirm(removeQuestion)) {
        return false;
    }

    $.ajax({
        type: "POST",
        url: remove_url,
        dataType: 'json',
        data: {
            'ids': ids
        },
        success: function (result) {
            if (result.status === "success") {
                table.bootstrapTable('refresh');
                return false;
            }
            else {

                table.bootstrapTable('refresh');
                return false;
            }
        }
    });

    return false;
});
