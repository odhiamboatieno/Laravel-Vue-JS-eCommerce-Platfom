<template>
<div class="row">
    <div class="col-lg-12" >
        <div class="ibox animated fadeInRightBig">
            <div class="ibox-title">
                <h5>role List</h5>
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
                             @keyup="getrole()"> 
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
                            <th>Role Name</th>
                            <th>Permissions</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(value,index) in roles.data" :key="index">
                            <td>{{ value.role_name }}</td>
                            <td><a @click.prevent="perMission(value.id)" href="" class="btn btn-secondary rounded"><i class="fa fa-key"></i></a></td>
                            <td>
                                <a @click.prevent="edit(value)" class="btn btn-primary" href="#"><i class="fa fa-edit" title="Edit"></i></a> 
                                <a @click.prevent="deleterole(value.id)" class="btn btn-danger" href="#"><i class="fa fa-trash" title="Delete"></i></a>
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
           <pagination v-if="roles" :pageData="roles"></pagination> 
        </div>

        <div class="ibox">
            <edit-role></edit-role>
            <role-permission></role-permission>
        </div>
    </div>





</div>
</template>

<script>

    import { EventBus } from  '../../../vue-assets';

    import Mixin from  '../../../mixin';

    import Pagination from  '../pagination/Pagination';

    import UpdateRole from './EditRole';
    import Permission from './Permission';
	
	export default {

        mixins : [Mixin],

        components : {
         
         'pagination' : Pagination,
         'edit-role' : UpdateRole,
         'role-permission' : Permission,

        },

       data(){
         
         return {
            
            roles : [],

            isLoading : false,

            keyword : '',

            url : base_url,

         }

       },

       mounted(){


        // this  will not work in eventBus that why 
        // we are initializing with _this

        var _this = this;

        _this.getrole();

        EventBus.$on('role-created',function(){

            // getting updated data when insert update delete happend 

        _this.getrole();


        });



       },

       methods : {
       

        getrole(page = 1){
         this.isLoading = true;
         axios.get(base_url+'admin/role-list?page='+page+'&keyword='+this.keyword)
              .then(response => {
               this.roles = response.data;
               this.isLoading = false;
              });
        },

        pageClicked(pageNo){
        var vm = this;
        vm.getrole(pageNo);
        },

        // edit role 

        edit(id){           
            EventBus.$emit('update-role',id);
        },

        perMission(id){

          EventBus.$emit('assign-permission',id);

        },


        // delete role 

        deleterole(id){
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

                axios.get(base_url+'admin/role/delete/'+id)
                .then(res => {

                    this.successMessage(res.data);
                    this.getrole();
                })
            }
        }) 

       },



       }

	}

</script>