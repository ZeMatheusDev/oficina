<!-- src/components/ProgressBarDropdown.vue -->
<template>
    <div class="relative w-full" >
        <div class="w-full bg-white ">
            <!-- Parte superior -->
            <center>
             <div class="items-center text-MRVverde" >
                    <b>{{props.Codigo}} - {{props.Nome}}</b>
                </div>
            </center>
          
            <!-- Barra de progresso -->
            <div class="flex items-center justify-between px-4 mb-4">
                <!-- Ícone de estrela à esquerda -->
              
                <!-- barra de progresso-->
                <div class="flex-grow bg-gray-200 h-3 rounded-lg relative">
                    <!-- season dividers -->
                    <div v-if="currentSeason <= 1" style="left: 33.2%" class="w-2 h-3 absolute bg-secundary" title="Inicio do Mês 2"></div>
                    <div v-if="currentSeason <= 2" style="left: 66.4%" class="w-2 h-3 absolute bg-secundary"  title="Inicio do Mês 3"></div>
                    <!-- progress bar -->
                    <div ref="progressBar" class="h-3 bg-orange-500 rounded-lg" style="width: 0%">
                        <span ref="progressPercent2"
                            class=" text-MRVverde font-bold text-sm absolute transform -translate-y-full"
                            style="top: -5px;">{{ progressPercent }}%</span>
                        <!--conectar o span com o banco de dados-->
                    </div>
                </div>

            </div>
            <center>
             <div class="items-center text-MRVamareloClaro -mt-4" style="font-size: 10pt">
                  Pontos Liberados: {{props.PontosTotais}} |   Pontos Conquistados: {{props.PontosConquistados}} |   Pontos Pendentes: {{props.PontosFaltantes}} 
                </div>
            </center>
         
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
    Nome: Object,
     Codigo: Object,
  });

  const showTooltip1 = ref(false);
  const showTooltip2 = ref(false);
  const showTooltip3 = ref(false);

  const isDropdownVisible = ref(false);
  const dropdownItems = ref([
    { week: 4, points: '16.000', progress: 80 },
    { week: 3, points: '14.000', progress: 60 },
    { week: 2, points: '10.000', progress: 40 },
    { week: 1, points: '5.000', progress: 20 },
  ]);
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
    const seasonDivisions = [33.2, 66.4, 100];
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

    switch (currentSeason.value) {
      case 1:
        percentOfSeason = (progressPercent.value / 100) * seasonDivisions[0];
        break;
      case 2:
        percentOfSeason = (progressPercent.value / 100) * seasonDivisions[1];
        break;
      case 3:
        percentOfSeason = (progressPercent.value / 100) * seasonDivisions[2];
        break;
      default:
        console.error('Season not recognized');
        return;
    }


    // Atualiza a largura da barra de progresso
    progressBar.value.style.width = `${percentOfSeason}%`;
    

    
  };

  onMounted(() => {
    updateProgressBar(); // Atualiza a barra de progresso com os dados iniciais
  });

</script>







  
  