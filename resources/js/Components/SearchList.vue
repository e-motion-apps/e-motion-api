<script setup>
const data = [
  {
    country: 'United States',
    city: 'New York City',
    iso: 'us',
    provider: 'Scoot',
  },
  {
    country: 'United Kingdom',
    city: 'London',
    iso: 'gb',
    provider: 'Lime',
  },
  {
    country: 'Germany',
    city: 'Berlin',
    iso: 'de',
    provider: 'Voi',
  },
  {
    country: 'France',
    city: 'Paris',
    iso: 'fr',
    provider: 'Tier',
  },
  {
    country: 'Netherlands',
    city: 'Amsterdam',
    iso: 'nl',
    provider: 'Dott',
  },
  {
    country: 'Australia',
    city: 'Sydney',
    iso: 'au',
    provider: 'Neuron',
  },
]



</script>
  
<template>
  <div class="text-md h-3/5 overflow-y-auto lg:h-4/6 lg:text-lg"> 
    <ul role="list" class="divide-y divide-gray-300">
      <li v-for="dataItem in data" :key="dataItem.city" class="flex justify-between gap-x-6 py-5">
        <div class="flex flex-auto items-center">
          <i :class="dataItem.iso" class="flat flag huge" />
          <div class="ml-4 flex flex-col justify-start">
            <p class="mr-2 font-bold text-gray-900">
              {{ dataItem.country }}   
            </p>
            <p class="font-semibold text-[#527ABA]">
              {{ dataItem.city }}
            </p>
          </div>
        </div>
        <a class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">View</a>
      </li>
    </ul>
  </div>
</template>

  
  
  
  
  


