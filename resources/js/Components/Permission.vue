<template>
  <div class="lg:col-span-2">
    <hr class="my-5">
  </div>

  <div class="col-span-2">
    <section>
      <div class="flex flex-col justify-center h-full">
        <!-- Table -->
        <div class="w-full mx-auto bg-white rounded-sm border border-gray-200">
          <header class="px-5 py-4 border-b border-gray-100 flex items-center">
          <h2>Permissões</h2>
            <div class="ml-auto space-x-2 items-center text-sm">
              <Checkbox id="selectAll" @click="markAll(range(1, 353))" v-model="selectedAll" placeholder="Select all" class="ml-auto" :binary="true" />
              <label for="selectAll" v-if="!selectedAll">Marcar Todos</label>
              <label for="selectAll" v-else>Desmarcar Todos</label>
            </div>
          </header>
          <div class="p-3">
            <div class="overflow-x-auto">
              <table class="w-full  table-auto">
                <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                  <tr>
                    <th class="p-2 whitespace-nowrap">
                      <div class="font-semibold text-left">Módulo</div>
                    </th>
                    <th class="p-2 whitespace-nowrap">
                      <div class="font-semibold text-center">Marcar Linha</div>
                    </th>
                    <th class="p-2 whitespace-nowrap">
                      <div class="font-semibold">Listagem</div>
                    </th>
                    <th class="p-2 whitespace-nowrap">
                      <div class="font-semibold">Cadastro</div>
                    </th>
                    <th class="p-2 whitespace-nowrap">
                      <div class="font-semibold">Edição</div>
                    </th>
                    <th class="p-2 whitespace-nowrap">
                      <div class="font-semibold">Ver</div>
                    </th>
                    <th class="p-2 whitespace-nowrap">
                      <div class="font-semibold">Deletar</div>
                    </th>
                  </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
           
 

    
                  <tr>
		<td class='p-2 whitespace-nowrap'>
		  <div class='flex items-center'>
			<div class='font-medium text-gray-800'>Carros</div>
		  </div>
		</td>
		<td class='p-2 text-center'>
		   <Checkbox @click='markLine(range(344, 348), 7)' v-model='lineChecked' class='ml-auto' :value='6' />
		</td>
		<td class='p-2 whitespace-nowrap text-center'>
		<Checkbox v-model='checked' :value='344' />
		</td>
		<td class='p-2 whitespace-nowrap text-center'>
		<Checkbox v-model='checked' :value='345' />
		  
		</td>
		<td class='p-2 whitespace-nowrap text-center'>
		<Checkbox v-model='checked' :value='346' />
		  
		</td>
		<td class='p-2 whitespace-nowrap text-center'>
		<Checkbox v-model='checked' :value='347' />
		  
		</td>
		<td class='p-2 whitespace-nowrap text-center'>
		<Checkbox v-model='checked' :value='348' />
		  
		</td>
	  </tr>
    
                  <tr>
		<td class='p-2 whitespace-nowrap'>
		  <div class='flex items-center'>
			<div class='font-medium text-gray-800'>Motos</div>
		  </div>
		</td>
		<td class='p-2 text-center'>
		   <Checkbox @click='markLine(range(349, 353), 7)' v-model='lineChecked' class='ml-auto' :value='7' />
		</td>
		<td class='p-2 whitespace-nowrap text-center'>
		<Checkbox v-model='checked' :value='349' />
		</td>
		<td class='p-2 whitespace-nowrap text-center'>
		<Checkbox v-model='checked' :value='350' />
		  
		</td>
		<td class='p-2 whitespace-nowrap text-center'>
		<Checkbox v-model='checked' :value='351' />
		  
		</td>
		<td class='p-2 whitespace-nowrap text-center'>
		<Checkbox v-model='checked' :value='352' />
		  
		</td>
		<td class='p-2 whitespace-nowrap text-center'>
		<Checkbox v-model='checked' :value='353' />
		  
		</td>
	  </tr>


<!-- #ModificaAqui -->

               
                  <tr>
                    <td class="p-2 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="font-medium text-gray-800">Gestão de Usuários</div>
                      </div>
                    </td>
                    <td class="p-2 text-center">
                      <Checkbox @click="markLine(range(1, 5), 1)" v-model="lineChecked" class="ml-auto" :value="1" />
                    </td>
                    <td class="p-2 whitespace-nowrap text-center">
                      <Checkbox v-model="checked" :value="1" class="ml-auto" />
                    </td>
                    <td class="p-2 whitespace-nowrap text-center">
                      <Checkbox v-model="checked" :value="2" class="ml-auto" />
                    </td>
                    <td class="p-2 whitespace-nowrap text-center">
                      <Checkbox v-model="checked" :value="3" class="ml-auto" />
                    </td>
                    <td class="p-2 whitespace-nowrap text-center">
						<Checkbox v-model="checked" :value="4" class="ml-auto" />
                    </td>
                    <td class="p-2 whitespace-nowrap text-center">
                      <Checkbox v-model="checked" :value="5" placeholder="Selecionar todas" class="ml-auto" />
                    </td>
                  </tr>
				  
				  <tr>
					<td class='p-2 whitespace-nowrap'>
					<div class='flex items-center'>
						<div class='font-medium text-gray-800'>Logs Usuários</div>
					</div>
					</td>
					<td class='p-2 text-center'>
					<Checkbox @click='markLine(range(21, 25), 5)' v-model='lineChecked' class='ml-auto' :value='5' />
					</td>
					<td class='p-2 whitespace-nowrap text-center'>
					<Checkbox v-model='checked' :value='21' />
					</td>
					<td class='p-2 whitespace-nowrap text-center'>
					<!-- <Checkbox v-model='checked' :value='22' /> -->-
					
					</td>
					<td class='p-2 whitespace-nowrap text-center'>
					<!-- <Checkbox v-model='checked' :value='23' /> -->-
					
					</td>
					<td class='p-2 whitespace-nowrap text-center'>
					<!-- <Checkbox v-model='checked' :value='24' /> -->-
					
					</td>
					<td class='p-2 whitespace-nowrap text-center'>
					<!-- <Checkbox v-model='checked' :value='25' /> -->-
					
					</td>
				</tr>

                  <tr>
                  <td class='p-2 whitespace-nowrap'>
                    <div class='flex items-center'>
                    <div class='font-medium text-gray-800'>Logs Erros</div>
                    </div>
                  </td>
                  <td class='p-2 text-center'>
                    <Checkbox @click='markLine(range(16, 20), 4)' v-model='lineChecked' class='ml-auto' :value='4' />
                  </td>
                  <td class='p-2 whitespace-nowrap text-center'>
                  <Checkbox v-model='checked' :value='16' />
                  </td>
                  <td class='p-2 whitespace-nowrap text-center'>
                  <!-- <Checkbox v-model='checked' :value='17' /> -->-
                    
                  </td>
                  <td class='p-2 whitespace-nowrap text-center'>
                  <!-- <Checkbox v-model='checked' :value='18' /> -->-
                    
                  </td>
                  <td class='p-2 whitespace-nowrap text-center'>
					<!-- <Checkbox v-model='checked' :value='19' /> -->-
                  </td>
                  <td class='p-2 whitespace-nowrap text-center'>
                  <!-- <Checkbox v-model='checked' :value='20' /> -->-
                    
                  </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import Checkbox from "primevue/checkbox";
import _ from "lodash";
import { ref, defineProps, watch, defineEmits } from "vue";

const props = defineProps({
  setChecked: Array,
});

const emit = defineEmits(["checked"]);

const selectedAll = ref(false);

function range(first, last) {
  return _.range(first, last + 1);
}

const checked = ref(props.setChecked || []);
const lineChecked = ref([]);

function markLine(Array, id) {
  if (!lineChecked?.value?.includes(id)) {
    Array.forEach(function (data) {
      if (checked?.value?.includes(data)) {
        return;
      }
      checked?.value?.push(data);
    });
  } else {
    Array.forEach(function (data) {
      checked?.value.remove(data);
    });
  }
}

function markAll(Array) {
  let lineChecked = range(1, 69);

  if (!selectedAll.value) {
    lineChecked?.value?.forEach(function (data) {
      lineChecked.value.push(data);
    });
    Array.forEach(function (data) {
      if (checked.value.includes(data)) {
        return;
      }
      checked.value.push(data);
    });
  } else {
    lineChecked?.value?.forEach(function (data) {
      lineChecked.value.remove(data);
    });
    Array.forEach(function (data) {
      checked.value.remove(data);
    });
  }
}

watch(checked, () => {
  emit("update:checked", checked.value);
});

watch(lineChecked, () => {
  emit("update:checked", checked.value);
});

watch(selectedAll, () => {
  emit("update:checked", checked.value);
});
</script>

<style>
</style>