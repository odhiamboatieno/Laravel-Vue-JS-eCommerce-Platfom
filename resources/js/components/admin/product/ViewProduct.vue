<template>
    <div class="row">
        <div class="col-lg-12" >
            <div class="ibox animated fadeInRightBig">
                <div class="ibox-title">
                    <h5>Product List</h5>
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
                        <div class="col-sm-2 m-b-xs">
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
                        <div class="col-sm-2 m-b-xs">
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

                        <div class="col-sm-2 m-b-xs">
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

                        <div class="col-sm-2 m-b-xs">
                            <multiselect
                            v-model="brand"
                            deselect-label
                            track-by="id"
                            label="brand_name"
                            :loading="isBrandLoading"
                            :searchable="true" 
                            open-direction="bottom"
                            placeholder="Filter By Brand"
                            :options="brands"
                            :disabled="false"
                            @input="getProduct()"
                            ></multiselect>
                        </div>
                        <div class="col-sm-2 m-b-xs">
                            <div class="input-group">
                                <input placeholder="Search By Keyword" type="text" class="form-control" 
                                v-model="keyword"
                                @keyup="getProduct()">
                            </div>

                        </div>

                        <div class="col-sm-2">
                            <button class="btn btn-primary" @click="clearFilter()">Clear Filter</button>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 15px;" v-if="!isLoading">
                        <div class="col-md-3" v-for="value in products.data" :key="value.id">
                            <div class="ibox">
                                <div class="ibox-content product-box">

                                    <div class="product-imitation">
                                        <img class="img-fluid" v-lazy="value.feature_image">
                                    </div>
                                    <div class="product-desc">
                                        <span style="left: 0px !important" class="product-price">
                                            Qty {{ value.current_quantity }}
                                        </span>                                           
                                        <span class="product-price">
                                            Price :  

                                            <span v-if="value.discount_status == 1 && value.discount_amount > 0" class="cut-text">
                                               {{ currency.symbol }}  {{ value.selling_price }} 
                                            </span>

                                            &nbsp; &nbsp;

                                            <span style="font-weight: bold" v-if="value.discount_status == 1">
                                            {{ currency.symbol }} 
                                            {{ value.selling_price - value.discount_amount }}
                                           </span>                                           

                                            <span style="font-weight: bold" v-else>
                                              {{ currency.symbol }} {{ value.selling_price }}
                                            </span>
                                        </span>
                                        <small class="text-muted">{{ value.category.category_name }} -> </small> 
                                        <small class="text-muted">{{ value.sub_category.sub_category_name }} -></small>
                                        <small class="text-muted">{{ value.sub_sub_category.sub_sub_category_name }} -></small>
                                        <small class="text-muted">{{ value.brand.brand_name }} </small>
                                        <a href="#" class="product-name"> {{ value.product_name }}</a>

                                        <div class="small m-t-sm">
                                          Status  : {{ value.status_text }} 
                                      </div>
                                      <div>
                                         <span>Hot Deal 
                                          <div class="switch">
                                            <div class="onoffswitch">
                                                <input @change="hotDeal(value.id)" type="checkbox" :checked="value.hot_deal == 1 ? true : false " class="onoffswitch-checkbox" :id="value.id">
                                                <label class="onoffswitch-label" :for="value.id">
                                                    <span class="onoffswitch-inner"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                                <div class="m-t text-righ">                   
                                    <a @click.prevent="Deactive(value.id,value.status)" href="" class="btn btn-sm btn-outline btn-primary" title="Make Active Or Deactive">

                                        <span v-if="value.status == 1"><i class="fa fa-check"></i> Deactivate</span>
                                        <span v-else><i class="fa fa-check"></i> Activate</span>
                                    </a>                                                                                  
                                    <a @click.prevent="getdiscount(value.id)" href="" class="btn btn-sm btn-outline btn-info"><i class="fa fa-fire"></i> Discount</a>  
                                     <a @click.prevent="edit(value.id)" href="" title="Edit Product" class="btn btn-sm btn-outline btn-warning"><i class="fa fa-edit"></i></a>                            
                                    <a @click.prevent="deleteProduct(value.id)" title="Delete Product" href="#" class="btn btn-sm btn-outline btn-danger"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 text-center" v-else>
                <img :src="url+'images/loading.gif'">
            </div>

        </div>
    </div>

    <div class="ibox animated fadeInRightBig">
     <pagination v-if="products.meta" :pageData="products.meta"></pagination> 
    </div>

 <div class="ibox">
    <update-product :categories="categories" :trial_setting="trial_setting"></update-product>
</div>
<div class="ibox">
    <discount-product :categories="categories"></discount-product>
</div>
<div class="ibox">
    <bulk-storage></bulk-storage>
</div>
</div>





</div>
</template>

<script>

    import { EventBus } from  '../../../vue-assets';

    import Mixin from  '../../../mixin';

    import Pagination from  '../pagination/Pagination';
    import Multiselect from 'vue-multiselect'

    import UpdateProduct from './UpdateProduct';
    import DiscountProduct from './DiscountProduct';
    import BulkStorage from './Bulkstock';
    
    export default {

        mixins : [Mixin],

        props : ['currency','trial_setting'],

        components : {

           'pagination' : Pagination,
           'update-product' : UpdateProduct,
           Multiselect,DiscountProduct,BulkStorage

       },

       data(){

           return {

            products : [],
            category : '',
            sub_category : '',
            sub_sub_category : '',
            brand : '',

            categories : [],
            sub_categories : [],
            sub_sub_categories : [],
            brands : [],

            isLoading : false,
            isCategoryLoading : false,
            isSubCategoryLoading : false,
            isBrandLoading : false,

            keyword : '',

            url : base_url,
        }
    },

    mounted(){


        // this  will not work in eventBus that why 
        // we are initializing with _this

        var _this = this;

        _this.getProduct();
        _this.getCategories();

        EventBus.$on('product-created',function(){
        // getting updated data when insert update delete happend 
        _this.getProduct();
        location.reload();

    });



    },

    methods : {


        getProduct(page = 1){

         this.isLoading = true;
         
         axios.get(base_url+'admin/product-list?page='+page+
            '&keyword='+this.keyword+
            '&category='+this.category.id + 
            '&sub_category='+this.sub_category.id+ 
            '&sub_sub_category='+this.sub_sub_category.id+ 
            '&brand='+this.brand.id
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

        // edit category

        getCategories()
        {

           axios.get(base_url+'admin/all-categories/'+'yes')
           .then(response => {

              this.categories = response.data;

          });

       },

       edit(id){

        EventBus.$emit('update-product',id);
    },

    getdiscount(id){
        EventBus.$emit('discount-product',id);
    },

        // delete category 

        deleteProduct(id){
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

                    axios.get(base_url+'admin/product/delete/'+id)
                    .then(res => {

                        this.successMessage(res.data);
                        this.getProduct();
                    })
                }
            }) 

        },

        getSubCategory()
        {
            // at first get product 
            this.getProduct();

            this.sub_category   = ''; 
            this.brand          = '';    
            this.sub_categories = [];
            this.brands = [];
            if(this.category == ''){

                this.sub_categories = [];

            }
            else{
                this.isCategoryLoading = true;
                axios.get(base_url+'admin/get-subcategory/'+this.category.id)
                .then(response => {
                    this.sub_categories = response.data;
                    // console.log(response.data.sub_Categories)
                    this.isCategoryLoading = false;

                });

            }

        },
		getSubSubCategories(){

            // at first get product 
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

            // at first get product 
            this.getProduct();

                this.brand = '';
            	this.brands = [];	
            	if(this.sub_sub_category === ''){

            		this.brands = [];

            	}
            	else
            	{
            		this.isBrandLoading = true;
            		axios.get(base_url+'admin/get-brand/'+this.sub_sub_category.id)
            		.then(response => {
            			this.brands = response.data;
            			this.isBrandLoading = false;
            		});

            	}

            },

        clearFilter(){

           this.category       = '';
           this.sub_category   = '';
           this.sub_sub_category   = '';
           this.brand          = '';
           this.keyword        = '';
           this.sub_sub_categories = [];
           this.brands         = [];

           this.getProduct();

       },

       Deactive(id,status)
       {

         // 'product/image/{id}/delete'

         Swal.fire({
            title: 'Are you sure ?',
            text: status == 1 ? "The Product Will Be Deactiveted" : "The Product Will Be Activeted",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        },() => {

        }).then((result) => {
            if (result.value) {

                axios.get(base_url+'admin/product/deactive/'+id)
                .then(res => {

                    this.successMessage(res.data);
                    this.getProduct();
                })
            }
        })

    },

    hotDeal(id){

      axios.get(base_url+'admin/product/hotdeal-status/'+id)
      .then(response => {
        this.successMessage(response.data);
    });

  }

}

}

</script>

<style scoped="">
    .cut-text {
       
       text-decoration: line-through 2px red;

    }
</style>