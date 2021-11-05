<template>
<!--    <div id="chatview" class="p1">-->
<!--        <div id="profile">-->
<!--            <div id="close">-->
<!--                <div class="cy"></div>-->
<!--                <div class="cx"></div>-->
<!--            </div>-->

<!--            <p>Miro Badev</p>-->
<!--            <span>miro@badev@gmail.com</span>-->
<!--        </div>-->
<!--        <div class="messages" id="chat-messages">-->
<!--            <MessageItem v-for="(message, index) in chat.message.list" :key="index" :message="message"></MessageItem>-->
<!--        </div>-->
<!--        <div id="sendmessage">-->
<!--            <textarea name="chat_message" v-model="message" @keyup.enter="saveMessage" placeholder="Viết tin nhắn..." class="input-message"></textarea>-->
<!--            <a class=" send-user-chat" @click="saveMessage"><i class="zmdi zmdi-mail-send"></i></a>-->
<!--        </div>-->
<!--    </div>-->
</template>

<script>
    // import MessageItem from './MessageItem'
    export default {
        props: {
            chat:{
                type:Object,
                require:true
            }

        },
        // components: {
        //     MessageItem
        // },
        data() {
            return {
                message: '',
                list_messages: []
            }
        },
        created () {
            // this.loadMessage()
            // Echo.channel('laravel_database_chatroom').listen('MessagePosted', (data)=>{
            //     let message = data.message
            //     message.user = data.user
            //     this.list_messages.push(message)
            // })
        },
        methods: {
            saveMessage () {

            },
            // async loadMessage() {
            //     try {
            //         const response = await axios.get('/messages/private',{
            //             receiver,
            //             content: message,
            //             room: receiver ? null : this.currentRoom.id
            //         })
            //         this.list_messages = response.data
            //     } catch (error) {
            //         console.log(error)
            //     }
            // },
            // async sendMessage() {
            //     try {
            //         const response = await axios.post('/messages', {
            //             message: this.message
            //         })
            //         this.list_messages.push(response.data.message)
            //         this.message = ''
            //     } catch (error) {
            //         console.log(error)
            //     }
            // }
        }
    }
</script>

