require('./vue-assets');
Vue.component('email-setting', require('./components/admin/setting/email/EmailSetting.vue').default);
Vue.component('view-email', require('./components/admin/email/ViewEmail.vue').default);

var app = new Vue({
    el: '#wrapper'
});