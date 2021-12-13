require('./vue-assets');
Vue.component('create-admin', require('./components/admin/admin/CreateAdmin.vue').default);
Vue.component('view-admin', require('./components/admin/admin/ViewAdmin.vue').default);

import VueLazyload from 'vue-lazyload';
 
Vue.use(VueLazyload,{

 loading: base_url+'images/loading.gif',

});

var app = new Vue({

    el: '#wrapper'
});