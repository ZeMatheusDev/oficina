<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <Menu as="div" class="inline-block text-left">

    <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
        <MenuItem style="background-color: rgb(3, 138, 255); border-radius: 20px;" v-slot="{ active }">
          <a style="color: white; font-size: 18px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;" href="#" @click="rent()" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'group flex items-center px-4 py-2 text-sm']">
  Comprar
</a>
        </MenuItem>
        

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
  routeComprar: String,
});

const openDel = ref(false);

function rent() {
  Inertia.post(props.routeComprar);
}
</script>
