require('./vue-assets');
Vue.component('create-campaign', require('./components/admin/offers/campaign/CreateCampaign.vue').default);
Vue.component('view-campaign', require('./components/admin/offers/campaign/ViewCampaign.vue').default);

// Slider
Vue.component('create-slider', require('./components/admin/offers/slider/CreateSlider.vue').default);
Vue.component('view-slider', require('./components/admin/offers/slider/ViewSlider.vue').default);


// Coupon
Vue.component('create-coupon', require('./components/admin/offers/coupon/CreateCoupon.vue').default);
Vue.component('view-coupon', require('./components/admin/offers/coupon/ViewCoupon.vue').default);

// Customer Coupon
Vue.component('create-customer-coupon', require('./components/admin/offers/customer_coupon/CreateCustomerCoupon.vue').default);
Vue.component('view-customer-coupon', require('./components/admin/offers/customer_coupon/ViewCustomerCoupon.vue').default);

import 'v2-datepicker/lib/index.css';   // v2 need to improt css
import V2Datepicker from 'v2-datepicker';
Vue.use(V2Datepicker)
import VueLazyload from 'vue-lazyload';
 
Vue.use(VueLazyload,{

 loading: base_url+'images/loading.gif',

});

var app = new Vue({

    el: '#wrapper'
});


