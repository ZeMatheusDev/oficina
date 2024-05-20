<template>

	  
	
	
	  <DataTable :dataTable="meusConsertos" :Filtros="Filtros" :Registros="Registros"  />
	</template>
	
	<script setup>
	import { Link } from '@inertiajs/inertia-vue3'
	import DataTable from "../Components/Tables/meusConsertosTable.vue";

	import { defineProps } from "vue";


const props = defineProps({
    meusConsertos: Object,	
    Filtros: Object,
    Registros: Object,
});

const logCarregamento = () => {
    console.log(props.meusConsertos);
};

logCarregamento();
	</script>
	
	<style>
	</style>