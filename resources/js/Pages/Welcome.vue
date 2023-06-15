<script>

import UserStatus from './Auth/UserStatus.vue'

export default {
  components: {
    UserStatus, 
  },
  data() {
    return {
      loggedIn: false,
      key: 0, 
    }
  },
  computed: {
    isAuthenticated() {
      return this.$page.props.auth.loggedIn
    },
  },
  watch: {
    '$route': {
      handler: function() {
        this.loggedIn = this.isAuthenticated
      },
      immediate: true,
    },
  },
  mounted() {
    this.loggedIn = this.isAuthenticated
  },
  methods: {
    logout() {
      this.$inertia.post('/logout')
        .then(() => {
          this.$inertia.visit('/')
        })
        .catch((error) => {
          console.error(error)
        })
    },
    forceRerender() {
      this.key++
    },
  },
}
</script>


<template>
  <div>
    <user-status class="justify-end bg-gray-200 p-3" />
    <section class="bg-gray-200 p-3" style="margin-top: -20px;">
      <header class="flex justify-between">
        <h1 class="text-3xl">
          <a href="/" class="text-blue-600 underline">Welcome</a>
          <span class="px-2" />
          <a v-if="!loggedIn" href="/login" class="text-blue-600 underline">Login</a>
          <span class="px-2" />
          <a v-if="!loggedIn" href="/signup" class="text-blue-600 underline">Sign Up</a>
          <span class="px-2" />
          <button v-if="loggedIn" class="text-blue-600 underline" @click="logout">
            Log Out
          </button>
        </h1>
      </header>
    </section>
  </div>
</template>
