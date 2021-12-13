require('./vue-assets');
Vue.component('create-product', require('./components/admin/product/CreateProduct.vue').default);
Vue.component('view-product', require('./components/admin/product/ViewProduct.vue').default);

Vue.component('create-color', require('./components/admin/product/color/CreateColor.vue').default);
Vue.component('view-color', require('./components/admin/product/color/ViewColor.vue').default);

Vue.component('create-size', require('./components/admin/product/size/CreateSize.vue').default);
Vue.component('view-size', require('./components/admin/product/size/ViewSize.vue').default);

import VueLazyload from 'vue-lazyload';
 
Vue.use(VueLazyload,{

 loading: base_url+'images/loading.gif',

});

var app = new Vue({

    el: '#wrapper'
});


