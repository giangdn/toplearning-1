$(document).ready(function(){
    $('#province_id').on('change',function (e) {
        var url = $(this).data('url') ;
        var data ={province_id : $(this).val()};
        $.get(url, data).done(function (result) {
            if (result && result.length) {
                var data = [{ id:'',text:'Chọn quận huyện'}];
                $.each(result, function (index, obj) {
                    data.push({
                        id: obj.id,
                        text: obj.name,
                    });
                });
                $('#district_id').empty().select2({
                    data: data,
                    width: '100%',
                });
            }
        });
    });
})