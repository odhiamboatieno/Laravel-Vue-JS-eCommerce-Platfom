<template>
<div class="row">
    <div class="col-lg-12" >
        <div class="ibox animated fadeInRightBig">
            <div class="ibox-title">
                <h5>Admin List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-wrench"></i>
                    </a>
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
                             @keyup="getadmin()"> 
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
                            <th>Image</th>
                            <th>Admin Name</th>
                            <th>Admin Email</th>
                            <th>Role Name</th>
                            <th>Area</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(value,index) in admins.data" :key="index">
                            <td>
                                <img v-if="value.avatar" v-lazy="url+'/images/admin/'+value.avatar" class="rounded-circle" style="max-height: 100px;">
                                <img v-else v-lazy="url+'/images/default_avatar.png'" class="rounded-circle" style="max-height: 100px;">
                            </td>
                            <td>{{ value.name }}</td>
                            <td>{{ value.email }}</td>
                            <td>{{ value.role.role_name }}</td>
                            <td>{{ value.area.city }}</td>
                            <td>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input @change="changeStatus(value.id)" type="checkbox" :checked="value.status" class="onoffswitch-checkbox" :id="value.id">
                                        <label class="onoffswitch-label" :for="value.id">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            <td>
                                <a @click.prevent="edit(value.id)" class="btn btn-primary" href="#"><i class="fa fa-edit" title="Edit"></i></a> 
                                <a @click.prevent="deleteadmin(value.id)" class="btn btn-danger" href="#"><i class="fa fa-trash" title="Delete"></i></a>
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
           <pagination v-if="admins" :pageData="admins"></pagination> 
        </div>

        <div class="ibox">
            <update-admin></update-admin>
        </div>
    </div>

</div>
</template>

<script>

    import { EventBus } from  '../../../vue-assets';

    import Mixin from  '../../../mixin';

    import Pagination from  '../pagination/Pagination';

    import Updateadmin from './Editadmin';
	
	export default {

        mixins : [Mixin],

        components : {
         'pagination' : Pagination,
         'update-admin' : Updateadmin,
        },

       data(){
         
         return {
            
            admins : [],

            isLoading : false,

            keyword : '',

            url : base_url,

         }

       },

       mounted(){


        // this  will not work in eventBus that why 
        // we are initializing with _this

        var _this = this;

        _this.getadmin();

        EventBus.$on('admin-created',function(){

            // getting updated data when insert update delete happend 

        _this.getadmin();


        });



       },

       methods : {
       

        getadmin(page = 1){

           this.isLoading = true;
         
         axios.get(base_url+'admin/admin-list?page='+page+'&keyword='+this.keyword)
              .then(response => {
               this.admins = response.data;
               this.isLoading = false;
               // console.log(this.admins);

              });

        },

        pageClicked(pageNo){
        var vm = this;
        vm.getadmin(pageNo);
        },

        // edit admin 

        edit(id){
            EventBus.$emit('update-admin',id);
        },

        // delete admin 

        deleteadmin(id){
                Swal.fire({
                    title: 'Are you sure ?',
                    text: "You won't be able to revert this!",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                },() => {

                }).then((result) => {
                    if (result.value) {

                        axios.get(base_url+'admin/admin/delete/'+id)
                        .then(res => {
                            this.successMessage(res.data);
                            this.getadmin();
                        })
                    }
                })

           },

        changeStatus(id){
            axios.get(base_url+'admin/admin/status/'+id)
              .then(response => {
               this.successMessage(response.data);
            });
        }
       }

	}

</script>