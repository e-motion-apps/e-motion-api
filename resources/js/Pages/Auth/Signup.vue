<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  password: '',
})

const submit = async () => {
  try { 
    form.post('/register') 
  } catch (error) {
    console.log(error)
  }
}



</script>

<template>
  <div class="flex h-screen w-full items-center justify-center bg-gradient-to-br from-blue-600 to-indigo-600">
    <form @submit.prevent="submit">
      <div class="w-screen max-w-sm rounded-xl bg-white px-10 py-8 shadow-md">
        <div class="space-y-4">
          <h1 class="text-center text-2xl font-semibold text-gray-600">
            Register
          </h1>
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
