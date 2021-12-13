require('./vue-assets');
Vue.component('seo-setting', require('./components/admin/setting/seo/SeoSetting.vue').default);
Vue.component('shop-setting', require('./components/admin/setting/shop/ShopSetting.vue').default);

Vue.component('trial-setting', require('./components/admin/setting/trial/TrialSetting.vue').default);

Vue.component('pwa-setting', require('./components/admin/setting/pwa/PwaSetting.vue').default);

// delivery date time slot setting 
Vue.component('date-slot-setting', require('./components/admin/setting/slot/DeliveryDateSlot.vue').default);
Vue.component('create-time-slot', require('./components/admin/setting/slot/CreateTimeSlot.vue').default);
Vue.component('view-time-slot', require('./components/admin/setting/slot/ViewTimeSlot.vue').default);


var app = new Vue({
    el: '#wrapper'
});


