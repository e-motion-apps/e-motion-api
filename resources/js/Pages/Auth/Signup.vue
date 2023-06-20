
<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'
const form = useForm({
  name: '',
  email: '',
  password: '',
  errors: {},
})

const submit = async () => {
  try { 
    form.post('/register') 
  } catch (error) {
    console.log(error)
  }
}
watch(() => form.errors, (errors) => {
  console.log(errors)
})
</script>

<template>
  <div class="flex h-screen w-full items-center justify-center bg-gradient-to-br from-blue-600 to-indigo-600">
    <form @submit.prevent="submit">
      <div class="w-screen max-w-sm rounded-xl bg-white px-10 py-8 shadow-md">
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <h1 class="text-center text-2xl font-semibold text-gray-600">
              Register
            </h1>
            <button type="button" class="mr-2 inline-flex items-center rounded-lg bg-blue-700 p-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
              <a href="/">back </a>
              <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
              <span class="sr-only">Icon description</span>
            </button>
          </div>
          <div>
            <label for="name" class="mb-1 block font-semibold text-gray-600">name</label>
            <input id="name" v-model="form.name" class="w-full rounded-md bg-indigo-50 px-4 py-2 outline-none" type="text" name="name" :placeholder="form.errors.name || 'Name is require'">
          </div>
          <div>
            <label for="email" class="mb-1 block font-semibold text-gray-600 ">email</label>
            <input id="email" v-model="form.email" class="w-full rounded-md bg-indigo-50 px-4 py-2 outline-none" type="email" name="email" placeholder="Email is require">
          </div>
          <div>
            <label for="password" class="mb-1 block font-semibold text-gray-600">password</label>
            <input id="password" v-model="form.password" class="w-full rounded-md bg-indigo-50 px-4 py-2 outline-none" type="password" name="password" :placeholder="form.errors.password|| 'Password of at least 8 characters'">
            <div v-if="form.errors" class="mt-1 text-xs text-red-500">
              {{ form.errors.password }}
            </div>
            <div v-if="form.errors.email && !form.errors.password" class="mt-1 text-xs text-red-500">
              Something goes wrong
            </div>
          </div>
        </div>
        <button type="submit" class="mt-4 w-full rounded-md bg-gradient-to-tr from-blue-600 to-indigo-600 py-2 text-lg tracking-wide text-indigo-100">
          Register
        </button>
      </div>
    </form>
  </div>
</template>

<style src="resources/css/app.css" scoped>
</style>
