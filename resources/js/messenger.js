require('./vue-assets');
Vue.component('messenger-setting', require('./components/admin/setting/messenger/ViewMessenger.vue').default);

var app = new Vue({
    el: '#wrapper'
});