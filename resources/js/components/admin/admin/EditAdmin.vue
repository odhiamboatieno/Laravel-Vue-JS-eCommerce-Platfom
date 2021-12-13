<template>
	
<div id="update-admin" class="modal fade" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header text-right">
				<button class="btn btn-default" data-dismiss="modal">X</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-8 b-r"><h3 class="m-t-none m-b">Add Admin</h3>
						<form @submit.prevent="save()" role="form">
							<div class="form-group">
								<label>Admin Name *</label> 
								<input v-model="admin.name" type="text" placeholder="Admin Name" class="form-control">
							</div>									


							<div class="form-group">
								<label>Email</label> 
								<input v-model="admin.email" type="text" placeholder="Admin Email" class="form-control">
							</div>									


							<div class="form-group">
								<label>Admin Icon (128X128) *</label> <br>
								<div class="fileinput fileinput-new" data-provides="fileinput">
									<span class="btn btn-block btn-primary btn-file"><span class="fileinput-new"><i class="fa fa-camera"></i> Chose Icon</span>
									<span class="fileinput-exists">Change Icon</span><input type="file" name="..." @change="onImageChange"/></span>
<!-- 											<span class="fileinput-filename"></span>
<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a> -->
                              </div> 
                              </div>

                              <div class="form-group">
									<label>Area *</label> 
									<select name="area" class="form-control" v-model="admin.area">
										<option v-for="city in cities" :value="city.id">{{ city.city }}</option>
										
									</select>
								</div>
								<div class="form-group">
									<label>Role *</label> 
									<select name="status" class="form-control" v-model="admin.role_id">
										<option v-for="role in roleList" :value="role.id">{{ role.role_name }}</option>
										
									</select>
								</div>

								<div class="form-group">
									<label>Status *</label> 
									<select name="status" class="form-control" v-model="admin.status">
										<option value="1">Active</option>
										<option value="0">Inactive</option>
									</select>
								</div>

								<div>
									<button style="margin-bottom: 20px;" class="btn btn-lg  btn-primary float-right " type="submit"><strong>{{ button_name }}</strong></button>
								</div>
								</form>
								</div>
								<div class="col-sm-4"><h4>Photo Preview</h4>

									<p class="text-center" v-if="admin.avatar">
										<img class="img-responsive img-fluid" v-lazy="admin.avatar">
									</p>
									<p class="text-center" v-else>
										
										<img v-if="admin.view_image" class="img-responsive img-fluid" v-lazy="url+'/images/admin/'+admin.view_image">
										<img v-else class="img-responsive img-fluid" v-lazy="url+'images/default_avatar.png'">
									</p>
								</div>

								<div class="col-md-12" v-if="validation_error" style="margin-top: 20px">
									<div class="form-group">

										<div >
											<ul>
												<li class="text-danger" v-for="error in validation_error">{{ error[0] }}</li>
											</ul>
										</div>

									</div>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>

</template>


<script>
	
	import { EventBus } from  '../../../vue-assets';

	import Mixin from  '../../../mixin';

	export default {

		mixins : [Mixin],

		data(){

			return {

				admin : {

					'id' : '',  
					'name' : '',  
					'email' : '',
					'area' : '',
					'role_id' : '',
					'avatar' : '',  
					'view_image' : '',  
					'status' : 1,

                     // when new file given it will update 

					'image_status' : 'unchanged',  

				},
				url : base_url,
				roleList : [],
				cities : [],
				button_name : "Update",
				validation_error : null, 

			}

		},

		mounted(){

          // this not work in event bus 

          var _this = this;
          _this.getRoleList();
          _this.getAreaList();
          EventBus.$on('update-admin',function(id) {
 
          _this.getAdmin(id);

          $('#update-admin').modal('show');


          });



		},


		methods : {

			getAdmin(id){
            
             axios.get(base_url+'admin/admin/'+id+'/edit')
                  .then(response => {
                     
                     this.admin.id = response.data.id;
                     this.admin.name = response.data.name;
                     this.admin.email = response.data.email;
                     this.admin.area = response.data.admin_area_id;
                     this.admin.role_id = response.data.role_id;
                     this.admin.view_image = response.data.avatar;
                     this.admin.status = response.data.status;

                  });

			},

			getRoleList()
			{
				axios.get(base_url+'admin/all-role')
				     .then(response => {
				     	this.roleList = response.data;
				    });
			},

			getAreaList()
			{
				axios.get(base_url+'admin/all-area')
				     .then(response => {
				     	this.cities = response.data;
				     });
			},

			onImageChange(e) {

				let files = e.target.files || e.dataTransfer.files;
				if (!files.length)
					return;
				this.createImage(files[0]);

			},
			createImage(file) {
				let reader = new FileReader();
				let vm = this;
				reader.onload = (e) => {

					vm.admin.avatar = e.target.result;


					// update status during chaning of new image 

					vm.admin.image_status = 'changed';
				};
				reader.readAsDataURL(file);
			},

			save(){

             this.button_name = "Updating...";

                 
             axios.post(base_url+'admin/admin/update/'+this.admin.id,this.admin)
                .then(response => {

                    $('#update-admin').modal('hide');

                    this.resetForm();
                    this.successMessage(response.data);
                    EventBus.$emit('admin-created');
                    this.button_name = "Update";

                    
                })
                .catch(err => {

                 if (err.response.status == 422) {

                    this.validation_error = err.response.data.errors;

                    this.validationError();

                    this.button_name = "Update";
                } 
                else 
                {

                    this.successMessage(err);

                    this.isloading = false;

                    this.button_name = "Update";
                }
             })

         },

         resetForm(){
          
          this.admin = {
					'id' : '',  
					'name' : '',  
					'email' : '',
					'role_id' : '',
					'avatar' : '',  
					'view_image' : '',  
					'status' : '', 
				}

		  this.validation_error = null;		

         }
     }

 }

</script>