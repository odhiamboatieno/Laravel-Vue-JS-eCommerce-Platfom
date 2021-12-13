require('./vue-assets');
Vue.component('dashboard-summary', require('./components/admin/dashboard/dashboard.vue').default);
Vue.component('chart-summary', require('./components/admin/dashboard/chart.vue').default);
// Vue.component('dash-check', require('./components/admin/dashboard/CheckDash.vue').default)

import VueLazyload from 'vue-lazyload';

Vue.use(VueLazyload, {

    loading: base_url + 'images/loading.gif',

});

var app = new Vue({

    el: '#wrapper'
});