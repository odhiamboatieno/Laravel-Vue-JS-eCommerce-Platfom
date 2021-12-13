require('./vue-assets');
Vue.component('create-page', require('./components/admin/setting/pages/CreatePage.vue').default);
Vue.component('view-page', require('./components/admin/setting/pages/ViewPage.vue').default);

var app = new Vue({
    el: '#wrapper'
});