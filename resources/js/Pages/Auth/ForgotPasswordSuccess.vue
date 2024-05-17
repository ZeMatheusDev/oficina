<template>
  <form @submit.prevent="submit" class="h-screen flex body-background">
    <div class="bg-white w-[24rem] m-auto p-10 rounded-lg shadow-md flex flex-col justify-center">
      <div class="flex justify-center">
        <img draggable="false" src="/images/logo_completa.png" class="w-64" alt="" srcset="">
      </div>
      <div class="flex justify-center mt-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
      </div>
      <div class="flex justify-center text-center flex-col">
        <span class="font-bold">Email de recuperação enviado com sucesso!</span>
        <span class="mt-5 mb-8 text-smbg-proconph/10 text-proconph font-semibold rounded-md p-4">Você receberá um link de recuperação no e-mail inserido em até 10 minutos!</span>
      </div>
      <div class="space-y-5">
        <div class="flex justify-center items-center space-x-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
          </svg>
          <a :href="route('login')" class="text-proconph uppercase font-extrabold text-[12px]">Voltar para login</a>
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
import { ref, onUnmounted } from "vue";
import InputText from "primevue/inputtext";
import Password from "primevue/password";

const sending = ref(false);
const error = ref(false);

const form = useForm({
  email: "",
});

function submit() {
  sending.value = true;
  form.post(route("forgot.password.send"), {
    onSuccess: () => "",
    onFinish: () => (sending.value = false),
  });
}
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