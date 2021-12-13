<template>
<div>
<div class="row" v-if="!isLoading">
	<div class="col-lg-12 col-sm-12">
	    <div class="user-info bg-white bg-shadow table-responsive ">
	        <table class="table table-hover">
	            <thead>
	                <tr>
	                    <th scope="col">Code</th>
	                    <th scope="col">Date</th>
	                    <th scope="col">Amount</th>
	                    <th scope="col">Delivery Status</th>
	                    <th scope="col">Payment Status</th>
	                    <th scope="col">Options</th>
	                </tr>
	            </thead>
	            <tbody>
	                <tr v-for="order in orders.data" :key="order.id">
	                    <th scope="row">#{{ order.id }}</th>
	                    <td>{{ order.order_date | dateToString }}</td>
	                    <td> {{ currency.symbol }}{{ order.total_amount }}</td>
	                    <td>
	                    	<span v-if="order.status == 0">Pending</span>
                            <span v-if="order.status == 1">On Process</span>
                            <span v-if="order.status == 2">On Delivery</span>
                            <span v-if="order.status == 3">Delivered</span></td>
	                    <td>
	                    	<span v-if="order.payment_status == 1"><i class='lni lni-shield color-green'></i> Paid</span>
                            <a href="" @click.prevent="makePayment(order)" class="btn button-xs theme-background text-white" v-else>Pay Now</a>
	                    </td>
	                    <td>
	                    	<a href="#" @click.prevent="viewDetails(order)" class="button button-xs bg-dark2 color-white ">Details</a>
						    <a :href="url+'user-order-details-pdf/'+order.id" class="btn btn-primary float-center btn-sm" title="Download Pdf"><i class='lni lni-files'></i></a>
	                    </td>
	                </tr>
	            </tbody>
	        </table>

	    </div>
	</div>
</div>
	    
		<div class="row" v-else>
	        <div class="col-md-12 text-center">
	            <img :src="url+'images/loading.gif'">
	        </div>            	
	    </div>
<div class="row">
	<div class="col-9">
	    <pagination v-if="orders" :pageData="orders"></pagination>
	</div>
	<!-- <div class="col-3 mt-4">
	    <a :href="url+'user-order-pdf'" class="btn btn-primary btn-sm">PDF</a>

	    <a :href="url+'user-order-print'" target="_blank" class="btn btn-primary btn-sm ml-1">Print</a>
	</div> -->
</div>
	<order-details :currency="currency"></order-details>
	<payment :currency="currency"></payment>
</div>
</template>

<script>
	import {EventBus} from  '../../../vue-assets';
	import Pagination from  '../pagination/paginate.vue';
	import Mixin from  '../../../mixin'
	import OrderDetails from  './OrderDetails'
	import Payment from  './Payment.vue'

	export default {
		props : ['currency'],
		mixins : [Mixin],
		components : { 
			'pagination' : Pagination,
			'order-details' : OrderDetails,
			'payment' : Payment,
					 },
		data(){
			return {
				orders : [],
				isLoading : false,
	            url : base_url
			}
		},
		mounted()
        {
        	this.fetchUserOrder();
        },
		methods : {
			fetchUserOrder: function(page = 1) {
				this.isLoading = true;
		        axios.get(base_url+'user-order-list?page='+page)
		        .then(response => {
		        	this.orders = response.data;
		        	this.isLoading = false;
		        })
	      	},

	      pageClicked(pageNo){
	        var vm = this;
	        vm.fetchUserOrder(pageNo);
	      },

	      viewDetails(order){
	      	EventBus.$emit('view-details',order)
		  },
		  
		  makePayment(order){
		   
		   EventBus.$emit('make-payment',order);

		  }
		},

		filters : {
           


		}
	}
</script>