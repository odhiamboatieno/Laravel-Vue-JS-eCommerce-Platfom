require('./vue-assets');
Vue.component('create-role', require('./components/admin/role/CreateRole.vue').default);
Vue.component('view-role', require('./components/admin/role/ViewRole.vue').default);


var app = new Vue({

    el: '#wrapper'
});


