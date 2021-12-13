<template>
   <div class="row">
    <div class="col-lg-12" >
        <div class="ibox animated fadeInRightBig">
            <div class="ibox-title">
                <h5>Coupon List</h5>
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
                            @keyup="getCoupon()"> 
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
                                    <th>Coupon</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Amount Limit</th>
                                    <th>Valid date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(value,index) in coupons.data" :key="index">
                                    <td>{{ value.coupon_code }}</td>
                                    <td>
                                        <span v-if="value.amount_type == 1">Amount</span>
                                        <span v-else>%</span>
                                    </td>
                                    <td>{{ value.amount }}</td>
                                    <td>{{ value.max_amount_limit }}</td>
                                    <td>{{ value.valid_date }}</td>
                                <td>
                                    <a @click.prevent="edit(value)" class="btn btn-primary" href="#"><i class="fa fa-edit" title="Edit"></i></a> 
                                    <a @click.prevent="deleteCoupon(value.id)" class="btn btn-danger" href="#"><i class="fa fa-trash" title="Delete"></i></a>
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
         <pagination v-if="coupons.meta" :pageData="coupons.meta"></pagination> 
     </div>

     <div class="ibox">
        <update-coupon></update-coupon>
    </div>
</div>

</div>
</template>

<script>

    import { EventBus } from  '../../../../vue-assets';

    import Mixin from  '../../../../mixin';

    import Pagination from  '../../pagination/Pagination';

    import UpdateCoupon from './EditCoupon';
    
    export default {

        mixins : [Mixin],

        components : {
           
           'pagination' : Pagination,
            UpdateCoupon,

       },

       data(){
           
           return {
            
            coupons : [],

            isLoading : false,

            keyword : '',

            url : base_url,

        }

    },

    mounted(){


        // this  will not work in eventBus that why 
        // we are initializing with _this

        var _this = this;

        _this.getCoupon();

        EventBus.$on('coupon-created',function(){

        // getting updated data when insert update delete happend 

        _this.getCoupon();


    });



    },

    methods : {
     

        getCoupon(page = 1){

         this.isLoading = true;
         
         axios.get(base_url+'admin/coupon-list?page='+page+'&keyword='+this.keyword)
         .then(response => {
             
             this.coupons = response.data;
             this.isLoading = false;

         });

     },

     pageClicked(pageNo){
        var vm = this;
        vm.getCoupon(pageNo);
    },

        // edit slider 

        edit(value){
            // alert(id)
            EventBus.$emit('update-coupon',value);
        },

        // delete slider 

        deleteCoupon(id){
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

                    axios.delete(base_url+'admin/coupon/'+id)
                    .then(res => {

                        this.successMessage(res.data);
                        this.getCoupon();
                    })
                }
            }) 

        },

        // sliderStatus(id)
        // {
        //     axios.get(base_url+'admin/coupon-status/'+id)
        //     .then(res => {
        //         this.successMessage(res.data);
        //     });
        // }



    }

}

</script>