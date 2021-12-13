<template>
   <div class="row">
    <div class="col-lg-12" >
        <div class="ibox animated fadeInRightBig">
            <div class="ibox-title">
                <h5>Campaign List</h5>
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
                            @keyup="getCampaign()"> 
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
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Meta Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(value,index) in campaigns.data" :key="index">
                                    <td>{{ value.campaign_title }}</td>
                                    <td><img v-lazy="value.banner" style="max-height: 100px;"></td>
                                    <td><img v-if="value.meta_image" v-lazy="value.meta_image" style="max-height: 100px;"></td>
                                    <td>                                                      <div class="switch">
                                        <div class="onoffswitch">
                                            <input @change="campaignStatus(value.id)" type="checkbox" :checked="value.status == 1 ? true : false" class="onoffswitch-checkbox" :id="value.id">
                                            <label class="onoffswitch-label" :for="value.id">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a @click.prevent="edit(value.id)" class="btn btn-primary" href="#"><i class="fa fa-edit" title="Edit"></i></a> 
                                    <a @click.prevent="deletecampaign(value.id)" class="btn btn-danger" href="#"><i class="fa fa-trash" title="Delete"></i></a>
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
         <pagination v-if="campaigns.meta" :pageData="campaigns.meta"></pagination> 
     </div>

     <div class="ibox">
        <update-campaign></update-campaign>
    </div>
</div>





</div>
</template>

<script>

    import { EventBus } from  '../../../../vue-assets';

    import Mixin from  '../../../../mixin';

    import Pagination from  '../../pagination/Pagination';

    import Updatecampaign from './EditCampaign';
    
    export default {

        mixins : [Mixin],

        components : {
           
           'pagination' : Pagination,
           'update-campaign' : Updatecampaign,

       },

       data(){
           
           return {
            
            campaigns : [],

            isLoading : false,

            keyword : '',

            url : base_url,

        }

    },

    mounted(){


        // this  will not work in eventBus that why 
        // we are initializing with _this

        var _this = this;

        _this.getCampaign();

        EventBus.$on('campaign-created',function(){

        // getting updated data when insert update delete happend 

        _this.getCampaign();


    });



    },

    methods : {
     

        getCampaign(page = 1){

         this.isLoading = true;
         
         axios.get(base_url+'admin/offer-list?page='+page+'&keyword='+this.keyword)
         .then(response => {
             
             this.campaigns = response.data;
             this.isLoading = false;

         });

     },

     pageClicked(pageNo){
        var vm = this;
        vm.getCampaign(pageNo);
    },

        // edit campaign 

        edit(id){
            
            

            EventBus.$emit('update-campaign',id);
        },

        // delete campaign 

        deletecampaign(id){
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

                    axios.get(base_url+'admin/offer/'+id+'/delete')
                    .then(res => {

                        this.successMessage(res.data);
                        this.getCampaign();
                    })
                }
            }) 

        },

        campaignStatus(id)
        {
            axios.get(base_url+'admin/offer/status/'+id)
            .then(res => {
                this.successMessage(res.data);
            });
        }



    }

}

</script>