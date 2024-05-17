<template>
	<Breadcrumb>
		  <nav class="py-3 rounded-md w-full -mt-6">
			<ol class="list-reset flex items-center text-sm">
			<li><Link :href="route('home')">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
				</svg>
			  </Link>
			  </li>
			  <li><span class="text-gray-500 mx-2">/</span></li>
			  <li class="text-gray-500">SMTP</li>
			</ol>
		  </nav>
		</Breadcrumb>  
	  
	
	
	  <DataTable :dataTable="SMTP" :Registros="Registros" :Filtros="Filtros" />
	</template>
	
	<script setup>
	import { Link } from '@inertiajs/inertia-vue3'
	import DataTable from "../../Components/Tables/SMTPTable.vue";

	import { defineProps } from "vue";
	
	defineProps({
		Registros: Object, Filtros: Object,
		SMTP: Object,
	});
	</script>
	
	<style>
	</style>