<!-- AchievementCard.vue -->
<template>
  <div class="flex flex-wrap md:flex-nowrap justify-center items-center md:space-x-6">
    <!-- primeiro Card -->
    <div
      class="bg-white rounded-3xl shadow-2xl p-4 w-full md:w-1/4 md:h-64 lg:w-64 m-2 md:m-0"
    >
      <div class="flex flex-col items-center">
        <h2 class="font-bold text-xl mb-2 text-black">Estatísticas</h2>
        <div class="flex space-x-4 mb-4">
          <div class="flex flex-col items-center">
            <span class="font-bold text-2xl text-proconph">{{
              props.Registros.ativo || 0
            }}</span>
            <span class="text-sm text-gray-600">Ativos</span>
          </div>
          <div class="flex flex-col items-center">
            <span class="font-bold text-2xl text-proconph">{{
              props.Registros.inativo || 0
            }}</span>
            <span class="text-sm text-gray-600">Inativos</span>
          </div>
        </div>
        <div class="flex items-center mb-2">
          <div
            class="w-16 h-16 rounded-full bg-proconph text-white flex items-center justify-center"
          >
            <span class="font-bold text-xl">{{ props.Registros.total || 0 }}</span>
          </div>
          <span class="ml-3 font-bold text-proconph">Total</span>
        </div>
        <div class="w-full h-2 rounded-full bg-gray-300">
          <div class="h-2 bg-proconph" :style="{ width: porcentagemAtivos + '%' }"></div>
        </div>
        <p class="text-center text-xs mt-2">{{ porcentagemAtivos }}%</p>
      </div>
    </div>
    <!-- segundo Card -->
    <div
      class="bg-white rounded-3xl shadow-2xl p-4 w-full md:w-1/4 md:h-64 lg:w-64 m-2 md:m-0"
    >
      <div class="flex flex-col items-center">
        <div class="mb-2 bg-proconph text-white rounded-full p-4 shadow-lg">
          <img src="/images/profile_default.png" alt="Ícone de Meta" class="h-10 w-10" />
        </div>
        <div class="text-center mb-2">
          <h2 class="font-bold text-xl">Ativos</h2>
          <p class="text-sm">Total de registros ativos preenchidos</p>
        </div>
        <div
          class="w-full h-3 bg-gray-300 rounded-full mb-2"
          :title="porcentagemAtivos + '%'"
        >
          <div
            class="h-full bg-proconph rounded-full"
            :style="{ width: porcentagemAtivos + '%' }"
          ></div>
          <!-- Altere a largura em estilo para representar a progressão -->
        </div>
        <div class="flex items-center mb-1">
          <div
            class="w-10 h-10 rounded-full bg-proconph text-white flex items-center justify-center"
          >
            <span class="font-bold text-xl">{{ props.Registros.ativo || 0 }}</span>
          </div>
          <span class="ml-3 font-bold text-proconph">Ativos</span>
        </div>
      </div>
    </div>
    <!-- terceiro Card -->
    <div
      class="bg-white rounded-3xl shadow-2xl p-4 w-full md:w-1/4 md:h-64 lg:w-64 m-2 md:m-0"
    >
      <div class="flex flex-col items-center">
        <div class="mb-2 bg-proconph text-white rounded-full p-4 shadow-lg">
          <img src="/images/profile_default.png" alt="Ícone de Meta" class="h-10 w-10" />
        </div>
        <div class="text-center mb-2">
          <h2 class="font-bold text-xl">Inativos</h2>
          <p class="text-sm">Total de registros inativos preenchidos</p>
        </div>
        <div
          class="w-full h-3 bg-gray-300 rounded-full mb-2"
          :title="porcentagemInativos + '%'"
        >
          <div
            class="h-full bg-proconph rounded-full"
            :style="{ width: porcentagemInativos + '%' }"
          ></div>
          <!-- Altere a largura em estilo para representar a progressão -->
        </div>
        <div class="flex items-center mb-1">
          <div
            class="w-10 h-10 rounded-full bg-proconph text-white flex items-center justify-center"
          >
            <span class="font-bold text-xl">{{ props.Registros.inativo || 0 }}</span>
          </div>
          <span class="ml-3 font-bold text-proconph">Inativos</span>
        </div>
      </div>
    </div>
    <!-- quarto Card -->
    <div
      class="bg-white rounded-3xl shadow-2xl p-4 w-full md:w-1/4 md:h-64 lg:w-64 m-2 md:m-0"
    >
      <div class="flex flex-col items-center">
        <div
          class="flex items-center justify-center bg-proconph rounded-full h-16 w-16 mb-2"
        >
          <img src="/images/profile_default.png" alt="Ícone de Meta" class="h-10 w-10" />
        </div>
        <h2 class="font-bold text-xl mb-1 text-black">Esse mês</h2>
        <p class="text mb-2 text-center text-black">Total este mês</p>
        <div class="relative pt-1 w-full mb-2">
          <div class="flex mb-2 items-center justify-between">
            <div>
              <span class="text font-semibold inline-block text-proconph">Progresso</span>
            </div>
            <div class="text-right">
              <span class="text-xs font-semibold inline-block text-proconph"
                >{{ props.Registros.mes || 0 }}/{{ props.Registros.total || 0 }}</span
              >
            </div>
          </div>
          <div
            class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200"
            :title="porcentagemMes + '%'"
          >
            <div
              :style="{ width: porcentagemMes + '%' }"
              class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-proconph"
            ></div>
          </div>
        </div>
      </div>
    </div>
    <!-- Quinto Card -->
    <div
      class="bg-white rounded-3xl shadow-2xl p-4 w-full md:w-1/4 md:h-64 lg:w-64 m-2 md:m-0"
    >
      <div class="flex flex-col items-center">
        <div
          class="flex items-center justify-center bg-proconph rounded-full h-14 w-14 mb-1"
        >
          <img src="/images/profile_default.png" alt="Ícone de Meta" class="h-10 w-10" />
        </div>
        <h2 class="font-bold text-xl mb-1 text-black">Painel</h2>
        <Link
          v-if="props.RotaCadastro"
          as="button"
          :href="props.RotaCadastro"
          class="py-2 px-4 mb-1 bg-proconph hover:bg-secundary text-center text-white rounded-full text-sm font-bold w-full"
        >
          + Cadastro
        </Link>
        <Link
          as="button"
          :href="route('list.DashboardCalendario')"
          class="py-2 px-4 mb-1 bg-proconph hover:bg-secundary text-center text-white rounded-full text-sm font-bold w-full"
        >
          Calendário
        </Link>
        <a
          v-if="props.RotaExcel"
          :href="props.RotaExcel"
          target="_blank"
          class="py-2 px-4 mb-1 bg-proconph hover:bg-secundary text-center text-white rounded-full text-sm font-bold w-full"
        >
          Exportar
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "AchievementCard",
};
</script>
<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { ref, computed, defineProps, onMounted } from "vue";

const props = defineProps({
  token: Object,
  Registros: Object,
  RotaCadastro: Object,
  RotaExcel: Object,
});

const total = props.Registros.total || 1;
const ativos = props.Registros.ativo || 0;
const inativos = props.Registros.inativo || 0;
const mes = props.Registros.mes || 0;

let porcentagemAtivos = ((ativos * 100) / total).toFixed(2);
let porcentagemInativos = ((inativos * 100) / total).toFixed(2);
let porcentagemMes = ((mes * 100) / total).toFixed(2);

if (porcentagemAtivos > 100) {
  porcentagemAtivos = 100;
}

if (porcentagemInativos > 100) {
  porcentagemInativos = 100;
}
if (porcentagemMes > 100) {
  porcentagemMes = 100;
}
</script>
<style scoped>
/* Estilos opcionais adicionais para os cartões aqui */
</style>
