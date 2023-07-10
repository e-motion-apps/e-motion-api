<script setup>

const props = defineProps({
 notes: Object,   
    
})

</script>

<template>

    <div v-for="note in props.notes ">
        {{ note }}
    </div>

</template>