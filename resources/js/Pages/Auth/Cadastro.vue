<template>
  <form @submit.prevent="submit" class="h-screen flex flex-col md:flex-row body-background">
    <div class="flex flex-col md:flex-row w-full h-full">
      <!-- Lado Esquerdo -->
      <div class="hidden bg-white flex-col justify-center items-center border-r text-center ">
        <img src="/images/background.jpg" style="width:100%">
      </div>
      <!-- Lado Direito -->
      <div class="fixed inset-0 md:static md:w-1/3 bg-white p-4 md:p-8 rounded-lg shadow-md flex flex-col items-center justify-center" style=" height: 163.5vh; width: 1200px; position: fixed; top: 0; left: 0;   overflow: hidden;">
        <!-- logo -->
        <div class=" mb-8 md:mb-20 w-full flex justify-center">
          <img draggable="false" style="width: 300px; height: 300px; margin-top: -450px;" src="/images/logo_menu.png" class="w-1/2 md:w-60" alt="">
        </div>
        <!--inputs-->
        <div class="btn btn-danger">Preencha todos os campos</div>
        <div class="space-y-5 mb-12 md:mb-60 w-full">    
          <div class="space-y-5 mb-12 md:mb-60 w-full">    
          <div class="w-4/5 md:w-2/3 mx-auto">    
            <span class="p-float-label">
              <InputText type="email" v-model="form.email" autocomplete="off" maxlength="35" required/>
              <label>E-mail</label>
            </span>
            <br>
            <div class="p-field p-col-12 p-md-6">
              <span class="p-float-label w-full">
                <Password v-model="form.password" :feedback="false" class="w-full" maxlength="35" toggleMask autocomplete="off" required/>
                <label for="">Senha</label>
              </span>
            </div>
            <br>
            <div class="p-field p-col-12 p-md-6">
              <span class="p-float-label w-full">
                <InputText required v-model="form.nome" :feedback="false" class="w-full" maxlength="35" toggleMask autocomplete="off"/>
                <label for="">Seu nome</label>
              </span>
            </div>
            <br>
            <div class="p-field p-col-12 p-md-6">
            <span class="p-float-label w-full">
              <input type="text" v-model="form.numero" @input="validateNumero" maxlength="15" class="w-full" toggleMask autocomplete="off" style="font-family: Arial, Helvetica, sans-serif;" placeholder="Seu numero" required>
            </span>
          </div>
          </div>
          <!-- esqueceu a senha-->

          <!-- botão entrar-->
          <div class="w-4/5 md:w-2/3 mx-auto flex flex-col justify-between h-full">
        
            <button type="submit"
              class="bg-primary text-white rounded-md py-4 w-full text-sm flex items-center justify-center hover:bg-MIGamarelo "
              :class="{ 'bg-opacity-70 cursor-wait': sending }">
              <svg v-if="sending" role="status"
                class="w-4 h-4 mr-2 text-gray-200 animate-spin dark:text-gray-200 fill-blue-600" viewBox="0 0 100 101"
                fill="none" xmlns="http://www.w3.org/2000/svg">
              </svg>
              <span>Cadastrar</span>
            </button>
          </div>
        </div>

        


          <!-- botão entrar-->
          <div class="w-4/5 md:w-2/3 mx-auto flex flex-col justify-between h-full">
        
            
          </div>
        </div>
        <div class="flex justify-center mt-4">
 
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
  nome: "",
  numero: "",
});


const validateNumero = () => {
  let value = form.numero.replace(/\D/g, '');
  if (value.length > 2) {
    value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
  }
  if (value.length > 10) {
    value = `${value.slice(0, 10)}-${value.slice(10)}`;
  }
  form.numero = value;
};

function submit() {
  sending.value = true;
  

  if(form.email && form.password && form.nome && form.numero){

form.post(route("cadastrar"), {
  onSuccess: () => (window.location.href = "/login"),
  onFinish: () => (sending.value = false),
});
} else {
  sending.value = false
  window.alert('Preencha todos os campos.');
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


