<template>
<div class="row">
    <div class="col-lg-12" >
        <div class="ibox animated fadeInRightBig">
            <div class="ibox-title">
                <h5>Category List</h5>
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
                             @keyup="getCategory()"> 
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
                            <th>Icon</th>
                            <th>Category Name</th>
                            <th>Native Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(value,index) in categories.data" :key="index">
                            <td><img v-lazy="value.image" style="max-height: 100px;"></td>
                            <td>{{ value.category_name }}</td>
                            <td>{{ value.category_native_name }}</td>
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
           <pagination v-if="categories.meta" :pageData="categories.meta"></pagination> 
        </div>

        <div class="ibox">
            <update-category></update-category>
        </div>
    </div>





</div>
</template>

<script>

    import { EventBus } from  '../../../vue-assets';

    import Mixin from  '../../../mixin';

    import Pagination from  '../pagination/Pagination';

    import UpdateCategory from './EditCategory';
	
	export default {

        mixins : [Mixin],

        components : {
         
         'pagination' : Pagination,
         'update-category' : UpdateCategory,

        },

       data(){
         
         return {
            
            categories : [],

            isLoading : false,

            keyword : '',

            url : base_url,

         }

       },

       mounted(){


        // this  will not work in eventBus that why 
        // we are initializing with _this

        var _this = this;

        _this.getCategory();

        EventBus.$on('category-created',function(){

            // getting updated data when insert update delete happend 

        _this.getCategory();


        });



       },

       methods : {
       

        getCategory(page = 1){

           this.isLoading = true;
         
         axios.get(base_url+'admin/category-list?page='+page+'&keyword='+this.keyword)
              .then(response => {
               
               this.categories = response.data;
               this.isLoading = false;

              });

        },

        pageClicked(pageNo){
        var vm = this;
        vm.getCategory(pageNo);
        },

        // edit category 

        edit(id){
            
            

            EventBus.$emit('update-category',id);
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

                axios.get(base_url+'admin/category/delete/'+id)
                .then(res => {

                    this.successMessage(res.data);
                    this.getCategory();
                })
            }
        }) 

       },



       }

	}

</script>