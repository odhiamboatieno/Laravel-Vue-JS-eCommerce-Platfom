<template>
<div id="modal-payment" class="modal fade" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-right">
				<h3 class="modal-title">You are paying for order <strong>#{{ order.id }}</strong></h3>
				<button class="btn btn-default" data-dismiss="modal">X</button>
			</div>
			<div class="modal-body">
                <div class="row"> 
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <p><strong> Sub Total : </strong> {{ currency.symbol }}{{ order.total_amount | formatPrice }}</p>
                            <p><strong> Shipping  : </strong> {{ currency.symbol }}{{ order.shipping_amount | formatPrice }}</p>
                            <p><strong> Total  :</strong>  {{ currency.symbol }}{{ (order.total_amount | formatPrice) + (order.shipping_amount | formatPrice) }}</p>
                        </div>
                        <div class="fomr-group" v-for="value in payment_methods" :key="value.id">
                            <div class="row" v-if="value.id == 3">
                                <div class="col-md-12">
                                <p class="text-left float-left login-with-social">
                                    Fill these field if you wants to pay in stripe
                                    </p>
                                <input type="text" class="form-control mt-2" v-model="stripe.card_no" placeholder="Card No">
                                <input type="text" class="form-control mt-2" v-model="stripe.cvc" placeholder="CVC - Ex:123">
                                </div>
                                <div class="col-md-6">
                                <input type="text"
                                 class="form-control mt-2"
                                  v-model="stripe.expire_month" 
                                  placeholder="Expire Month EX:06">
                                </div>
                                <div class="col-md-6">
                                <input type="text"
                                 class="form-control mt-2"
                                  v-model="stripe.expire_year" 
                                  placeholder="Expire Year EX:2030">
                                </div>
                            </div>
                          <a @click="validateStripe(value.id, $event)" 
                          :href="value.id == 3 ? url+value.provider+'/'+order.id+'?card_no='+stripe.card_no+'&cvc='+stripe.cvc+'&expire_month='+stripe.expire_month+'&expire_year='+stripe.expire_year : url+value.provider+'/'+order.id" 
                          class="btn btn-primary btn-block mt-2 mb-2">
                          <i v-if="value.id == 2" class="lni lni-paypal"></i> 
                          <i v-if="value.id == 3" class="lni lni-stripe"></i> 
                          
                          Pay with {{ value.provider }}
                          </a>   
                        </div>
                    </div>
                </div>
               <button class="btn btn-succes"></button>
			</div>
		</div>
	</div>
</div>

</template>


<script>
	
	import {EventBus} from  '../../../vue-assets';
	import Mixin from  '../../../mixin';


	export default {
		props : ['currency'],
		mixins : [Mixin],
		data(){
			return {
				order : {},
				url : base_url,
                stripe : {
                    'card_no'          : '',
                    'cvc'              : '',
                    'expire_month'     : '',
                    'expire_year'      : '',
                },
                payment_methods : [],
			}
		},

		mounted(){
			var _this = this
			var _symb = $
			EventBus.$on('make-payment',function(order){
                _this.order = order
                _this.paymentMethodList();
				_symb("#modal-payment").modal('show')

			})
		},

		methods : {

            paymentMethodList(){
                 
                 axios.get(base_url+'payment-method-list')
                      .then(response => {
                       this.payment_methods  = response.data;
                      });

            },

            validateStripe(id,event)
            {

                // if payment method is stripe then those field are required 
                let stripe_id = 3;
                if(id == stripe_id)
                {

               if(this.stripe.card_no == '' || this.stripe.cvc == '' || this.stripe.expire_month == '' || this.stripe.expire_year == '')
                {
                    
                    event.preventDefault();

                      Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'card information required when you want to pay in stripe ',
						// footer: '<a href>Why do I have this issue?</a>'
						});

                }
                 
                }

            }
     	}
 }

</script>

<style scoped="">

</style>