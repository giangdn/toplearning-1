<template>
    <div class="message" v-if="message.from === 2">
        <img src="images/chat/1_copy.jpg" />
        <div class="bubble">
            {{ message.content}}
            <div class="corner"></div>
            <span>3 min</span>
        </div>
    </div>

    <div class="message right" v-else>
        <img src="images/chat/2_copy.jpg" />
        <div class="bubble">
            {{ message.content}}
            <div class="corner"></div>
            <span>1 min</span>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            message: {
                type: Object,
                default: {}
            }
        }
    }
</script>

