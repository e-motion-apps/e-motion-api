<template>
  <div class="container-of-login ">
    <div class="button-container">
      <button v-if="!isLoggedIn" class="login-button" @click="toggleLoginForm">
        {{ showLoginForm ? 'Hide Login' : 'Login' }}
      </button>
      <button v-if="isLoggedIn" class="logout-button" @click="logoutUser">
        Logout
      </button>
    </div>
    <div v-if="!isLoggedIn" class="login-form" :class="{ hidden: !showLoginForm }">
      <form @submit.prevent="loginUser">
        <div class="form-inside">
          <label>Email</label>
          <input type="email" v-model="user.email" class="text-inside-form" placeholder="Email">
        </div>
        <div class="form-inside">
          <label>Password</label>
          <input type="password" v-model="user.password" class="text-inside-form" placeholder="Password">
        </div>
        <button v-if="!isLoggedIn" type="submit" class="submit-login-button">Login</button>
      </form>
    </div>
    <!--Component Register, which is visible when user is not logged in-->
    <Register v-if="!isLoggedIn" :isLoggedIn="isLoggedIn" />
  </div>
</template>

<script>
import axios from 'axios';
import Register from './Register.vue';

export default {
  name: 'Login',
  components: { Register },
  data() {
    return {
      result: {},
      user: {
        email: '',
        password: ''
      },
      showLoginForm: false,
      isLoggedIn: false
    };
  },
  methods: {
    toggleLoginForm() {
      this.showLoginForm = !this.showLoginForm;
    },
    loginUser() {
      axios
        .post("http://localhost:3851/api/login", this.user)
        .then(({ data }) => {
          console.log(data);
          try {
            if (data.status === true) {
              this.isLoggedIn = true;
              this.showLoginForm = false;
              alert("Login Successful");
            } else {
              alert("Login Failed");
            }
          } catch (err) {
            alert("Error, please try again");
          }
        })
        .catch((error) => {
          console.error(error);
          alert("Error, please try again");
        });
    },
    logoutUser() {
      axios
        .post("http://localhost:3851/api/logout")
        .then(({ data }) => {
          console.log(data);
          try {
            if (data.status === true) {
              this.isLoggedIn = false;
              alert("Logged out successfully");
            } else {
              alert("Logout failed");
            }
          } catch (err) {
            alert("Error, please try again");
          }
        })
        .catch((error) => {
          console.error(error);
          alert("Error, please try again");
        });
    }
  }
};
</script>

<style src="/resources/css/style.css" scoped></style>
