function loadNotify(id)
    {
        /*$.ajax({
            url:"index.php?_mod=ttc&_view=calendar&_act=ajaxAlertEventCalendar",
            type:"POST",
            contentType: "application/x-www-form-urlencoded",
            dataType: 'json',
            data:{id:id},
            success:function(data)
            {

                if(data){ 
                    if($('.notifyjs-wrapper .notifyjs-metro-base').attr('id')==data.id) return;
                    dt = new Date(data.start_event);
                    $.notify(
                        {
                            id: data.id,
                            title: 'Lịch sự kiện ('+ dt.getDate()+"/"+(dt.getMonth()+1)+"/"+dt.getFullYear()+" "+dt.getFullHours()+":"+dt.getFullMinutes()+":"+dt.getFullSeconds()+")",
                            text: data.title,
                            image: "<img src='/gapp/themes/vw1/images/notify.png'/>"
                        },                    
                        {
                            position:"bottom left",
                            style: 'metro',
                            className: 'info',
                            autoHide: false,
                            clickToHide: true
                        }
                    );
                    $('.notifyjs-metro-base').attr('id', data.id);
                }
            }
        })*/
    };
    Date.prototype.getFullMinutes = function () {
       if (this.getMinutes() < 10) {
           return '0' + this.getMinutes();
       }
       return this.getMinutes();
    };
    Date.prototype.getFullHours = function () {
       if (this.getHours() < 10) {
           return '0' + this.getHours();
       }
       return this.getHours();
    };
    Date.prototype.getFullSeconds = function () {
       if (this.getSeconds() < 10) {
           return '0' + this.getSeconds();
       }
       return this.getSeconds();
    };
    $(document).on('click', '.notifyjs-metro-base', function() {
      loadNotify($(this).attr('id'));
    });
    loadNotify();
    setInterval(function(){
        loadNotify();
    }, 10000);
