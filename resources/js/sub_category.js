require('./vue-assets');
Vue.component('create-subcategory', require('./components/admin/subcategory/CreateSubCategory.vue').default);
Vue.component('view-subcategory', require('./components/admin/subcategory/ViewSubCategory.vue').default);

import VueLazyload from 'vue-lazyload';
 
Vue.use(VueLazyload,{

 loading: base_url+'images/loading.gif',

});

var app = new Vue({

    el: '#wrapper'
});


