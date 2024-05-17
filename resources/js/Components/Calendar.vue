<script setup>
import "@fullcalendar/core/vdom"; // solves problem with Vite
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridWeek from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";

const calendarOptions = {
  locale: "br",
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
};
</script>
<template>
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