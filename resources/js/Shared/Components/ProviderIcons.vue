<script setup>
const props = defineProps({
  item: Object,
  providers: Object,
})

function getProviderColor(providerName) {
  const provider = props.providers.find(provider => provider.name === providerName)

  return provider ? provider.color : ''
}

</script>

<template>
  <div class="mb-2 mr-3 mt-4 flex w-fit flex-row-reverse flex-wrap items-center justify-end sm:mt-0 sm:justify-start">
    <div v-for="cityProvider in item.cityProviders" :key="cityProvider.provider_name">
      <div :style="{ 'background-color': getProviderColor(cityProvider.provider_name) }"
           class="m-1 flex h-7 w-fit shrink-0 items-center justify-center rounded-md border border-zinc-300 p-1 transition-all hover:z-50 hover:scale-150 lg:h-8"
      >
        <img loading="lazy" class="w-7 lg:w-8" :src="'/providers/' + cityProvider.provider_name.toLowerCase() + '.png'" alt="">
      </div>
    </div>
  </div>
</template>
