<script setup>
import Nav from '@/Shared/Layout/Nav.vue'
import Map from '@/Shared/Layout/Map.vue'
import { breakpointsTailwind, useBreakpoints } from '@vueuse/core'
import { computed, onUnmounted, ref, reactive } from 'vue'
import { MapIcon, XMarkIcon, StarIcon, PaperAirplaneIcon, ArrowDownIcon } from '@heroicons/vue/24/outline'
import { useFilterStore } from '@/Shared/Stores/FilterStore'
import FavoriteButton from '@/Shared/Components/FavoriteButton.vue'
import ProviderIcons from '@/Shared/Components/ProviderIcons.vue'
import { __ } from '@/translate'
import { useForm, usePage } from '@inertiajs/vue3'
import ErrorMessage from '@/Shared/Components/ErrorMessage.vue'
import { useToast } from 'vue-toastification'
import Pagination from '@/Shared/Components/Pagination.vue'
import InfoPopup from '@/Shared/Components/InfoPopup.vue'
import Opinion from '@/Shared/Components/Opinion.vue'
import axios from 'axios'


const toast = useToast()
const page = usePage()
const isAuth = computed(() => page.props.auth.isAuth)
const regulationsOpen = ref(false)
const rules = reactive({ pl: '<i class="text-gray-400">ładowanie informacji o zasadach, proszę czekać...</i>', en: '<i class="text-gray-400">loading info about rules, please wait...</i>' })

fetchRegulations()
const props = defineProps({
  city: Object,
  providers: Object,
  cityOpinions: Object,
})

const currentLocale = ref(computed(() => page.props.locale))
const currentRules = ref(computed(()=>rules[currentLocale.value]))

const breakpoints = useBreakpoints(breakpointsTailwind)
const isMobile = ref(breakpoints.smaller('lg'))
const isDesktop = ref(breakpoints.greaterOrEqual('lg'))

const shouldShowMap = ref(false)

function switchMap() {
  shouldShowMap.value = !shouldShowMap.value
}

const buttonIcon = computed(() => {
  return shouldShowMap.value ? XMarkIcon : MapIcon
})

const filterStore = useFilterStore()

onUnmounted(() => {
  filterStore.changeSelectedCity(null)
})

const opinionForm = useForm({
  rating: 0,
  content: '',
  city_id: props.city.id,
})

const maxRating = 5

function setRating(starIndex) {
  opinionForm.rating = starIndex
}

function toggleRegulations() {
  regulationsOpen.value = !regulationsOpen.value

}

function fetchRegulations() {
  axios.get(`/api/rules/${props.city.country.name}/${props.city.name}`)
    .then(response => {
      rules.pl= response.data.rulesPL
      rules.en = response.data.rulesEN
    })
    .catch(() => {
      toast.error(__('There was an error fetching rules.'))
    })
}

const emptyRatingError = ref('')

function createOpinion() {
  if (opinionForm.rating === 0) {
    emptyRatingError.value = __('Please, rate that city')
  } else {
    opinionForm.post('/opinions', {
      onSuccess: () => {
        opinionForm.reset()
        toast.success(__('Opinion added successfully.'))
        emptyRatingError.value = ''
      },
      onError: () => {
        toast.error(__('There was an error adding your opinion.'))
        emptyRatingError.value = ''
      },
    })
  }

}

</script>

<template>
  <div class="flex h-screen flex-col">
    <Nav ref="nav" class="z-30" />

    <div class="mt-16 flex grow flex-col lg:flex-row">
      <div v-if="isDesktop || !shouldShowMap" class="relative grow lg:w-1/2">
        <div class="mx-auto mt-4 flex w-11/12 flex-col sm:mt-12">
          <div class="flex items-end justify-between md:items-center">
            <h1 class="flex text-4xl font-bold md:text-5xl">
              {{ city.name }}
            </h1>
            <div class="hover:drop-shadow">
              <FavoriteButton v-if="isAuth" :cityid="city.id" :grow-up="true" class="ml-3 flex hover:drop-shadow" />
              <InfoPopup v-else class="flex rounded-full hover:drop-shadow" />
            </div>
          </div>

          <div class="mt-3 flex items-center">
            <i class="flat flag large ml-1" :class="city.country.iso" />
            <h2 class="ml-2 text-xl font-medium text-blumilk-500">
              {{ city.country.name }}
            </h2>
          </div>
          <h2 class="ml-1 mt-1 text-sm text-gray-400 ">
            {{ city.latitude }}, {{ city.longitude }}
          </h2>
          <ProviderIcons class="pt-4" :item="city" :providers="props.providers" />
          <div class="regulations relative overflow-hidden rounded border-[1px] border-solid border-gray-200 px-3">
            <div class="my-3 flex cursor-pointer items-center text-2xl font-bold text-gray-700" @click="toggleRegulations()">
              {{ __('Rules') }} <ArrowDownIcon :class="regulationsOpen ? 'rotated' : ''" class="absolute right-3 inline-block h-6 w-6 transition-all" />
            </div>
            <div :class="regulationsOpen?'show':''" class="overflow-scroll transition" v-html="currentRules" />
          </div>
          <form v-if="isAuth" class="mt-8 flex flex-col" @submit.prevent="createOpinion">
            <p class="mb-2 text-xs font-medium text-gray-700">
              {{ __('Add opinion') }}
            </p>
            <div class="mb-2 flex items-center space-x-1">
              <StarIcon
                v-for="index in maxRating"
                :key="index"
                class="h-6 w-6 cursor-pointer text-yellow-400"
                :class="{ 'fill-yellow-400': index <= opinionForm.rating }"
                @click="setRating(index)"
              />
            </div>
            <textarea v-model.trim="opinionForm.content" required class="h-32 w-full rounded-lg border border-gray-300" @keydown.enter.prevent="createOpinion" />

            <div class="mt-1 flex flex-col">
              <ErrorMessage :message="emptyRatingError" />
              <ErrorMessage :message="opinionForm.errors.rating" />
              <ErrorMessage :message="opinionForm.errors.content" />
              <ErrorMessage :message="opinionForm.errors.city_id" />
            </div>

            <button class="mt-2 flex w-full items-center justify-center rounded-lg bg-emerald-500 p-3 text-xs font-medium text-white hover:bg-emerald-600 sm:w-fit sm:px-4 sm:py-2">
              {{ __('Send') }}
              <PaperAirplaneIcon class="ml-2 h-4 w-4" />
            </button>
          </form>


          <div v-if="props.cityOpinions.data.length" class="mt-6">
            <p class="mb-2 text-xs font-medium text-gray-700">
              {{ __(`Users' opinions`) }}
            </p>
            <div v-for="opinion in props.cityOpinions.data" :key="opinion.id" class="mb-3 flex flex-col rounded-lg border border-gray-300 p-2">
              <Opinion :opinion="opinion" :city-id="props.city.id" />
            </div>
          </div>

          <Pagination class="mb-6" :meta="props.cityOpinions.meta" :links="props.cityOpinions.links" />
        </div>
      </div>

      <div v-if="isDesktop || shouldShowMap" class="h-full lg:w-1/2">
        <Map :cities="[props.city]" :is-city-page="true" class="z-10" />
      </div>

      <div v-if="isMobile" class="flex justify-center">
        <button class="hover:blumilk-600 fixed bottom-5 z-20 flex items-center justify-center rounded-full bg-blumilk-500 px-2 py-1.5 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                @click="switchMap"
        >
          <component :is="buttonIcon" class="h-6 w-6" />
        </button>
      </div>
    </div>
  </div>
</template>

