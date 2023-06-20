<script setup>
import { ref } from 'vue'
import ComboboxField from './ComboboxField.vue'

const emit = defineEmits(['go-back'])

const goBack = () => {
    
  emit('go-back')
  
}

const countries = [
  
  
  { id: 1, name: 'Polska' },
  { id: 2, name: 'Szwecja' },
  { id: 3, name: 'Niemcy' },
  { id: 4, name: 'Hiszpania' },
  
]

const providers = [
  
  
  { id: 1, name: 'Bolt' },
  { id: 2, name: 'Lime' },
  { id: 3, name: 'Tier' },
  { id: 4, name: 'Hiszpania' },
  
]

const displayItem = (item) => item?.name ?? ''

const selectedValue = ref(null)

const handleComboboxUpdate = (query, items) => {
  selectedValue.value = items.find((item) => item.name.toLowerCase() === query.toLowerCase())
  console.log('Selected item:', selectedValue.value)
}



</script>
  
<template>
  <div class="mx-auto flex w-full">
    <div id="ComboWrapper" class="mx-auto w-8/12 flex-col justify-center">
      <ComboboxField label="Select Country" :items="countries" :display-value="displayItem" @update="handleComboboxUpdate" />
      <ComboboxField label="Select Provider" :items="providers" :display-value="displayItem" @update="handleComboboxUpdate" />
      <button type="button" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-lg font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 lg:text-4xl" @click="goBack">
        Go Back
      </button>
    </div>
  </div>
</template>


