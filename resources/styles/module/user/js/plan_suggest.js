$(document).ready(function () {
    var subject, title;
    $('button[name=btnCreate]').on('click',function (e) {
        e.preventDefault();
        var course='', $this = $(this), tmpSubject='', tmpTitle='';
        var btn_text =$this.html();
        // $this.prop('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> Đang load...');
        $.ajax({
            'async': false,
            type: "GET",
            url: $this.data('url-create'),
            data: { },
            dataType:'json',
            success: function(result){
                $.each(result.subject, function (index,value) {
                    tmpSubject +='<option value="'+value.name+'">'+value.name+'</option>';
                });
                callback(tmpSubject,1); 
                $.each(result.title, function (i,e) { 
                    tmpTitle +='<option value="'+e.id+'">'+e.name+'</option>';
                });
                callback(tmpTitle,2); 
            }
        });  
        Swal.fire(
            {
            width:'50%',
            title: 'Thêm đề xuất kế hoạch',
            text: 'Something went wrong!',
            html:
                '<form method="post" action="" name="frm" enctype="multipart/form-data" class="form-ajax">'+
                '<div class="form-group row">' +
                    '<div class="col-md-4" stye ="vertical-align:middle"><label>Thời gian dự kiến</label></div>' +
                    '<div class="col-md-8"><input name="intend" id="intend" class="form-control"></div>' +
                '</div>' +
                '<div class="form-group row">' +
                    '<div class="col-md-4" stye ="vertical-align:middle"><label>Tên học phần</label></div>' +
                    '<div class="col-md-8">' +
                        '<select id="swal-select" name="subject_name" class="form-control select2">' +
                            '<option value="">Chọn/nhập học phần</option>' + subject+
                        '</select>' +
                    '</div>' +
                '</div>'+
                '<div class="form-group row">' +
                    '<div class="col-md-4" stye ="vertical-align:middle"><label>Đối tượng</label></div>' +
                    '<div class="col-md-8">' +
                        '<select class="form-control select2" multiple name="title[]">' +
                            '<option value="">Chọn chức danh</option>'+title+ 
                        '</select>' +
                    '</div>' +
                '</div>'+
                '<div class="form-group row">' +
                    '<div class="col-md-4" stye ="vertical-align:middle"><label>Số lượng học viên</label></div>' +
                    '<div class="col-md-8">' +
                        '<input type="number" name="amount" class="form-control" />' +
                    '</div>' +
                '</div>'+
                '<div class="form-group row">' +
                    '<div class="col-md-4" stye ="vertical-align:middle"><label>Thời lượng</label></div>' +
                    '<div class="col-md-8">' +
                        '<input type="number" name="duration" placeholder="Nhập số buổi" class="form-control" />' +
                    '</div>' +
                '</div>'+
                '<div class="form-group row">' +
                    '<div class="col-md-4" stye ="vertical-align:middle"><label>Giảng viên</label></div>' +
                    '<div class="col-md-8">' +
                        '<input type="text" name="teacher" class="form-control" />' +
                    '</div>' +
                '</div>'+
                '<div class="form-group row">' +
                    '<div class="col-md-4" stye ="vertical-align:middle"><label>Mục tiêu đào tạo</label></div>' +
                    '<div class="col-md-8">' +
                        '<input type="text" name="purpose" class="form-control" />' +
                    '</div>' +
                '</div>'+
                '<div class="form-group row">' +
                    '<div class="col-md-4" stye ="vertical-align:middle"><label>File đính kèm</label></div>' +
                    '<div class="col-md-8">' +
                        '<input type="file" name="attach" accept="image/*" class="form-control" />' +
                    '</div>' +
                '</div>'+
                '<div class="form-group row">' +
                    '<div class="col-md-4" stye ="vertical-align:middle"><label>Danh sách học viên</label></div>' +
                    '<div class="col-md-8">' +
                        '<textarea class="form-control" name="students" id="" rows="2"></textarea>' +
                    '</div>' +
                '</div>'+
                '<div class="form-group row">' +
                    '<div class="col-md-4" stye ="vertical-align:middle"><label>Ghi chú</label></div>' +
                    '<div class="col-md-8">' +
                        '<textarea class="form-control" name="note" id="" rows="2"></textarea>' +
                    '</div>' +
                '</div>'+
                '</form>',
            showCloseButton: true,
            showCancelButton: true,
            animation: false,
            customClass: 'animated zoomIn',
            showLoaderOnConfirm: true,
                preConfirm: () => {
                var form =$('#swal2-content form');
                var formData = new FormData(form[0]);
                    return $.ajax({
                        url: $this.data('url-save'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                    })
                    .then(response => {
                        if (response.status=='error'){
                            Swal.showValidationMessage('Request failed: '+response.message);
                        }
                    })
                    .catch(
                        error => {
                        Swal.showValidationMessage(
                            'Đã có lỗi xảy ra khi cập nhật'
                        )}
                    )
                },
                allowOutsideClick: () => !Swal.isLoading()

        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    type: 'success',
                    title: 'Cập nhật thành công',
                    animation: false,
                    customClass: 'animated zoomIn',
                })
            }
        });
        // $this.prop('disabled', false).html(btn_text);
        $(".swal2-popup .select2").each(function() {
            $(this).select2({
                dropdownAutoWidth : true,
                width:'100%',
                tags: true,
            });
        });
        $("#intend").datepicker( {
            format: "mm/yyyy",
            startView: "year",
            minViewMode: "months"
        });
    });
    function callback(data,type) { 
        if(type==1)
            subject=data; 
        else
            title=data;
    }
    $('table.bootstrap-table').on('click','.edit a',function (e) {
        var $this = $(this),
        url = $this.parents('table').data('url-edit'),
        id=$this.data('id');
        Swal.fire({
            width:'50%',
            title: 'Cập nhật đề xuất kế hoạch',
            animation: false,
            customClass: 'animated zoomIn',
            showCloseButton: true,
            showCancelButton: true,
            html: $('.form-popup').html(),
            onOpen: function (e) {
                $.ajax({
                    type: "get",
                    url: url,
                    data: {id:id},
                    dataType: "json",
                    beforeSend: function() { $('body').append("<div id='spinner-loader' class='loading'></div>") },
                    success: function (result) {
                        $('#spinner-loader').remove();
                        $('input[name=amount]').val(result.planSuggest.amount);
                        $('input[name=duration]').val(result.planSuggest.duration);
                        $('input[name=teacher]').val(result.planSuggest.teacher);
                        $('input[name=purpose]').val(result.planSuggest.purpose);
                        $('textarea[name=students]').val(result.planSuggest.students);
                        $('input[name=note]').val(result.planSuggest.note);
                        var tmpSubject=[], tmpTitle=[];
                        $.each(result.subject, function (index,value) {
                            tmpSubject.push(value.name);
                        });
                        $('.swal2-container select[name=subject_name]').select2({'data': null});
                        $(".swal2-container select[name=subject_name]").select2({
                            data:  tmpSubject,
                            dropdownAutoWidth : true,
                            width:'100%',
                            tags: true
                        });
                        if (result.subject_select!=null)
                            $('.swal2-container select[name=subject_name]').append("<option value='"+result.subject_select+"' selected>"+result.subject_select+"</option>").change();
                        else
                            $('.swal2-container select[name=subject_name]').val('').trigger('change');
                        $.each(result.subject, function (index,value) {
                            tmpTitle.push({id:value.id,text:value.name});
                        });
                        $('.swal2-container select[name="title[]"]').select2({'data': null});
                        $('.swal2-container select[name="title[]"]').select2({
                            data:  tmpTitle,
                            multiple: true,
                            dropdownAutoWidth : true,
                            width:'100%',
                        });
                        if (result.title_select!=null)
                            $('.swal2-container select[name="title[]"]').val(result.title_select).trigger('change');
                    },
                    complete: function() { $('#spinner-loader').remove(); }
                });
            }
        })
    })
});