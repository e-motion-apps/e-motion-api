<script setup>
import PrimaryButton from '@/Shared/Components/PrimaryButton.vue'
import SecondaryButton from '@/Shared/Components/SecondaryButton.vue'
import { Link } from '@inertiajs/vue3'

const emit = defineEmits(['try-it-out'])
</script>

<template>
    <div class="bg-blumilk-25 h-full flex flex-col items-start justify-around pb-16 px-6 sm:px-12 lg:px-14">

        <div class="hidden md:flex md:h-1/2 lg:h-1/3">
            <img class="max-h-full" src="@/assets/undraw_scooter.svg" alt="Man riding an e-scooter">
        </div>



        <div>
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                Looking for an e&#8209scooter?
            </h1>
            <p class="pt-10 mx-auto leading-7 text-gray-600 text-sm sm:text-base lg:text-lg">
                There are plenty of e-scooter providers, so sometimes you can be confused about which app you should
                use during your trips.
                <br>
                <strong>This application can help you with planning your vacations or business trips.</strong>
            </p>
        </div>

        <div class="pt-3 flex items-center flex w-full justify-center sm:justify-between space-y-5 sm:space-y-0 flex-wrap">
            <PrimaryButton class="w-11/12 h-fit sm:w-5/12 py-5" @click="emit('try-it-out')">
                Try it out
            </PrimaryButton>

            <SecondaryButton class="w-11/12 h-fit sm:w-5/12 py-5">
                Create account
            </SecondaryButton>
        </div>


    </div>
</template>
