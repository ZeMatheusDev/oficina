<template>
	<AchievementCard :Registros="props.Registros"  />
	<p><br></p>  
<div class="bg-white px-4 shadow-md rounded-xl">
   <div class="flex max-md:flex-reverse items-center" >
	<button @click="toggleFilter = !toggleFilter" class="mt-4 p-3 shadow-md hover:bg-primary flex space-x-2 items-center cursor-pointer hover:text-white rounded-lg" title="Filtrar">
	  <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M10.9404 22.6501C10.4604 22.6501 9.99039 22.5301 9.55039 22.2901C8.67039 21.8001 8.14039 20.9101 8.14039 19.9101V14.6101C8.14039 14.1101 7.81039 13.3601 7.50039 12.9801L3.76039 9.0201C3.13039 8.3901 2.65039 7.3101 2.65039 6.5001V4.2001C2.65039 2.6001 3.86039 1.3501 5.40039 1.3501H18.6004C20.1204 1.3501 21.3504 2.5801 21.3504 4.1001V6.3001C21.3504 7.3501 20.7204 8.5401 20.1304 9.1301L15.8004 12.9601C15.3804 13.3101 15.0504 14.0801 15.0504 14.7001V19.0001C15.0504 19.8901 14.4904 20.9201 13.7904 21.3401L12.4104 22.2301C11.9604 22.5101 11.4504 22.6501 10.9404 22.6501ZM5.40039 2.8501C4.70039 2.8501 4.15039 3.4401 4.15039 4.2001V6.5001C4.15039 6.8701 4.45039 7.5901 4.83039 7.9701L8.64039 11.9801C9.15039 12.6101 9.65039 13.6601 9.65039 14.6001V19.9001C9.65039 20.5501 10.1004 20.8701 10.2904 20.9701C10.7104 21.2001 11.2204 21.2001 11.6104 20.9601L13.0004 20.0701C13.2804 19.9001 13.5604 19.3601 13.5604 19.0001V14.7001C13.5604 13.6301 14.0804 12.4501 14.8304 11.8201L19.1104 8.0301C19.4504 7.6901 19.8604 6.8801 19.8604 6.2901V4.1001C19.8604 3.4101 19.3004 2.8501 18.6104 2.8501H5.40039Z" fill="currentColor" />
		<path d="M5.99968 10.75C5.85968 10.75 5.72968 10.71 5.59968 10.64C5.24968 10.42 5.13968 9.95002 5.35968 9.60002L10.2897 1.70002C10.5097 1.35002 10.9697 1.24002 11.3197 1.46002C11.6697 1.68002 11.7797 2.14002 11.5597 2.49002L6.62968 10.39C6.48968 10.62 6.24968 10.75 5.99968 10.75Z" fill="currentColor" />
	  </svg>
	  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
		<path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
	  </svg>
	</button>
	<button @click="resetFilter" class="text-gray-500 mt-4 ml-4 p-3 shadow-md flex space-x-2 items-center cursor-pointer rounded-lg"  title="Limpar Filtros e Ordenações">
	  <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M10.9308 22.75C10.4508 22.75 9.97078 22.63 9.53078 22.38C8.64078 21.88 8.11078 20.99 8.11078 19.98V14.63C8.11078 14.12 7.78078 13.37 7.47078 12.98L3.67078 8.98999C3.04078 8.35999 2.55078 7.26001 2.55078 6.45001V4.13C2.55078 2.52 3.77078 1.26001 5.32078 1.26001H18.6508C20.1808 1.26001 21.4208 2.50003 21.4208 4.03003V6.25C21.4208 7.3 20.7908 8.50998 20.1908 9.09998C19.9008 9.38998 19.4208 9.38998 19.1308 9.09998C18.8408 8.80998 18.8408 8.32998 19.1308 8.03998C19.5008 7.66998 19.9208 6.84 19.9208 6.25V4.03003C19.9208 3.33003 19.3508 2.76001 18.6508 2.76001H5.33078C4.62078 2.76001 4.06078 3.36 4.06078 4.13V6.45001C4.06078 6.82001 4.36078 7.55 4.75078 7.94L8.60078 11.99C9.11078 12.62 9.61078 13.68 9.61078 14.63V19.98C9.61078 20.64 10.0608 20.96 10.2608 21.07C10.6908 21.31 11.2008 21.3 11.6008 21.06L13.0008 20.16C13.3508 19.94 13.8108 20.04 14.0408 20.39C14.2608 20.74 14.1608 21.2 13.8108 21.43L12.4008 22.34C11.9508 22.61 11.4408 22.75 10.9308 22.75Z" fill="currentColor" />
		<path d="M16.8789 20.25C14.9589 20.25 13.1489 19.23 12.1689 17.58C11.6489 16.74 11.3789 15.76 11.3789 14.75C11.3789 11.72 13.8489 9.25 16.8789 9.25C19.9089 9.25 22.3789 11.72 22.3789 14.75C22.3789 15.76 22.0989 16.74 21.5789 17.59C20.6089 19.23 18.8089 20.25 16.8789 20.25ZM16.8789 10.75C14.6689 10.75 12.8789 12.54 12.8789 14.75C12.8789 15.48 13.0789 16.19 13.4589 16.8C14.1789 18.01 15.4889 18.75 16.8789 18.75C18.2989 18.75 19.5789 18.03 20.2989 16.81C20.6789 16.19 20.8789 15.48 20.8789 14.75C20.8789 12.54 19.0889 10.75 16.8789 10.75Z" fill="currentColor" />
		<path d="M18.1501 16.7399C17.9601 16.7399 17.7701 16.6699 17.6201 16.5199L15.1101 14.0099C14.8201 13.7199 14.8201 13.2399 15.1101 12.9499C15.4001 12.6599 15.8801 12.6599 16.1701 12.9499L18.6801 15.4599C18.9701 15.7499 18.9701 16.2299 18.6801 16.5199C18.5401 16.6699 18.3501 16.7399 18.1501 16.7399Z" fill="currentColor" />
		<path d="M15.6205 16.77C15.4305 16.77 15.2405 16.7 15.0905 16.55C14.8005 16.26 14.8005 15.78 15.0905 15.49L17.6005 12.98C17.8905 12.69 18.3705 12.69 18.6605 12.98C18.9403 13.27 18.9403 13.7499 18.6605 14.0399L16.1403 16.55C16.0005 16.7 15.8105 16.77 15.6205 16.77Z" fill="currentColor" />
		<path d="M10.9308 22.75C10.4508 22.75 9.97078 22.63 9.54078 22.39C8.65078 21.89 8.12078 20.99 8.12078 19.98V14.63C8.12078 14.13 7.79078 13.37 7.47078 12.98L3.67078 8.98999C3.04078 8.33999 2.55078 7.25001 2.55078 6.45001V4.12C2.55078 2.51 3.77078 1.25 5.32078 1.25H18.6608C20.1908 1.25 21.4308 2.49002 21.4308 4.02002V6.23999C21.4308 7.28999 20.8108 8.47003 20.2108 9.09003L18.4108 10.68C18.2308 10.84 17.9708 10.9 17.7408 10.85C17.4708 10.78 17.1808 10.75 16.8808 10.75C14.6708 10.75 12.8808 12.54 12.8808 14.75C12.8808 15.48 13.0808 16.19 13.4608 16.81C13.7808 17.34 14.2108 17.78 14.7108 18.09C14.9308 18.23 15.0708 18.47 15.0708 18.73V19.07C15.0708 19.86 14.5908 20.97 13.7908 21.44L12.4108 22.33C11.9608 22.61 11.4408 22.75 10.9308 22.75ZM5.33078 2.75C4.62078 2.75 4.06078 3.35 4.06078 4.12V6.45001C4.06078 6.81001 4.36078 7.55001 4.76078 7.95001L8.61078 12C9.12078 12.63 9.63078 13.7 9.63078 14.64V19.99C9.63078 20.65 10.0908 20.98 10.2808 21.09C10.7108 21.33 11.2208 21.32 11.6108 21.08L13.0108 20.18C13.2808 20.02 13.5508 19.51 13.5708 19.15C13.0208 18.74 12.5408 18.21 12.1808 17.61C11.6608 16.76 11.3808 15.78 11.3808 14.78C11.3808 11.75 13.8508 9.28003 16.8808 9.28003C17.1608 9.28003 17.4408 9.30003 17.7008 9.34003L19.1808 8.03003C19.5208 7.68003 19.9408 6.85001 19.9408 6.26001V4.03998C19.9408 3.33998 19.3708 2.77002 18.6708 2.77002H5.33078V2.75Z" fill="currentColor" />
	  </svg>
	</button>	
	<Search class="p-field p-col-12 p-md-4 mt-6 max-md:w-full ml-auto">
		<span class="p-float-label p-input-icon-right w-full">
		  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute mt-[0.8rem] right-4 z-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
			<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
		  </svg>
		  <InputText @keyup="setParamsWithThrottle" v-model="searchBy" class="p-input-icon-right w-full" />
		  <label for="inputtext-left p-error">Procurar</label>
		</span>
	  </Search>
	</div>

	
	  <SlideUpDown v-model="toggleFilter" :duration="300">
		<div class="flex mb-4 max-md:flex-col-reverse items-center">
		  <Limit class="p-field p-col-12 p-md-4 mt-6 max-md:w-full max-md:mb-5 flex items-center space-x-3 text-xs text-gray-500">
			<span>Mostrar</span>
			<select name="" @change="setParams" v-model="recordValue" id="" class="rounded-md focus:ring-primary h-8 text-xs w-20">
			  <option value="10">10</option>
			  <option value="25">25</option>
			  <option value="50">50</option>
			  <option value="100">100</option>
			  <option value="500">500</option>
			</select>
			<span>registros</span>
		  </Limit>
		 
		</div>
		<hr class="my-4">
		<div class="text-sm text-gray-500 flex items-center">
			<div class="space-x-6 flex ml-4  grid grid-cols-6 gap-6 max-md:grid-cols-1">
			<span class="font-semibold">Colunas Visíveis:</span>
		 	 
<div class="flex items-center space-x-2">
				<Checkbox @change="toggleColumns" :binary="true" v-model="formColumns['columns']['tipo']" />
				<span> Tipo</span>
			  	</div>
<div class="flex items-center space-x-2">
				<Checkbox @change="toggleColumns" :binary="true" v-model="formColumns['columns']['modulo']" />
				<span> Módulo</span>
			  	</div>
<div class="flex items-center space-x-2">
				<Checkbox @change="toggleColumns" :binary="true" v-model="formColumns['columns']['acao']" />
				<span> Ação</span>
			  	</div>
<div class="flex items-center space-x-2">
				<Checkbox @change="toggleColumns" :binary="true" v-model="formColumns['columns']['created_at']" />
				<span> Data de Cadastro</span>
			  	</div>
		  </div>
		</div>
	  </SlideUpDown>
  
	  <div class="mt-4 flex flex-col max-md:px-2 py-1 rounded-lg shadow-sm">
		<div class="inline-block min-w-full py-2 align-middle">
		  <h2 class="text-xl font-semibold fontInter text-center py-5"><b>Logs de Usuário</b></h2>
		  <div class="overflow-hidden overflow-x-visible ring-1 ring-black ring-opacity-5 md:rounded-lg">
			<table class="min-w-full divide-y divide-gray-300">
			  <thead class=" mb-24">
				<tr class="text-gray-500 font-bold select-none" @click="setParams">
  
				
<th v-if="formColumns.columns.tipo" scope="col" class="px-4 text-sm cursor-pointer text-center border-r group px-3 py-3.5 text-center text-sm sm:pr-12" @click="orderBy = {column: 'tipo', sorting: sortTable(sortVal.tipo) ? sortVal.tipo = 1 : sortVal.tipo = 0}">
				<div class="flex">
				  <span class="group-hover:text-indigo-800">Usuário</span>
				  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-auto group-hover:text-indigo-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
					<path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
				  </svg>
				</div>
			 	</th>
<th v-if="formColumns.columns.modulo" scope="col" class="px-4 text-sm cursor-pointer text-center border-r group px-3 py-3.5 text-center text-sm sm:pr-12" @click="orderBy = {column: 'modulo', sorting: sortTable(sortVal.modulo) ? sortVal.modulo = 1 : sortVal.modulo = 0}">
				<div class="flex">
				  <span class="group-hover:text-indigo-800"> Módulo</span>
				  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-auto group-hover:text-indigo-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
					<path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
				  </svg>
				</div>
			 	</th>
<th v-if="formColumns.columns.acao" scope="col" class="px-4 text-sm cursor-pointer text-center border-r group px-3 py-3.5 text-center text-sm sm:pr-12" @click="orderBy = {column: 'acao', sorting: sortTable(sortVal.acao) ? sortVal.acao = 1 : sortVal.acao = 0}">
				<div class="flex">
				  <span class="group-hover:text-indigo-800"> Ação</span>
				  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-auto group-hover:text-indigo-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
					<path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
				  </svg>
				</div>
			 	</th>
<th v-if="formColumns.columns.created_at" scope="col" class="px-4 text-sm cursor-pointer text-center border-r group px-3 py-3.5 text-center text-sm sm:pr-12" @click="orderBy = {column: 'created_at', sorting: sortTable(sortVal.created_at) ? sortVal.created_at = 1 : sortVal.created_at = 0}">
				<div class="flex">
				  <span class="group-hover:text-indigo-800">Data de Cadastro</span>
				  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-auto group-hover:text-indigo-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
					<path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
				  </svg>
				</div>
			 	</th>

				
				</tr>
			  </thead>
			  <tbody class="divide-y divide-gray-200 bg-white">
				<tr v-for="(data, key) in dataTable?.data" :key="key" class="hover:bg-indigo-50/20" :class="{'bg-gray-50': (key % 2)}">
  
				 
<td v-if="formColumns?.columns?.tipo" class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
					<div class="flex items-center">
					  <div>
						<div class="font-medium text-gray-900">{{ data?.user }}</div>
					  </div>
					</div>
					 </td>
<td v-if="formColumns?.columns?.modulo" class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
					<div class="flex items-center">
					  <div>
						<div class="font-medium text-gray-900">{{ data?.modulo }}</div>
					  </div>
					</div>
					 </td>
<td v-if="formColumns?.columns?.acao" class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
					<div class="flex items-center">
					  <div>
						<div class="font-medium text-gray-900">{{ data?.acao }}</div>
					  </div>
					</div>
					 </td>
<td v-if="formColumns?.columns?.created_at" class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
					<div class="flex items-center">
					  <div>
						<div class="font-medium text-gray-900">{{ data?.data_final }}</div>
					  </div>
					</div>
					 </td>
				</tr>

			  </tbody>
			</table>
		  </div>
		</div>
	  </div>
<Pagination :links="dataTable" :orderBy="props.Filtros.orderBy"  :limit="props.Filtros.limit" :searchBy="searchBy" :byStatus="statusValue"/>
	</div>
  
  </template>
  
  
  
  <script setup>
  import Checkbox from "primevue/checkbox";
  import SlideUpDown from "vue3-slide-up-down";
  import Button from "primevue/button";
  import InputText from "primevue/inputtext";
  import Dropdown from "primevue/dropdown";
  import Pagination from "../Pagination.vue";
  import Actions from "./Actions.vue";
  import Delete from "../Modals/Delete.vue";
  import AchievementCard from "../../Layouts/CardsListagens.vue";
  import { Link } from "@inertiajs/inertia-vue3";
  import { ref, defineProps, watch } from "vue";
  import { Inertia } from "@inertiajs/inertia";
  import { useForm } from "@inertiajs/inertia-vue3";
  const _ = require("lodash");
  
  const props = defineProps({
	dataTable: Object,
	Registros: Object, Filtros: Object,
  });
  
  const showDeleteModal = ref([]);
  
  const searchBy = ref(getParams("searchBy") || "");
  
  const statusValue = ref(getParams("byStatus"));
  
  const status = [
	{ label: "Ativo", code: '0' },
	{ label: "Inativo", code: '1' },
  ];
  
  const recordValue = ref(getParams("limit") || 10);
  
  const orderBy = ref(["column", "sorting"]);
  
  const toggleFilter = ref(false);
  
  const sortVal = {
	
tipo: 1,
modulo: 1,
acao: 1,
id_ref: 1,
created_at: 1,
  };
  
  const formColumns = useForm({
	route_of_list: "list.logsUsuario",
	columns: {
		
tipo: validateColumnsVisibility("tipo"),
modulo: validateColumnsVisibility("modulo"),
acao: validateColumnsVisibility("acao"),
id_ref: validateColumnsVisibility("id_ref"),
created_at: validateColumnsVisibility("created_at"),
	},
  });
  
  function validateColumnsVisibility(column) {
	let columnValue = Inertia.page.props.columnsTable?.[column];
	if (typeof columnValue == "boolean") {
	  return columnValue;
	}
	return true;
  }
  
  function toggleColumns() {
	formColumns.post(route("toggle.columns.tables"), {
	  preserveState: true,
	});
  }
  function sortTable(sort) {
	if (sort) {
	  return 0;
	} else {
	  return 1;
	}
  }
  
  function hasFilterActived() {
	if (
	  getParams("searchBy") !== null ||
	  getParams("limit") !== null ||
	  getParams("orderBy") !== null
	) {
	  return true;
	}
	return false;
  }
  
  function resetFilter() {
	window.history.replaceState(null, null, window.location.pathname);
	recordValue.value = 10;
	searchBy.value = "";
	Inertia.reload();
  }
  
  function setParams() {
	let data = {
	  limit: recordValue?.value,
	  searchBy: searchBy.value,
	  byStatus: statusValue?.value?.value,
	};
  
	!orderBy.value?.length ? (data.orderBy = orderBy?.value) : "";
  
	Inertia.visit("", {
	  preserveState: true,
	  replace: false,
	  data,
	});
  }
  
  const setParamsWithThrottle = _.throttle(setParams, 600);
  </script>