<template>

	  
	
	
	  <DataTable :dataTable="ConfigCarros"  :Filtros="Filtros" :Registros="Registros"  />
	</template>
	
	<script setup>
	import { Link } from '@inertiajs/inertia-vue3'
	import DataTable from "../Components/Tables/ConfigVendaCarrosTable.vue";

	import { defineProps } from "vue";
	
	defineProps({
		ConfigCarros: Object,	
		Filtros: Object,
		Registros: Object,
	});
	
	</script>
	
	<style>
	</style>