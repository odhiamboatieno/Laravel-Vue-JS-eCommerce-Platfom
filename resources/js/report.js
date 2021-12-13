require('./vue-assets');
// Vue.component('create-product', require('./components/admin/product/CreateProduct.vue').default);
Vue.component('view-stockreport', require('./components/admin/report/stockreport.vue').default);
Vue.component('view-salesreport', require('./components/admin/report/salesreport.vue').default);
Vue.component('view-invoicereport', require('./components/admin/report/invoicereport.vue').default);
Vue.component('view-accountreport', require('./components/admin/report/accountreport.vue').default);

import 'v2-datepicker/lib/index.css';   // v2 need to improt css
import V2Datepicker from 'v2-datepicker';
Vue.use(V2Datepicker)
import VueLazyload from 'vue-lazyload';
 
Vue.use(VueLazyload,{

 loading: base_url+'images/loading.gif',

});

var app = new Vue({

    el: '#wrapper'
});
