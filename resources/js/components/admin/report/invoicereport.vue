<template>
<div class="row">
    <div class="col-lg-12" >
        <div class="ibox animated fadeInRightBig">
            <div class="ibox-title">
                <h5>Invoice List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#" class="dropdown-item">clear Filter </a>
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
                    <div class="col-sm-4 m-b-xs">
                    <multiselect v-model='city'
                        deselect-label
                        track-by="id"
                        label="city"
                        :searchable="true" 
                        open-direction="bottom"
                        placeholder="Filter By City"
                        :options="cities"
                        @input="getProduct()"
                        :disabled="false"
                        ></multiselect>
                    </div>
                    <div class="col-sm-3 m-b-xs">
                      <v2-datepicker-range lang="en" format="yyyy-MM-DD" v-model="rangeDate" :picker-options="pickerOptions" @change="getProduct()"></v2-datepicker-range>
                    </div>

                    <div class="col-sm-1">
                        <button class="btn btn-primary" @click="clearFilter()">Clear Filter</button>
                    </div>
                </div>
    <div class="ibox-content">
        <div class="row" style="margin-top: 15px;" v-if="!isLoading">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>OrderID </th>
                        <th>Order Date </th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Payment </th>
                        <th>Delivery </th>
                        <th>Item Qty </th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="value in products.data" :key="value.id">
                        <td>{{ value.id }}</td>
                        <td>{{ value.order_date }}</td>
                        <td>{{ value.customer_name }}</td>
                        <td>{{ value.phone }}</td>
                        <td>
                            <span v-if="value.payment_status == 1">Paid</span>
                            <span v-else>Unpaid</span>
                        </td>
                        <td>
                            <span v-if="value.status == 0">Pending</span>
                            <span v-if="value.status == 1">On Process</span>
                            <span v-if="value.status == 2">On Delivery</span>
                            <span v-if="value.status == 3">Delivered</span>
                        </td>
                        <td>{{ value.total_item }}</td>
                        <td>{{ value.total_amount - value.coupon_discount }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>   
        </div>

        <div class="col-md-12 text-center" v-else>
            <img :src="url+'images/loading.gif'">
        </div>
    </div>

    </div>
</div>

<div class="ibox animated fadeInRightBig">
        <div class="row">
            <div class="col-md-9">
                <pagination v-if="products" :pageData="products"></pagination>
            </div>
            <div class="col-md-3">
                <a :href="url+'admin/export?req=invoice&range='+this.rangeDate+'&city='+this.city.id" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
                <a :href="url+'admin/product-invoice-report-pdf?range='+this.rangeDate+'&city='+this.city.id" class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>

                <a :href="url+'admin/product-invoice-report-print?range='+this.rangeDate+'&city='+this.city.id" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
            </div>
        </div>
     
    </div>

</div>

</div>
</template>

<script>

    import { EventBus } from  '../../../vue-assets';
    import Mixin from  '../../../mixin';
    import Pagination from  '../pagination/Pagination';
    import Multiselect from 'vue-multiselect'
    
    export default {

        mixins : [Mixin],
        components : {
           'pagination' : Pagination,
           Multiselect,
       },

       data(){
           return {
              rangeDate: '',
                pickerOptions: {
                    shortcuts: [{
                        text: 'Last Week',
                        onClick (picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                            picker.$emit('pick', [start, end]);
                        }
                    }, {
                        text: 'Last Month',
                        onClick (picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                            picker.$emit('pick', [start, end]);
                        }
                    }, {
                        text: 'Last 3 Month',
                        onClick (picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                            picker.$emit('pick', [start, end]);
                        }
                    }]
                },
                city : '',
                cities : [],
                products : [],            
                isLoading : false,
                isCategoryLoading : false,
                isSubCategoryLoading : false,
                isLoading : false,
                url : base_url
        }
    },
 
    mounted(){
        var _this = this;
        _this.getProduct();
        _this.getCity();
    },
    methods : {
        getProduct(page = 1){
         this.isLoading = true;
         // console.log(this.range)
         axios.get(base_url+'admin/product-invoice-report?page='+page+'&range='+this.rangeDate+'&city='+this.city.id
            )
         .then(response => {
             this.products = response.data;
             this.isLoading = false;
         });
     },

     pageClicked(pageNo){
        var vm = this;
        vm.getProduct(pageNo);
    },
   
        clearFilter(){
           this.rangeDate = '';
           this.city = '';
           this.getProduct();
       },

    getCity()
        {

           axios.get(base_url+'admin/all-cities')
           .then(response => {
              this.cities = response.data;

          });

       },
}
}
</script>

<style scoped="">
    .cut-text {
       
       text-decoration: line-through 2px red;
    }
</style>