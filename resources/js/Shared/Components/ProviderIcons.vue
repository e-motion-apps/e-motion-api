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
// get 
document.addEventListener('click', function (event) {
  if (!event.target.closest('.provider-buttons') && !event.target.closest('.provider-icon')) {
    document.querySelectorAll('.provider-buttons').forEach(element => {
      setTimeout(() => {
        element.classList.remove('show')
      }, 150)
    })
  }

  if (event.target.closest('.provider-icon')) {
    const providerButtons = event.target.closest('.provider-icon').querySelector('.provider-buttons')
    providerButtons.classList.add('show')
    document.querySelectorAll('.provider-icon').forEach(element => {
      if (element !== event.target.closest('.provider-icon')) {
        const otherProviderButtons = element.querySelector('.provider-buttons')
        setTimeout(() => {
          otherProviderButtons.classList.remove('show')
        }, 150)
      }
    })
  }
})

</script>

<template>
  <div class="relative z-0 mb-2 mr-3 mt-4 flex w-fit flex-row-reverse flex-wrap items-center justify-end sm:mt-0 sm:justify-start">
    <div v-for="cityProvider in item.cityProviders" :key="cityProvider.provider_name" class="w-[72px]">
      <div :style="{ 'background-color': getProviderColor(cityProvider.provider_name) }"
           class="provider-icon relative z-0 m-3 mr-5 flex h-7 w-fit scale-150 items-center justify-center rounded-md p-1 lg:h-8" onclick=""
      >
        <div v-if="item.web_url||item.android_url||item.ios_url" class="provider-buttons">
          <a v-if="item.web_url" :href="item.web_url" target="_blank" class="flex h-7 w-24 flex-row place-items-center justify-items-center rounded bg-black">
            <img loading="lazy" class="mx-1 w-4" src="/icons/globe.svg" alt="">
            <p class="provider-button text-sm font-semibold text-white">Web</p>
          </a>
          <a v-if="item.android_url" :href="item.android_url" target="_blank" class="flex h-7 w-24 flex-row place-items-center justify-items-center rounded bg-black">
            <img loading="lazy" class="mx-1 w-4" src="/icons/android.svg" alt="">
            <p :href="item.android_url" target="_blank" class="provider-button text-sm font-semibold text-white hover:underline">
              Android
            </p>
          </a>
          <a v-if="item.ios_url" :href="item.ios_url" target="_blank" class="flex h-7 w-24 flex-row place-items-center justify-items-center rounded bg-black ">
            <img loading="lazy" class="mx-1 w-4" src="/icons/apple.svg" alt="">
            <p :href="item.ios_url" target="_blank" class="provider-button w-24 text-sm font-semibold text-white hover:underline">
              AppStore
            </p>
          </a>
        </div>
        <img loading="lazy" class="w-7 lg:w-8" :src="'/providers/' + cityProvider.provider_name.toLowerCase() + '.png'" alt="">
      </div>
    </div>
  </div>
</template>
