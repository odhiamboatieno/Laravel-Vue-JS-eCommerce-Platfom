require('./vue-assets');

// start vuex

import Vuex from 'vuex'
Vue.use(Vuex)
import storeData from "./store/index"
const store = new Vuex.Store(
    storeData
)

// end vuex 
// Vue.component('home-category', require('./components/front/category/HomeCategory.vue').default);
Vue.component('category-subcategory', require('./components/front/category/CategorySubCategory.vue').default);
Vue.component('category-product', require('./components/front/category/CategoryProduct.vue').default);
Vue.component('home-offers', require('./components/front/offer/HomeOffer.vue').default);
Vue.component('hot-deal', require('./components/front/product/HotDeal.vue').default);
Vue.component('on-sale-product', require('./components/front/product/onSaleProduct.vue').default);
// cart
Vue.component('cart', require('./components/front/cart/Cart.vue').default);
Vue.component('checkout-cart', require('./components/front/cart/CheckoutCart.vue').default);

Vue.component('search-box', require('./components/front/search/SearchBox.vue').default);
Vue.component('search-product', require('./components/front/product/SearchProduct.vue').default);

Vue.component('user-subscribe', require('./components/front/subscribe/Subscribe.vue').default);
Vue.component('all-offers', require('./components/front/offer/AllOffer.vue').default);
Vue.component('offers-product', require('./components/front/offer/OfferProduct.vue').default);
Vue.component('level-three-category', require('./components/front/category/LevelThreeCategory.vue').default);
Vue.component('sub-category-product', require('./components/front/product/SubCategoryProduct.vue').default);
Vue.component('sub-sub-category-product', require('./components/front/product/SubSubCategoryProduct.vue').default);
Vue.component('product-details', require('./components/front/product/ProductDetails.vue').default);


// verification 

Vue.component('verification', require('./components/front/setting/Verification.vue').default);

// profile 
Vue.component('order-history', require('./components/front/user/Orders.vue').default);
Vue.component('order-tracking', require('./components/front/user/OrderTrack.vue').default);

Vue.component('profile-update', require('./components/front/profile/ProfileUpdate.vue').default);
Vue.component('user-dashboard', require('./components/front/profile/UserDashboard.vue').default);

// flutter wave payment 

Vue.component('flutter-wave-payment', require('./components/front/payment/FlutterWavePayment.vue').default);

import VueLazyload from 'vue-lazyload';

Vue.use(VueLazyload, {

    loading: base_url + 'images/loading.gif',

});

var app = new Vue({

    el: '#front-wrapper',
    store
});