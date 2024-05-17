<template>
  <PageTitle title="Empresas" />
  <Nav class="mt-4 text-sm text-gray-400 list-none bg-white p-3 px-10 rounded-sm flex space-x-10 shadow-sm">
    <button class="font-bold" :class="{'text-proconph': currentNav == 1}" @click="currentNav = 1">
      Informações Gerais
    </button>
  </Nav>
  <form @submit.prevent="submit">
    <section class="mt-6 bg-white rounded-sm p-10 shadow-sm" v-if="currentNav == 1">
      <div class="flex flex-col space-y-1">
        <SectionTitle class="text-xs text-gray-600 font-bold uppercase">INFORMAÇÕES GERAIS</SectionTitle>
        <SectionTitle class="text-xs text-gray-600">Informações essenciais para inserção de Empresas no sistema.</SectionTitle>
      </div>

        <div class="mt-10 grid grid-cols-4 gap-6 max-md:grid-cols-1">
          <span class="p-float-label">
          <InputText type="text" v-model="form.name" required />
          <label>Razão Social</label>
        </span>
        <span class="p-float-label">
          <InputText type="text" v-model="form.nome_fantasia" required />
          <label>Nome Fantasia</label>
        </span>
        <span class="p-float-label">
          <InputText v-maska="'##.###.###/####-##'" type="text" v-model="form.cnpj" required />
          <label>CNPJ</label>
        </span>
        <span class="p-float-label">
          <InputText type="text" v-model="form.quality_responsable" required />
          <label>Responsável</label>
        </span>
        <span class="p-float-label">
          <InputText type="number" v-model="form.insc_municipal" required />
          <label>Inscrição Municipal</label>
        </span>
        <span class="p-float-label">
          <InputText type="number" v-model="form.insc_estadual" required />
          <label>Inscrição Estadual</label>
        </span>
        <span class="p-float-label">
         
         <Dropdown class="w-full" v-model="form.status" :options="statusOption" optionLabel="name" dataKey="value" required />
         <label for="username">Selecione um status</label>
       </span>   

          <div>
          <span class="p-float-label">
          <Dropdown class="w-full" v-model="form.icms_origem" :options="Origem" optionLabel="descricao" dataKey="codigo" required :filter="true" />
          <label for="status" class="text-sm">ICMS Origem</label>
          </span>
          </div>

          <div>
          <span class="p-float-label">
          <Dropdown class="w-full" v-model="form.icms_situacao_tributaria" :options="Tributaria" optionLabel="descricao" dataKey="codigo" required :filter="true" />
          <label for="status" class="text-sm">ICMS Situação Tributária</label>
          </span>
          </div>

          <div>
          <span class="p-float-label">
          <Dropdown class="w-full" v-model="form.cofins_situacao_tributaria" :options="Cofins" optionLabel="descricao" dataKey="codigo" required :filter="true" />
          <label for="status" class="text-sm">Cofins Situação Tributária</label>
          </span>
          </div>

          <div>
          <span class="p-float-label">
          <Dropdown class="w-full" v-model="form.pis_situacao_tributaria" :options="Pis" optionLabel="descricao" dataKey="codigo" required :filter="true" />
          <label for="status" class="text-sm">PIS Situação Tributária</label>
          </span>
          </div>


          <span class="p-float-label">
          <InputText type="text" v-model="form.token_api" required />
          <label>Token API</label>
        </span>


        <div class='file-input border w-full p-1 text-sm lg:col-span-1'>
          <input @change="attachAvatar" type='file'>
          <span class='button text-gray-400'>Logo</span>
          <label class='label' data-js-label>{{ form.logo_path?.name || 'Nenhum Arquivo selecionado'}}</label>
        </div>
      

      

<hr class="my-5 col-span-4">
      <div class="flex flex-col space-y-1 col-span-4">
        <SectionTitle class="text-xs text-gray-600 font-bold uppercase">Endereço</SectionTitle>
      </div>
    
        <span class="p-float-label p-input-icon-right">
          <i class="pi pi-spin pi-spinner" v-show="searchingAddress" />
          <InputText @input="getAddress" :disabled="searchingAddress" type="text" v-maska="'##.###-###'" v-model="form.cep" />
          <label>CEP *</label>
        </span>
        <span class="p-float-label">
          <InputText type="text" :disabled="searchingAddress" v-model="form.logradouro" />
          <label>Logradouro</label>
        </span>
        <span class="p-float-label">
          <InputText type="tel" :disabled="searchingAddress" v-model="form.numero" />
          <label>Número</label>
        </span>
        <span class="p-float-label">
          <InputText type="text" :disabled="searchingAddress" v-model="form.complemento" />
          <label>Complemento</label>
        </span>
        <span class="p-float-label">
          <InputText type="text" :disabled="searchingAddress" v-model="form.bairro" />
          <label>Bairro</label>
        </span>
        <span class="p-float-label">
          <InputText type="text" :disabled="searchingAddress" v-model="form.cidade" />
          <label>Cidade</label>
        </span>
        <span class="p-float-label">
          <InputText type="text" :disabled="searchingAddress" v-model="form.estado" />
          <label>UF</label>
        </span>
      </div>
      <div class="flex space-x-5 mt-8">
        <button type="submit" :disabled="sending" class="p-2 flex rounded-md bg-primary text-white px-6 text-sm font-medium items-center" :class="{'bg-opacity-80 cursor-not-allowed': submited}">
          <svg role="status" v-show="submited" class="mr-2 w-4 h-4 animate-spin fill-primary" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
          </svg>
          Salvar
        </button>
        <Link :href="route('list.company')" as="button" type="button" class="p-2 rounded-md bg-secundary text-white px-6 text-sm font-medium">
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

const toast = useToast();

const props = defineProps({
  errorBags: Object,
  Origem: Object,
  Tributaria: Object,
  Cofins: Object,
  Pis: Object,
});

const Origem = ref(props.Origem);
const Tributaria = ref(props.Tributaria);
const Cofins = ref(props.Cofins);
const Pis = ref(props.Pis);

const sendable = ref(false);
const searchingAddress = ref(false);
const currentNav = ref(1);

const statusOption = [
  { name: "Ativo", value: '1' },
  { name: "Inativo", value: '0' },
];


const submited = ref(false);

const form = useForm({
  name: "",
  nome_fantasia: "",
  logo_path: "",
  cnpj: "",
  quality_responsable: "",
  status: "",
  insc_municipal: "",
  insc_estadual: "",
  token_api: "",
  icms_situacao_tributaria: "",
  icms_origem: "",
  pis_situacao_tributaria: "",
  cofins_situacao_tributaria: "",  
  cep: "",
  logradouro: "",
  complemento: "",
  numero: "",
  bairro: "",
  cidade: "",
  estado: "",
});

function validateForm() {
  if (!form.status) {
   throw new Error(
     toast.error(
       "O campo Status não pode ser enviado vazio."
     )
   );
  }
  if (!form.icms_situacao_tributaria) {
   throw new Error(
     toast.error(
       "O campo ICMS Situação Tributaria não pode ser enviado vazio."
     )
   );
  }
  if (!form.icms_origem) {
   throw new Error(
     toast.error(
       "O campo ICMS Origem não pode ser enviado vazio."
     )
   );
  }
  if (!form.pis_situacao_tributaria) {
   throw new Error(
     toast.error(
       "O campo Pis Situação Tributaria não pode ser enviado vazio."
     )
   );
  }

  if (!form.cofins_situacao_tributaria) {
   throw new Error(
     toast.error(
       "O campo Cofins Situacao Tributaria não pode ser enviado vazio."
     )
   );
  }

}

async function getAddress() {
  searchingAddress.value = true;

  let numberOnly = form.cep?.replace(/\D/g, "");

  if (numberOnly.length == 8) {
    const { data } = await axios.get(
      route("get.address.viacep", { cep: numberOnly })
    );

    if (data) {
      // Set Values
      form.logradouro = data.logradouro;
      form.bairro = data.bairro;
      form.cidade = data.localidade;
      form.estado = data.uf;
    }
  }
  searchingAddress.value = false;
}

function submit() {
  validateForm();
  submited.value = true;

  form.post(route("store.company"), {
    preserveState: true,
    // onError: () => {
    //   toast.error("Você não atendeu a todos os requisitos do formulário!");
    // },
    onSuccess: () => {
      toast.success("Salvo com sucesso!");
    },
    onFinish: () => (submited.value = false),
  });
}

function attachAvatar(e) {
  form.logo_path = e.target.files[0];
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