<template>
    <div>
        <ListUser :usersOnline="usersOnline" @selectReceiver="selectReceiver"  />
        <chat-user :chat="privateChat" v-if="privateChat.selectedReceiver" />
    </div>
</template>

<script>
    import ListUser from './ListUser'
    import ChatUser from './ChatUser'
    export default {
        components: {
            ListUser,
            ChatUser,
        },
        data() {
            return {
                usersOnline: [],
                currentRoom: {},
                publicChat: {},
                privateChat: {
                    selectedReceiver: null,
                    isPrivateChatExpand: false,
                    isSelectedReceiverTyping: false,
                    hasNewMessage: false,
                    isSeen: null, // null: no new message, false: a message is waiting to be seen, true: user seen message (should display "Seen at..")
                    seenAt: '',
                    roomId: '',
                    isOnline: true,
                    message: {
                        isLoading: false,
                        list: [],
                        currentPage: 0,
                        perPage: 0,
                        total: 0,
                        lastPage: 0,
                        newMessageArrived: 0 // number of new messages we just got (use for saving scroll position)
                    }
                },
            }
        },
        created () {

            Echo.join('login')
                .here((users) => {
                    this.usersOnline = users
                    // console.log(users);
                })
                .joining((user) => {
                    this.usersOnline.push(user)
                })
                .leaving((user) => {
                    // this.usersOnline = this.usersOnline.findIndex(item => item !== user)
                    const index = this.usersOnline.findIndex(item => item.id === user.id)
                    if (index > -1) {
                        this.usersOnline.splice(index, 1)
                    }
                })
                // .listen('MessagePosted', e => {
                //     this.publicChat.message.list.push(e.message)
                //     this.scrollToBottom(document.getElementById('shared_room'), true)
                // })
        },
        methods: {
            async getMessages (room, page = 1, loadMore = false) {
                const isPrivate = room.toString().includes('__')
                const chat = isPrivate ? this.privateChat : this.publicChat
                try {
                    chat.message.isLoading = true
                    const response = await axios.get(`/messages?room=${room}&page=${page}`)

                    chat.message.list = [...response.data.data.reverse(), ...chat.message.list]
                    chat.message.currentPage = response.data.current_page
                    chat.message.perPage = response.data.per_page
                    chat.message.lastPage = response.data.last_page
                    chat.message.total = response.data.total
                    chat.message.newMessageArrived = response.data.data.length

                    // if (loadMore) {
                    //     this.$nextTick(() => {
                    //         const el = $(isPrivate ? '#private_room' : '#shared_room')
                    //         const lastFirstMessage = el.children().eq(chat.message.newMessageArrived - 1)
                    //         el.scrollTop(lastFirstMessage.position().top - 10)
                    //     })
                    // } else {
                    //     this.scrollToBottom(document.getElementById(isPrivate ? 'private_room' : 'shared_room'), false)
                    // }
                } catch (error) {
                    console.log(error)
                } finally {
                    chat.message.isLoading = false
                }
            },
            async loadMessage() {
                // try {
                //     const response = await axios.get('/messages/private',{
                //         receiver,
                //         content: message,
                //         room: receiver ? null : this.currentRoom.id
                //     })
                //     this.list_messages = response.data
                // } catch (error) {
                //     console.log(error)
                // }
            },
            async sendMessage() {
                // try {
                //     const response = await axios.post('/messages', {
                //         message: this.message
                //     })
                //     this.list_messages.push(response.data.message)
                //     this.message = ''
                // } catch (error) {
                //     console.log(error)
                // }
            },
            async selectReceiver (receiver) {
                const roomId = this.$root.user > receiver.id ? `${receiver.id}__${this.$root.user}` : `${this.$root.user}__${receiver.id}`
                this.privateChat.selectedReceiver = receiver
                this.privateChat.isPrivateChatExpand = true
                this.privateChat.roomId = roomId
                await this.getMessages(roomId)
            }
        }
    }
</script>
