require('./vue-assets');
Vue.component('create-currency', require('./components/admin/setting/currency/CreateCurrency.vue').default);
Vue.component('view-currency', require('./components/admin/setting/currency/ViewCurrency.vue').default);

import VueLazyload from 'vue-lazyload';
 
Vue.use(VueLazyload,{

 loading: base_url+'images/loading.gif',

});

var app = new Vue({

    el: '#wrapper'
});


