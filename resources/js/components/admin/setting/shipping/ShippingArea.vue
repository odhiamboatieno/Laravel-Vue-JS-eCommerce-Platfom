<template>
<div class="row">
    <div class="col-lg-12" >
        <div class="ibox animated fadeInRightBig">
            <div class="ibox-title">
                <h5>City Name</h5>
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
                    <div class="col-sm-3">
                        <div class="input-group">
                        <input placeholder="Search By City" v-model="keyword" type="text" class="form-control form-control-sm" @keyup="getOrderArea()"> 
                     </div>
                    </div>                	
                    <div class="col-md-3 m-b-xs">
                    <div class="input-group">
                        <select class="form-control" v-model="status" @change="getOrderArea()" >
                          <option value="">All</option>
                          <option value="0">Inactive</option>
                     	  <option value="1">Active</option>
                        </select>                    		
                    </div>

                    </div>
                    <div class="col-sm-5 m-b-xs">
                      <button @click="clearFilter()" class="btn btn-primary">Clear Filter</button>
                    </div>
                </div>
                <div class="table-responsive" style="margin-top: 15px;" v-if="!isLoading">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>SL no</th>
                            <th>Area</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="value in areas.data" :key="value.id">
                            <td>{{ value.id }}</td>
                            <td>{{ value.city }}</td>
                            <td>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" :checked="value.status == 1 ? true : false" class="onoffswitch-checkbox" :id="value.id" @change="changeStatus(value.id)">
                                        <label class="onoffswitch-label" :for="value.id">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                           
                            <td>
                                <a @click.prevent="edit(value)" class="btn btn-primary" href="#"><i class="fa fa-edit" title="Edit"></i></a>
                                <a @click.prevent="deletearea(value)" class="btn btn-danger" href="#"><i class="fa fa-trash" title="Delete"></i></a>
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
           <pagination v-if="areas" :pageData="areas"></pagination> 
        </div>
        <update-city></update-city>
    </div>
</div>
</template>

<script>

    import { EventBus } from  '../../../../vue-assets';

    import Mixin from  '../../../../mixin';

    import Pagination from  '../../pagination/Pagination';
    import updateCity from  './EditCity.vue';

	
	export default {

        mixins : [Mixin],
        components : { 'pagination' : Pagination,
            'update-city' : updateCity
         },

       data(){
         
         return {
            
            areas : [],

            isLoading : false,

            status : '',
            keyword : '',
            url : base_url,

         }

       },

       mounted(){
        
        var _this = this;

        _this.getOrderArea();

        EventBus.$on('order-area',function(){
            _this.getOrderArea();
        })

       },

       methods : {
       

        getOrderArea(page = 1){

           this.isLoading = true;
         
         axios.get(base_url+'admin/order-area-list?page='+page+'&keyword='+this.keyword+'&status='+this.status)
              .then(response => {
               console.log(response.data)
               this.areas = response.data;
               this.isLoading = false;

              });

        },

        pageClicked(pageNo){
        var vm = this;
        vm.getOrderArea(pageNo);
        },

        // edit order 
        changeStatus(id){
            axios.get(base_url+'admin/change/shipping/status/'+id)
              .then(response => {
                if(response.data.status == 'success'){
                    this.successMessage(response.data);
                    // this.form.status = response.data.status;
                }
            });
        },

        edit(value){  //alert(value.city)     
            EventBus.$emit('edit-area',value);
        },

        // delete order 
        deletearea(value){
        Swal.fire({
            title: 'Delete '+value.city+', sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        },() => {

        }).then((result) => {
            if (result.value) {

                axios.get(base_url+'admin/area/delete/'+value.id)
                .then(res => {

                    this.successMessage(res.data);
                    this.getOrderArea()
                })
            }
        }) 

       },

       clearFilter(){
        
         this.keyword = '';
         this.status = '';
         this.getOrderArea();   

        },

       }

	}

</script>