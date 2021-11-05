<div id="chatuser">
    <div id="friendslist">
        <div id="topmenu">
            <span class="friends"></span>
            <span class="chats"></span>
            <span class="history"></span>
        </div>

        <div id="friends">
            <div class="friend">
                <img src="{{asset('/images/chat/1_copy.jpg')}}" />
                <p>
                    <strong>Miro Badev</strong><br>
                    <span>mirobadev@gmail.com</span>
                </p>
                <div class="status available"></div>
            </div>
            <div class="friend">
                <img src="{{asset('/images/chat/2_copy.jpg')}}" />
                <p>
                    <strong>Martin Joseph</strong><br>
                    <span>marjoseph@gmail.com</span>
                </p>
                <div class="status away"></div>
            </div>
            <div class="friend">
                <img src="{{asset('/images/chat/3_copy.jpg')}}" />
                <p>
                    <strong>Tomas Kennedy</strong><br>
                    <span>tomaskennedy@gmail.com</span>
                </p>
                <div class="status inactive"></div>
            </div>
            <div class="friend">
                <img src="{{asset('images/chat/4_copy.jpg')}}" />
                <p>
                    <strong>Enrique	Sutton</strong><br>
                    <span>enriquesutton@gmail.com</span>
                </p>
                <div class="status inactive"></div>
            </div>
            <div class="friend">
                <img src="{{asset('images/chat/5_copy.jpg')}}" />
                <p>
                    <strong>	Darnell	Strickland</strong><br>
                    <span>darnellstrickland@gmail.com</span>
                </p>
                <div class="status inactive"></div>
            </div>
            <div class="friend">
                <img src="{{asset('images/chat/5_copy.jpg')}}" />
                <p>
                    <strong>	Darnell	Strickland</strong><br>
                    <span>darnellstrickland@gmail.com</span>
                </p>
                <div class="status inactive"></div>
            </div>
            <div class="friend">
                <img src="{{asset('images/chat/5_copy.jpg')}}" />
                <p>
                    <strong>	Darnell	Strickland</strong><br>
                    <span>darnellstrickland@gmail.com</span>
                </p>
                <div class="status inactive"></div>
            </div>
            <div class="friend">
                <img src="{{asset('images/chat/5_copy.jpg')}}" />
                <p>
                    <strong>	Darnell	Strickland</strong><br>
                    <span>darnellstrickland@gmail.com</span>
                </p>
                <div class="status inactive"></div>
            </div>
            <div class="friend">
                <img src="{{asset('images/chat/5_copy.jpg')}}" />
                <p>
                    <strong>	Darnell	Strickland</strong><br>
                    <span>darnellstrickland@gmail.com</span>
                </p>
                <div class="status inactive"></div>
            </div>
        </div>
        <div id="search">
            <input type="text" class="searchfield" value="" placeholder="Tìm kiếm..." />
            <a id="search-user-chat" class="search-user-chat"><i class="fas fa-search"></i></a>
        </div>
    </div>

    <div id="chatview" class="p1">
        <div id="profile">

            <div id="close">
                <div class="cy"></div>
                <div class="cx"></div>
            </div>

            <p>Miro Badev</p>
            <span>miro@badev@gmail.com</span>
        </div>
        <div id="chat-messages">
            <label>Thursday 02</label>

            <div class="message">
                <img src="{{asset('images/chat/1_copy.jpg')}}" />
                <div class="bubble">
                    Really cool stuff!
                    <div class="corner"></div>
                    <span>3 min</span>
                </div>
            </div>

            <div class="message right">
                <img src="{{asset('images/chat/2_copy.jpg')}}" />
                <div class="bubble">
                    Can you share a link for the tutorial?
                    <div class="corner"></div>
                    <span>1 min</span>
                </div>
            </div>

            <div class="message">
                <img src="{{asset('images/chat/1_copy.jpg')}}" />
                <div class="bubble">
                    Yeah, hold on
                    <div class="corner"></div>
                    <span>Now</span>
                </div>
            </div>

            <div class="message right">
                <img src="{{asset('images/chat/2_copy.jpg')}}" />
                <div class="bubble">
                    Can you share a link for the tutorial?
                    <div class="corner"></div>
                    <span>1 min</span>
                </div>
            </div>

            <div class="message">
                <img src="{{asset('images/chat/1_copy.jpg')}}" />
                <div class="bubble">
                    Yeah, hold on
                    <div class="corner"></div>
                    <span>Now</span>
                </div>
            </div>

        </div>

        <div id="sendmessage">
            <textarea name="chat_message" placeholder="Viết tin nhắn..." class="input-message"></textarea>
            <a class=" send-user-chat"><i class="zmdi zmdi-mail-send"></i></a>
        </div>

    </div>
</div>
