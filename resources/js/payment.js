require('./vue-assets');
Vue.component('view-payment', require('./components/admin/setting/payment/ViewPayment.vue').default);


var app = new Vue({

    el: '#wrapper'
});


