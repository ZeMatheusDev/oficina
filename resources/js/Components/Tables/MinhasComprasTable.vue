<template>
  <div class="bg-white px-4 shadow-lg rounded-xl">
  

    <div class="mt-4 flex flex-col max-md:px-2 py-1 rounded-lg shadow-sm">
      <div class="inline-block min-w-full py-2 align-middle">
        <h2 class="text-xl font-semibold fontInter text-center py-5"><b>Seus veiculos comprados:</b></h2>
        <div
          class="overflow-hidden overflow-x-visible ring-1 ring-black ring-opacity-5 md:rounded-lg"
        >
          <table class="min-w-full divide-y divide-gray-300">
            <thead class="mb-24">
              <tr class="text-gray-500 font-bold select-none" @click="setParams">
                <th
                  scope="col"
                  class="px-4 text-sm cursor-pointer text-center border-r group"
                  style="width: 2%"
                >
  
                </th>

                <th
                  v-if="formColumns.columns.modelo"
                  scope="col"
                  class="px-4 text-sm cursor-pointer text-center border-r group"
                  @click="
                    orderBy = {
                      column: 'modelo',
                      sorting: sortTable(sortVal.modelo)
                        ? (sortVal.modelo = 1)
                        : (sortVal.modelo = 0),
                    }
                  "
                >
                  <div class="flex">
                    <span class="group-hover:text-indigo-800">Modelo</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 ml-auto group-hover:text-indigo-800"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  v-if="formColumns.columns.valor_compra"
                  scope="col"
                  class="px-4 text-sm cursor-pointer text-center border-r group"
                  @click="
                    orderBy = {
                      column: 'valor_compra',
                      sorting: sortTable(sortVal.valor_compra)
                        ? (sortVal.valor_compra = 1)
                        : (sortVal.valor_compra = 0),
                    }
                  "
                >
                  <div class="flex">
                    <span class="group-hover:text-indigo-800">Valor da compra</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 ml-auto group-hover:text-indigo-800"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  v-if="formColumns.columns.veiculo"
                  scope="col"
                  class="px-4 text-sm cursor-pointer text-center border-r group"
                  @click="
                    orderBy = {
                      column: 'veiculo',
                      sorting: sortTable(sortVal.veiculo)
                        ? (sortVal.veiculo = 1)
                        : (sortVal.veiculo = 0),
                    }
                  "
                >
                  <div class="flex">
                    <span class="group-hover:text-indigo-800">Veiculo</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 ml-auto group-hover:text-indigo-800"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  v-if="formColumns.columns.placa"
                  scope="col"
                  class="px-4 text-sm cursor-pointer text-center border-r group"
                  @click="
                    orderBy = {
                      column: 'placa',
                      sorting: sortTable(sortVal.placa)
                        ? (sortVal.placa = 1)
                        : (sortVal.placa = 0),
                    }
                  "
                >
                  <div class="flex">
                    <span class="group-hover:text-indigo-800">placa</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 ml-auto group-hover:text-indigo-800"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                      />
                    </svg>
                  </div>
                </th>

                
								</tr>
                

            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              <tr
                v-for="(data, key) in dataTable?.data"
                :key="key"
                class="hover:bg-indigo-50/20"
                :class="{ 'bg-gray-50': key % 2 }"
              >
                <td class="whitespace-nowrap py-6 pl-4 pr-3 text-sm sm:pl-6">
                  <div class="flex items-center">
                    <div>
                      <SlideUpDown v-model="DeleteSelect" :duration="300">
                        <Checkbox
                          inputId="id"
                          name="selected"
                          :value="data?.id"
                          v-model="selected"
                        />
                      </SlideUpDown>
                    </div>
                  </div>
                </td>

                <td
                  v-if="formColumns?.columns?.modelo"
                  class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6"
                >
                  <div class="flex items-center">
                    <div>
                      <div class="font-medium text-gray-900">{{ data?.modelo }}</div>
                    </div>
                  </div>
                </td>
                <td
                  v-if="formColumns?.columns?.valor_compra"
                  class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6"
                >
                  <div class="flex items-center">
                    <div>
                      <div class="font-medium text-gray-900">R${{parseInt(data?.valor_compra) + parseInt(data?.lucro) }}</div>
                    </div>
                  </div>
                </td>
                <td
                  v-if="formColumns?.columns?.veiculo"
                  class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6"
                >
                  <div class="flex items-center">
                    <div>
                      <div class="font-medium text-gray-900">{{ data?.veiculo }}</div>
                    </div>
                  </div>
                </td>
                <td
                  v-if="formColumns?.columns?.placa"
                  class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6"
                >
                  <div class="flex items-center">
                    <div>
                      <div class="font-medium text-gray-900">{{ data?.placa }}</div>
                    </div>
                  </div>
                </td>

              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <Pagination
      :links="dataTable"
      :orderBy="props.Filtros.orderBy"
      :limit="props.Filtros.limit"
    />
    <Delete v-model:open="openDelTodos" @del="delTodos" />
    <Delete v-model:open="openDelSelect" @del="del" />
    <Restaurar v-model:open="openRestaurarTodos" @del="RestaurarTodos" />
  </div>
</template>

<script setup>
import Message from "../../Layouts/Message.vue";
import Checkbox from "primevue/checkbox";
import Toolbar from "primevue/toolbar";
import SlideUpDown from "vue3-slide-up-down";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import MultiSelect from "primevue/multiselect";
import Dropdown from "primevue/dropdown";
import ColorPicker from "primevue/colorpicker";
import InputSwitch from "primevue/inputswitch";
import Pagination from "../Pagination.vue";
import Comprar from "./ComprarCarro.vue";
import Delete from "../Modals/Delete.vue";
import Restaurar from "../Modals/Restaurar.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { ref, defineProps, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";
import AchievementCard from "../../Layouts/CardsListagens.vue";
const _ = require("lodash");

const props = defineProps({
  dataTable: Object,
  Filtros: Object,
  Registros: Object,
});

const showDeleteModal = ref([]);
const openDelSelect = ref(false);
const openDelTodos = ref(false);
const openRestaurarTodos = ref(false);

const searchBy = ref(getParams("searchBy") || "");

const statusValue = ref(getParams("byStatus"));

const status = [
  { label: "Ativo", code: "0" },
  { label: "Inativo", code: "1" },
];

const recordValue = ref(getParams("limit") || 10);

const orderBy = ref(["column", "sorting"]);

const toggleFilter = ref(false);
const FiltroAvancado = ref(false);
const DeleteSelect = ref(false);
const checked = ref(false);
const selected = ref([]);
const valor = ref([]);

const statusOption = [
  { name: "Ativo", value: "0" },
  { name: "Inativo", value: "1" },
];

const sortVal = {
  modelo: 1,
  valor_compra: 1,
  veiculo: 1,
  placa: 1,
};

const formColumns = useForm({
  route_of_list: "list.ConfigCarros",
  columns: {
    modelo: validateColumnsVisibility("modelo"),
    valor_compra: validateColumnsVisibility("valor_para_venda"),
    lucro: validateColumnsVisibility("lucro"),
    valor_para_venda: validateColumnsVisibility("valor_para_venda"),
    veiculo: validateColumnsVisibility("veiculo"),
    placa: validateColumnsVisibility("placa"),

  },
});

const form = useForm({
  lucro: 0,
  valor_para_venda: 0,
  valor_compra: 0,
});


const form2 = useForm({
  modelo: props.Filtros?.modelo || null,
  valor_compra: props.Filtros?.valor_compra || null,
  lucro: props.Filtros?.lucro || null,
  valor_para_venda: props.Filtros?.valor_para_venda || null,
  veiculo: props.Filtros?.veiculo || null,
  placa: props.Filtros?.placa || null,
  limparFiltros: "",
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

function del() {
  valor.value = selected.value;
  selected.value = "";
  Inertia.post(route("deleteSelected.ConfigCarros", { id: valor.value }));
}
function delTodos() {
  Inertia.post(route("deletarTodos.ConfigCarros"));
}

function RestaurarTodos() {
  Inertia.post(route("RestaurarTodos.ConfigCarros"));
}

function getFormFiltered() {
  const newForm = {};
  for (let [key, value] of Object.entries(form2)) {
    if (typeof value == "object" && value?.value) {
      newForm[key] = value.value;
    } else {
      newForm[key] = value;
    }
  }
  return newForm;
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

function FiltroAvancadoAplica() {
  const submitForm = getFormFiltered();
  let data = {
    submitForm,
  };
  !orderBy.value?.length ? (data.orderBy = orderBy?.value) : "";
  submitForm.post(route("listP.ConfigCarros"), {
    replace: false,
    data,
    onSuccess: () => {
      (FiltroAvancado.value = false), window.location.reload(); // recarrega a página após a atualização
    },
  });
}

watch(
  () => props.Filtros,
  (novoValor, valorAntigo) => {
    let OrderBy = "";
    let MeuLimit = recordValue.value;
    if (novoValor !== valorAntigo) {
      if (props.Filtros.orderBy) {
        OrderBy = `&orderBy[column]=${props.Filtros.orderBy.column}&orderBy[sorting]=${props.Filtros.orderBy.sorting}`;
      }
      if (props.Filtros.limit) {
        MeuLimit = props.Filtros.limit;
      }
      const url = `${window.location.pathname}?page=1&limit=${MeuLimit}${OrderBy}`;
      window.location.replace(url);
    }
  }
);
</script>
