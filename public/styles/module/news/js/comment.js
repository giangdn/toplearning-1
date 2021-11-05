$('.reply-comment').click(function () { 
        
        $('.reply-comment').click(function () { 
            this.id;
        });
        
        if ($('#rep-binhluan' + this.id).hasClass('hiddd')) {
            $('#rep-binhluan' + this.id).removeClass('hiddd');
        } else {
            $('#rep-binhluan' + this.id).addClass('hiddd');
        }
    });  
    
function get_comment(id_content){
    $('.reply-comment').click(function () { 
        
        $('.reply-comment').click(function () { 
            this.id;
        });
    
        if ($('#rep-binhluan' + this.id).hasClass('hiddd')) {
            $('#rep-binhluan' + this.id).removeClass('hiddd');
        } else {
            $('#rep-binhluan' + this.id).addClass('hiddd');
        }
    });
    
    $('.btn-send-comment').click(function (){

        var id_parent = $(this).data('cmt-id');
        var content = $('#rep-binhluan'+ id_parent +' #comment-content').val();        

        $.ajax({         
            method: "POST",
            url: "/index.php?_mod=content&_view=article&_lay=save&id="+ id_content +"&_page=42",
            data: { content: content, parent: id_parent}
        })
        .done(function( data ) {
            var obj = jQuery.parseJSON(data);
            if(obj.result === 'ok') {
                var strclass = '';
                if(id_parent > 0) strclass = 'rep-cmt';
                var html = '<div class="comment-list cmt-'+ obj.id_cmt +' '+strclass+'">  <section class="avatar col-xs-3">  <img class="img-fluid" alt="Responsive image" src="includes/images/user_avatar.png">  </section>  <section class="comment-detail col-xs-9 col-10">  <a> '+ obj.user_name +' </a> </section><div class="row bao_comment"> <div class="col-sm-12"> '+ obj.created_cmt +'            </div> <div class="col-sm-12"> '+ content +'            </div> </div> <div class="row"> <div class="col-sm-8"> </div> <div class="col-sm-3 text-right repcomment"> <div class="like-reply-action"><a class="reply-comment" id="'+ obj.id_cmt +'" style="cursor: pointer;" title="Trả lời">Trả lời</a></div>  </div> </div> <div class="row hidden showrep " id="rep-6"> <div class="col-sm-2"></div> <div class="col-sm-10">  <form method="post" action="/index.php?_mod=content&amp;_view=article&amp;_lay=save&amp;id=16&amp;_page=42" class="form-inline" id="frm-comment" style="display: block;"> <div id="rep-binhluan'+ obj.id_cmt +'" class="rep_cmt hiddd row form-group" style="padding: 0.2em;"> <img class="img-fluid hide_img" src="includes/images/user_tt.png"> <input type="hidden" name="parent" id="parent-'+ obj.id_cmt +'" value="'+ obj.id_cmt +'">  <textarea class="form-control col-md-9" name="content" id="comment-content" placeholder="Trả lời..." style="border:0px;"></textarea> <button type="submit" class="btn-send-comment" style="background: none;border: none;color: #003768;font-size: 30px;outline: none;" data-cmt-id="'+ obj.id_cmt +'"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>  </div> </form> <div id="responseDiv"> </div> <br></div> </div></div>';       

                var strid = id_parent > 0 ? '.cmt-'+id_parent : '.show_cmt_1';
                $(strid).html($(strid).html()+html);
                $('#rep-binhluan'+ id_parent +' #comment-content').val('');
                
                $.getScript('gapp/modules/modContent/asset/js/comment.js');
                get_comment(id_content);
                
                
            }
            else
            {

                $('#error-comment-0').html(obj.msg);
            }    
        });
        return false;
    });
}

