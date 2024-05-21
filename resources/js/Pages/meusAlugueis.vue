<template>

	  
	
	
	  <DataTable :dataTable="meusAlugueis" :categoria="categoria" :Filtros="Filtros" :Registros="Registros"  />
	</template>
	
	<script setup>
	import { Link } from '@inertiajs/inertia-vue3'
	import DataTable from "../Components/Tables/meusAlugueisTable.vue";

	import { defineProps } from "vue";


const props = defineProps({
    meusAlugueis: Object,	
    Filtros: Object,
    Registros: Object,
	categoria: String,
	Users: Object,
});

const logCarregamento = () => {
    console.log(props.meusAlugueis);
};

logCarregamento();
	</script>
	
	<style>
	</style>