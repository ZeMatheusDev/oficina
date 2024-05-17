<template>
  <div class="relative w-full ">
    <div class="w-full h-32">
      <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 p-4">
        <!-- Iterando por cada mês -->
        <div class="flex-grow bg-white p-4 rounded shadow">
          <!-- Parte superior -->
          <div class="flex flex-col sm:flex-row items-center justify-between space-y-2 sm:space-y-0 sm:space-x-4">
            <!-- Esquerda: Ícone e Semana -->
            <div class="flex items-center mb-4">
              <!--svg-->
              <svg width="16" height="16" viewBox="0 0 30 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M21.9839 1.3836L21.9855 2.57051C26.347 2.91234 29.2281 5.88435 29.2328 10.4421L29.25 23.7829C29.2562 28.7521 26.1344 31.8095 21.1304 31.8174L8.90715 31.8333C3.93439 31.8396 0.77346 28.7093 0.767206 23.7259L0.750011 10.5418C0.743757 5.95398 3.52325 2.98988 7.88477 2.5895L7.88321 1.40259C7.88164 0.706276 8.39752 0.182455 9.08536 0.182455C9.7732 0.180872 10.2891 0.703111 10.2906 1.39943L10.2922 2.50721L19.578 2.49455L19.5765 1.38677C19.5749 0.69045 20.0908 0.168212 20.7786 0.16663C21.4508 0.165047 21.9823 0.687285 21.9839 1.3836ZM3.15901 11.0308L26.8269 10.9991V10.4452C26.7597 7.04277 25.0526 5.25766 21.9886 4.9918L21.9902 6.21035C21.9902 6.89085 21.4602 7.43049 20.788 7.43049C20.1002 7.43208 19.5827 6.89401 19.5827 6.21352L19.5812 4.93166L10.2953 4.94432L10.2969 6.2246C10.2969 6.90667 9.78258 7.44474 9.09474 7.44474C8.40691 7.44632 7.88946 6.90984 7.88946 6.22776L7.8879 5.00921C4.83953 5.31464 3.15276 7.10607 3.15745 10.5386L3.15901 11.0308ZM20.1299 18.2234V18.2408C20.1455 18.9688 20.7395 19.5211 21.4602 19.5053C22.1637 19.4879 22.7249 18.8849 22.7093 18.157C22.6764 17.4606 22.1121 16.8925 21.4102 16.8941C20.6911 16.9099 20.1283 17.4955 20.1299 18.2234ZM21.4211 25.329C20.702 25.3132 20.122 24.7134 20.1205 23.9855C20.1049 23.2575 20.6817 22.6545 21.4008 22.6371H21.4164C22.1512 22.6371 22.7468 23.2369 22.7468 23.9807C22.7483 24.7245 22.1543 25.3274 21.4211 25.329ZM13.6892 18.2487C13.7205 18.9767 14.3161 19.5448 15.0352 19.5132C15.7386 19.48 16.2999 18.8786 16.2686 18.1506C16.2514 17.4385 15.673 16.8846 14.9695 16.8862C14.2504 16.9178 13.6876 17.5208 13.6892 18.2487ZM15.0414 25.2578C14.3223 25.2895 13.7283 24.7213 13.6954 23.9934C13.6954 23.2654 14.2567 22.664 14.9758 22.6308C15.6792 22.6292 16.2592 23.1831 16.2748 23.8937C16.3077 24.6232 15.7449 25.2246 15.0414 25.2578ZM7.24852 18.3041C7.27979 19.0321 7.8754 19.6018 8.5945 19.5686C9.29797 19.5369 9.85918 18.934 9.82635 18.206C9.81072 17.4939 9.23231 16.94 8.52728 16.9416C7.80818 16.9732 7.24696 17.5762 7.24852 18.3041ZM8.60075 25.2657C7.88165 25.299 7.28761 24.7292 7.25478 24.0013C7.25321 23.2733 7.81599 22.6704 8.53509 22.6387C9.23857 22.6371 9.81854 23.191 9.83417 23.9032C9.867 24.6311 9.30579 25.2341 8.60075 25.2657Z"
                  fill="#793f98" />
              </svg>
              <span class="ml-2 font-bold text-sm text-MRVverde">Mês {{ props.mes }} - <b v-if="props.DiasRestantes >= 0"> {{ props.DiasRestantes }} dias para o encerramento </b> 
               <b v-if="props.DiasRestantes < 0">Prazo Encerrado </b>
               </span>
            </div>

            <!-- Centro: Título e Imagem -->
            <div class="flex items-center text-MRVverde">
              <span class="text-lg font-bold mb-2 md:mt-5">PROGRESSO</span>
              <img src="/images/MRV Start svg 2.png" alt="your image" class="ml-4 h-12 w-25 mb-4 md:mb-4">
            </div>
          </div>

          <!-- Barra de progresso para o mês específico -->
          <div class="bg-gray-200 h-3 rounded-lg relative mt-2">
            <!-- Adicione aqui a lógica de progresso para cada mês -->
            <div :ref="'progressBar' + month" class="h-3 bg-orange-500 rounded-lg" :style="'width:' +props.Porcentagem+'%'">
              <span :ref="'progressPercent' + month"
                class="text-MRVverde font-bold text-sm absolute transform -translate-y-full" style="top: -5px;">{{
                  progressPercent }}%</span>                 
            </div>


          
           
          </div>
        </div>
      </div>

      
    </div>
  </div>
</template>




<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { ref, computed, defineProps, reactive, onMounted } from "vue";

const props = defineProps({
  Porcentagem: Object,
  DiasRestantes: Object,
  FaseRespectiva: Object,
  PontosTotais: Object,
  PontosConquistados: Object,
  PontosFaltantes: Object,
  mes: Object,
});

const showTooltip1 = ref(false);
const showTooltip2 = ref(false);
const showTooltip3 = ref(false);

const isDropdownVisible = ref(false);

const progressPercent = ref(props.Porcentagem);
const currentSeason = ref(props.FaseRespectiva);
const seasonLength = 30;
const colorChanged = ref(false);
const progressBar = ref(props.Porcentagem); // Criar a referência para a barra de progresso

const toggleDropdown = () => {
  isDropdownVisible.value = !isDropdownVisible.value;
};

const updateProgressBar = () => {
  const progressPercent = ref(props.Porcentagem);
  let percentOfSeason;


  if (!progressBar.value) {
    progressBar.value = document.querySelector('.progress-bar');
    if (!progressBar.value) return; // Se ainda não conseguiu encontrar o elemento, retorna
  }

  if (progressPercent.value >= 60 && !colorChanged.value) {
    progressBar.value.classList.remove('bg-orange-500');
    progressBar.value.classList.add('bg-primary');
    colorChanged.value = true;
  }




};

onMounted(() => {
  updateProgressBar(); // Atualiza a barra de progresso com os dados iniciais
});

</script>







  
  