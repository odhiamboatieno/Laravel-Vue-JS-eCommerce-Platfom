<template>
    
      <div class="row order_box">
     <div class="col-md-6 ml-auto mr-auto text-center">
       <div class="box bg-shadow">
         <div class="order-heading">
     
           <!-- <h2>Payment For Order {{ order.id }}</h2> -->

           <img :src="url+'images/static/success_red.png'" class="img-fluid">

         </div>
         <div class="order-body">
             <p>
              your order  #{{ order.id }} is taken and payment is  under processing if payment popup get cancel for any reason please 
              click try again method from bellow
            </p>

             <button @click="makePayment()"  class="btn theme-background color-white" style="margin-top:20px">Try Again</button>
         </div>
       </div>
     </div>
  </div>

</template>


<script>
export default {
    props : ['order','currency','shop_info','payment_info'],
    data()
    {
        return {
           
           url: base_url,

        }
    },

    mounted()
    {
      this.makePayment();
    },

    methods : {
      
      makePayment() {
       
       var user_email = this.order.user.email != '' ? this.order.user.email : this.order.email;
       var user_phone = this.order.user.phone != '' ? this.order.user.phone : this.order.phone;
       FlutterwaveCheckout({
        public_key: this.payment_info.public_key,
        tx_ref: this.order.id,
        amount: this.order.total_amount-this.order.coupon_discount,
        currency: this.currency.code,
        payment_options: "card, mobilemoneyghana, ussd",
        redirect_url:this.url+'flutter-success',
        meta: {
          consumer_id: this.order.user.id,
          consumer_mac: "92a3-912ba-1192a",
        },
        customer: {
          email: user_email,
          phone_number: user_phone,
          name: this.order.customer_name,
        },
        callback: function (data) {
        //   console.log(data);
        },
        onclose: function() {
          // close modal
        },
        customizations: {
          title: this.shop_info.shop_name,
          description: "Payment for shopping",
          logo: this.url+'images/logo/'+this.shop_info.logo_footer,
        },
      });
    },

    },

}
</script>