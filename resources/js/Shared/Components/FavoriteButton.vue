<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import { HeartIcon as SolidHeartIcon } from '@heroicons/vue/24/solid'
import { HeartIcon as OutlineHeartIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  cityid: {
    type: Number,
    required: true,
  },
})

const result = ref(null)
const url = `/favorites/${props.cityid}`
const intersectionTarget = ref(null)

const fetchData = async () => {
  try {
    const response = await axios.get(url)
    result.value = response.data
  } catch (error) {
    console.error(error)
  }
}

const toggleFavorite = async () => {
  try {
    if (result.value === null) {
      await fetchData()
    }

    await router.post('/favorites', {
      city_id: props.cityid,
    })
    result.value = !result.value
  } catch (error) {
    console.error(error)
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
                 class="h-6 w-6 text-red-500"
      />
      <span v-else>{{ __('Loading') }}...</span>
    </button>
  </div>
</template>
