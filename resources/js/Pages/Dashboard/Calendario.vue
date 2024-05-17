<script setup>
import "@fullcalendar/core/vdom"; // solves problem with Vite
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridWeek from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import { Link } from '@inertiajs/inertia-vue3'
import moment from "moment";
import { ref, computed, defineProps, onMounted } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import Password from "primevue/password";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Dropdown from "primevue/dropdown";
import { useToast } from "vue-toastification";
import { Inertia } from '@inertiajs/inertia';
const props = defineProps({
    Solicitations: Object
});
const calendarOptions = {
  locale: "BR",
  headerToolbar: {
    left: "prev,next today",
    center: "title",
    right: "dayGridMonth,timeGridWeek,timeGridDay",
  },
  buttonText: {
    today: "Hoje",
    month: "Mês",
    week: "Semana",
    day: "Dia",
    list: "Agenda",
    allDayText: "Todo o dia",
  },
  aspectRatio: 2.1,
  editable: true,
  selectable: true,
  selectMirror: true,
  dayMaxEvents: true,
  plugins: [dayGridPlugin, timeGridWeek, interactionPlugin],
  initialView: "dayGridMonth",
  //select: this.handleDateSelect,

 //eventClick: this.handleEventClick(),
  events: props.Solicitations,
};

// function handleDateSelect(selectInfo: DateSelectArg) {
//     window.location.href = clickInfo.event.url;
//     }
</script>
<template>	<Breadcrumb>
    <nav class="py-3 rounded-md w-full -mt-6">
      <ol class="list-reset flex items-center text-sm">
      <li><Link :href="route('home')">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
        </Link>
        </li>
        <li><span class="text-gray-500 mx-2">/</span></li>
        <li class="text-gray-500">Calendário</li>
      </ol>
    </nav>
  </Breadcrumb>
  <FullCalendar :options="calendarOptions" />
</template>

<style scoped>
@media screen and (max-width: 960px) {
  ::v-deep(.fc-header-toolbar) {
    display: flex;
    flex-wrap: wrap;
  }
}
</style>