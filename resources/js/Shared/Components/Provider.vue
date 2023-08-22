<script setup>
import {computed, onMounted, reactive, ref} from 'vue'
import {router, useForm} from '@inertiajs/vue3'
import {PencilIcon, TrashIcon, XMarkIcon} from '@heroicons/vue/24/outline'
import ErrorMessage from '@/Shared/Components/ErrorMessage.vue'
import {onClickOutside} from '@vueuse/core'
import SecondarySaveButton from '@/Shared/Components/SecondarySaveButton.vue'
import {useToast} from 'vue-toastification'
import {__} from '@/translate'

const props = defineProps({
  provider: Object,
  // cities: Object,
})

function destroyProvider(providerId) {
  router.delete(`/admin/providers/${providerId}`)
  toast.success(__('Provider deleted successfully.'))
}

function updateProvider(providerId) {
  updateProviderForm.patch(`/admin/providers/${providerId}`, {
    onSuccess: () => {
      toggleEditDialog()
      toast.success(__('Provider updated successfully.'))
    },
  })
}

const updateProviderForm = useForm({
  name: props.provider.name,
  url: props.provider.url,
  color: props.provider.color,
})

const commaInputError = ref('')

function preventCommaInput(event) {
  if (event.key === ',') {
    event.preventDefault()
    commaInputError.value = __('Use `.` instead of `,`')
  }
}

const isEditDialogOpened = ref(false)
const editDialog = ref(null)
onClickOutside(editDialog, () => (isEditDialogOpened.value = false))

function toggleEditDialog() {
  isEditDialogOpened.value = !isEditDialogOpened.value
  commaInputError.value = ''
  isProviderFormOpened.value = false
  //isCitiesFormOpened.value = false
}

// const selectedCityProviders = reactive([])

// onMounted(() => {
//   props.provider.cityProviders.forEach(provider => {
//     selectedCityProviders.push(provider.provider_name)
//   })
// })

// function toggleProviderSelection(provider) {
//   if (selectedCityProviders.includes(provider)) {
//     const index = selectedCityProviders.indexOf(provider)
//     selectedCityProviders.splice(index, 1)
//   } else {
//     selectedCityProviders.push(provider)
//   }
// }

// function updateCityProviders(cityId) {
//   router.patch(`/update-city-providers/${cityId}`, {
//     providerNames: selectedCityProviders,
//   }, {
//     onSuccess: () => {
//       toggleEditDialog()
//     },
//   })
// }

// const filteredSelectedCityProviders = computed(() => {
//   return props.providers.filter(provider => selectedCityProviders.includes(provider.name))
// })

function goToWebsite(url) {
  if (!url.startsWith("http://") && !url.startsWith("https://")) {
    url = "https://" + url;
  }
  window.open(url, "_blank");
}

const isProviderFormOpened = ref(false)

// function toggleCityForm() {
//   isProviderFormOpened.value = !isCityFormOpened.value
//
//   isAlternativeCityNameFormOpened.value = false
//   isProvidersFormOpened.value = false
// }

// const isAlternativeCityNameFormOpened = ref(false)

// function toggleAlternativeCityNameForm() {
//   isAlternativeCityNameFormOpened.value = !isAlternativeCityNameFormOpened.value
//   isCityFormOpened.value = false
//   isProvidersFormOpened.value = false
// }

function toggleProviderForm() {
  isProviderFormOpened.value = !isProviderFormOpened.value

  // isCityFormOpened.value = false
  // isAlternativeCityNameFormOpened.value = false
}

</script>

<template>
  <td class="relative py-4 pl-4 text-sm sm:pl-6 sm:pr-3">
    <div class="flex items-center font-medium text-gray-800">
      <div class="mb-2 mr-2 flex h-8 w-fit shrink-0 cursor-pointer items-center justify-center rounded-md  p-1" :style="{ 'background-color': provider.color }">
        <img class="w-8" :src="'/providers/' + provider.name.toLowerCase() + '.png'" alt="">
      </div>
      {{ provider.name }}
    </div>
  </td>
  <td class="hidden break-all py-3.5 text-sm text-gray-500 lg:table-cell">
    <p class="cursor-pointer break-all rounded hover:bg-blumilk-25" @click="goToWebsite(provider.url)">
      {{ provider.url }}
    </p>
  </td>
  <td class="hidden break-all py-3.5 text-sm text-gray-500 lg:table-cell">
    {{ provider.color }}
  </td>

  <!--  <td class="py-3.5 text-sm text-gray-500 lg:table-cell">-->
  <!--    <div class="flex lg:hidden">-->
  <!--      <div v-if="selectedCityProviders.length > 0" class="m-1 flex h-5 w-fit items-center justify-center rounded border border-zinc-300 bg-zinc-300 p-1">-->
  <!--        <div class="flex h-5 w-5 items-center justify-center text-xs text-gray-500">-->
  <!--          {{ selectedCityProviders.length }}-->
  <!--        </div>-->
  <!--      </div>-->
  <!--      <div v-else class="m-1 flex h-5 w-fit items-center justify-center p-1">-->
  <!--        <div class="flex h-5 w-5 items-center justify-center text-xs text-gray-500">-->
  <!--          - -->
  <!--        </div>-->
  <!--      </div>-->
  <!--    </div>-->
  <!--    <div class="hidden items-center lg:flex">-->
  <!--      <div class="items-top flex h-1/2 flex-wrap items-center">-->
  <!--        <div-->
  <!--            v-for="provider in filteredSelectedCityProviders.slice(0, 4)"-->
  <!--            :key="provider.name"-->
  <!--            :style="{'background-color': selectedCityProviders.includes(provider.name) ? provider.color : ''}"-->
  <!--            :class="selectedCityProviders.includes(provider.name) ? 'border-zinc-600 drop-shadow-lg' : 'hidden'"-->
  <!--            class="m-1 flex h-5 w-fit items-center justify-center rounded  p-1 "-->
  <!--        >-->
  <!--          <img class="w-5" :src="'/providers/' + provider.name.toLowerCase() + '.png'" alt="">-->
  <!--        </div>-->

  <!--        <div-->
  <!--            v-if="selectedCityProviders.length > 4 "-->
  <!--            class="m-1 flex h-5 w-fit items-center justify-center rounded border border-zinc-300 bg-zinc-300 p-1"-->
  <!--        >-->
  <!--          <div class="flex h-5 w-5 items-center justify-center text-xs text-gray-500">-->
  <!--            +{{ selectedCityProviders.length - 4 }}-->
  <!--          </div>-->
  <!--        </div>-->
  <!--        <div-->
  <!--            v-else-if="selectedCityProviders.length === 0"-->
  <!--            class="m-1 flex h-5 w-fit items-center justify-center p-1"-->
  <!--        >-->
  <!--          <div class="flex h-5 w-5 items-center justify-center text-xs text-gray-500">-->
  <!--            - -->
  <!--          </div>-->
  <!--        </div>-->
  <!--      </div>-->
  <!--    </div>-->
  <!--  </td>-->

  <td class="relative table-cell justify-end border-t text-right text-xs font-medium sm:pl-3 md:pr-2">
    <span class="flex flex-wrap">
      <button
          class="mx-0.5 mb-1 flex w-fit shrink-0 items-center rounded py-1 pr-2 text-blumilk-500 hover:bg-blumilk-25"
          @click="toggleEditDialog"
      >
        <PencilIcon class="h-5 w-8 text-blumilk-500"/>
        {{ __('Edit') }}
      </button>

      <button class="mx-0.5 mb-1 flex w-fit shrink-0 items-center rounded py-1 pr-2 text-rose-500 hover:bg-rose-100"
              @click="destroyProvider(provider.id)"
      >
        <TrashIcon class="h-5 w-8 text-rose-500"/>
        {{ __('Delete') }}
      </button>
    </span>
  </td>

  <div v-if="isEditDialogOpened" class="flex flex-col overflow-y-auto">
    <div class="fixed inset-0 z-10 flex items-center overflow-y-auto bg-black/50">
      <div ref="editDialog" class="mx-auto w-11/12 rounded-lg bg-white pb-6 sm:w-5/6 md:w-3/4 lg:w-1/2 xl:w-1/3">
        <div class="flex w-full justify-end">
          <button class="px-4 pt-4" @click="toggleEditDialog">
            <XMarkIcon class="h-6 w-6"/>
          </button>
        </div>

        <button :class="isProviderFormOpened ? 'bg-blumilk-50' : ''"
                class="mb-3 ml-6 rounded-lg bg-blumilk-25 px-3 py-1 text-sm font-bold text-gray-800 hover:bg-blumilk-50"
                @click="toggleProvidersForm">
          {{ __('Update provider') }}
        </button>
        <form v-if="isProviderFormOpened" class="flex flex-col rounded px-6 text-xs font-bold text-gray-600"
              @submit.prevent="updateProvider(provider.id)"
        >
          <label class="mb-1 mt-4">{{ __('Name') }}</label>
          <input v-model="updateProviderForm.name"
                 class="rounded border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3"
                 type="text"
                 required
          >
          <ErrorMessage :message="updateProviderForm.errors.name"/>
          <label class="mb-1 mt-4">{{ __('Url') }}</label>
          <input v-model="updateProviderForm.url"
                 class="rounded border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3"
                 type="text"
                 required @keydown="preventCommaInput"
          >
          <ErrorMessage :message="updateProviderForm.errors.url"/>
          <label class="mb-1 mt-4">{{ __('Color') }}</label>
          <input v-model="updateProviderForm.color"
                 class="rounded border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3"
                 type="text"
                 required @keydown="preventCommaInput"
          >
          <ErrorMessage :message="updateProviderForm.errors.color"/>
          <small class="text-rose-600">{{ commaInputError }}</small>

          <div class="flex w-full justify-end">
            <SecondarySaveButton>
              {{ __('Save') }}
            </SecondarySaveButton>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
