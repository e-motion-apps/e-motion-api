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
          <h1 class="mb-4 text-2xl">
            Log In
          </h1>
          
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
