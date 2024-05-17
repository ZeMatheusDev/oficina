<template>
  <div class="py-3 flex items-center justify-between border-t border-gray-200">
    <div class="flex-1 flex justify-between sm:hidden">
      <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Anterior </a>
      <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Próxima </a>
    </div>
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Mostrando
          <span class="font-medium">{{ links?.current_page }}</span>
          de
          <span class="font-medium">{{ links?.last_page }}</span>
          total
          <span class="font-medium">{{links?.data?.length}}</span>
          Registro(s)
        </p>
      </div>
      <div v-if="links?.links?.length > 2">
        <div class="flex flex-wrap -mb-1">
          <template v-for="(link, key) in links?.links">
            <div v-if="link.url === null" :key="key" class="mr-1 mb-1 px-4 py-3 text-xs leading-4 text-gray-400 border rounded-lg select-none cursor-not-allowed" v-html="link.label" />
            <Link preserve-scroll v-else :key="key + 1" class="mr-1 mb-1 px-4 py-3 text-xs leading-4 border rounded-lg" :class="{ 'text-whitebg-primary shadow-md': link.active }" :href="link.url" v-html="link.label" />
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { defineProps } from "vue";

defineProps({
  links: Object,
});
</script>

<style>
</style>