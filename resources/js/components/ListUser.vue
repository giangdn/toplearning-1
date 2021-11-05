<template>
    <div id="chatuser">
        <div id="friendslist">
            <div id="topmenu">
                <span class="friends"></span>
                <span class="chats"></span>
                <span class="history"></span>
            </div>
        </div>

        <div id="friends">
            <div v-for="user in filteredUsersList" :key="user.id" @click="$emit('selectReceiver',user)" class="friend">
                <img src="/images/chat/1_copy.jpg" />
                <p>
                    <strong>{{ user.username }} {{ user.id === $root.user ? '(Bạn)' : '' }}</strong><br>
                    <span>{{user.email}}</span>
                </p>
                <div class="status available"></div>
            </div>
        </div>
        <div id="search">
            <input type="text" class="searchfield" value="" placeholder="Tìm kiếm..." />
            <a id="search-user-chat" class="search-user-chat"><i class="fas fa-search"></i></a>
        </div>
    </div>
</template>

<script>
export default {
  props: {
    usersOnline: {
      type: Array,
      required: true
    }
  },
  data () {
    return {
      searchQuery: ''
    }
  },
  computed: {
    filteredUsersList () {
      return this.usersOnline.filter(row => row.username.toLowerCase().includes(this.searchQuery.toLowerCase()))
    }
  }
}
</script>

<style>

</style>
