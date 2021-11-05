$(".view-skill").on('click', function(){
    var url = "/?_mod=ttc&_act=getskillsubject";
    var subjectid   = $(this).data('subject-id');
    var subjectname = $(this).data('subject-name');
    var courseid = $(this).data('course-id');
    var coursetype = $(this).data('course-type');
    var endtime = $(this).data('end-time');

    $("#modal-skill-"+courseid).modal();
});

$(".add-new").on('click', function(){

    var courseid = $(this).data('courseid');
    var modal = "#modal-skill-"+courseid;

    var html = '<br /><div class="row block-item"> <div class="col-md-12"><label>Định hướng</label> <input type="text" name="title[]" class="form-control" placeholder="Định hướng"/> </div> </div>';
    $(modal+" #show-add").append(html);
});

$(".form-skill").on('submit', function(){
    var url = '/?_mod=ttc&_act=addskillsubject';
    var btn = $(".btn-modal-add");
    var data = $(this).serialize();
    var courseid = $(this).data('courseid');
    var modal = "#modal-skill-"+courseid;
    var skillid = $(modal+" #skill").val();

    if(skillid === "0")
    {
        flash_msg('Bạn chưa chọn kỹ năng', 'warning');
        return false;
    }

    btn.attr("disabled", true);
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function(data)
        {
            if(data === "ok")
            {
                btn.attr("disabled", false);
                flash_msg('Cập nhật thành công!');
            }
        }
    });
    return false;
});

$(".remove-block-item").on('click', function(){
    var url = '/?_mod=ttc&_act=remove_item_skill_users_orientation';
    var itemid = $(this).data('id');
    $.ajax({
        type: "POST",
        url: url,
        data: {'itemid': itemid},
        success: function(data)
        {
            if(data === "ok")
            {
                $("#item-"+itemid).remove();
            }
        }
    });
});

function flash_msg(message,type)
{message=typeof message=='undefined'?' ':message;var style='style = "display:none;z-index:16000;position:fixed;left:40%; top:40%;max-width:25em;min-width:15em;padding:15px;text-align:center;font-size:1.2em;box-shadow:2px 2px 3px gray;padding:15px;';switch(type){case 'warning':style+='border:1px solid #EC971F;color:#fff;background:#EC971F';break;case 'error':style+='border:1px solid #900;color:#fff;background:#F00';break;default:style+='border:1px solid #449D44;color:#fff;background:#449D44;';break}
style+='"';$('body').append('<div id="flash-msg" '+style+' >'+message+'</div>');$('#flash-msg').fadeIn();setTimeout(function(){$('#flash-msg').remove()},3000)}