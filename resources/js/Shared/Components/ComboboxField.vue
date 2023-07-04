<script setup>
import { computed, ref } from 'vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'
import { Combobox, ComboboxButton, ComboboxInput, ComboboxLabel, ComboboxOption, ComboboxOptions } from '@headlessui/vue'

const props = defineProps({
  label: { type: String, required: true },
  items: { type: Array, required: true },
  displayValue: { type: Function, default: (item) => item.name },
})

const emit = defineEmits(['update'])
const query = ref('')
const selectedItem = ref(null)

const filteredItems = computed(() =>
  query.value === ''
    ? props.items
    : props.items.filter((item) => {
      return props.displayValue(item).toLowerCase().includes(query.value.toLowerCase())
    }),
)

const updateQuery = (event) => {
  query.value = event.target.value
  emit('update', query.value)
}
</script>

<template>
  <Combobox v-model="selectedItem" as="div">
    <ComboboxLabel class="block text-sm font-medium leading-6 text-blumilk-700">
      {{ label }}
    </ComboboxLabel>
    <div class="relative mt-0.5">
      <ComboboxInput class="w-full rounded-md border-0 bg-white py-3 pl-3 pr-10 text-blumilk-700 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blumilk-500 sm:leading-6" :display-value="displayValue" @change="updateQuery" />
      <ComboboxButton class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
        <ChevronUpDownIcon class="h-5 w-5 text-blumilk-200" aria-hidden="true" />
      </ComboboxButton>

      <ComboboxOptions v-if="filteredItems.length > 0" class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base  shadow-lg ring-1 ring-black focus:outline-none">
        <ComboboxOption v-for="item in filteredItems" :key="item.id" v-slot="{ active, selected }" :value="item" as="template">
          <li :class="['relative cursor-pointer select-none py-2 pl-3 pr-9 text-lg hover:text-blumilk-500', active ? 'bg-blumilk-25' : 'text-blumilk-700']">
            <span :class="['block truncate', selected && 'text-blumilk-900']">
              {{ item.name }}
            </span>

            <span v-if="selected" :class="['absolute inset-y-0 right-0 flex items-center pr-4', active ? 'text-blumilk-300' : 'text-blumilk-500']">
              <CheckIcon class="h-5 w-5" aria-hidden="true" />
            </span>
          </li>
        </ComboboxOption>
      </ComboboxOptions>
    </div>
  </Combobox>
</template>
