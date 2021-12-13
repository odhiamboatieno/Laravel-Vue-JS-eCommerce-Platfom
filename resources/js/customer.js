require('./vue-assets');
// Vue.component('create-brand', require('./components/admin/customers/Createcustomer.vue').default);
Vue.component('view-customer', require('./components/admin/customers/ViewCustomer.vue').default);

import VueLazyload from 'vue-lazyload';


Vue.use(VueLazyload,{

 loading: base_url+'images/loading.gif',

});

var app = new Vue({

    el: '#wrapper'
});


