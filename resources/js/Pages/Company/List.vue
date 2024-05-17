<template>
  <DataTable :dataTable="$page.props?.companies" :Registros="Registros" :Filtros="Filtros"  />
</template>

<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import DataTable from "../../Components/Tables/CompanyTables.vue";
	import { defineProps } from "vue";

defineProps({
		Registros: Object, Filtros: Object,
		Diarias: Object,
	});
</script>

<style>
</style>