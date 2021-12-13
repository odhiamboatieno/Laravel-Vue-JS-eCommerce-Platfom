require('./vue-assets');
Vue.component('create-subcategory', require('./components/admin/subsubcategory/CreateSubSubCategory.vue').default);
Vue.component('view-subcategory', require('./components/admin/subsubcategory/ViewSubSubCategory.vue').default);

import VueLazyload from 'vue-lazyload';
 
Vue.use(VueLazyload,{

 loading: base_url+'images/loading.gif',

});

var app = new Vue({

    el: '#wrapper'
});


