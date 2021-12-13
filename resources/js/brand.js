require('./vue-assets');
Vue.component('create-brand', require('./components/admin/brand/CreateBrand.vue').default);
Vue.component('view-brand', require('./components/admin/brand/ViewBrand.vue').default);

import VueLazyload from 'vue-lazyload';
 
Vue.use(VueLazyload,{

 loading: base_url+'images/loading.gif',

});

var app = new Vue({

    el: '#wrapper'
});


