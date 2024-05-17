<template>	
	<div class="items-center justify-center w-full">
	<!-- Card code block start -->
	<div class="bg-white  shadow rounded">
		<div class="relative">
			<img class="h-56 shadow rounded-t w-full object-cover object-center" src="/images/dash.jpg" alt="" />
			<div class="inset-0 m-auto w-24 h-24 absolute bottom-0 -mb-12 xl:ml-10 rounded border-2 shadow border-white">
				<img class="w-full h-full overflow-hidden object-cover rounded" :src="$page.props.user?.profile_picture" onerror="this.src='/images/profile_default.png';" alt="" />
			</div>
		</div>
		<div class="px-5 xl:px-10 pb-10">
			<div class="flex justify-center xl:justify-end w-full pt-16 xl:pt-5">
				<div class="flex items-center">
					<svg class="w-4 mr-1 text-yellow-400 icon icon-tabler icon-tabler-star" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
						<path stroke="none" fill="none" d="M0 0h24v24H0z" />
						<path fill="currentColor" d="M12 17.75l-6.172 3.245 1.179-6.873-4.993-4.867 6.9-1.002L12 2l3.086 6.253 6.9 1.002-4.993 4.867 1.179 6.873z" />
					</svg>
					<svg class="w-4 mr-1 text-yellow-400 icon icon-tabler icon-tabler-star" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
						<path stroke="none" fill="none" d="M0 0h24v24H0z" />
						<path fill="currentColor" d="M12 17.75l-6.172 3.245 1.179-6.873-4.993-4.867 6.9-1.002L12 2l3.086 6.253 6.9 1.002-4.993 4.867 1.179 6.873z" />
					</svg>
					<svg class="w-4 mr-1 text-yellow-400 icon icon-tabler icon-tabler-star" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
						<path stroke="none" fill="none" d="M0 0h24v24H0z" />
						<path fill="currentColor" d="M12 17.75l-6.172 3.245 1.179-6.873-4.993-4.867 6.9-1.002L12 2l3.086 6.253 6.9 1.002-4.993 4.867 1.179 6.873z" />
					</svg>
					<svg class="w-4 mr-1 text-yellow-400 icon icon-tabler icon-tabler-star" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
						<path stroke="none" fill="none" d="M0 0h24v24H0z" />
						<path fill="currentColor" d="M12 17.75l-6.172 3.245 1.179-6.873-4.993-4.867 6.9-1.002L12 2l3.086 6.253 6.9 1.002-4.993 4.867 1.179 6.873z" />
					</svg>
					<svg class="w-4 mr-1 text-yellow-400 icon icon-tabler icon-tabler-star" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
						<path stroke="none" fill="none" d="M0 0h24v24H0z" />
						<path fill="currentColor" d="M12 17.75l-6.172 3.245 1.179-6.873-4.993-4.867 6.9-1.002L12 2l3.086 6.253 6.9 1.002-4.993 4.867 1.179 6.873z" />
					</svg>
				</div>
			</div>
			<div class="pt-3 xl:pt-5 flex flex-col xl:flex-row items-start xl:items-center justify-between">
				<div class="xl:pr-16 w-full xl:w-2/3">
					<Link as="button"  :href="route('form.update.profile')" title="Clique para editar o perfil">
					<div class="text-center xl:text-left mb-3 xl:mb-0 flex flex-col xl:flex-row items-center justify-between xl:justify-start">
						<h2 class="mb-3 xl:mb-0 xl:mr-4 text-2xl text-gray-800  font-medium tracking-normal">{{$page.props?.user?.name}}</h2>
						<div class="text-sm bg-indigo-700 dark:bg-indigo-600 text-white px-5 py-1 font-normal rounded-full">{{ $page.props?.user?.email }}</div>
					</div>
					<p class="text-center xl:text-left mt-2 text-sm tracking-normal text-gray-600 dark:text-gray-400 leading-5">Bem-Vindo de Volta! Este é o seu Dashboard, onde poderá acompanhar algumas informações básicas da sua organização! Alguns botões nesta tela necessitam de permissões de acesso. Caso não consiga acessar algum botão, solicite o seu acesso ao administrador do sistema.</p>
					</Link>
				</div>

				<div class="xl:px-10 xl:border-l xl:border-r w-full py-5 flex items-start justify-center xl:w-1/3">
					<div class="mr-6 xl:mr-10">
					
						<h2 class="text-gray-600 dark:text-gray-400 font-bold text-xl xl:text-2xl leading-6 mb-2 text-center">14</h2>
						<p class="text-gray-800  text-sm xl:text-xl leading-5">Dados 1</p>
					
					</div>
					<div class="mr-6 xl:mr-10">
					
						<h2 class="text-gray-600 dark:text-gray-400 font-bold text-xl xl:text-2xl leading-6 mb-2 text-center">23</h2>
						<p class="text-gray-800  text-sm xl:text-xl leading-5">Dados 2</p>
						
					</div>
					<div>
						
						<h2 class="text-gray-600 dark:text-gray-400 font-bold text-xl xl:text-2xl leading-6 mb-2 text-center">87</h2>
						<p class="text-gray-800  text-sm xl:text-xl leading-5">Dados 3</p>
						
					</div>
				</div>
				<div class="w-full xl:w-2/3 flex-col md:flex-row justify-center xl:justify-end flex md:pl-6">
					<div class="flex items-center justify-center xl:justify-start mt-1 md:mt-0 mb-5 md:mb-0">
						<div class="rounded-full bg-red-400 text-white-600 text-sm px-6 py-2 flex justify-center items-center" style="color:white">Botão 1</div>
						<div class="ml-5 rounded-full bg-green-200 text-green-500 text-sm px-6 py-2 flex justify-center items-center" style="color:black">Botão 2</div>
					</div>
						<button class="rounded-full focus:outline-none ml-0 md:ml-5 bg-indigo-700 dark:bg-indigo-600 transition duration-150 ease-in-out hover:bg-indigo-600 rounded text-white px-3 md:px-6 py-2 text-sm">Botão 3</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Card code block end -->
</div>














 <p><br></p>
  <div class="w-full grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 rounded-xl">
	<div class="bg-white  rounded shadow px-8 py-6 flex items-center rounded-xl">
		
		<div class="flex items-center justify-center w-12 h-12 bg-proconph rounded-full" style="color:white">
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
			<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
			</svg>
		</div>
		<div class="ml-6">
			<h3 class="mb-1 leading-5 text-gray-800 dark:text-proconph-100 font-bold text-2xl">50</h3>
			<p class="text-gray-600 dark:text-proconph-400 text-sm tracking-normal font-normal leading-5">Item A</p>
		</div>
		
	</div>
	<div class="bg-white  rounded shadow px-8 py-6 flex items-center rounded-xl">
		
		<div class="flex items-center justify-center w-12 h-12 bg-red-400 rounded-full rounded-xl" style="color:white">
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
			<path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
			</svg>

		</div>
		<div class="ml-6">
			<h3 class="mb-1 leading-5 text-proconph-800 dark:text-proconph-100 font-bold text-2xl">30</h3>
			<p class="text-gray-600 dark:text-proconph-400 text-sm tracking-normal font-normal leading-5">Item B</p>
		</div>
		
	</div>
	<div class="bg-white  rounded shadow px-8 py-6 flex items-center rounded-xl">
		
		<div class="flex items-center justify-center w-12 h-12 bg-proconph rounded-full" style="color:white">
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
			<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
			</svg>
		</div>
		<div class="ml-6">
			<h3 class="mb-1 leading-5 text-proconph-800 dark:text-proconph-100 font-bold text-2xl">20</h3>
			<p class="text-gray-600 dark:text-proconph-400 text-sm tracking-normal font-normal leading-5">Item C</p>
		</div>
		
	</div>
	<div class="bg-white  rounded shadow px-8 py-6 flex items-center rounded-xl">
		
		<div class="flex items-center justify-center w-12 h-12 bg-red-400 rounded-full rounded-xl" style="color:white">
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
			<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0" />
			</svg>
		</div>
		<div class="ml-6">
			<h3 class="mb-1 leading-5 text-proconph-800 dark:text-proconph-100 font-bold text-2xl">70</h3>
			<p class="text-gray-600 dark:text-proconph-400 text-sm tracking-normal font-normal leading-5">Item D</p>
		</div>
		
	</div>
</div>
 
 
<div class="mt-10 grid grid-cols-1 gap-6 max-md:grid-cols-1">
	<div class="bg-white px-4 shadow-md rounded-xl">
  <div class="mt-4 flex flex-col max-md:px-2 py-1 rounded-lg shadow-sm">
	<div class="inline-block min-w-full py-2 align-middle">
	  <h2 class="text-xl font-semibold fontInter text-center py-5"><b>Listagem Padrão</b></h2>
	  <div class="overflow-hidden overflow-x-visible ring-1 ring-black ring-opacity-5 md:rounded-lg">
		<table class="min-w-full divide-y divide-gray-300" style="overflow-x: hidden;">
		  <thead class=" mb-24">
			<tr class="text-gray-500 font-bold select-none" style="overflow-x: hidden;">  
			
			<th scope="col" class="px-4 text-sm cursor-pointer text-center border-r group" >
			<div class="flex">
			  <span class="group-hover:text-indigo-800"> Coluna A</span>				
			</div>
			 </th>
			<th  scope="col" class="px-4 text-sm cursor-pointer text-center border-r group">
			<div class="flex">
			  <span class="group-hover:text-indigo-800 text-center">  Coluna B</span>				 
			</div>
			 </th>
			<th  scope="col" class="px-4 text-sm cursor-pointer text-center border-r group" >
			<div class="flex">
			  <span class="group-hover:text-indigo-800"> Coluna C</span>				 
			</div>
			 </th>
			<th scope="col" class="px-4 text-sm cursor-pointer text-center border-r group" >
			<div class="flex">
			  <span class="group-hover:text-indigo-800"> Coluna D</span>				  
			</div>
			 </th>
			<th  scope="col" class="px-4 text-sm cursor-pointer text-center border-r group">
			<div class="flex">
			  <span class="group-hover:text-indigo-800">  Coluna E</span>				
			</div>
			 </th>			
			</tr>
		  </thead>
		  <tbody class="divide-y divide-gray-200 bg-white">
			<tr v-for="data in OrdemdeServico" :key="id" class="hover:bg-indigo-50/20 cursor-pointer" :class="{'bg-gray-50 cursor-pointer': (id % 2)}" >
				   <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
				<div class="flex items-center">
				  <div>
					<div class="font-medium text-gray-900"></div>
				  </div>
				</div>
				 </td>
				<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
				<div class="flex items-center">
				  <div>
					
				  </div>
				</div>
				 </td>
				<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
				<div class="flex items-center">
				  <div>
				
				  </div>
				</div>
				 </td>
			<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
				<div class="flex items-center">
				  <div>
				
				  </div>
				</div>
				 </td>
			<td  class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
				<div class="flex items-center">
				  <div>
					
				  </div>
				</div>
				 </td>
			</tr>

		  </tbody>
		</table>
	  </div>
	</div>
  </div>	
</div>



</div>
<ModalDelete v-model:open="showDeleteModal.show" @del="DeleteFinanceiro(showDeleteModal.id)"/>
</template>



<script setup>
import Checkbox from "primevue/checkbox";
import SlideUpDown from "vue3-slide-up-down";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Pagination from "../../Components/Pagination.vue";
import Actions from "../../Components/Tables/Actions.vue";
import Delete from "../../Components/Modals/Delete.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { ref, defineProps, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";  
import { useToast } from "vue-toastification";
import ModalDelete from "@/Components/Modals/Delete";
const _ = require("lodash");

const props = defineProps({
dataTable: Object,
ContasAVencer: Object,
Registros: Object,
OrdemdeServico: Object,
AlertaError: Object,
});

const showDeleteModal = ref({
id: null,
show: false,
});

const AlertaError = props.AlertaError;

const searchBy = ref(getParams("searchBy") || "0");
const searchBy2 = ref(getParams("searchBy2") || "0"); 
const statusValue = ref(getParams("byStatus"));

const status = [
{ label: "Ativo", code: '0' },
{ label: "Inativo", code: '1' },
];



const toast = useToast();

const InputsRequired = { empresa_vinculada: "",id_fixo: "",id_ordem_servico: "",data_prevista: "",data_realizada: "",tipo: "",valor: "",obs: "",created_at: "", };

const form = useForm({

empresa_vinculada: "",

id_fixo: "",

id_ordem_servico: "",

data_prevista: "",

data_realizada: "",

tipo: "",

valor: "",

obs: "",

created_at: "",

});

const recordValue = ref(getParams("limit") || 10);

const orderBy = ref(["column", "sorting"]);

const toggleFilter = ref(false);
const toggleDetalhamento = ref(false);



const sortVal = {
empresa_vinculada: 1,
id_fixo: 1,
id_ordem_servico: 1,
data_prevista: 1,
data_realizada: 1,
tipo: 1,
valor: 1,
obs: 1,
created_at: 1,
};

const formColumns = useForm({
route_of_list: "list.Financeiro",
columns: {
empresa_vinculada: validateColumnsVisibility("empresa_vinculada"),
id_fixo: validateColumnsVisibility("id_fixo"),
id_ordem_servico: validateColumnsVisibility("id_ordem_servico"),
data_prevista: validateColumnsVisibility("data_prevista"),
data_realizada: validateColumnsVisibility("data_realizada"),
tipo: validateColumnsVisibility("tipo"),
valor: validateColumnsVisibility("valor"),
obs: validateColumnsVisibility("obs"),
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
ExibiAlertas();


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
  getParams("searchBy2") !== null ||
  getParams("limit") !== null ||
  getParams("orderBy") !== null
) {
  return true;
}
return false;
}

function setModalDeleteVal(id) {
showDeleteModal.value.id = id;
showDeleteModal.value.show = true;
}


function redirectToItem(token) {
const id = token;
Inertia.visit(route('form.update.Financeiro', {id: id}, ), {
  preserveScroll: true,
  onSuccess: () => toast.info('Você foi redirecionado com sucesso. Após atualizar a informação desejada, você será direcionado ao Financeiro Geral. Para voltar ao Dashboard, selecione a opção no menu lateral em Dashboard.'),
});
  }

function addFinanceiro() {

for (const formKey in InputsRequired) {
	if(form[formKey] == '') {
		toast.info('Todos os campos são obrigatórios!');
		return false;
	}
}


form.post(route('storeAjax.Financeiro'), {
  preserveScroll: true,
  onError: () => toast.error('Mensagem de erro.'),
  onSuccess: () => { 
	form.reset();
	toast.success("Dados armarzenados com sucesso!");      
  },
});
}



function DeleteFinanceiro(financeiroIndex) {
const id = financeiroIndex;
Inertia.post(route('deleteAjax.Financeiro', {id: id}, ), {
  preserveScroll: true,
  onSuccess: () => toast.error('Campo apagado com sucesso!'),
})
}


function updateFinanceiro(financeiroIndex) {
const financeiro =  props.dataTable?.data[financeiroIndex]; 
const id = financeiro.id;
Inertia.post(route('updateAjax.Financeiro', {id: id}, ), financeiro, {
  preserveScroll: true,
  onSuccess: () => toast.success('Campo editado com sucesso!'),
})
}

function ExibiAlertas(){
	if(AlertaError == 1){
		toast.error('Atenção! Usuário não autorizado.'); 
		console.log('1');
	}
}

function resetFilter() {
window.history.replaceState(null, null, window.location.pathname);
recordValue.value = 10;
searchBy.value = "0";
searchBy2.value = "0";
Inertia.reload();
}

function setParams() {
let data = {
  limit: recordValue?.value,
  searchBy: searchBy.value,
  searchBy2: searchBy2.value,
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