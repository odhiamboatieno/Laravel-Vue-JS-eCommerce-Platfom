<template>
<div class="row">
    <div class="col-lg-12" >
        <div class="ibox animated fadeInRightBig">
            <div class="ibox-title">
                <h5>Area Order List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#" class="dropdown-item">Config option 1</a>
                        </li>
                        <li><a href="#" class="dropdown-item">Config option 2</a>
                        </li>
                    </ul>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input placeholder="Search By Order ID" type="text" class="form-control form-control-sm" 
                             v-model="order_id"
                             @keyup="getAreaOrder()"> 
                            <span class="input-group-append">
                    </span>
                     </div>
                    </div>                	
                    <div class="col-md-2 m-b-xs">
                    	<div class="input-group">
                     <select class="form-control" @change="getAreaOrder()"  v-model="status" >
                     	<option value="">All Order</option>
                     	<option value="0">Pending</option>
                     	<option value="1">On Process</option>
                     	<option value="2">On Delivery</option>
                     	<option value="3">Delivered</option>
                     </select>                    		
                    	</div>

                    </div>
                    <div class="col-sm-2 m-b-xs">
	                <div class="input-group">
	                        <select class="form-control" @change="getAreaOrder()" v-model="payment_status" >
	                     	<option value="">Payment</option>
	                     	<option value="1">Paid</option>
	                     	<option value="0">Unpaid</option>
	                     	
	                     </select>
	                 </div>
                    </div>
                    <div class="col-sm-1">
                        <button class="btn btn-primary" @click="clearFilter()">Clear Filter</button>
                    </div>
                </div>
                <div class="table-responsive" style="margin-top: 15px;" v-if="!isLoading">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>OrderID</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>City</th>
                            <th>Total Item</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(value,index) in orders.data" :key="index">
                            <td>{{ value.id }}</td>
                            <td>{{ value.order_date | dateToString }}</td>
                            <td>{{ value.user.name }}</td>
                            <td>{{ value.shipping_area.city }}</td>
                            <td>{{ value.total_item }}</td>
                            <td>{{ currency.symbol }}{{ value.total_amount }}</td>
                            <td>
                                <span v-if="value.payment_status==0">Unpaid</span>
                                <span v-else>Paid</span>
                            </td>
                            <td>
                                <span v-if="value.status==0">Pending</span>
                                <span v-else-if="value.status == 1">Processing</span>
                                <span v-else-if="value.status == 2">On delivery</span>
                                <span v-else>Delivered</span>
                            </td>
                            <td>
                                <a @click.prevent="getDetails(value.id)" class="btn btn-primary" href="#"><i class="fa fa-eye" title="View Details"></i></a> 
                                <a @click.prevent="deleteorder(value.id)" class="btn btn-danger" href="#"><i class="fa fa-trash" title="Delete"></i></a>
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
           <pagination v-if="orders" :pageData="orders"></pagination> 
        </div>

        <div class="ibox">
            <order-details :currency="this.currency"></order-details>
        </div> 
    </div>

</div>
</template>

<script>

    import { EventBus } from  '../../../vue-assets';

    import Mixin from  '../../../mixin';

    import Pagination from  '../pagination/Pagination';
    import Multiselect from 'vue-multiselect';
    import OrderDetails from './orderDetails';
	
	export default {

        mixins : [Mixin],
        props : ['currency','cityid'],
        components : {
         
         'pagination'    : Pagination,
         'order-details' : OrderDetails,
         Multiselect

        },

       data(){
         
         return {
            
            orders : [],
            payment_status : '',
            isLoading : false,

            status :   '',

            order_id : '',

            user : {
            	'id' : '',
            	'name' : '',
            },


            url : base_url,

         }

       },

       mounted(){
        // this  will not work in eventBus that why 
        // we are initializing with _this

        var _this = this;
        _this.getAreaOrder();

        EventBus.$on('order-created',function(){
        _this.getAreaOrder();
        });
       },

       methods : {
       

        getAreaOrder(page = 1){

           this.isLoading = true;
         
         axios.get(base_url+'admin/order-list?page='+page+'&order_id='+this.order_id+'&status='+this.status+'&payment='+this.payment_status+'&city='+this.cityid)
              .then(response => {
               this.orders = response.data;
               this.isLoading = false;

              });

        },

        pageClicked(pageNo){
        var vm = this;
        vm.getAreaOrder(pageNo);
        },

        // edit order 

        getDetails(id){
            
            EventBus.$emit('order-details',id);

        },

        clearFilter(){
           this.payment_status = '';
           this.status        = '';
           this.getAreaOrder();

       },
        // delete order 
        deleteorder(id){
        Swal.fire({
            title: 'Are you sure ?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        },() => {

        }).then((result) => {
            if (result.value) {

                axios.get(base_url+'admin/order/delete/'+id)
                .then(res => {

                    this.successMessage(res.data);
                    this.getAreaOrder();
                })
            }
        }) 

       },



       }

	}

</script>