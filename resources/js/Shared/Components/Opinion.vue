<script setup>
import { PaperAirplaneIcon, PencilIcon, StarIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { router, useForm, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import { onClickOutside } from '@vueuse/core'
import { __ } from '@/translate'
import DeleteModal from './DeleteModal.vue'
import { useToast } from 'vue-toastification'
import ErrorMessage from './ErrorMessage.vue'

const isAdmin = computed(() => page.props.auth.isAdmin)
const toast = useToast()
const page = usePage()
const user = computed(() => page.props.auth.user)

const props = defineProps({
  opinion: Object,
  cityId: Number,
})

const updateOpinionForm = useForm({
  rating: props.opinion.rating,
  content: props.opinion.content,
  city_id: props.cityId,
})

function updateOpinion(opinionId) {
  if (updateOpinionForm.rating === 0) {
    emptyRatingError.value = __('Please, rate that city')
  } else {
    updateOpinionForm.patch(`/opinions/${opinionId}`, {
      onSuccess: () => {
        toast.success(__('Opinion edited successfully.'))
        toggleUpdateOpinionDialog()
      },
      onError: () => {
        toast.error(__('There was an error editing your opinion.'))
      },
    })
  }
}

const isUpdateOpinionDialogOpened = ref(false)

const updateOpinionDialog = ref(null)
onClickOutside(updateOpinionDialog, () => (isUpdateOpinionDialogOpened.value = false))

function toggleUpdateOpinionDialog() {
  isUpdateOpinionDialogOpened.value = !isUpdateOpinionDialogOpened.value
}

function deleteOpinion(opinionId) {
  router.delete(`/opinions/${opinionId}`, {
    onSuccess: () => {
      toast.success(__('Opinion removed successfully!'))
    },
    onError: () => {
      toast.error(__('There was an error removing your opinion.'))
    },
    preserveScroll: true,
  })
}

const isDeleteOpinionDialogOpened = ref(false)

function toggleDeleteOpinionDialog() {
  isDeleteOpinionDialogOpened.value = !isDeleteOpinionDialogOpened.value
}

const dateOptions = {
  year: 'numeric',
  month: 'numeric',
  day: 'numeric',
  hour: '2-digit',
  minute: '2-digit',
}


const maxRating = 5

function setRating(starIndex) {
  updateOpinionForm.rating = starIndex
}

const emptyRatingError = ref('')

</script>

<template>
  <div class="flex items-center">
    <p class="mr-1 text-xs font-medium text-blumilk-500">
      {{ opinion.user.name }}
    </p>
    <StarIcon v-for="index in maxRating"
              :key="index"
              :class="{ 'fill-yellow-400': index <= props.opinion.rating }" class="h-4 w-4 text-yellow-400"
    />
  </div>
  <p class="mr-1 text-xs font-light text-blumilk-500">
    {{ new Date(opinion.updated_at).toLocaleString("pl-PL", dateOptions) }}
  </p>

  <div class="mt-1 text-sm text-gray-700">
    {{ opinion.content }}
  </div>

  <div v-if="user.id === props.opinion.user.id || isAdmin" class="mt-1 flex justify-end">
    <button v-if="user.id === props.opinion.user.id" class="flex px-1 hover:drop-shadow" @click="toggleUpdateOpinionDialog">
      <PencilIcon class="h-5 w-5 text-blumilk-500 hover:drop-shadow sm:h-4 sm:w-4" />
    </button>
    <button class="flex px-1 hover:drop-shadow" @click="toggleDeleteOpinionDialog">
      <TrashIcon class="ml-1 h-5 w-5 text-blumilk-500 hover:drop-shadow sm:h-4 sm:w-4" />
    </button>
  </div>

  <div v-if="isUpdateOpinionDialogOpened" class="fixed inset-0 z-50 flex items-center bg-black/50">
    <div ref="updateOpinionDialog" class="mx-auto w-11/12 rounded-lg bg-white shadow-lg sm:w-3/4 md:w-2/3 lg:w-1/2 xl:w-1/3">
      <div class="flex flex-col">
        <div class="flex justify-end">
          <button class="w-fit px-4 pt-4" @click.stop="toggleUpdateOpinionDialog">
            <XMarkIcon class="h-6 w-6" />
          </button>
        </div>


        <form class="flex flex-col px-5 pb-4" @submit.prevent="updateOpinion(opinion.id)">
          <p class="mb-2 text-xs font-medium text-gray-700">
            {{ __('Edit opinion') }}
          </p>
          <div class="mb-2 flex items-center space-x-1">
            <StarIcon
              v-for="index in maxRating"
              :key="index"
              class="h-6 w-6 cursor-pointer text-yellow-400"
              :class="{ 'fill-yellow-400': index <= updateOpinionForm.rating }"
              @click="setRating(index)"
            />
          </div>
          <textarea v-model.trim="updateOpinionForm.content" required class="h-32 w-full rounded-lg border border-gray-300" @keydown.enter.prevent="updateOpinion(opinion.id)" />

          <div class="mt-1 flex flex-col">
            <ErrorMessage :message="emptyRatingError" />
            <ErrorMessage :message="updateOpinionForm.errors.rating" />
            <ErrorMessage :message="updateOpinionForm.errors.content" />
            <ErrorMessage :message="updateOpinionForm.errors.city_id" />
          </div>

          <button :disabled="!updateOpinionForm.isDirty" :class="updateOpinionForm.isDirty ? 'bg-emerald-500 hover:bg-emerald-600 ' : 'bg-gray-400 hover:bg-gray-500'" class="mt-2 flex w-full items-center justify-center rounded-lg p-3 text-xs font-medium text-white sm:w-fit sm:px-4 sm:py-2">
            {{ __('Send') }}
            <PaperAirplaneIcon class="ml-2 h-4 w-4" />
          </button>
        </form>
      </div>
    </div>
  </div>

  <DeleteModal v-if="isDeleteOpinionDialogOpened" :name="''" :type="'That opinion'" @close="toggleDeleteOpinionDialog" @delete="deleteOpinion(opinion.id)" />
</template>
