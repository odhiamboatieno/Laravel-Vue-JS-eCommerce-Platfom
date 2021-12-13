<template>
    <div class="row">
        <div class="col-lg-12" >
            <div class="ibox animated fadeInRightBig">
                <div class="ibox-title">
                    <h5>Cutomer List</h5>
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
                        <div class="col-sm-5 m-b-xs">

                        </div>
                        <div class="col-sm-4 m-b-xs">

                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input placeholder="Search By Name" type="text" class="form-control form-control-sm" 
                                 v-model="keyword"
                                 @keyup="getcustomer()"> 
                                <span class="input-group-append">
                          <!--    <button type="button" class="btn btn-sm btn-primary">Go!
                            </button> -->
                        </span></div>

                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 15px;" v-if="!isLoading">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Register</th>
                                <th>View Order</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(value,index) in customers.data" :key="index">
                                <td>{{ value.name }}</td>
                                <td>{{ value.email }}</td>
                                <td>{{ value.phone }}</td>
                                <td>{{ value.address }}</td>
                                <td>{{ value.created_at | dateToString }}</td>
                                <td>
                                    <a @click.prevent="viewOrder(value.id,value.name)" class="btn btn-primary" href="#"><i class="fa fa-eye" title="View Orders"></i></a> 
                                    <!-- <a @click.prevent="deletecustomer(value.id)" class="btn btn-danger" href="#">
                                        <span v-if="value.status == 1"><i class="fa fa-edit" title="Active"></i></span>
                                        <span v-else><i class="fa fa-ban" title="Inactive"></i></span>
                                        
                                    </a> -->
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
               <pagination v-if="customers" :pageData="customers"></pagination> 
            </div>

            <div class="ibox">
                <show-order></show-order>
            </div>
        </div>
    </div>
</template>

<script>

    import { EventBus } from  '../../../vue-assets';

    import Mixin from  '../../../mixin';

    import Pagination from  '../pagination/Pagination';

    import showOrder from './ShowOrder';
	
	export default {

        mixins : [Mixin],

        components : {
         
         'pagination' : Pagination,
         'show-order' : showOrder,

        },

       data(){
         
         return {
            
            customers : [],

            isLoading : false,

            keyword : '',

            url : base_url,

         }

       },

       mounted(){


        // this  will not work in eventBus that why 
        // we are initializing with _this

        var _this = this;

        _this.getcustomer();

        EventBus.$on('customer-created',function(){
            // getting updated data when insert update delete happend 
        _this.getcustomer();

        });



       },

       methods : {
       

        getcustomer(page = 1){

           this.isLoading = true;
         
         axios.get(base_url+'admin/customer-list?page='+page+'&keyword='+this.keyword)
              .then(response => {
                 this.customers = response.data;
                 this.isLoading = false;

              });

        },

        pageClicked(pageNo){
        var vm = this;
        vm.getcustomer(pageNo);
        },

        // edit brand 

        viewOrder(id,name){
          
            EventBus.$emit('customer-orders',[id,name]); 
        },

        // delete brand 

        deletecustomer(id){
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

                axios.get(base_url+'admin/customer/delete/'+id)
                .then(res => {

                    this.successMessage(res.data);
                    this.getcustomer();
                })
            }
        }) 

       },





       }

	}

</script>