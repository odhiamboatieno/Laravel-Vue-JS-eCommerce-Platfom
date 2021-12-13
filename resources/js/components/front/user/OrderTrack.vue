<template>
<div>
<section class="order-track">
    <div class="bg-overlay pt50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <form @submit.prevent="getOrder()">
                    <div class="form-group">
                        <input type="text" required v-model="order_id" class="form-control" placeholder="Enter Order Number. EX:1020">
                    </div>

                    <div class="form-group text-right">
                      <button class="btn button-md theme-background text-white">
                        <span v-if="isLoading">Tracking....</span>
                        <span v-else>Track</span>
                      </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
</section>
<section class="order-track" v-if="order.hasOwnProperty('id')">
    <div class="bg-overlay pt50 pb50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="progressbar-track clearfix">
                        <ul class="progressbar progressbar-2">
                            <li 
                            :class="(order.status == 0 || order.status == 1 || order.status == 2 || order.status == 3 ) ?  'active' : ''">
                                <span>Pending</span>
                                <!-- <p>Thursday, April 23, 2020</p> -->
                            </li>
                            <li 
                            :class="(order.status == 1 || order.status == 2 || order.status == 3 ) ?  'active' : ''">
                                <span>On Process</span>
                                <!-- <p>Thursday, April 23, 2020</p> -->
                            </li>
                            <li :class="(order.status == 2 || order.status == 3 ) ?  'active' : ''">
                                <span>On Delivery</span>
                                <!-- <p>Thursday, April 23, 2020</p> -->
                            </li>
                            <li :class="order.status == 3  ?  'active' : ''">
                                <span>Delivered</span>
                                <!-- <p>Thursday, April 23, 2020</p> -->
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="checkout" v-if="order.hasOwnProperty('order_details')">
    <div class="bg-overlay pt50 pb50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="form product-info bg-white bg-shadow">
                        <div class="heading  clearfix p10">
                            <h4 class="color-black">Order  Summary</h4>
                        </div>
                        <small class="heading heading-solid center-block heading-width-100 border-light"></small>
                        <div class="table-responsive">
                            <table class="">

                                <tbody class="text-center">
                                     <tr>
					                    <th >Image</th>
                                        <th >Name</th>
					                    <th >Qty</th>
					                    <th >Unit Price</th>
					                    <th >Total Price</th>
					                </tr>
					                <tr v-for="value in order.order_details" :key="value.id">
					                    <td><img v-lazy="url+'images/product/feature/'+value.product.product_image" alt=".webp not supported in safari" height="40" width="50"></td>					                	
					                    <td>{{ value.product.product_name }} <br> <small>{{ value.product.quantity_unit }}</small></td>
					                    <td>{{ value.quantity }}</td>
					                    <td>
					                    	{{ currency.symbol }} {{ value.selling_price | formatPrice }}
                                         <span class="discount-price" v-if="value.unit_discount > 0">{{ currency.symbol }} {{ value.selling_price + value.unit_discount | formatPrice }}</span>
					                    </td>
					                    <td>{{ currency.symbol }} {{ value.total_selling_price | formatPrice }}</td>
					                </tr>

					             <tr class="no-border">
    								<td class="thick-line"></td>
    								<td class="thick-line"></td>
    								<td class="thick-line"></td>
    								<td class="thick-line"><strong>Subtotal</strong></td>
    								<td class="thick-line">{{ currency.symbol }} {{ order.total_amount | formatPrice }}</td>
    							</tr>
    							<tr class="no-border">
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line"><strong>Shipping</strong></td>
    								<td class="no-line">{{ currency.symbol }} {{ order.shipping_amount | formatPrice }}</td>
    							</tr>
                                <tr v-if="order.coupon_discount > 0">
                                    <td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
                                    <td class="no-line"><strong>Total :</strong></td>
                                    <td class="no-line">{{ currency.symbol }}{{ (order.shipping_amount | formatPrice) + (order.total_amount | formatPrice) }}</td>
                                </tr>
                                <tr v-if="order.coupon_discount > 0">
                                    <td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
                                    <td class="no-line"><strong>(-) Coupon Discount ({{ order.cupon }})  :</strong></td>
                                    <td class="no-line">{{ currency.symbol }}{{ order.coupon_discount }}</td>
                                </tr>
    							<tr class="no-border">
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line"><strong>Grand Total</strong></td>
    								<td class="no-line">{{ currency.symbol }} {{ ((order.shipping_amount | formatPrice) + (order.total_amount | formatPrice)) - order.coupon_discount }}</td>
    							</tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>  
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
                isLoading : false,
                order_id  : '',
                url : base_url

			}
        },

		methods : {
         getOrder(){
             this.isLoading = true;
             axios.post(base_url+'order-track',{order_id : this.order_id})
                  .then(response => 
                  {
                    this.order = response.data;
                    this.isLoading = false;
                  }
                  );
         }
     	}
 }

</script>