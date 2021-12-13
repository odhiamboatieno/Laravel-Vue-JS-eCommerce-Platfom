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
                    <div class="col-sm-3 m-b-xs pb-1">
                        <multiselect
                        v-model="category"
                        deselect-label
                        track-by="id"
                        label="category_name"
                        :searchable="true" 
                        open-direction="bottom"
                        placeholder="Filter By Category"
                        :options="categories"
                        @input="getSubCategory()"
                        :disabled="false"
                        ></multiselect>
                    </div>
                    <div class="col-sm-3 m-b-xs pb-1">
                        <multiselect
                        v-model="sub_category"
                        deselect-label
                        track-by="id"
                        label="sub_category_name"
                        :loading="isCategoryLoading"
                        :searchable="true" 
                        open-direction="bottom"
                        placeholder="By Sub Category"
                        :options="sub_categories"
                        @input="getSubSubCategories()"
                        :disabled="false"
                        ></multiselect>
                    </div>

                    <div class="col-sm-3 m-b-xs pb-1">
                        <multiselect
                        v-model="sub_sub_category"
                        deselect-label
                        track-by="id"
                        label="sub_sub_category_name"
                        :loading="isSubCategoryLoading"
                        :searchable="true" 
                        open-direction="bottom"
                        placeholder="By Sub Sub Category"
                        :options="sub_sub_categories"
                        @input="getBrand()"
                        :disabled="false"
                        ></multiselect>
                    </div>

                    <div class="col-sm-3 m-b-xs pb-1">
                        <multiselect
                        v-model="brand"
                        deselect-label
                        track-by="id"
                        label="brand_name"
                        :searchable="true" 
                        open-direction="bottom"
                        placeholder="Filter By Brand"
                        :options="brands"
                        :disabled="false"
                        @input="getProduct()"
                        ></multiselect>
                    </div>
                    <div class="col-sm-3 m-b-xs pb-1">
                      <v2-datepicker-range lang="en" format="yyyy-MM-DD" v-model="rangeDate" :picker-options="pickerOptions" @change="getProduct()"></v2-datepicker-range>
                    </div>
                    <div class="col-sm-3 m-b-xs pb-1">
                        <div class="input-group">
                            <input placeholder="Search By Keyword" type="text" class="form-control" 
                            v-model="keyword"
                            @keyup="getProduct()">
                        </div>

                    </div>

                    <div class="col-sm-2 pb-1">
                        <button class="btn btn-primary" @click="clearFilter()">Clear Filter</button>
                    </div>
                </div>
    <div class="ibox-content">
        <div class="row" style="margin-top: 15px;" v-if="!isLoading">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>P-Code </th>
                        <th>Product </th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>SubSubCategory</th>
                        <th>Brand </th>
                        <th>Total Sale Qty</th>
                        <th>Total Buying Amount</th>
                        <th>Total Sales Amount</th>
                        <th>Profit</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="value in products.data" :key="value.id">
                        <td>{{ value.product.id }}</td>
                        <td>{{ value.product.product_name }}</td>
                        <td>{{ value.category.category_name }}</td>
                        <td>{{ value.sub_category.sub_category_name }}</td>
                        <td>{{ value.sub_sub_category.sub_sub_category_name }}</td>
                        <td>{{ value.brand.brand_name }}</td>
                        <td>{{ value.total_sold_qty }}</td>
                        <td>{{ value.total_buying_amount }}</td>
                        <td>{{ value.total_sales_amount }}</td>
                        <td>{{ value.total_sales_amount - value.total_buying_amount }}</td>
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
                <a :href="url+'admin/export?req=sales&category='+this.category.id + '&sub_category='+this.sub_category.id+'&sub_sub_category='+this.sub_sub_category.id+'&brand='+this.brand.id+'&range='+this.rangeDate" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>
                <a :href="url+'admin/product-sales-report-pdf?category='+this.category.id + '&sub_category='+this.sub_category.id+'&sub_sub_category='+this.sub_sub_category.id+'&brand='+this.brand.id+'&range='+this.rangeDate" class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>

                <a :href="url+'admin/product-sales-report-print?category='+this.category.id + '&sub_category='+this.sub_category.id+'&sub_sub_category='+this.sub_sub_category.id+'&brand='+this.brand.id+'&range='+this.rangeDate" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
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
                
                products : [],
                category : '',
                sub_category : '',
                brand : '',
                sub_sub_category : '',

                categories : [],
                sub_categories : [],
                brands : [],
                sub_sub_categories : [],

                isLoading : false,
                isCategoryLoading : false,
                isSubCategoryLoading : false,

                keyword : '',
                isLoading : false,

                keyword : '',

                url : base_url
        }

    },

    mounted(){

        var _this = this;

        _this.getProduct();
        _this.getCategories();

    },

    methods : {

        getProduct(page = 1){

         this.isLoading = true;
         axios.get(base_url+'admin/product-sale-report?page='+page+
            '&keyword='+this.keyword+
            '&category='+this.category.id + 
            '&sub_category='+this.sub_category.id+
            '&sub_sub_category='+this.sub_sub_category.id+
            '&brand='+this.brand.id+
            '&range='+this.rangeDate
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
    getCategories()
        {
           axios.get(base_url+'admin/all-categories/'+'yes')
           .then(response => {
              this.categories = response.data;
          });

       },

        getSubCategory()
        {
            this.getProduct();
            this.sub_category   = ''; 
            this.brand          = '';    
            this.sub_categories = [];
            this.brands = [];
            if(this.category === ''){
                this.sub_categories = [];
            }
            else{
                this.isCategoryLoading = true;
                axios.get(base_url+'admin/get-subcategory/'+this.category.id)
                .then(response => {
                    this.sub_categories = response.data;
                    this.isCategoryLoading = false;
                    this.getProduct();
                });

            }

        },

        getSubSubCategories(){
            this.getProduct();
            this.sub_sub_category = '';
            this.brand = '';
            this.sub_sub_categories = [];
            this.brands = [];
            if(this.sub_category === '')
            {
                this.sub_sub_categories = [];
            }
            else
            {
                this.isSubCategoryLoading = true;
                axios.get(base_url+'admin/get-sub-subcategory/'+this.sub_category.id)
                     .then(response => {
                         this.sub_sub_categories = response.data;
                         this.isSubCategoryLoading = false;
                     })
            }

        },
        getBrand()
        {
            this.getProduct();
            this.brand  = '';
            this.brands = [];   
            if(this.sub_category === ''){
                this.brands = [];
            }
            else
            {
                this.isSubCategoryLoading = true;
                axios.get(base_url+'admin/get-brand/'+this.sub_category.id)
                .then(response => {
                    this.brands = response.data;
                    this.isSubCategoryLoading = false;
                });

            }

        },

        clearFilter(){

           this.rangeDate      = '';
           this.category       = '';
           this.sub_category   = '';
           this.sub_sub_category = '';
           this.brand          = '';
           this.keyword        = '';
           this.sub_categories = [];
           this.brands         = [];
           this.sub_sub_categories = [];

           this.getProduct();

       },

}

}

</script>

<style scoped="">
    .cut-text {
       
       text-decoration: line-through 2px red;

    }
</style>