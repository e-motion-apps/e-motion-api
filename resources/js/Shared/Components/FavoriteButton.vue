<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import { useToast } from 'vue-toastification'
import { HeartIcon as SolidHeartIcon } from '@heroicons/vue/24/solid'
import { HeartIcon as OutlineHeartIcon } from '@heroicons/vue/24/outline'
import { __ } from '@/translate'

const props = defineProps({
  cityid: {
    type: Number,
    required: true,
  },
})

const toast = useToast()
const result = ref(null)
const url = `/favorites/${props.cityid}`
const intersectionTarget = ref(null)

const fetchData = async () => {
  try {
    const response = await axios.get(url)
    result.value = response.data
  } catch (error) {
    toast.error(__('There was an error fetching data'))
  }
}

const toggleFavorite = async () => {
  try {
    if (result.value === null) {
      await fetchData()
    }

    await router.post('/favorites', {
      city_id: props.cityid,
    }, {
      preserveScroll: true,
    })
    result.value = !result.value

    if (result.value === false) {
      toast.info(__('City removed from favorites.'))
    } else if (result.value === true) {
      toast.success(__('City added to favorites.'))
    }
  } catch (error) {
    toast.error('There was an error!')
  }
}

const onIntersection = async (entries) => {
  entries.forEach(async (entry) => {
    if (entry.isIntersecting) {
      await fetchData()
    }
  })
}

onMounted(() => {
  if (intersectionTarget.value) {
    const observer = new IntersectionObserver(onIntersection, {
      root: null,
      threshold: 0.5,
    })
    observer.observe(intersectionTarget.value)
  }
})
</script>

<template>
  <div ref="intersectionTarget">
    <button @click="toggleFavorite">
      <component :is="result ? SolidHeartIcon : OutlineHeartIcon" v-if="result !== null"
                 class="h-6 w-6 text-rose-500"
      />
      <span v-else class="animate-pulse text-rose-200">
        <SolidHeartIcon class="h-6 w-6" />
      </span>
    </button>
  </div>
</template>
