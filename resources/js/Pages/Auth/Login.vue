<template>
  <form @submit.prevent="submit" class="h-screen flex flex-col md:flex-row body-background">
    <div class="flex flex-col md:flex-row w-full h-full">
      <!-- Lado Esquerdo -->
      <div class="hidden bg-white flex-col justify-center items-center border-r text-center ">
        <img src="/images/background.jpg" style="width:100%">
      </div>
      <!-- Lado Direito -->
      <div class="fixed inset-0 md:static md:w-1/3 bg-white p-4 md:p-8 rounded-lg shadow-md flex flex-col items-center justify-center" style=" height: 163.5vh; position: fixed; top: 0; left: 0;   overflow: hidden;
">
        <!-- logo -->
        <div class=" mb-8 md:mb-20 w-full flex justify-center">
          <img draggable="false" style="width: 300px; height: 300px; margin-left: -20px" src="/images/logo_menu.png" class="w-1/2 md:w-60" alt="">
        </div>
        <!--inputs-->
        <div class="space-y-5 mb-12 md:mb-60 w-full">    
          <div class="w-4/5 md:w-2/3 mx-auto">    
            <span class="p-float-label">
              <InputText type="text" v-model="form.email" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" required/>
              <label>E-mail *</label>
            </span>
            <br>
            <div class="p-field p-col-12 p-md-6">
              <span class="p-float-label w-full">
                <Password v-model="form.password" :feedback="false" class="w-full" toggleMask autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" required/>
                <label for="">Senha</label>
              </span>
            </div>
          </div>
          <!-- esqueceu a senha-->
          <div class="text-center">
            <a :href="route('forgot.password')"
              class="text-primary uppercase font-extrabold text-[12px] block">ESQUECEU SUA SENHA?</a>
          </div>
          
            <span class="text-xs text-center flex justify-center bg-red-500 rounded-md p-3 text-white" v-if="$page.props.errorBags?.default">
          Dados incorretos, favor verificar.
          </span>
          <!-- botão entrar-->
          <div class="w-4/5 md:w-2/3 mx-auto flex flex-col justify-between h-full">
        
            <button type="submit"
              class="bg-primary text-white rounded-md py-4 w-full text-sm flex items-center justify-center hover:bg-MIGamarelo "
              :class="{ 'bg-opacity-70 cursor-wait': sending }">
              <svg v-if="sending" role="status"
                class="w-4 h-4 mr-2 text-gray-200 animate-spin dark:text-gray-200 fill-blue-600" viewBox="0 0 100 101"
                fill="none" xmlns="http://www.w3.org/2000/svg">
              </svg>
              <span>Entrar</span>
            </button>
          </div>
        </div>
        <div class="flex justify-center mt-4">
          <span class="text-gray-400 text-[10px]"> &copy; 2023 - Projeto Estagio ProConph | Todos os direitos reservados. | Política de
            Privacidade ©</span>
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
  email: "",
  password: "",
});

function submit() {
  sending.value = true;
  if(form.email && form.password){

  form.post(route("action.login"), {
    onSuccess: () => (window.location.href = "/index"),
    onFinish: () => (sending.value = false),
  });
  } else {
    sending.value = false
  }
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
  background-size: 100% 100% 100% 100%;
  background-size: cover;
  background-attachment: fixed;
  background-position: center;
  height: 163.5vh;
}

</style>


