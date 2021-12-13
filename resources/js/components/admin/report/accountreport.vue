<template>
<div class="row">
    <div class="col-lg-12" >
        <div class="ibox animated fadeInRightBig">
            <div class="ibox-title">
                <h5>Sales List</h5>
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
                    <div class="col-sm-3 m-b-xs">
                        <multiselect
                        v-model="paymethod"
                        deselect-label
                        track-by="id"
                        label="provider"
                        :searchable="true" 
                        open-direction="bottom"
                        placeholder="Filter By Provider"
                        :options="allmethod"
                        :disabled="false"
                        @input="getMethodData()"
                        ></multiselect>
                    </div>
                <div class="col-sm-3 m-b-xs">
                  <v2-datepicker lang="en" format="yyyy-MM-DD" v-model="singleDate" :picker-options="pickerOptions2" @change="getMethodData()"></v2-datepicker>
                </div>
                <div class="col-sm-3 m-b-xs">
                  <v2-datepicker-range lang="en" format="yyyy-MM-DD" v-model="rangeDate" :picker-options="pickerOptions" @change="getMethodData()"></v2-datepicker-range>
                </div>

                <div class="col-sm-2 m-b-xs">
                    <button class="btn btn-primary" @click="clearFilter()">Clear Filter</button>
                </div>
            </div>
    <div class="ibox-content">
        <div class="row" style="margin-top: 15px;" v-if="!isLoading">
            <div class="table-responsive text-center">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Method </th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="value in pay_amount.data" :key="value.id">
                        <td>{{ value.payment_date }}</td>
                        <td>{{ value.provider.provider }}</td>
                        <td>{{ value.amount }}</td>
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
                <pagination v-if="pay_amount" :pageData="pay_amount"></pagination>
            </div>
            <div class="col-md-3">
                <a :href="url+'admin/export?req=payment&methods='+this.paymethod.id+'&range='+this.rangeDate+'&singledate='+this.singleDate" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
                <a :href="url+'admin/product-amount-report-pdf?methods='+this.paymethod.id+'&range='+this.rangeDate+'&singledate='+this.singleDate" class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>

                <a :href="url+'admin/product-amount-report-print?methods='+this.paymethod.id+'&range='+this.rangeDate+'&singledate='+this.singleDate" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
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
                pickerOptions2: {
                shortcuts: [{
                    text: 'Today',
                    onClick (picker) {
                        picker.$emit('pick', new Date());
                    }
                }, {
                    text: 'Yesterday',
                    onClick (picker) {
                        const date = new Date();
                        date.setTime(date.getTime() - 3600 * 1000 * 24);
                        picker.$emit('pick', date);
                    }
                }, {
                    text: 'A week ago',
                    onClick (picker) {
                        const date = new Date();
                        date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
                        picker.$emit('pick', date);
                    }
                }]
            },

            singleDate : '',  
            paymethod : '',
            pay_amount : [],
            allmethod : [],
            isLoading : false,
            keyword : '',
            url : base_url
        }

    },

    mounted(){

        var _this = this;
        _this.getMethodData();
        _this.getMethod();
    },

    methods : {

        getMethodData(page = 1){

         this.isLoading = true;
         axios.get(base_url+'admin/payment-method-wise-amount?page='+page+'&methods='+this.paymethod.id+'&range='+this.rangeDate+'&singledate='+this.singleDate
            )
         .then(response => {
             this.pay_amount = response.data;
             this.isLoading = false;

         });

     },

    pageClicked(pageNo){
        var vm = this;
        vm.getMethodData(pageNo);
    },

    getMethod()
    {
        axios.get(base_url+'admin/get-payment-method')
        .then(response => {
            this.allmethod = response.data;
        });
    },

        clearFilter(){
            this.singleDate = '';
           this.rangeDate  = '';
           this.paymethod  = '';
           this.pay_amount = [];
           this.allmethod  = [];
           this.getMethodData();

       },

}

}

</script>

<style scoped="">
    .cut-text {
       
       text-decoration: line-through 2px red;

    }
</style>