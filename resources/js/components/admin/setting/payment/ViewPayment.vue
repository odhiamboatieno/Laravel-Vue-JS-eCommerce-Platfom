<template>
<div class="row">
    <div class="col-lg-12" >
        <div class="ibox animated fadeInRightBig">
            <div class="ibox-title">
                <h5>Payment List</h5>
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
                <div class="table-responsive" style="margin-top: 15px;" v-if="!isLoading">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Provider</th>
                            <th>Client ID/KEY | PUBLIC_KEY</th>
                            <th>Secret</th>
                            <th>Status</th>
                            <th>Platform</th>
                            <th>Update</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(value,index) in payments" :key="index">
                            <td>{{ value.provider }}</td>
                            <td><span class="text-info" v-if="value.id == 6">Encryption Key:  </span>{{ value.client_id }}</td>
                            <td><span class="text-info" v-if="value.id == 6">SECRET Key:  </span> {{ value.client_secret }} <br>
                            <span class="text-info" v-if="value.id == 6">Public Key : {{ value.public_key }}</span></td>
                            <td>
                              <span v-if="value.status == 0" class="text-danger">Inactive</span>
                              <span v-else class="text-success">Active</span>
                            </td>
                            <td>
                              <span v-if="value.live_status == 1" class="text-info"> Live</span>
                              <span v-else class="text-warning">SandBox</span>
                            </td>
                            <td>
                                <a @click.prevent="edit(value)" class="btn btn-primary" href="#"><i class="fa fa-edit" title="Edit"></i></a> 
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
        <div class="ibox">
            <update-payment></update-payment>
        </div>
    </div>
</div>
</template>

<script>

    import { EventBus } from  '../../../../vue-assets';

    import Mixin from  '../../../../mixin';

    import Updatepayment from './UpdatePayment';
	
	export default {

        mixins : [Mixin],

        components : {

         'update-payment' : Updatepayment,

        },

       data(){
         
         return {
            
            payments : [],

            isLoading : false,


            url : base_url,

         }

       },

       mounted(){


        // this  will not work in eventBus that why 
        // we are initializing with _this

        var _this = this;

        _this.getpayment();

        EventBus.$on('payment-created',function(){

            // getting updated data when insert update delete happend 

        _this.getpayment();


        });



       },

       methods : {
       

        getpayment(){

           this.isLoading = true;
         
         axios.get(base_url+'admin/setting/payment-method-list')
              .then(response => {
               
               this.payments = response.data;
               this.isLoading = false;

              });

        },


        edit(value){
            EventBus.$emit('update-payment',value);
        },



       }

	}

</script>