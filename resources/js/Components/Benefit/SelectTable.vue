<template>
  <div class="flex flex-col border rounded-md">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
        <div class="overflow-hidden">
          <table class="min-w-full">
            <thead class="border-b">
              <tr>
                <th scope="col" class="text-sm font-bold text-gray-900 px-6 py-4 text-left flex space-x-2 whitespace-nowrap">
                  <Checkbox @click="selectAll" v-model="hasSelectedAll" binary />
                  <span>#</span>
                </th>
                <th scope="col" class="text-sm font-bold text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                  Cargo
                </th>
                <th scope="col" class="text-sm font-bold text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                  Nível
                </th>
                <th scope="col" class="text-sm font-bold text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                  Departamento
                </th>
                <th scope="col" class="text-sm font-bold text-gray-900 px-6 py-4 text-left whitespace-nowrap">
                  Centro de Custos
                </th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b" v-for="data in dataTable" :key="data?.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  <Checkbox v-model="checks" :value="data?.id" />
                </td>
                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                  {{data?.name}}
                </td>
                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                  {{data?.level?.name}}
                </td>
                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                  {{data?.department?.name}}
                </td>
                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                  {{data?.cost_center?.name}}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import _ from "lodash";
import Checkbox from "primevue/checkbox";
import { ref, computed, defineProps, onMounted, defineEmits, watch } from "vue";

const props = defineProps({
  dataTable: Object,
  benefit: Object,
});

const emit = defineEmits("checks");

const checks = ref(traitOffices());
const hasSelectedAll = ref(false);

function selectAll() {
  if (hasSelectedAll.value) {
    checks.value.splice(0, checks.value.length);
    return;
  }

  const dataTableMap = props.dataTable?.map?.((data) => {
    return data?.id;
  });

  checks.value = dataTableMap;
}

function traitOffices() {
  return (
    props.benefit?.benefit_as_office?.map((data) => {
      return data?.id;
    }) || []
  );
}

watch(checks, () => {
  emit("update:checks", checks.value);
});

onMounted(() => {
  const dataTableMap = props.dataTable?.map?.((data) => {
    return data?.id;
  });

  if (
    _.isEqual(
      _.isEqual(dataTableMap.sort(), Object.values(checks.value)?.sort?.())
    )
  ) {
    hasSelectedAll.value = true;
  }
});
</script>