<template>

	  
	
	
	  <DataTable :dataTable="minhasCompras" :Filtros="Filtros" :Registros="Registros"  />
	</template>
	
	<script setup>
	import { Link } from '@inertiajs/inertia-vue3'
	import DataTable from "../Components/Tables/minhasComprasTable.vue";

	import { defineProps } from "vue";


const props = defineProps({
    minhasCompras: Object,	
    Filtros: Object,
    Registros: Object,
});

const logCarregamento = () => {
    console.log(props.minhasCompras);
};

logCarregamento();
	</script>
	
	<style>
	</style>