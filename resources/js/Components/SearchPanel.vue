<script setup>
import { ref } from 'vue'
import ComboboxField from './ComboboxField.vue'


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
  { id: 4, name: 'Zip' },
  
]

const displayItem = (item) => item?.name ?? ''

const selectedValue = ref(null)

const handleComboboxUpdate = (query, items) => {
  selectedValue.value = items.find((item) => item.name.toLowerCase() === query.toLowerCase())
  console.log('Selected item:', selectedValue.value)
}
</script>
  
<template>
  <div class="mx-auto flex h-full w-full">
    <div id="ComboWrapper" class="mx-auto w-8/12 flex-col justify-center">
      <ComboboxField label="Select Country" :items="countries" :display-value="displayItem" @update="handleComboboxUpdate" />
      <ComboboxField label="Select Provider" :items="providers" :display-value="displayItem" @update="handleComboboxUpdate" />
    </div>
  </div>
</template>


