<template>

<div class="form product-info bg-white bg-shadow">
    <!-- <div class="heading  clearfix p10">
        <h4 class="color-black">Cart Summary</h4>
    </div> -->
    <!-- <small class="heading heading-solid center-block heading-width-100 border-light"></small> -->
    <div class="table-responsive" v-if="cartCount > 0">
        <table class="table-condensed">
           <tfoot>
                <tr>
                    <td colspan="4"><span class="totitle">SubTotal</span></td>
                    <td><span class="total">{{ currency.symbol }}{{ cartTotal | formatPrice }}</span></td>
                </tr>

                <tr>
                    <td colspan="4"><span class="totitle">(+) Delivery Charge</span></td>
                    <td><span class="total">{{ currency.symbol }} {{ shippingCost }}</span></td>
                </tr>

                <tr v-if="is_coupon">
                  <td colspan="4" class="text-right"><span class="totitle theme-color">Total : </span></td>
                    <td><span class="total">{{ currency.symbol }}{{ ((cartTotal| formatPrice )+( shippingCost | formatPrice ))  }}</span></td>
                </tr>
                
                <tr v-if="is_coupon">
                    <td colspan="4"><span class="totitle">(-) Coupon Discount</span></td>
                    <td><span class="total">{{ currency.symbol }}{{ couponDiscount }}</span></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right "><span class="totitle theme-color">Grand Total : </span></td>
                    <td><span class="total">{{ currency.symbol }}{{ ((cartTotal| formatPrice )+( shippingCost | formatPrice ) - couponDiscount)  }}</span></td>
                </tr>
                
            </tfoot>
        </table>

        
                    <input type="hidden" name="coupon_discount" :value="couponDiscount">                    
                    <input type="hidden" name="coupon_code" :value="applied_coupon.coupon_code">                    
                    <input type="hidden" name="delivery_cost" :value="shippingCost">                    
                    <input type="hidden" name="cart_total" :value="cartTotal">
    </div>

<div class="text-center" v-else>
   <div class="cart-empty">
     <div class="cart-icon">
         <i class='lni lni-shopping-basket theme-color'></i>
     </div>
     <span class="mt10">Your shopping bag is empty.</span>
      <a href="" class="shopping-now theme-color">Start shopping now.</a>
 </div>
</div>


<div>
   <div style="padding:10px;" >

 <div class="row" v-if="!is_coupon">
     <div class="col-md-7 col-7">
       <input type="text" v-model="coupon_form.coupon_code" class="form-control" placeholder="Enter Coupon Code">
     </div>
     <div class="col-md-5 col-5">
       <button class="btn btn-outline-secondary" @click.prevent="applyCoupon()">Apply Coupon</button>
     </div>
   </div>

   <div class="row" v-else>
       <div class="col-md-12">
         <p>Coupon  {{ applied_coupon.coupon_code }} Applied For {{ applied_coupon.amount }}
           <span v-if="applied_coupon.amount_type ==1">{{ currency.symbol }}</span>
           <span v-else>% Up To {{  applied_coupon.max_amount_limit }}{{ currency.symbol }}</span> Discount
            </p>
       </div>
   </div>

   </div>
</div>

  <!-- trial items  -->

  

</div>
   
</template>

<script>
  import {EventBus} from  '../../../vue-assets';
  import Mixin from  '../../../mixin';

  export default {
    mixins : [Mixin],
    props : ['currency'],
   data(){
    return {
     // search_keyword : {}
     url : base_url,
     shipping : {},
     is_coupon : false,

     coupon_form : {
       coupon_code : ''
     },
     coupon_errors : null,
     applied_coupon : {
      amount:0,
      amount_type:1,
      coupon_code:"",
      max_amount_limit:0,
     },
    }
   },

   mounted()
   {
     
    this.getAppliedCoupon();
    this.$store.dispatch("getCart");
    this.$store.dispatch("getTrial");
    this.getShipping();

   },

   methods : {
    removeItem(id)
    {
      axios.get(base_url+'cart/remove/'+id)
          .then(response => {
            this.$store.dispatch("getCart");
          })
    },
    removeTrial(id)
    {
      axios.get(base_url+'trial/remove/'+id)
          .then(response => {
            this.$store.dispatch("getTrial");
          })
    },
    updateCart(id,status)
    {
      axios.get(base_url+'cart/update/'+id+'/'+status)
           .then(response => {
            
            if(response.data.status === 'success'){
             this.$store.dispatch("getCart");
            }
            else
            {
              this.successMessage(response.data);
            }

           })
    },
    getShipping()
    {
     axios.get(base_url+'get-shipping')
           .then(response => {
            this.shipping = response.data;
           });
    },

    applyCoupon()
    {
      axios.post(base_url+'apply-coupon',this.coupon_form)
           .then(response => {
             if (response.data.status === 'success') 
             {  
              // call success message  
              this.successMessage(response.data);
              this.getAppliedCoupon();

             }
             else
             {
              //  this.successMessage();
               this.validationError(response.data.message);
             }
           });
            
    },

    getAppliedCoupon()
    {
       axios.get(base_url+'user-coupon')
            .then(response => {
              if (response.data.status === 'error')
              {
                this.is_coupon = false
              }
              else{
                this.is_coupon = true
                this.applied_coupon = response.data;
              }
           
            })
    }

   },

   computed : 
   {

    cartCount(){

      return this.$store.getters.cart_count;
    },    

    cartTotal(){

      return this.$store.getters.cart_total;
    },
    isLoading(){

      return this.$store.getters.cart_loading;
    },    

    cartItems(){

      return this.$store.getters.cart_items;

    },

        // get trial item 

    trialCount(){

      return this.$store.getters.trial_count;
    }, 

    trialItems(){

      return this.$store.getters.trial_items;

    },

    shippingCost(){
     
     if(this.shipping.shipping_status == 1)
     {
      
      if(this.shipping.discount_status == 1 && this.cartTotal > this.shipping.order_amount)
      {
        return this.shipping.shipping_amount - this.shipping.discount_amount;
      }
      else
      {
        return this.shipping.shipping_amount;
      }


     }
     else
     {
      return 0;
     }

      
    },

    couponDiscount()
    {
      if(this.is_coupon) 
      {
       
      //  if discount type is amount 
        if (this.applied_coupon.amount_type == 1 ) 
        {
          return this.applied_coupon.amount;
        }
        else
        {
          //  discount type is percentage 

          let percentageAmount = ((this.cartTotal*this.applied_coupon.amount)/100);

          // if percentage greater then maiximum amount 
          if (percentageAmount > this.applied_coupon.max_amount_limit) 
          {
            return this.applied_coupon.max_amount_limit;
          }
          
          return percentageAmount;
         
        }
       
      }
      
      return 0;

    },

   }

  }
</script>



