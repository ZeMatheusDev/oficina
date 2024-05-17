<template>
  <PageTitle title="Grupo de Permissões" />
  <Nav class="mt-4 text-sm text-gray-400 list-none bg-white p-3 px-10 rounded-sm flex space-x-10 shadow-sm">
    <button class="font-bold" :class="{'text-proconph': currentNav == 1}" @click="currentNav = 1">
      INFORMAÇÕES GERAIS
    </button>
  </Nav>

  <form @submit.prevent="submit">
    <section class="mt-6 bg-white rounded-sm p-10 shadow-sm" v-if="currentNav == 1">
      <div class="flex flex-col space-y-1">
        <SectionTitle class="text-xs text-gray-600 font-bold uppercase">INFORMAÇÕES GERAIS</SectionTitle>
      </div>

      <div class="grid grid-cols-2 gap-6 mt-6 ">
        <span class="p-float-label">
          <InputText id="username" type="text" v-model="form.name" />
          <label for="username">Nome*</label>
        </span>
        <span class="p-float-label">
          <Dropdown v-model="form.status" :options="statusOption" optionValue="value" optionLabel="name" />
          <label for="username">Status</label>
        </span>
      </div>
      <hr class="my-4">
      <div>
        <Permission :setChecked="getPermissionsArray()" v-model:checked="form.permission" />
      </div>
      <div class="flex space-x-5 mt-8">
        <button type="submit" :disabled="sending" class="p-2 flex rounded-md bg-proconph text-white px-6 text-sm font-medium items-center" :class="{'bg-opacity-80 cursor-not-allowed': submited}" >
          <svg role="status" v-show="submited" class="mr-2 w-4 h-4 animate-spin fill-primary" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
          </svg>
          Salvar
        </button>
        <Link :href="route('list.permission')" as="button" type="button" class="p-2 rounded-md bg-secundary text-white px-6 text-sm font-medium">
        Voltar
        </Link>
      </div>
    </section>
  </form>
</template>

<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import moment from "moment";
import { ref, computed, defineProps, onMounted } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import Password from "primevue/password";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Permission from "../../Components/Permission.vue";
import { useToast } from "vue-toastification";

const toast = useToast();

const props = defineProps({
  errorBags: Object,
  role: Object,
});

const sendable = ref(false);

const currentNav = ref(1);

const statusOption = [
{ name: "Ativo", value: "true" },
  { name: "Inativo", value: "false" },
];

const submited = ref(false);

const form = useForm({
  name: props.role?.name,
  status: props.role?.status,
  permission: getPermissionsArray(),
});

function validateForm() {
  if (!form.name) {
    throw new toast.error(
      "Os campos obrigatórios do formulário devem ser preenchidos. Por favor, cheque as sessões e tente novamente."
    );
  }
}

function getPermissionsArray() {
  return props.role?.permissions.map((val) => {
    return val?.id;
  });
}

function submit() {
  validateForm();
  submited.value = true;

  form.post(route("update.permission", { id: props.role?.id }), {
    preserveState: true,
    onError: () => {
      toast.error(
        props.errorBags?.default?.name?.[0] ||
          "Você não atendeu a todos os requisitos do formulário!"
      );
    },
    onSuccess: () => {
      toast.success("Salvo com sucesso!");
    },
    onFinish: () => (submited.value = false),
  });
}
</script>



<style scoped>
.file-input {
  display: inline-block;
  text-align: left;
  background: #fff;
  width: 100%;
  position: relative;
  border-radius: 3px;
}

.file-input > [type="file"] {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  z-index: 10;
  cursor: pointer;
}

.file-input > .button {
  display: inline-block;
  cursor: pointer;
  background: #eee;
  padding: 8px 16px;
  border-radius: 2px;
  margin-right: 8px;
}

.file-input:hover > .button {
  background: rgb(25, 25, 112);
  color: white;
  border-radius: 6px;
  transition: all 0.2s;
}

.file-input > .label {
  color: #333;
  white-space: nowrap;
  opacity: 0.3;
}

.file-input.-chosen > .label {
  opacity: 1;
}
</style>