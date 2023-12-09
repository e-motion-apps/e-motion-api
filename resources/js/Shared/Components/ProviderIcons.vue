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
function getProviderURLs(providerName, appType) {
  ;
  const provider = props.providers.find(provider => provider.name === providerName)
  switch (appType) {
    case 'web':
      return provider.web_url ? provider.web_url : null
      break;
    case 'android':
      return provider.android_url ? provider.android_url : null
      break;
    case 'ios':
      return provider.ios_url ? provider.ios_url : null
      break;
  }
  return null
}
if (window.location.pathname !== '/') {
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
      if (providerButtons!==null) {
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
    }
  })
}
</script>
<template>
    <div class="relative z-0 mb-2 mr-3 mt-4 flex w-fit flex-row-reverse flex-wrap items-center justify-end sm:mt-0 sm:justify-start">
      <div v-for="cityProvider in item.cityProviders" :key="cityProvider.provider_name" class="w-[72px]">
        <div :style="{ 'background-color': getProviderColor(cityProvider.provider_name) }"
             class="provider-icon relative z-0 m-3 mr-5 flex h-7 w-fit scale-150 items-center justify-center rounded-md p-1 lg:h-8" onclick=""
        >
        <div v-if="getProviderURLs(cityProvider.provider_name, 'web') || getProviderURLs(cityProvider.provider_name, 'android') || getProviderURLs(cityProvider.provider_name, 'ios')">
          <div class="provider-buttons">
            <a v-if="getProviderURLs(cityProvider.provider_name, 'web')" :href="getProviderURLs(cityProvider.provider_name, 'web')" target="_blank" class="flex h-7 w-24 flex-row place-items-center justify-items-center rounded bg-black">
              <img loading="lazy" class="mx-1 w-4" src="/icons/globe.svg" alt="">
              <p class="provider-button text-sm font-semibold text-white">Web</p>
            </a>
            <a v-if="getProviderURLs(cityProvider.provider_name, 'android')" :href="getProviderURLs(cityProvider.provider_name, 'android')" target="_blank" class="flex h-7 w-24 flex-row place-items-center justify-items-center rounded bg-black">
              <img loading="lazy" class="mx-1 w-4" src="/icons/android.svg" alt="">
              <p class="provider-button text-sm font-semibold text-white hover:underline">
                Android
              </p>
            </a>
            <a v-if="getProviderURLs(cityProvider.provider_name, 'ios')" :href="getProviderURLs(cityProvider.provider_name, 'ios')" target="_blank" class="flex h-7 w-24 flex-row place-items-center justify-items-center rounded bg-black ">
              <img loading="lazy" class="mx-1 w-4" src="/icons/apple.svg" alt="">
              <p class="provider-button w-24 text-sm font-semibold text-white hover:underline">
                AppStore
              </p>
            </a>
          </div>
        </div>
        <img loading="lazy" class="w-7 lg:w-8" :src="'/providers/' + cityProvider.provider_name.toLowerCase() + '.png'" alt="">
      </div>
    </div>
  </div>
</template>
