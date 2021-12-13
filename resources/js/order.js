require('./vue-assets');
Vue.component('view-order', require('./components/admin/order/ViewOrder.vue').default);
Vue.component('area-order', require('./components/admin/order/viewAreaOrder.vue').default);

import VueLazyload from 'vue-lazyload';

Vue.use(VueLazyload,{

 loading: base_url+'images/loading.gif',

});

var app = new Vue({

    el: '#wrapper'

});