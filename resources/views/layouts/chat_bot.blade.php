<div class="fabs">
    <div class="chat">
        <div class="chat_header">
            <span id="chat_head">Live Chat</span>
            <div class="chat_loader"></div>
            <div class="chat_option"><i class="zmdi zmdi-more-vert"></i>
                <ul>
                    <li><span class="chat_color" style="border:solid 5px #2196F3" color="blue"></span></li>
                    <li><span class="chat_color" style="border:solid 5px #00bcd4" color="cyan"></span></li>
                    <li><span class="chat_color" style="border:solid 5px #607d8b" color="blue-grey"></span></li>
                    <li><span class="chat_color" style="border:solid 5px #4caf50" color="green"></span></li>
                    <li><span class="chat_color" style="border:solid 5px #8bc34a" color="light-green"></span></li>
                    <li><span class="chat_color" style="border:solid 5px #cddc39" color="lime"></span></li>
                    <li><span class="chat_color" style="border:solid 5px #ffc107" color="amber"></span></li>
                    <li><span class="chat_color" style="border:solid 5px #ff5722" color="deep-orange"></span></li>
                    <li><span class="chat_color" style="border:solid 5px #f44336" color="red"></span></li>
                </ul>
            </div>
            <div id="close">
                <div class="cy s1 s2 s3"></div>
                <div class="cx s1 s2 s3"></div>
            </div>
        </div>
        <div class="chat_login">
            <a id="chat_send_email" class="fab"><i class="zmdi zmdi-email"></i></a>
            <input id="chat_log_email" placeholder="Email">
            <div class="chat_login_alert"></div>
        </div>
        <div id="chat_converse" class="chat_converse is-max">
      <span class="chat_msg_item chat_msg_item_admin">
            <div class="chat_avatar">
              <i class="zmdi zmdi-headset-mic"></i>
            </div>Xin chào ! Tôi có thể giúp gì cho bạn</span>
        </div>
        <div class="fab_field">
            <a id="fab_listen" class="fab_listen fab2"><i class="zmdi zmdi-mic-outline"></i></a>
            <a id="fab_send" class="fab_send fab2"><i class="zmdi zmdi-mail-send"></i></a>
            <textarea id="chatSend" name="chat_message" placeholder="Write a message" class="chat_field chat_message"></textarea>
        </div>
    </div>
    <a target="_blank" id="chat_user" class="fab"><i class="fa fa-comments" aria-hidden="true"></i></a>
    <a target="_blank" id="chat_bot" class="fab"><i class="fas fa-robot"></i></a>
    <a id="prime" class="fab"><i class="prime zmdi zmdi-plus"></i></a>
</div>
<script src='{{ asset('js/chat.js?v='.time()) }}'></script>
