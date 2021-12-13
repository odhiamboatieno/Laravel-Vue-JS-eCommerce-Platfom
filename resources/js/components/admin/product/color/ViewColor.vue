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
                            @keyup="getColors()"> 
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
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Color</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(color,index) in colors.data" :key="index">
                                        <td> {{ index+1 }} </td>
                                        <td> {{ color.name }} </td>
                                        <td>{{ color.color_code }}</td>
                                        <td><svg width="60" height="25">
                                                <rect width="60" height="20" :style="'stroke-width:3;stroke:rgb(0,0,0);fill:'+color.color_code" />
                                            </svg>
                                        </td>
                                    <td>
                                        <a @click.prevent="edit(color)" class="btn btn-primary" href="#"><i class="fa fa-edit" title="Edit"></i></a> 
                                        <a @click.prevent="deleteColor(color.id)" class="btn btn-danger" href="#"><i class="fa fa-trash" title="Delete"></i></a>
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
         <pagination v-if="colors" :pageData="colors"></pagination> 
     </div>

     <div class="ibox">
        <update-color></update-color>
    </div>
</div>

</div>
</template>

<script>

    import { EventBus } from  '../../../../vue-assets';

    import Mixin from  '../../../../mixin';

    import Pagination from  '../../pagination/Pagination';
    import UpdateColor from './EditColor.vue';
    
    export default {

        mixins : [Mixin],

        components : {

           'pagination' : Pagination,
            UpdateColor,
       },

       data(){

        return {

            colors : [],
           
            isLoading : false,
    
            keyword : '',

            url : base_url,
        }
    },

    mounted(){


        // this  will not work in eventBus that why 
        // we are initializing with _this

        var _this = this;
         _this.getColors()
        EventBus.$on('color-created',function(){
        // getting updated data when insert update delete happend
            _this.getColors()
        });



    },

    methods : {

    getColors(page=1){
       this.isLoading = true;
         
         axios.get(base_url+'admin/color-list?page='+page+'&keyword='+this.keyword)
         .then(response => {
             
             this.colors = response.data;
            //  console.log(this.colors)
             this.isLoading = false;

         });
    },
       
    pageClicked(pageNo){
        var vm = this;
        vm.getColors(pageNo);
    },

       

    edit(color){

        EventBus.$emit('update-color',color);
    },

   
        // delete category 

        deleteColor(id){
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

                    axios.delete(base_url+'admin/product-color/'+id)
                    .then(res => {

                        this.successMessage(res.data);
                        this.getColors();
                    })
                }
            }) 

        },

    
        clearFilter(){
           this.keyword = '';
           this.colors  = [];
           this.getColors()
       },

}

}

</script>

<style scoped="">
    .cut-text {
       
       text-decoration: line-through 2px red;

    }
</style>