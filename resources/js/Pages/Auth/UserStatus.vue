<script>
export default {
  computed: {
    isAuthenticated() {
      return this.$page.props.auth.loggedIn
    },
    user() {
      return this.$page.props.auth.user
    },
  },
}
</script>


<template>
  <div class="flex justify-end">
    <div v-if="isAuthenticated">
      Logged in as: {{ user.name }}
    </div>
    <div v-else>
      You are not logged in.
    </div>
  </div>
</template>
  
