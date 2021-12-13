require('./vue-assets');
Vue.component('create-category', require('./components/admin/category/CreateCategory.vue').default);
Vue.component('view-category', require('./components/admin/category/ViewCategory.vue').default);

import VueLazyload from 'vue-lazyload';
 
Vue.use(VueLazyload,{

 loading: base_url+'images/loading.gif',

});

var app = new Vue({

    el: '#wrapper'
});