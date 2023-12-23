<script setup>
import { reactive } from 'vue'
const props = defineProps({
  item: Object,
  providers: Object,
  apps: Object,
})

function getProviderColor(providerName) {
  const provider = props.providers.find(provider => provider.name === providerName)

  return provider ? provider.color : ''
}

function getProviderURLs(providerName) {
  const provider = props.providers.find(provider => provider.name === providerName)
  urls.pname = provider.name
  urls.url = provider.url ? provider.url : null
  urls.android_url =  provider.android_url ? provider.android_url : null
  urls.ios_url = provider.ios_url ? provider.ios_url : null
  console.log(provider)
} 

if (window.location.pathname !== '/') {
  document.addEventListener('click', function (event) {
    const providerButtons = document.querySelector('.provider-buttons')

    if ((!event.target.closest('.provider-buttons') && !event.target.closest('.provider-icon'))) {
      if (document.querySelector('.provider-buttons')) {
        setTimeout(() => {
          document.querySelector('.provider-buttons').classList.remove('show')
          urls.hidden = true
        }, 150)
        urls.transparent = true
        console.log('clicked outside')
      }
    }

    if (event.target.closest('.provider-icon')) {
      getProviderURLs(event.target.closest('.provider-icon').getAttribute('pname'))

      if (urls.url||urls.android_url||urls.ios_url) {
        providerButtons.classList.add('show')
        urls.hidden = false
        urls.transparent = false
      }
      console.log('clicked on provider icon')
      console.log(urls) 
    }
  })
}
const urls = reactive({
  pname: null,
  url: null,
  android_url: null,
  ios_url: null,
  hidden: true,
  transparent: true,
})
</script>

<template>
  <div class="z-0 mb-2 mr-3 mt-4 flex w-fit flex-row-reverse flex-wrap items-center justify-end sm:mt-0 sm:justify-start">
    <div v-for="cityProvider in item.cityProviders" :key="cityProvider.provider_name" class="w-[72px]">
      <div :style="{ 'background-color': getProviderColor(cityProvider.provider_name) }"
           class="provider-icon z-0 m-3 mr-5 flex h-7 w-fit scale-150 items-center justify-center rounded-md p-1 lg:h-8" onclick="" :pname="cityProvider.provider_name"
      >
        <img loading="lazy" class="w-7 lg:w-8" :src="'/providers/' + cityProvider.provider_name.toLowerCase() + '.png'" alt="">
      </div>
    </div>
    <div class="provider-buttons border border-solid bg-white shadow-lg">
      <p class="text-center text-lg text-blumilk-500">
        {{ urls.pname }}
      </p>
      <a v-if="urls.url" :href="urls.url" target="_blank" class="flex h-11 w-36 flex-row place-items-center justify-items-center rounded bg-blumilk-400 shadow-inner">
        <img loading="lazy" class="mx-2 w-6" src="/icons/globe.svg" alt="">
        <p class="provider-text text-lg font-semibold text-white hover:underline">Web </p>
      </a>
      <a v-if="urls.android_url" :href="urls.android_url" target="_blank" class="flex h-11 w-36 flex-row place-items-center justify-items-center rounded bg-blumilk-400">
        <img loading="lazy" class="mx-2 w-6" src="/icons/android.svg" alt="">
        <p class="provider-text text-lg font-semibold text-white hover:underline">
          Android
        </p>
      </a>
      <a v-if="urls.ios_url" :href="urls.ios_url" target="_blank" class="flex h-11 w-36 flex-row place-items-center justify-items-center rounded bg-blumilk-400 ">
        <img loading="lazy" class="mx-2 w-6" src="/icons/apple.svg" alt="">
        <p class="provider-text text-lg font-semibold text-white hover:underline">
          AppStore
        </p>
      </a>
    </div>
    <div :style="{ opacity: urls.transparent?'0%':'60%' , visibility: urls.hidden?'hidden':'visible' }" class="decoration absolute left-1/2 top-1/2 h-screen w-screen -translate-x-1/2 -translate-y-1/2 bg-white   transition-all" />
  </div>
</template>
