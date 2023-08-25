<script setup>
import { ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import { PencilIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import ErrorMessage from '@/Shared/Components/ErrorMessage.vue'
import { onClickOutside } from '@vueuse/core'
import SecondarySaveButton from '@/Shared/Components/SecondarySaveButton.vue'
import { useToast } from 'vue-toastification'
import { __ } from '@/translate'
import DeleteModal from '@/Shared/Components/DeleteModal.vue'

const showDeleteModal = ref(false)
const toast = useToast()
const props = defineProps({
  country: Object,
})

const destroyCountry = (countryId) => {
  router.delete(`/admin/countries/${countryId}`)
  toast.success(__('Country deleted successfully'))
  showDeleteModal.value = false
}

function updateCountry(countryId) {
  updateCountryForm.patch(`/admin/countries/${countryId}`, {
    onSuccess: () => {
      toggleEditDialog()
      toast.success(__('Country updated successfully.'))
    },
  })
}

const updateCountryForm = useForm({
  name: props.country.name,
  alternativeName: props.country.alternativeName,
  latitude: props.country.latitude,
  longitude: props.country.longitude,
  iso: props.country.iso,
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
}


</script>

<template>
  <td class="relative py-4 pl-4 text-sm sm:pl-6 sm:pr-3">
    <div class="flex items-center font-medium text-gray-800">
      <i :class="country.iso" class="flat flag large mr-2 shrink-0" />
      <p class="cursor-pointer break-all rounded hover:bg-blumilk-25">
        {{ country.name }}
      </p>
    </div>
    <div class="mt-1 flex flex-col break-all text-gray-500 xl:hidden">
      <span>{{ country.latitude }},</span>
      <span>{{ country.longitude }}</span>
    </div>
  </td>
  <td class="table-cell break-all py-3.5 text-sm text-gray-500">
    {{ country.alternativeName }}
  </td>
  <td class="hidden break-all py-3.5 pl-1 text-sm text-gray-500 xl:table-cell">
    {{ country.latitude }}
  </td>

  <td class="hidden break-all py-3.5 pl-1 text-sm text-gray-500 xl:table-cell">
    {{ country.longitude }}
  </td>

  <td class="hidden break-all py-3.5 pl-1 text-sm text-gray-500 xl:table-cell">
    {{ country.iso }}
  </td>

  <td class="relative table-cell justify-end border-t text-right text-xs font-medium sm:pl-3 md:pr-2 xl:pr-0">
    <span class="flex flex-wrap">
      <button class="mx-0.5 mb-1 flex w-fit shrink-0 items-center rounded py-1 pr-2 text-blumilk-500 hover:bg-blumilk-25"
              @click="toggleEditDialog"
      >
        <PencilIcon class="h-5 w-8 text-blumilk-500" />
        {{ __('Edit') }}
      </button>

      <button class="mx-0.5 mb-1 flex w-fit shrink-0 items-center rounded py-1 pr-2 text-rose-500 hover:bg-rose-100"
              @click="showDeleteModal = true"
      >
        <TrashIcon class="h-5 w-8 text-rose-500" />
        {{ __('Delete') }}
      </button>

      <DeleteModal v-if="showDeleteModal" @close="showDeleteModal = false" @delete="destroyCountry(country.id)" />

    </span>
  </td>


  <div v-if="isEditDialogOpened" class="flex flex-col overflow-y-auto">
    <div class="fixed inset-0 z-10 flex items-center overflow-y-auto bg-black/50">
      <div ref="editDialog" class="mx-auto w-11/12 rounded-lg bg-white pb-6 sm:w-5/6 md:w-3/4 lg:w-1/2 xl:w-1/3">
        <div class="flex w-full justify-end">
          <button class="px-4 pt-4" @click="toggleEditDialog">
            <XMarkIcon class="h-6 w-6" />
          </button>
        </div>

        <form class="flex flex-col rounded px-6 text-xs font-bold text-gray-600"
              @submit.prevent="updateCountry(country.id)"
        >
          <label class="mb-1">{{ __('Name') }}</label>
          <input v-model="updateCountryForm.name"
                 class="rounded border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text"
                 required
          >
          <ErrorMessage :message="updateCountryForm.errors.name" />
          <label class="mb-1 mt-4">{{ __('Alternative name') }}</label>
          <input v-model="updateCountryForm.alternativeName"
                 class="rounded border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text"
          >
          <ErrorMessage :message="updateCountryForm.errors.alternativeName" />

          <label class="mb-1 mt-4">{{ __('Latitude') }}</label>
          <input v-model="updateCountryForm.latitude"
                 class="rounded border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text"
                 required @keydown="preventCommaInput"
          >
          <ErrorMessage :message="updateCountryForm.errors.latitude" />

          <label class="mb-1 mt-4">{{ __('Longitude') }}</label>
          <input v-model="updateCountryForm.longitude"
                 class="rounded border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text"
                 required @keydown="preventCommaInput"
          >
          <ErrorMessage :message="updateCountryForm.errors.longitude" />
          <label class="mb-1 mt-4">ISO</label>
          <input v-model="updateCountryForm.iso"
                 class="rounded border border-blumilk-100 p-4 text-sm font-semibold text-gray-800 shadow md:p-3" type="text"
                 required
          >
          <ErrorMessage :message="updateCountryForm.errors.iso" />
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
