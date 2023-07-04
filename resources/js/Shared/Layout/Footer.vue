<script setup>
import { ref } from 'vue'

const getCurrentYear = () => {
  return ref(new Date().getFullYear())
}
</script>


<template>
  <footer>
    <div class="flex justify-center">
      <div class="fixed bottom-0 text-xs font-thin text-blumilk-600">
        {{ getCurrentYear() }} &copy; Blumilk
      </div>
    </div>
  </footer>
</template>
