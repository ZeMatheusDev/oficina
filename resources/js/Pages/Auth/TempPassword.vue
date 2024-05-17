<template>
  <form @submit.prevent="submit" class="h-screen flex body-background">
    <div class="bg-white w-[24rem] m-auto p-10 rounded-lg shadow-md flex flex-col justify-center">
      <div class="flex justify-center">
        <img draggable="false" src="/images/logo_completa.png" class="w-64" alt="" srcset="">
      </div>
      <div class="mb-4 mt-6 flex flex-col text-center space-y-2">
        <span class="font-bold">Criar uma Password de acesso</span>
        <span class=" text-xs">Bem vindo(a)! Para ter acesso ao sistema, insira uma Password segura e memorável!</span>
      </div>
      <div class="space-y-5">
        <span class="p-float-label w-full">
          <Password v-model="form.password" :class="{'p-invalid': errorBags?.default?.password?.[0] }" class="w-full" toggleMask>
            <template #footer="sp">
              {{sp.level}}
              <Divider />
              <p class="mt-2">Sugestões</p>
              <ul class="pl-2 ml-2 mt-0 list-disc" style="line-height: 1.5">
                <li>Ao menos uma letra minúscula</li>
                <li>Ao menos uma letra maiúscula</li>
                <li>Ao menos um número</li>
                <li>8 caracteres, no mínimo.</li>
              </ul>
            </template>
          </Password>
          <label for="">Insira sua nova Password</label>
        </span>
        <small id="username2-help" class="p-error">{{errorBags?.default?.password?.[0] }}</small>
        <div class="p-field p-col-12 p-md-4">
          <span class="p-float-label w-full">
            <Password required v-model="form.password_confirmation" :feedback="false" class="w-full" toggleMask />
            <label for="">Confirme sua nova Password</label>
          </span>
        </div>
        <span class="text-xs text-center flex justify-center bg-red-500 rounded-md p-3 text-white" v-if="$page.props.errorBags?.default">
          {{$page.props.errorBags?.default?.[0]?.[0]}}
        </span>
        <div class="">
          <button type="submit" class="bg-proconph text-white rounded-md py-3 w-full text-sm flex items-center justify-center" :class="{'bg-opacity-70 cursor-wait': sending}">
            <svg v-if="sending" role="status" class="w-4 h-4 mr-2 text-gray-200 animate-spin dark:text-gray-200 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor" />
              <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill" />
            </svg>
            <span>Send</span>
          </button>
        </div>

        <div class="flex justify-center">
          <a href="#" class="text-proconph uppercase font-extrabold text-[12px]">ESQUECEU SUA SENHA?</a>
        </div>
        <div class="flex justify-center">
          <span class="text-gray-400 text-[10px]">Raio X Gestão Empresarial - All rights reserved ©</span>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
import Layout from "../../Layouts/Auth.vue";
export default {
  layout: [Layout],
};
</script>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { useToast } from "vue-toastification";
import { ref, onUnmounted } from "vue";
import InputText from "primevue/inputtext";
import Password from "primevue/password";

const toast = useToast();
const sending = ref(false);
const error = ref(false);

const form = useForm({
  password: "",
  password_confirmation: "",
});

function submit() {
  sending.value = true;

  form.post(route("send.temp.password"), {
    onSuccess: () => (window.location.href = "/index"),
    onFinish: () => (sending.value = false),
  });
}

onUnmounted(() => {
  let body = document.querySelector(".body-background");
  body.classList.remove(".body-background");
});
</script>

<style>
.body-background {
  background-image: url("/images/background.jpg");
  background-repeat: no-repeat;
  background-size: 100% 100%;
  background-size: cover;
  background-attachment: fixed;
  background-position: center;
}
</style>