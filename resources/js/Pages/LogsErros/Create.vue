<template>
  <PageTitle title="Logs Erros" />
  <Nav class="mt-4 text-sm text-gray-400 list-none bg-white p-3 px-10 rounded-sm flex space-x-10 shadow-sm">
    <button class="font-bold" :class="{'text-proconph': currentNav == 1}" @click="currentNav = 1">
      INFORMAÇÕES GERAIS
    </button>
  </Nav>
  <form @submit.prevent="submit">
    <section class="mt-6 bg-white rounded-sm p-10 shadow-sm" v-if="currentNav == 1">
      <div class="flex flex-col space-y-1">
        <SectionTitle class="text-xs text-gray-600 font-bold uppercase">INFORMAÇÕES GERAIS</SectionTitle>
        <SectionTitle class="text-xs text-gray-600">Essential information for insertion Logs Erros no sistema.</SectionTitle>
      </div>

      <div class="grid grid-cols-2 gap-6 mt-6 ">
	  


<div>
  <span class="p-float-label">
   <InputText v-model="form.pagina" id="pagina" type="text" class="w-full" required />
    <label for="pagina" class="text-sm">Página</label>
    </span>
</div>


<div>
  <span class="p-float-label">
   <InputText v-model="form.modulo" id="modulo" type="text" class="w-full" required />
    <label for="modulo" class="text-sm">Módulo</label>
    </span>
</div>


<div>
  <span class="p-float-label">
   <InputText v-model="form.erro" id="erro" type="text" class="w-full" required />
    <label for="erro" class="text-sm">Erro</label>
    </span>
</div>


<div>
 <span class="p-float-label">
  <Textarea v-model="form.erro_completo" :autoResize="true" rows="3" cols="60" class="w-full"  required />
  <label for="erro_completo" class="text-sm">Erro Completo</label>
  </span>
</div>

      </div>
      <div class="flex space-x-5 mt-8">
        <button type="submit" :disabled="sending" class="p-2 flex rounded-mdbg-primary text-white px-6 text-sm font-medium items-center" :class="{'bg-opacity-80 cursor-not-allowed': submited}">
          <svg role="status" v-show="submited" class="mr-2 w-4 h-4 animate-spin fill-primary" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
          </svg>
          Salvar
        </button>
        <Link :href="route('list.logsErros')" as="button" type="button" class="p-2 rounded-md bg-secundary text-white px-6 text-sm font-medium">
        Voltar
        </Link>
      </div>
    </section>
  </form>
</template>

<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import moment from "moment";
import { ref, computed, defineProps } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import Password from "primevue/password";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Dropdown from "primevue/dropdown";
import { useToast } from "vue-toastification";

defineProps({
  errorBags: Object,
});

const toast = useToast();

const sendable = ref(false);

const currentNav = ref(1);

const statusOption = [
  { name: "Active", value: true },
  { name: "InActive", value: false },
];

const submited = ref(false);

const form = useForm({
	
pagina: "",

modulo: "",

erro: "",

erro_completo: "",

});

function getFormFiltered() {
  const newForm = {};
  for (let [key, value] of Object.entries(form)) {
    if (typeof value == "object" && value?.value) {
      newForm[key] = value.value;
    } else {
      newForm[key] = value;
    }
  }
  return newForm;
}
 

function submit() {
  submited.value = true;
  const submitForm = getFormFiltered();

  submitForm.post(route("store.logsErros"), {
    preserveState: true,
    onError: () => {
      toast.error("Você não atendeu a todos os requisitos do formulário!");
    },
    onSuccess: () => {
      toast.success("Salvo com sucesso!");
      reset();
      
    },
    onFinish: () => (submited.value = false),
  });
  	
}
</script>