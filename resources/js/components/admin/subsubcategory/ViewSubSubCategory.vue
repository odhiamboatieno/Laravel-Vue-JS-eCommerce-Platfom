<template>
   <div class="row">
    <div class="col-lg-12" >
        <div class="ibox animated fadeInRightBig">
            <div class="ibox-title">
                <h5>Sub Sub Category List</h5>
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

                    <div class="col-sm-3 m-b-xs">
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

                    <div class="col-sm-3 m-b-xs">
                      <multiselect
                        v-model="sub_category"
                        deselect-label
                        track-by="id"
                        label="sub_category_name"

                        :searchable="true" 
                       
                        open-direction="bottom"
                        placeholder="Filter By Sub Category"
                        :options="sub_categories"
                        @input="subCategory()"
                        :disabled="false"
                      ></multiselect>
                    </div>

                    <div class="col-sm-3">
                        <div class="input-group">
                            <input placeholder="Search By Name" type="text" class="form-control" 
                            v-model="keyword"
                            @keyup="subCategory()"> 
                            <span class="input-group-append">
                                  <!--    <button type="button" class="btn btn-sm btn-primary">Go!
                                  </button> -->
                              </span></div>

                    </div>


                    <div class="col-sm-3 m-b-xs">
                      <button @click="clearFilter()" class="btn btn-primary">Clear Filter</button>
                    </div>


                      </div>
                      <div class="table-responsive" style="margin-top: 15px;" v-if="!isLoading">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>

                                    <th>Icon</th>
                                    <th>Sub Sub Category Name</th>
                                    <th>Native Name</th>
                                    <th>Parent Tree</th>
                                    <th>Brand</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(value,index) in sub_sub_categories.data" :key="index">

                                    <td><img v-lazy="value.image" style="max-height: 100px;"></td>
                                    <td>
                                        {{ value.sub_sub_category_name }}
                                    </td>
                                    <td>
                                        {{ value.sub_sub_category_native_name }}
                                    </td>
                                    <td>
                                        {{ value.category.category_name }} -> 
                                        {{ value.sub_category.sub_category_name }} -> 
                                        {{ value.sub_sub_category_name }}
                                    </td>

                                    <td><span v-for="br in value.sub_sub_category_brand"
                                     :key="br.id" class="label label-primary"
                                      style="margin-right: 2px;">{{ br.brand.brand_name }}</span>
                                      </td>
                                    <td>{{ value.status_text }}</td>
                                    <td>
                                        <a @click.prevent="edit(value.id)" class="btn btn-primary" href="#"><i class="fa fa-edit" title="Edit"></i></a> 
                                        <a @click.prevent="deleteCategory(value.id)" class="btn btn-danger" href="#"><i class="fa fa-trash" title="Delete"></i></a>
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
             <pagination v-if="sub_sub_categories.meta" :pageData="sub_sub_categories.meta"></pagination> 
         </div>

         <div class="ibox">
            <update-sub-category :categories="categories" :brands="brands"></update-sub-category>
        </div>
    </div>





</div>
</template>

<script>

    import { EventBus } from  '../../../vue-assets';

    import Mixin from  '../../../mixin';

    import Pagination from  '../pagination/Pagination';

    import UpdateCategory from './EditSubSubCategory';

    import Multiselect from 'vue-multiselect'


    export default {

        mixins : [Mixin],
        props : ['categories','brands'],

        components : {

           'pagination' : Pagination,
           'update-sub-category' : UpdateCategory,
           Multiselect,


       },

       data(){

           return {
            sub_categories : [],
            sub_sub_categories : [],
            // sub_sub_sub_categories : [],

            isLoading : false,

            keyword : '',

            category : {

                id : '',
                category_name : 'Filter By Category',
                category_native_name : '',
            },

            sub_category : {
                id : '',
                sub_category_name : 'Filter By Subcategory',
                sub_category_native_name : '',
            },

            url : base_url,

            isCategoryLoading : false,

        }

    },

    mounted(){


        // this  will not work in eventBus that why 
        // we are initializing with _this

         // $(".select2_demo_2").select2();

         var _this = this;

         _this.subCategory();

         EventBus.$on('sub-sub-category-created',function(){

            // getting updated data when insert update delete happend 

            _this.subCategory();


        });



     },

     methods : {


        subCategory(page = 1){

         this.isLoading = true;
         
         axios.get(base_url+'admin/sub-sub-category-list?page='+page+
         '&keyword='+this.keyword+
         '&category='+this.category.id+
         '&sub_category='+this.sub_category.id)
         .then(response => {

             this.sub_sub_categories = response.data;
             this.isLoading = false;

         });

     },

     pageClicked(pageNo){
        var vm = this;
        vm.subCategory(pageNo);
    },

        getSubCategory()
        {       
            // call sub sub category method before getting leleve 3 category 
            this.subCategory();
            // reset sub category 
            this.sub_category = {
                id: '',
                sub_category_name : 'Filter By Subcategory',
                sub_category_native_name : '',
            };
            // finally get sub category via category id 
            axios.get(base_url+'admin/get-subcategory/'+this.category.id)
            .then(response => {
                this.sub_categories = response.data;
            });
        },

        // edit category 

        edit(id){

            EventBus.$emit('update-sub-sub-category',id);
            
        },

        // delete category 

        deleteCategory(id){
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

                    axios.get(base_url+'admin/sub-sub-category/delete/'+id)
                    .then(res => {

                        this.successMessage(res.data);
                        this.subCategory();
                    })
                }
            }) 

        },

        clearFilter(){
        
         this.keyword = '';
         this.category = {

                id : '',
                category_name : 'Filter By Category',
                category_native_name : '',
            }
          
        this.sub_category = {
                id: '',
                sub_category_name : 'Filter By Subcategory',
                sub_category_native_name : '',
            }  

         this.subCategory();   

        },



    }

}

</script>
