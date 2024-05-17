<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <Menu as="div" class="inline-block text-left">
    <div>
      <MenuButton class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 hover:bg-proconph text-sm font-medium text-gray-700 hover:text-white focus:outline-none ">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
        </svg>
        <ChevronDownIcon class="-mr-1 ml-2 h-5 w-5" aria-hidden="true" />
      </MenuButton>
    </div>
    <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
      <MenuItems class="origin-top-right z-50  absolute sm:right-16 max-sm:right-4 mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none">
        <MenuItem v-slot="{ active }">
        <a href="#" @click="edit" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'group flex items-center px-4 py-2 text-sm']">
          <PencilAltIcon class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
          Ver / Editar
        </a>
        </MenuItem>
        <MenuItem v-slot="{ active }">
        <a href="#" @click="openDel = true" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'group flex items-center px-4 py-2 text-sm']">
          <TrashIcon class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
          Excluir
        </a>
        </MenuItem>   
        <MenuItem v-slot="{ active }" v-if="props.routeResendPassword">
        <a title="Clique para Send Password por e-mail para o usuário" :href="props.routeResendPassword" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'group flex items-center px-4 py-2 text-sm']">
          <RefreshIcon class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
          Password
        </a>
        </MenuItem>
      </MenuItems>
    </transition>
  </Menu>
  <!-- Modal Delete -->
  <Delete v-model:open="openDel" @del="del" />
  <Reabrir v-model:open="openReabrir" @delReabrir="delReabrir" />

</template>

<script setup>
import Delete from "../Modals/Delete.vue";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ref, defineProps } from "vue";
import { Inertia } from "@inertiajs/inertia";
import {
  ArchiveIcon,
  ArrowCircleRightIcon,
  ChevronDownIcon,
  DuplicateIcon,
  HeartIcon,
  PencilAltIcon,
  TrashIcon,
  RefreshIcon,
  UserAddIcon,
} from "@heroicons/vue/solid";
import { useToast } from "vue-toastification";

const toast = useToast();

const props = defineProps({
  routeUpdate: String,
  routeDel: String,
  routeResendPassword: { type: String, default: null },
  routeReabrirPermissao: String,
});

const openDel = ref(false);

function del() {
  Inertia.post(props.routeDel);
}


function edit() {
  Inertia.visit(props.routeUpdate);
}
</script>
