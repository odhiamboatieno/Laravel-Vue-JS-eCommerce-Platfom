<template>
	<div id="modal-order" class="modal fade" >
		<div class="modal-dialog modal-custom">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="ml-3">{{ customer_name }}</h3>
					<button class="btn btn-default text-right" data-dismiss="modal">X</button>
				</div>
				<div class="modal-body">
		            <div class="ibox animated fadeInRightBig">
	                    <div class="table-responsive" style="margin-top: 10px;" v-if="!isLoading">
	                        <table class="table table-bordered">
	                            <thead>
	                            <tr>
	                            	<th>OrderID</th>
	                                <th>Date</th>
	                                <th>Customer</th>
	                                <th>Total Item</th>
	                                <th>Total Amount</th>
	                                <th>Payment Status</th>
	                                <th>Payment Method</th>
	                                <th>Status</th>
	                            </tr>
	                            </thead>
	                            <tbody>
	                            <tr v-for="(value,index) in customer_order" :key="index">
	                            	<td>{{ value.id }}</td>
	                                <td>{{ value.order_date | dateToString }}</td>
	                                <td>{{ value.user.name }}</td>
	                                <td>{{ value.total_item }}</td>
	                                <td>{{ value.total_amount | formatPrice }}</td>
	                                <td>
	                                	<span v-if="value.payment_status == 1">Paid</span>
                                		<span v-else>Unpaid</span>
	                                </td>
	                                <td>{{ value.provider.provider }}</td>
	                                <td>
	                                   	<span v-if="value.status == 0">Pending</span>
	                                   	<span v-else-if="value.status == 1">On Process</span>
	                                   	<span v-else-if="value.status == 2">On Delivery</span>
                                		<span v-else>Delivered</span>
	                          		</td>
	                            </tr>
	                            </tbody>
	                        </table>
	                    </div>

	                    <div class="col-md-12 text-center" v-else>
	                        <img :src="url+'images/loading.gif'">
	                    </div>
		            </div>
		            <div class="ibox animated fadeInRightBig">
		               <pagination v-if="customer_order" :pageData="customer_order"></pagination> 
		            </div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	
	import {EventBus} from  '../../../vue-assets';

	import Mixin from  '../../../mixin';
	import Pagination from  '../pagination/Pagination';
	// import showOrderDetails from './ShowOrderDetails';

	export default {

		mixins : [Mixin],

		components : {
         
         'pagination' : Pagination,
         // 'show-orderdetails' : showOrderDetails,

        },

		data(){

			return {
				customer_order : [],
				isLoading : false,
				url : base_url,
				customer_name : '',
			}

		},

		mounted()
		{
         var _this = this;
         EventBus.$on('customer-orders',function([id,name]){
          $('#modal-order').modal('show');
         	_this.customer_name = name;
           _this.getOrder(id);
         });

		},


		methods : {
            getOrder(id){
             this.isLoading = true
             axios.get(base_url+'admin/customer/'+id+'/show')
                .then(response => {
                	this.isLoading = false
                	this.customer_order = response.data;
                	console.log(response.data)
                });
            },
        }

    }

</script>

<style scoped="">
.modal-custom {

	max-width: 90% !important;

}

@media screen and (max-width: 573px)
{


	.modal-custom {

		max-width: 100% !important;
		background-color: #000 !important;
	}



}
</style>