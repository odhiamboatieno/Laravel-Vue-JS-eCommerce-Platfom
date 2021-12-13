<template>
	<div id="modal-orderdetails" class="modal fade" >
		<div class="modal-dialog modal-custom">
			<div class="modal-content">
				<div class="modal-header ">
					<h3 class="m-t-none m-b">Customer Order Details</h3>
					<button class="btn btn-default text-right close" data-dismiss="modal">X</button>
				</div>
				<div class="modal-body">
			            <div class="ibox animated fadeInRightBig">
			               
			                <div class="ibox-content">
			                    
			                    <div class="table-responsive" style="margin-top: 10px;" v-if="!isLoading">
			                        <table class="table table-bordered">
			                            <thead>
			                            <tr>
			                                <th>Quantity</th>
			                                <th>selling price</th>
			                                <th>total amount</th>
			                                <th>buying price</th>
			                                <th>Action</th>
			                            </tr>
			                            </thead>
			                            <tbody>
			                            <tr v-for="(value,index) in customer_orderdetails" :key="index">
			                                <td>{{ value.quantity }}</td>
			                                <td>{{ value.selling_price }}</td>
			                                <td>{{ value.buying_price }}</td>
			                                <td>{{ value.total_selling_price }}</td>
			                                <td>
			                                    <a @click.prevent="viewOrderDetails(value.id)" class="btn btn-primary" href="#"><i class="fa fa-edit" title="View"></i></a> 
			                                    <!-- <a @click.prevent="deleteOrder(value.id)" class="btn btn-danger" href="#"><i class="fa fa-trash" title="Delete"></i></a> -->
			                                </td>
			                            </tr>

			                            </tbody>
			                        </table>
			                    </div>

			                    <div class="col-md-12 text-center" v-else>
			                        <img :src="url+'images/loading.gif'">
			                    </div>

			                </div>
			            </div>

			            <div class="ibox animated fadeInRightBig">
			               <pagination v-if="customer_orderdetails" :pageData="customer_orderdetails"></pagination> 
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

	export default {

		mixins : [Mixin],

		components : {
         
         'pagination' : Pagination,

        },

		data(){

			return {
				customer_orderdetails : [],
				isLoading : false,
				url : base_url,
			}

		},

		mounted()
		{
         var _this = this;
         EventBus.$on('order-details',function(id){
          	$('#modal-orderdetails').modal('show');
           	_this.getOrderdetails(id);
         });

		},


		methods : {
            getOrderdetails(id){
             this.isLoading = true
             axios.get(base_url+'admin/customer/orderdetails/'+id+'/show')
                .then(response => {
                	this.isLoading = false
                	this.customer_orderdetails = response.data;
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