<script setup>
import { useForm } from '@inertiajs/vue3'
import ErrorMessage from '@/Shared/Components/ErrorMessage.vue'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

function registerUser() {
  form.post('/register')
}
</script>

<template>
  <div class="flex h-screen w-full items-center justify-center bg-gradient-to-br from-blue-600 to-indigo-600">
    <form @submit.prevent="registerUser">
      <div class="w-screen max-w-sm rounded-xl bg-white px-10 py-8 shadow-md">
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <h1 class="text-center text-2xl font-semibold text-gray-600">
              Register
            </h1>
            <a href="/" class="mr-2 inline-flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
              <span>Back</span>
            </a>
          </div>
          <div>
            <label for="name" class="mb-1 block font-semibold text-gray-600">Name</label>
            <input id="name" v-model="form.name" class="w-full rounded-md bg-indigo-50 px-4 py-2 outline-none" type="text" name="name" :placeholder="form.errors.name || 'Name is required'">
            <error-message :message="form.errors.name" />
          </div>
          <div>
            <label for="email" class="mb-1 block font-semibold text-gray-600">Email</label>
            <input id="email" v-model="form.email" class="w-full rounded-md bg-indigo-50 px-4 py-2 outline-none" type="email" name="email" placeholder="Email is required">
            <error-message :message="form.errors.email" />
          </div>
          <div>
            <label for="password" class="mb-1 block font-semibold text-gray-600">Password</label>
            <input id="password" v-model="form.password" class="w-full rounded-md bg-indigo-50 px-4 py-2 outline-none" type="password" name="password" :placeholder="form.errors.password || 'Password of at least 8 characters'">
            <error-message :message="form.errors.password" />
          </div>
          <div>
            <label for="password_confirmation" class="mb-1 block font-semibold text-gray-600">Confirm Password</label>
            <input id="password_confirmation" v-model="form.password_confirmation" class="w-full rounded-md bg-indigo-50 px-4 py-2 outline-none" type="password" name="password_confirmation" placeholder="Confirm your password">
            <error-message :message="form.errors.password_confirmation" />
          </div>
        </div>
        <button type="submit" class="mt-4 w-full rounded-md bg-gradient-to-tr from-blue-600 to-indigo-600 py-2 text-lg tracking-wide text-indigo-100">
          Register
        </button>
      </div>
    </form>
  </div>
</template>
