<script setup>
const data = [
  {
    country: 'United States',
    city: 'New York City',
    icon: 'https://upload.wikimedia.org/wikipedia/commons/a/a4/Flag_of_the_United_States.svg',
    provider: 'Scoot',
  },
  {
    country: 'United Kingdom',
    city: 'London',
    icon: 'https://upload.wikimedia.org/wikipedia/en/a/ae/Flag_of_the_United_Kingdom.svg',
    provider: 'Lime',
  },
  {
    country: 'Germany',
    city: 'Berlin',
    icon: 'https://upload.wikimedia.org/wikipedia/en/b/ba/Flag_of_Germany.svg',
    provider: 'Voi',
  },
  {
    country: 'France',
    city: 'Paris',
    icon: 'https://upload.wikimedia.org/wikipedia/en/c/c3/Flag_of_France.svg',
    provider: 'Tier',
  },
  {
    country: 'Netherlands',
    city: 'Amsterdam',
    icon: 'https://upload.wikimedia.org/wikipedia/commons/2/20/Flag_of_the_Netherlands.svg',
    provider: 'Dott',
  },
  {
    country: 'Australia',
    city: 'Sydney',
    icon: 'https://upload.wikimedia.org/wikipedia/commons/8/88/Flag_of_Australia_%28converted%29.svg',
    provider: 'Neuron',
  },
]


</script>
  
<template>
  <div class="text-md h-3/5 overflow-y-auto lg:h-4/6 lg:text-lg"> 
    <ul role="list" class="divide-y divide-gray-300">
      <li v-for="dataItem in data" :key="dataItem.city" class="flex justify-between gap-x-6 py-5">
        <div class="flex flex-auto items-center">
          <img class="h-14 w-14 rounded-full bg-gray-50" :src="dataItem.icon" :alt="dataItem.provider">
          <div class="ml-4 flex flex-row items-center">
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

  
  
  
  
  


