<template>
<div class="row">
    <div class="col-lg-12" >
        <div class="ibox animated fadeInRightBig">
            <div class="ibox-title">
                <h5>Social Credential List</h5>
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
                <div class="table-responsive" style="margin-top: 15px;" v-if="!isLoading">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Provider</th>
                            <th>App ID/KEY</th>
                            <th>App Secret</th>
                            <th>Callback uri</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(value,index) in socials" :key="index">
                            <td>{{ value.provider }}</td>
                            <td>{{ value.app_id }}</td>
                            <td>{{ value.app_secret }}</td>
                            <td>{{ url+'login/'+value.provider+'/callback' }}</td>
                            <td>
                              <span v-if="value.status == 0" class="text-danger">Inactive</span>

                              <span v-else class="text-success">Active</span>
                            </td>
                            <td>
                                <a @click.prevent="edit(value)" class="btn btn-primary" href="#"><i class="fa fa-edit" title="Edit"></i></a> 
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

        <div class="ibox">
            <update-social></update-social>
        </div>
    </div>
</div>
</template>

<script>

    import { EventBus } from  '../../../../vue-assets';

    import Mixin from  '../../../../mixin';

    import Updatesocial from './Updatesocial';
	
	export default {

        mixins : [Mixin],

        components : {

         'update-social' : Updatesocial,

        },

       data(){
         return {  
            socials : [],
            isLoading : false,
            url : base_url,

         }

       },

       mounted(){
        // this  will not work in eventBus that why 
        // we are initializing with _this
        var _this = this;

        _this.getsocial();

        EventBus.$on('social-created',function(){
            // getting updated data when insert update delete happend 
            _this.getsocial();
        });
       },

       methods : {
        getsocial(){

           this.isLoading = true;
         
            axios.get(base_url+'admin/setting/social-method-list')
              .then(response => {
               
               this.socials = response.data;
               this.isLoading = false;

            });

        },

        edit(value){
            EventBus.$emit('update-social',value);
        },

       }

	}

</script>