<template>
  <span class="font-bold text-lg mb-4">{{ title }}</span>
</template>

<script setup>
import { defineProps } from "vue";

defineProps({
    title: String
})

</script>

<style>
</style>