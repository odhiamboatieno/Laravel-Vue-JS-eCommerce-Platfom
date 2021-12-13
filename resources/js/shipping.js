require('./vue-assets');
Vue.component('shipping-setting', require('./components/admin/setting/shipping/shippingSetting.vue').default);
Vue.component('shipping-area', require('./components/admin/setting/shipping/ShippingArea.vue').default);
Vue.component('create-area', require('./components/admin/setting/shipping/CreateArea.vue').default);

var app = new Vue({

    el: '#wrapper'
});