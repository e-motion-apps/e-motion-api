<script setup>
const props = defineProps({
  item: Object,
  providers: Object,
  apps: Object,
})

function getProviderColor(providerName) {
  const provider = props.providers.find(provider => provider.name === providerName)

  return provider ? provider.color : ''
}

// function getProviderUrl(providerName) {
//   const provider = props.providers.find(provider => provider.name === providerName)
//
//   return provider ? provider.url : ''
// }

// make buttons visible if provider is clicked and hide if clicked again and hide if clicked outside, if clicked .provider-icon, remove hidden class from .provider-buttons and add hidden class to all other .provider-buttons
document.addEventListener('click', function(event) {
  if (!event.target.closest('.provider-buttons') && !event.target.closest('.provider-icon')) {
    document.querySelectorAll('.provider-buttons').forEach(element => {
      element.classList.add('hidden')
    })
  }

  if (event.target.closest('.provider-icon')) {
    event.target.closest('.provider-icon').querySelector('.provider-buttons').classList.remove('hidden')
    document.querySelectorAll('.provider-icon').forEach(element => {
      if (element !== event.target.closest('.provider-icon')) {
        element.querySelector('.provider-buttons').classList.add('hidden')
      }
    })
  }

})

</script>

<template>
  <div class="relative mb-2 mr-3 mt-4 flex w-fit flex-row-reverse flex-wrap items-center justify-end sm:mt-0 sm:justify-start">
    <div v-for="cityProvider in item.cityProviders" :key="cityProvider.provider_name" class="w-[72px]">
      <div :style="{ 'background-color': getProviderColor(cityProvider.provider_name) }"
           class="provider-icon m-3 mr-5 flex h-7 w-fit scale-150 items-center justify-center rounded-md p-1 lg:h-8 " onclick=""
      >
        <img loading="lazy" class="w-7 lg:w-8" :src="'/providers/' + cityProvider.provider_name.toLowerCase() + '.png'" alt="">
        <!-- modal for displaying download buttons -->
        <div class="provider-buttons absolute bottom-[120%]  left-1/2 h-10 w-10 -translate-x-1/2 rounded bg-slate-200 opacity-80">
          <!-- buttons visible for web, android or apple if properties are set -->
          <div v-if="item.web_url" class="flex flex-col items-center">
            <a :href="item.web_url" target="_blank" class="provider-button text-sm font-semibold text-white hover:underline">Web</a>
          </div>
          <div v-if="item.android_url" class="flex flex-col items-center">
            <a :href="item.android_url" target="_blank" class="provider-button text-sm font-semibold text-white hover:underline">Android</a>
          </div>
          <div v-if="item.ios_url" class="flex flex-col items-center">
            <a :href="item.ios_url" target="_blank" class="provider-button text-sm font-semibold text-white hover:underline">Apple</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
