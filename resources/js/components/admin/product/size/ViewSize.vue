<template>
    <div class="row">
        <div class="col-lg-12" >
            <div class="ibox animated fadeInRightBig">
                <div class="ibox-title">
                    <h5>Color List</h5>
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
                   <div class="col-sm-3">
                        <div class="input-group">
                            <input placeholder="Search By Name" type="text" class="form-control form-control-sm" 
                            v-model="keyword"
                            @keyup="getSizes()"> 
                            <span class="input-group-append">
                                  <!--    <button type="button" class="btn btn-sm btn-primary">Go!
                                  </button> -->
                              </span>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary" @click="clearFilter()">Clear Filter</button>
                    </div>
                    </div>
                        <div class="table-responsive" style="margin-top: 15px;" v-if="!isLoading">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL No.</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(size,index) in sizes.data" :key="index">
                                        <td> {{ index+1 }} </td>
                                        <td> {{ size.name }} </td>
                                        <td>{{ size.category.category_name }}</td>
                                    <td>
                                        <a @click.prevent="edit(size)" class="btn btn-primary" href="#"><i class="fa fa-edit" title="Edit"></i></a> 
                                        <a @click.prevent="deleteSize(size.id)" class="btn btn-danger" href="#"><i class="fa fa-trash" title="Delete"></i></a>
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
         <pagination v-if="sizes" :pageData="sizes"></pagination> 
     </div>

     <div class="ibox">
        <update-size :categories="categories"></update-size>
    </div>
</div>

</div>
</template>

<script>

    import { EventBus } from  '../../../../vue-assets';

    import Mixin from  '../../../../mixin';

    import Pagination from  '../../pagination/Pagination';
    import UpdateSize from './EditSize';
    
    export default {

        mixins : [Mixin],
        props: ['categories'],
        components : {

           'pagination' : Pagination,
            UpdateSize,
       },

       data(){

        return {

            sizes : [],
           
            isLoading : false,
    
            keyword : '',

            url : base_url,
        }
    },

    mounted(){

        var _this = this;
         _this.getSizes()
        EventBus.$on('size-created',function(){
            _this.getSizes()
        });

    },

    methods : {

    getSizes(page=1){
       this.isLoading = true;
         
         axios.get(base_url+'admin/size-list?page='+page+'&keyword='+this.keyword)
         .then(response => {
             
             this.sizes = response.data;
            //  console.log(this.sizes)
             this.isLoading = false;

         });
    },
       
    pageClicked(pageNo){
        var vm = this;
        vm.getSizes(pageNo);
    },

       

    edit(size){

        EventBus.$emit('update-size',size);
    },

   
        // delete category 

        deleteSize(id){
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

                    axios.delete(base_url+'admin/product-size/'+id)
                    .then(res => {

                        this.successMessage(res.data);
                        this.getSizes();
                    })
                }
            }) 

        },

    
        clearFilter(){
           this.keyword = '';
           this.sizes  = [];
           this.getSizes()
       },

}

}

</script>

<style scoped="">
    .cut-text {
       
       text-decoration: line-through 2px red;

    }
</style>