$(document).ready(function () {

    $(document).on('click', '.add-roadmap-title', function () {
        let roadmap_id = $(this).data('id');
        let btn = $(this);
        let icon = btn.find('i').attr('class');
        btn.find('i').attr('class', 'fa fa-spinner fa-spin');

        $('#parent_title').html('<option value="">--- Chọn chức danh ---</option>');
        $('#title_id').html('<option value="">--- Chọn chức danh ---</option>');

        $.ajax({
            type: 'GET',
            url: career.parents_url,
            dataType: 'json',
            data: {
                'roadmap_id': roadmap_id,
            }
        }).done(function (data) {

            btn.find('i').attr('class', icon);

            $.each(data.parent_titles, function (index, item) {
                $('#parent_title').append('<option value="' + item.id + '">' + item.name + '</option>');
            });

            $.each(data.titles_list, function (index, item) {
                $('#title_id').append('<option value="' + item.id + '">' + item.name + '</option>');
            });

            $('#add-title-modal input[name=id]').val(roadmap_id);
            $('#add-title-modal').modal();

            return false;
        }).fail(function (data) {
            return false;
        });
    });

    $(document).on('click', '.edit-roadmap-title', function () {
        let roadmap_id = $(this).data('id');
        let roadmap_type = $(this).data('type');
        // console.log(roadmap_id);

        $.ajax({
            type: 'GET',
            url: career.edit_career_roadmap,
            dataType: 'json',
            data: {
                'roadmap_id': roadmap_id,
                'roadmap_type': roadmap_type,
            }
        }).done(function (data) {
            $('#roadmap_titles_id').html('');
            console.log(data);
            if (data) {
                if (data.type == 1) {
                    $('#roadmap_title').show();
                } else {
                    $('#roadmap_title').hide();
                }
                $.each(data.rows, function (index, item) {
                    console.log(item.title_id);
                    $('#roadmap_titles_id').append('<option value="' + item.title_id + '">' + item.name + '</option>');
                    $('#set_seniority').html(`<label>Thâm niên (năm)</label>
                                              <input type='number' step='0.1' value='`+ item.seniority +`' 
                                                id="seniority_career_title" 
                                                placeholder='0.0'  name="seniority"  class="form-control"/>
                                            `);
                    
                });
            }
            $('#edit-title-modal input[name=id]').val(roadmap_id);
            $('#edit-title-modal').modal();
            return false;
        }).fail(function (data) {
            return false;
        });
    });

    $(document).on('click', '.delete-roadmap-title', function () {
        let row = $(this).closest('tr');
        let roadmap_id = $(this).data('id');
        let btn = $(this);
        let icon = btn.find('i').attr('class');

        Swal.fire({
            title: '',
            text: 'Bạn có chắc muốn xóa chức danh này?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý!',
            cancelButtonText: 'Hủy!',
        }).then((result) => {
            if (result.value) {
                btn.find('i').attr('class', 'fa fa-spinner fa-spin');

                $.ajax({
                    type: 'POST',
                    url: career.remove_title_url,
                    dataType: 'json',
                    data: {
                        roadmap_id: roadmap_id,
                    }
                }).done(function (data) {

                    btn.find('i').attr('class', icon);
                    btn.prop("disabled", false);

                    if (data.status === "error") {
                        show_message(data.message, 'error');
                        return false;
                    }

                    row.remove();
                    window.location = "";

                    return false;
                }).fail(function (data) {
                    btn.find('i').attr('class', icon);
                    btn.prop("disabled", false);

                    show_message('Data Error', 'error');
                    return false;
                });
            }
        });


    });

    $(document).on('click', '.delete-roadmap', function () {
        let row = $(this).closest('tr');
        let roadmap_id = $(this).data('id');
        let btn = $(this);
        let icon = btn.find('i').attr('class');

        Swal.fire({
            title: '',
            text: 'Bạn có chắc muốn xóa chức danh này?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý!',
            cancelButtonText: 'Hủy!',
        }).then((result) => {
            if (result.value) {
                btn.find('i').attr('class', 'fa fa-spinner fa-spin');

                $.ajax({
                    type: 'POST',
                    url: career.remove_roadmap_url,
                    dataType: 'json',
                    data: {
                        roadmap_id: roadmap_id,
                    }
                }).done(function (data) {

                    btn.find('i').attr('class', icon);
                    btn.prop("disabled", false);

                    if (data.status === "error") {
                        show_message(data.message, 'error');
                        return false;
                    }

                    row.remove();
                    window.location = "";

                    return false;
                }).fail(function (data) {
                    btn.find('i').attr('class', icon);
                    btn.prop("disabled", false);

                    show_message('Data Error', 'error');
                    return false;
                });
            }
        });


    });
});
