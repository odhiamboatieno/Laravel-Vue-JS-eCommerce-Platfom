<template>
   <div class="row">
    <div class="col-lg-12" >
        <div class="ibox animated fadeInRightBig">
            <div class="ibox-title">
                <h5>Sub Category List</h5>
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

                    <div class="col-sm-4 m-b-xs">
                      <multiselect
                        v-model="category"
                        deselect-label
                        track-by="id"
                        label="category_name"

                        :searchable="true" 
                       
                        open-direction="bottom"
                        placeholder="Filter By Category"
                        :options="categories"
                        @input="sub_category()"
                        :disabled="false"
                      ></multiselect>
                    </div>

                    <div class="col-sm-3">
                        <div class="input-group">
                            <input placeholder="Search By Name" type="text" class="form-control" 
                            v-model="keyword"
                            @keyup="sub_category()"> 
                            <span class="input-group-append">
                                  <!--    <button type="button" class="btn btn-sm btn-primary">Go!
                                  </button> -->
                              </span></div>

                    </div>


                    <div class="col-sm-5 m-b-xs">
                      <button @click="clearFilter()" class="btn btn-primary">Clear Filter</button>
                    </div>


                      </div>
                      <div class="table-responsive" style="margin-top: 15px;" v-if="!isLoading">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>

                                    <th>Icon</th>
                                    <th>Sub Category Name</th>
                                    <th>Native Name</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(value,index) in sub_categories.data" :key="index">

                                    <td><img v-lazy="value.image" style="max-height: 100px;"></td>
                                    <td>{{ value.sub_category_name }}</td>
                                    <td>{{ value.sub_category_native_name }}</td>
                                    <td>{{ value.category.category_name }}</td>
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
             <pagination v-if="sub_categories.meta" :pageData="sub_categories.meta"></pagination> 
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

    import UpdateCategory from './EditSubCategory';

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

            isLoading : false,

            keyword : '',

            category : {

                id : '',
                category_name : 'Filter By Category',
                category_native_name : 'Filter By Category',
            },

            url : base_url,

        }

    },

    mounted(){


        // this  will not work in eventBus that why 
        // we are initializing with _this

         // $(".select2_demo_2").select2();

         var _this = this;

         _this.sub_category();

         EventBus.$on('sub-category-created',function(){

            // getting updated data when insert update delete happend 

            _this.sub_category();


        });



     },

     methods : {


        sub_category(page = 1){

         this.isLoading = true;
         
         axios.get(base_url+'admin/sub-category-list?page='+page+'&keyword='+this.keyword+'&category='+this.category.id)
         .then(response => {

             this.sub_categories = response.data;
             this.isLoading = false;

         });

     },

     pageClicked(pageNo){
        var vm = this;
        vm.sub_category(pageNo);
    },

        // edit category 

        edit(id){



            EventBus.$emit('update-sub-category',id);

            
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

                    axios.get(base_url+'admin/sub-category/delete/'+id)
                    .then(res => {

                        this.successMessage(res.data);
                        this.sub_category();
                    })
                }
            }) 

        },

        clearFilter(){
        
         this.keyword = '';
         this.category = {

                id : '',
                category_name : 'Filter By Category',
                category_native_name : 'Filter By Category',
            }

         this.sub_category();   

        },



    }

}

</script>
