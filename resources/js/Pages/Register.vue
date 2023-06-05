<template>
  <div class="register-container" v-if="!isLoggedIn">
    <div class="button-container">
      <button class="register-button" @click="toggleRegisterForm">
        {{ showRegisterForm ? 'Hide Register' : 'Register' }}
      </button>
    </div>
    <div class="register-form" :class="{ hidden: !showRegisterForm }">
      <form @submit.prevent="saveData">
        <div class="form-inside">
          <label>First Name</label>
          <input type="text" v-model="user.name" class="text-inside-form" placeholder="First Name">
        </div>
        <div class="form-inside">
          <label>Email</label>
          <input type="email" v-model="user.email" class="text-inside-form" placeholder="Email">
        </div>
        <div class="form-inside">
          <label>Password</label>
          <input type="password" v-model="user.password" class="text-inside-form" placeholder="Password">
        </div>
        <button type="submit" class="register-submit-button">Register</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Register',
  props: {
    isLoggedIn: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      user: {
        name: '',
        email: '',
        password: ''
      },
      showRegisterForm: false
    };
  },
  methods: {
    toggleRegisterForm() {
      this.showRegisterForm = !this.showRegisterForm;
    },
    saveData() {
      axios.post("http://localhost:3851/api/register", this.user)
        .then(({ data }) => {
          console.log(data);
          try {
            alert("Your account has been registered");
            this.user.name = '';
            this.user.email = '';
            this.user.password = '';
          } catch (err) {
            alert("Registration completed unsuccessfully");
          }
        })
        .catch((error) => {
          console.error(error);
          alert("Failed");
        });
    }
  }
};
</script>

<style src="/resources/css/style.css" scoped></style>
