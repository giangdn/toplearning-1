$(document).ready(function () {

    $('#btn-category').on('click', function() {
        var cate_id = parseInt($('.item-category').last().attr('data-position'), 10) + 1;
        if (isNaN(cate_id)) {
            cate_id = 0;
        }

        var html = '<div class="item-category" data-position="' + cate_id + '">';
        html += '<div class="form-group row">';
        html += '<div class="col-sm-2 control-label"><label>Danh mục</label></div>';
        html += '<div class="col-md-7"><input name="category_name[]" type="text" class="form-control" value="" placeholder="-- Danh mục --"></div>';
        html += '<div class="col-md-3"><a href="java:void(0)" class="btn btn-danger" id="del-category">Xóa</a> <a class="btn btn-success" id="btn-question"><i class="fa fa-plus-circle"></i> Thêm câu hỏi</a></div>';
        html += '</div>'
        html += '<div class="col-md-12 input-question-'+cate_id+'"></div>';
        html += '</div>'; // đóng class = 'item-category'

        $('#input-category').append(html);
    });

    $('#input-category').on('click', '#btn-question', function() {
        var category = $(this).parents('.item-category').attr('data-position');
        var ques_id = parseInt($(this).parents('.item-category').find('.item-criteria').last().attr('data-point'), 10) + 1;

        if (isNaN(ques_id)) {
            ques_id = 0;
        }
        var html = '<div class="item-criteria" data-point= "' + ques_id + '">';
        html += '<div class="form-group row">';
        html += '<div class="col-sm-3 text-right"> <label> Câu hỏi </label> </div>';
        html += '<div class="col-md-6"><input name="question_name['+category+'][]" type="text" class="form-control" value="" placeholder="-- Câu hỏi --"></div>';
        html += '<div class="col-md-3"><div><input name="check_essay['+category+'][' + ques_id + ']" type="checkbox" id="btn-essay" value="">Tự luận ';
        html += '<input name="check_multiple['+category+'][' + ques_id + ']" value="" type="checkbox" id="btn-multiple">Chọn nhiều </div>';
        html += '<a href="java:void(0)" class="btn btn-danger" id="del-question">Xóa</a>';
        html += ' <a class="btn btn-success" id="btn-question-answer"><i class="fa fa-plus-circle"></i> Thêm câu trả lời</a> </div>';
        html += '</div>'; 
        html += '<div class="col-md-12 input-question-'+category+'-answer-'+ques_id+'"></div>';
        html += '</div>' // đóng class = "item-criteria"

        $('.input-question-'+category+'').append(html);
    });

    $('#input-category').on('change', '#btn-essay', function() {
        var category = $(this).parents('.item-category').attr('data-position');
        var ques_id = $(this).parents('.item-criteria').attr('data-point');

        if($(this).is(':checked')) {
            $('.item-answer-'+category+'-'+ques_id+'').remove();
            $(this).val('essay');
        }
        else {
            $(this).val('multiple_choice');
        }
    });
    $('#input-category').on('change', '#btn-multiple', function() {
        if($(this).is(':checked')) {
            $(this).val(1);
        }
        else {
            $(this).val(0);
        }
    });

    $('#input-category').on('click', '#btn-question-answer', function(){
        var category = $(this).parents('.item-category').attr('data-position');
        var question = $(this).parents('.item-criteria').attr('data-point');
        var answer = parseInt($(this).parents('.item-criteria').find('.item-answer-'+category+'-'+question+'').last().attr('data-ans'), 10) + 1;

        if (isNaN(answer)) {
            answer = 0;
        }

        var html = '<div class="form-group row item-answer-'+category+'-'+question+' " data-ans="'+ answer +'" >';
        html += '<div class="col-sm-4 text-right"> <label> Câu trả lời </label> </div>';
        html += '<div class="col-md-5"><input name="question_answer_name['+category+']['+question+'][]" type="text" class="form-control" value="" placeholder="-- Câu trả lời --"></div>';
        html += '<div><input name="check_answer['+category+']['+question+']['+answer+']" value="" id="check-answer" type="checkbox" >  Nhập text <a href="java:void(0)" class="btn btn-danger" id="del-answer">Xóa</a></div>';
        html += '</div>';

        $('.input-question-'+category+'-answer-'+question+'').append(html);

        $('input[name="check_essay['+category+'][]"]').prop('checked', false);

    });
    $('#input-category').on('change', '#check-answer', function() {

        if($(this).is(':checked')) {
            $(this).val(1);
        }
        else {
            $(this).val(0);
        }
    });

    $('#input-category').on('click', '#del-category', function() {
        $(this).parents('.item-category').remove();
    });

    $('#input-category').on('click', '#del-question', function() {
        $(this).parents('.item-criteria').remove();
    });

    $('#input-category').on('click', '#del-answer', function() {
        var category = $(this).parents('.item-category').attr('data-position');
        var question = $(this).parents('.item-criteria').attr('data-point');
        $(this).parents('.item-answer-'+category+'-'+question+'').remove();
    });
    
});