<script setup>
import {onMounted, onUpdated, ref} from 'vue'
import { Dialog, DialogPanel } from '@headlessui/vue'
import { Bars3Icon, XMarkIcon, UserCircleIcon, ArrowRightOnRectangleIcon } from '@heroicons/vue/24/outline'
import {Link, usePage} from '@inertiajs/vue3'
import {onClickOutside} from "@vueuse/core";
import { useForm } from '@inertiajs/vue3'
import ErrorMessage from '@/Shared/Components/ErrorMessage.vue'


onMounted(() => {
    console.log(page.props)
})

onUpdated(() => {
    console.log(page.props)
})

const loginForm = useForm({
    email: '',
    password: '',
})

function login() {
    loginForm.post('/login')
}

const registerForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

function register() {
    registerForm.post('/register')
}


const page = usePage();

const navigation = [
    { name: 'Prices', href: '#' },
    { name: 'Find a ride', href: '#' },
    { name: 'Rules', href: '#' },
]

const mobileMenuOpen = ref(false)

const isAuthDialogOpened = ref(false)
const authDialog = ref(null)
onClickOutside(authDialog, () => (isAuthDialogOpened.value = false))

function toggleAuthDialog() {
    isAuthDialogOpened.value = !isAuthDialogOpened.value;
}

const isLoginForm = ref(true)

function toggleAuthOption() {
    isLoginForm.value = !isLoginForm.value;
    loginForm.reset()
    loginForm.errors = []

    registerForm.reset()
    registerForm.errors = []

}

function toggleCreateAccountOption() {
    toggleAuthDialog()
    isLoginForm.value = false;
}

defineExpose({
    toggleCreateAccountOption
});

</script>

<template>
  <header class="w-full bg-white">
    <nav class="mx-auto flex items-center justify-between px-6 py-3" aria-label="Global">
      <Link href="/" class="flex items-center justify-center">
        <img class="h-10" src="@/assets/scooter.png" alt="escooter logo">
        <span class="ml-3 hidden text-2xl font-semibold text-gray-800 sm:flex">e-scooters</span>
      </Link>
      <div class="flex md:hidden">
        <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = true">
          <span class="sr-only">Open main menu</span>
          <Bars3Icon class="h-6 w-6" aria-hidden="true" />
        </button>
      </div>
      <div class="hidden items-center md:flex md:gap-x-12">
        <Link v-for="item in navigation" :key="item.name" :href="item.href" class="text-sm font-semibold leading-6 text-gray-800 lg:text-base">
          {{ item.name }}
        </Link>
          <button @click="toggleAuthDialog">
              <UserCircleIcon v-if="page.props.auth.isAuth" class="h-6 w-6" />
              <ArrowRightOnRectangleIcon v-else class="h-6 w-6"/>
          </button>
      </div>
    </nav>

      <div v-if="isAuthDialogOpened" class="fixed inset-0 flex items-center z-50 bg-black bg-opacity-50">
          <div ref="authDialog" class="w-11/12 sm:w-3/4 md:w-2/3 lg:w-1/2 xl:w-1/3 mx-auto bg-white rounded-lg shadow-lg">
              <div class="flex w-full justify-end">
                  <button @click="toggleAuthDialog" class="p-2">
                      <XMarkIcon class="h-6 w-6"/>
                  </button>
              </div>
              <div class="px-6 pb-8 rounded-lg" v-if="isLoginForm">
                  <form @submit.prevent="login" class="space-y-5">
                      <div>
                          <label class="block text-sm text-gray-800 font-semibold">E-mail</label>
                          <input v-model="loginForm.email" type="email" class="w-full border-blumilk-200 rounded-lg py-3 md:p-2" required>
                      </div>
                      <div>
                          <label class="block text-sm text-gray-800 font-semibold">Password</label>
                          <input v-model="loginForm.password" type="password" class="w-full border-blumilk-200 rounded-lg py-3 md:p-2" required>
                          <ErrorMessage :message="loginForm.errors.loginError" />
                      </div>
                      <div class="flex w-full md:w-fit">
                          <button type="submit" class="px-4 w-full font-semibold py-4 md:py-2 text-white bg-blumilk-500 rounded-lg hover:bg-blumilk-600">Log in</button>
                      </div>
                  </form>
                  <button :disabled="loginForm.processing" class="mt-6 text-xs font-light" @click="toggleAuthOption">Don't have an account? <span class="font-normal">Sign up</span></button>
              </div>

              <div class="px-6 pb-8 rounded-lg" v-else>
              <form @submit.prevent="register" class="space-y-5">
                  <div>
                      <label class="block text-sm text-gray-800 font-semibold">Name</label>
                      <input v-model="registerForm.name" type="text" class="w-full border-blumilk-200 rounded-lg py-3 md:p-2" required>
                      <ErrorMessage :message="registerForm.errors.name" />
                  </div>

                  <div>
                      <label class="block text-sm text-gray-800 font-semibold">E-mail</label>
                      <input v-model="registerForm.email" type="email" class="w-full border-blumilk-200 rounded-lg py-3 md:p-2" required>
                      <ErrorMessage :message="registerForm.errors.email" />
                  </div>
                  <div>
                      <label class="block text-sm text-gray-800 font-semibold">Password</label>
                      <input v-model="registerForm.password" type="password" class="w-full border-blumilk-200 rounded-lg py-3 md:p-2" required>
                  </div>
                  <div>
                      <label class="block text-sm text-gray-800 font-semibold">Confirm password</label>
                      <input v-model="registerForm.password_confirmation" type="password" class="w-full border-blumilk-200 rounded-lg py-3 md:p-2" required>
                      <ErrorMessage :message="registerForm.errors.password" />
                  </div>
                  <div class="flex w-full md:w-fit">
                      <button type="submit" class="px-4 w-full font-semibold py-4 md:py-2 text-white bg-blumilk-500 rounded-lg hover:bg-blumilk-600">Sign up</button>
                  </div>
              </form>
                  <button :disabled="registerForm.processing"  class="mt-6 text-xs font-light" @click="toggleAuthOption">Already have an account? <span class="font-normal">Log in</span></button>
              </div>
          </div>
      </div>


    <Dialog v-if="mobileMenuOpen" as="div" class="z-30 lg:hidden" :open="mobileMenuOpen" @close="mobileMenuOpen = false">
      <div class="fixed inset-0 z-30 " />
      <DialogPanel class="fixed inset-y-0 right-0 z-30 w-full overflow-y-auto border-b-2 bg-white px-6 py-3 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
        <div class="flex items-center justify-between sm:justify-end">
          <img class="h-10 sm:hidden" src="@/assets/scooter.png" alt="escooter logo">
          <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = false">
            <span class="sr-only">Close menu</span>
            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
          </button>
        </div>
        <div class="mt-6 flow-root">
          <div class="-my-6 divide-y divide-gray-500/10">
            <div class="space-y-2 py-6">
              <a v-for="item in navigation" :key="item.name" :href="item.href" class=" -mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-blumilk-25">{{ item.name }}</a>
            </div>
            <div class="py-6">
              <a href="#" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold  leading-7 text-gray-900 hover:bg-blumilk-25">Log in</a>
            </div>
          </div>
        </div>
      </DialogPanel>
    </Dialog>
  </header>
</template>
