$(document).ready(function () {
    var category_template = document.getElementById('category-template').innerHTML;
    var question_template = document.getElementById('question-template').innerHTML;
    var answer_template = document.getElementById('answer-template').innerHTML;
    var table_matrix_template = document.getElementById('table-matrix-template').innerHTML;

    $('#btn-category').on('click', function() {
        var cate_key = parseInt($('.item-category').last().data('cate_key'), 10) + 1;
        if (isNaN(cate_key)) {
            cate_key = 0;
        }
        let category = replacement_template(category_template, {
            'cate_key' : cate_key
        });

        $('#input-category').append(category);
    });

    $('#input-category').on('click', '#btn-question', function() {
        var cate_key = $(this).data('cate_key');
        var ques_key = parseInt($(this).parents('.item-category').find('.item-question').last().data('ques_key'), 10) + 1;

        if (isNaN(ques_key)) {
            ques_key = 0;
        }

        let question = replacement_template(question_template, {
            'index_question': parseInt(ques_key + 1),
            'cate_key' : cate_key,
            'ques_key' : ques_key,
        });

        $('.input-question-'+cate_key+'').append(question);
        $(this).parents('.item-category').find('.item-question').last().find('#btn-question-answer-matrix').hide();
    });

    $('#input-category').on('click', '#btn-question-answer', function(){
        var cate_key = $(this).data('cate_key');
        var ques_key = $(this).data('ques_key');
        var ans_key = parseInt($(this).parents('.item-question').find('.item-answer').last().data('ans_key'), 10) + 1;

        if (isNaN(ans_key)) {
            ans_key = 0;
        }
        var ques_type = $('#ques_type_'+cate_key+'_'+ques_key+' option:selected').val();

        let answer = replacement_template(answer_template, {
            'index_answer': parseInt(ans_key + 1),
            'cate_key' : cate_key,
            'ques_key' : ques_key,
            'ans_key' : ans_key,
        });

        $('.input-question-'+cate_key+'-answer-'+ques_key+'').append(answer);
    });

    $('#input-category').on('click', '#btn-question-answer-row', function(){
        var cate_key = $(this).data('cate_key');
        var ques_key = $(this).data('ques_key');

        var ans_row_key = parseInt($('#table-matrix-'+cate_key+'-'+ques_key+'').find('.matrix-row-content').last().data('ans_key'), 10) + 1;

        var matrix_row_content = '<tr class="matrix-row-content" data-ans_key="'+ ans_row_key +'">\n' +
            '<th>\n' +
            '<input name="is_row['+ cate_key +']['+ ques_key +'][]" type="hidden" value="1">\n' +
            '<input name="answer_id['+ cate_key +']['+ ques_key +'][]" type="hidden" value="">\n' +
            '<div class="input-group">\n' +
            '<textarea name="answer_code['+ cate_key +']['+ ques_key +'][]" rows="3" class="w-25 answer_code" data-cate_key="'+ cate_key +'" data-ques_key="'+ ques_key +'" placeholder="Mã tùy chọn"></textarea>\n' +
            '<textarea name="answer_name['+ cate_key +']['+ ques_key +'][]" class="form-control" placeholder="Tùy chọn"></textarea>\n' +
            '<a href="javascript:void(0)" class="btn btn-remove align-items-center" id="del-answer-row" data-ans_id=""> <i class="fa fa-trash"></i> </a>\n' +
            '</div>\n' +
            '</th>\n';

        $('.matrix-col-item-'+ cate_key +'-'+ ques_key+'').each(function( index ) {
            var ans_key = $(this).data('ans_key');
            matrix_row_content += '<th class="col-item-'+ ans_key +'">\n' +
                '<textarea name="answer_matrix_code['+ cate_key +']['+ ques_key +']['+ ans_row_key +']['+ ans_key +']" class="form-control" placeholder=""></textarea>\n' +
                '</th>\n';
        });
        matrix_row_content += '</tr>';

        $('#table-matrix-'+cate_key+'-'+ques_key+'').append(matrix_row_content);
    });

    $('#input-category').on('click', '#btn-question-answer-col', function(){
        var cate_key = $(this).data('cate_key');
        var ques_key = $(this).data('ques_key');

        var ans_key = parseInt($('#table-matrix-'+cate_key+'-'+ques_key+'').find('.matrix-row-title').find('.matrix-col-item-'+ cate_key +'-'+ ques_key +'').last().data('ans_key'), 10) + 1;

        var matrix_row_title = '<th class="matrix-col-item-'+ cate_key +'-'+ ques_key +' col-item-'+ ans_key +'" data-ans_key="'+ ans_key +'">\n' +
            '<input name="is_row['+ cate_key +']['+ ques_key +'][]" type="hidden" value="0">\n' +
            '<input name="answer_id['+ cate_key +']['+ ques_key +'][]" type="hidden" value="">\n' +
            '<div class="input-group">\n' +
            '<textarea name="answer_code['+ cate_key +']['+ ques_key +'][]" class="form-control w-100 answer_code" data-cate_key="'+ cate_key +'" data-ques_key="'+ ques_key +'" placeholder="Mã tùy chọn"></textarea>\n' +
            '<textarea name="answer_name['+ cate_key +']['+ ques_key +'][]" class="form-control w-100" placeholder="Tùy chọn"></textarea>\n' +
            '<a href="javascript:void(0)" class="btn btn-remove-col-matrix text-center w-100" id="del-answer-col" data-ans_id="" data-ans_key="'+ ans_key +'"> <i class="fa fa-trash"></i> </a>\n' +
            '</div>\n' +
            '</th>';

        var matrix_row_content = '';

        $('#table-matrix-'+cate_key+'-'+ques_key+'').find('.matrix-row-title').append(matrix_row_title);
        $('#table-matrix-'+cate_key+'-'+ques_key+'').find('.matrix-row-content').each(function( index ) {
            var ans_row_key = $(this).data('ans_key');

            matrix_row_content = '<th class="col-item-'+ ans_key +'"><textarea name="answer_matrix_code['+ cate_key +']['+ ques_key +']['+ ans_row_key +']['+ ans_key +']" class="form-control" placeholder=""></textarea></th>';

            $(this).append(matrix_row_content)
        });
    });

    $('#input-category').on('change', '.check-answer', function() {
        if($(this).is(':checked')) {
            $(this).val(1);
        } else {
            $(this).val(0);
        }
    });

    $('#input-category').on('change', '.check-multiples', function() {
        if($(this).is(':checked')) {
            $(this).val(1);
        } else {
            $(this).val(0);
        }
    });

    $('#input-category').on('click', '#del-category', function() {
        var cate_id = parseInt($(this).data('cate_id'));
        if (isNaN(cate_id)) {
            $(this).parents('.item-category').remove();
        }else{
            $(this).parents('.item-category').remove();

            $.ajax({
                type: 'POST',
                url : remove_category,
                data : {
                    cate_id : cate_id,
                }
            }).done(function(data) {

                return false;
            }).fail(function(data) {

                Swal.fire(
                    'Lỗi hệ thống',
                    '',
                    'error'
                );
                return false;
            });
        }
    });

    $('#input-category').on('click', '#del-question', function() {
        var ques_id = parseInt($(this).data('ques_id'));
        if (isNaN(ques_id)) {
            $(this).parents('.item-question').remove();
        }else{
            $(this).parents('.item-question').remove();

            $.ajax({
                type: 'POST',
                url : remove_question,
                data : {
                    ques_id : ques_id,
                }
            }).done(function(data) {

                return false;
            }).fail(function(data) {

                Swal.fire(
                    'Lỗi hệ thống',
                    '',
                    'error'
                );
                return false;
            });
        }
    });

    $('#input-category').on('click', '#del-answer', function() {
        var ans_id = parseInt($(this).data('ans_id'));
        if (isNaN(ans_id)) {
            $(this).parents('.item-answer').remove();
        }else{
            $(this).parents('.item-answer').remove();

            $.ajax({
                type: 'POST',
                url : remove_answer,
                data : {
                    ans_id : ans_id,
                }
            }).done(function(data) {

                return false;
            }).fail(function(data) {

                Swal.fire(
                    'Lỗi hệ thống',
                    '',
                    'error'
                );
                return false;
            });
        }
    });

    $('#input-category').on('click', '#del-answer-row', function() {
        var ans_id = parseInt($(this).data('ans_id'));
        if (isNaN(ans_id)) {
            $(this).parents('.matrix-row-content').remove();
        }else{
            $(this).parents('.matrix-row-content').remove();

            $.ajax({
                type: 'POST',
                url : remove_answer,
                data : {
                    ans_id : ans_id,
                }
            }).done(function(data) {

                return false;
            }).fail(function(data) {

                Swal.fire(
                    'Lỗi hệ thống',
                    '',
                    'error'
                );
                return false;
            });
        }
    });

    $('#input-category').on('click', '#del-answer-col', function() {
        var ans_id = parseInt($(this).data('ans_id'));
        var ans_key = $(this).data('ans_key');

        if (isNaN(ans_id)) {
            $(this).parents('.table').find('.col-item-'+ ans_key +'').remove();
        }else{
            $(this).parents('.table').find('.col-item-'+ ans_key +'').remove();

            $.ajax({
                type: 'POST',
                url : remove_answer,
                data : {
                    ans_id : ans_id,
                }
            }).done(function(data) {

                return false;
            }).fail(function(data) {

                Swal.fire(
                    'Lỗi hệ thống',
                    '',
                    'error'
                );
                return false;
            });
        }
    });

    $('#input-category').on('change', '.ques_type', function() {
        var cate_key = $(this).data('cate_key');
        var ques_key = $(this).data('ques_key');

        $('.input-question-'+cate_key+'-answer-'+ques_key+'').html('');
        $('.table-matrix-'+cate_key+'-'+ques_key+'').html('');

        var ques_type = $('#ques_type_'+cate_key+'_'+ques_key+' option:selected').val();

        if (ques_type == 'matrix' || ques_type == 'matrix_text' || ques_type == 'essay' || ques_type == 'time'){
            $(this).parents('.item-question').find('#btn-question-answer').hide();
        }else {
            $(this).parents('.item-question').find('#btn-question-answer').show();
        }

        if (ques_type == 'essay'){
            var answer_essay = '<textarea class="form-control" placeholder="Nội dung" readonly></textarea>';
            $('.input-question-'+cate_key+'-answer-'+ques_key+'').html(answer_essay);
            $(this).parents('.item-question').find('#check-multiples').val(0);
            $(this).parents('.item-question').find('#check-multiples').prop('checked', false);
        }

        if (ques_type == 'time'){
            var answer_time = '<div class="input-group mb-3">\n' +
                '  <input type="text" class="form-control" placeholder="Ngày/Tháng/Năm" aria-describedby="basic-addon2" readonly>\n' +
                '  <div class="input-group-append">\n' +
                '    <span class="input-group-text" id="basic-addon2"> <i class="fa fa-clock"></i></span>\n' +
                '  </div>\n' +
                '</div>';
            $('.input-question-'+cate_key+'-answer-'+ques_key+'').html(answer_time);
            $(this).parents('.item-question').find('#check-multiples').val(0);
            $(this).parents('.item-question').find('#check-multiples').prop('checked', false);
        }

        if (ques_type == 'matrix' || ques_type == 'matrix_text'){
            let table_matrix = replacement_template(table_matrix_template, {
                'cate_key' : cate_key,
                'ques_key' : ques_key,
            });

            $(this).parents('.item-question').find('#btn-question-answer-matrix').find('.table-matrix-'+cate_key+'-'+ques_key+'').append(table_matrix);
            $(this).parents('.item-question').find('#btn-question-answer-matrix').show();
        }else{
            $(this).parents('.item-question').find('#btn-question-answer-matrix').hide();
        }
    });

    $('#input-category').on('change', '.question_code', function() {
        var cate_key = $(this).data('cate_key');
        var ques_code = '';

        var values = [];
        $("textarea[name=question_code\\["+cate_key+"\\]\\[\\]]").each(function(){
            var text = $(this).val();
            values.push(text);
            ques_code = text;
        });

        if (chkDuplicates(values, true)){
            show_message('Mã câu hỏi '+ ques_code +' đã được dùng', 'error');
            return false;
        }
    });

    $('#input-category').on('change', '.answer_code', function() {
        var cate_key = $(this).data('cate_key');
        var ques_key = $(this).data('ques_key');
        var answer_code = '';

        var values_answer = [];
        $("textarea[name=answer_code\\["+cate_key+"\\]\\["+ques_key+"\\]\\[\\]]").each(function(){
            var text = $(this).val();
            if (text != ''){
                values_answer.push(text);
                answer_code = text;
            }
        });

        if (chkDuplicates(values_answer, true)){
            show_message('Mã đáp án '+ answer_code +' đã được dùng', 'error');
            return false;
        }
    });

    function replacement_template(template, data){
        return template.replace(
            /{(\w*)}/g,
            function( m, key ){
                return data.hasOwnProperty( key ) ? data[ key ] : "";
            }
        );
    }

    function chkDuplicates(arr,justCheck){
        var len = arr.length, tmp = {}, arrtmp = arr.slice(), dupes = [];
        arrtmp.sort();
        while(len--){
            var val = arrtmp[len];
            if (/nul|nan|infini/i.test(String(val))){
                val = String(val);
            }
            if (tmp[JSON.stringify(val)]){
                if (justCheck) {return true;}
                dupes.push(val);
            }
            tmp[JSON.stringify(val)] = true;
        }
        return justCheck ? false : dupes.length ? dupes : null;
    }
});
