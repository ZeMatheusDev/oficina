require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import PrimeVue from 'primevue/config';
import 'primevue/resources/themes/md-light-indigo/theme.css'
import 'primevue/resources/primevue.min.css'
import Toast, { POSITION } from "vue-toastification";
import "vue-toastification/dist/index.css";
import PageTitle from './Components/PageTitle.vue'
import Maska from 'maska'
import { Link } from '@inertiajs/inertia-vue3'
import Layout from './Layouts/App.vue'
import { usePage } from '@inertiajs/inertia-vue3'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: name => {
        const page = require(`./Pages/${name}`).default
        page.layout = page.layout || Layout
        return page
    },
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(Maska)
            .use(Toast, {
                position: POSITION.BOTTOM_RIGHT,
            })
            .use(PrimeVue, {
                locale: {
                    startsWith: 'Começa com',
                    contains: 'Contém',
                    notContains: 'Não contém',
                    endsWith: 'Termina com',
                    equals: 'É igual a',
                    notEquals: 'Não é igual a',
                    noFilter: 'Sem filtro',
                    lt: 'Menor que',
                    lte: 'Menor que ou igual a',
                    gt: 'Maior que',
                    gte: 'Melhor que ou igual a',
                    dateIs: 'Data é',
                    dateIsNot: 'Data não é',
                    dateBefore: 'A data é antes',
                    dateAfter: 'A data é depois',
                    clear: 'Limpar',
                    apply: 'Aplicar',
                    matchAll: 'Combinar tudo',
                    matchAny: 'Corresponder a qualquer um',
                    addRule: 'Adicionar Regra',
                    removeRule: 'Remover Regra',
                    accept: 'Sim',
                    reject: 'Não',
                    choose: 'Escolher',
                    upload: 'Upload',
                    cancel: 'Cancelar',
                    dayNames: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
                    dayNamesShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
                    dayNamesMin: ["Se", "Te", "Qa", "Qi", "Se", "Sa", "Do"],
                    monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                    monthNamesShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                    today: 'Hoje',
                    weekHeader: 'CS',
                    firstDayOfWeek: 0,
                    dateFormat: 'dd/mm/yyyy',
                    weak: 'Fraca',
                    medium: 'Média',
                    strong: 'Forte',
                    passwordPrompt: 'Insira uma senha',
                    emptyFilterMessage: 'Nenhum resultado encontrado',
                    emptyMessage: 'Não há opções disponíveis'
                }
            })
            .mixin({ components: { PageTitle, Link } })
            .mixin({ methods: { route } })
            .mount(el);
    },
});


window.getParams = function getParams(param) {
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);

    if (params.has(param)) {
        return params.get(param)
    }

    return null
}

window.$propsPage = usePage().props



Array.prototype.remove = function () {
    var what,
        a = arguments,
        L = a.length,
        ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};



InertiaProgress.init({ color: '#4B5563' });
