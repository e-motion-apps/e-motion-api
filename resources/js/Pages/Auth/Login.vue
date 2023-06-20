<script setup>
import { useForm } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
const form = useForm({
  email: '',
  password: '',
})

const props = defineProps({ 
  'errors': Object,
})

onMounted(() => {
  console.log('test' + props.errors)
})

const error = ref('') 

function attemptLogin() {
  form.post('/login', {
    onError: function(err) {
      error.value=err
    },
  })
}
</script>

<template>
  <div class="bg-gradient-to-br from-blue-600 to-indigo-600">
    <head title="Log In" />
  
    <main class="custom-main grid min-h-screen place-items-center">
      <div class="float-right mt-4 w-1/2">
        <section class="mx-auto max-w-md rounded-xl border bg-white p-4">
          <div class="flex items-center justify-between">
            <h1 class="mb-4 text-2xl font-semibold text-gray-600">
              Log In
            </h1>
            <button type="button" class="mr-2 inline-flex items-center rounded-lg bg-blue-700 p-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
              <a href="/">back</a>
              <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
              <span class="sr-only">Icon description</span>
            </button>
          </div>
          
          <form @submit.prevent="attemptLogin">
            <div class="mb-4">
              <label class="mb-1 block text-xs font-bold uppercase text-gray-500" for="email">Email</label>
              <input id="email" v-model="form.email" class="w-full rounded-md bg-indigo-50 px-4 py-2 outline-none" type="email" name="email">
            </div>

            <div class="mb-4">
              <label class="mb-1 block text-xs font-bold uppercase text-gray-500" for="password">Password</label>
              <input id="password" v-model="form.password" class="w-full rounded-md bg-indigo-50 px-4 py-2 outline-none" type="password" name="password">
              <div class="mt-1 text-xs text-red-500">
                {{ form.errors.password }}
              </div>
            </div>

            <div>
              <button type="submit" class="mt-4 w-full rounded-md bg-gradient-to-tr from-blue-600 to-indigo-600 py-2 text-lg tracking-wide text-indigo-100" :disabled="form.processing">
                Log In
              </button>
              <div class="mt-1 text-xs text-red-500">
                {{ form.errors.field }}
              </div>
            </div>
          </form>
        </section>
      </div>
    </main>
  </div>
</template>

<style src="resources/css/app.css" scoped>
</style>
