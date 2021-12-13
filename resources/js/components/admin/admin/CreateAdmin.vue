<template>
	
<div id="modal-form" class="modal fade" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header text-right">
				<button class="btn btn-default" data-dismiss="modal">X</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-8 b-r"><h3 class="m-t-none m-b">Create  Admin</h3>
						<form @submit.prevent="save()" role="form">
							<div class="form-group">
								<label>admin Name *</label> 
								<input v-model="admin.name" type="text" placeholder="Admin Name" class="form-control">
							</div>									

							<div class="form-group">
								<label>Email</label> 
								<input v-model="admin.email" type="email" placeholder="Email" class="form-control">
							</div>								
							<div class="form-group">
								<label>Password</label> 
								<input v-model="admin.password" type="password" placeholder="Password" class="form-control">
							</div>								
							<div class="form-group">
								<label>Confirm Password</label> 
								<input v-model="admin.password_confirmation" type="password" placeholder="Confirm Password" class="form-control">
							</div>									
							<div class="form-group">
								<label>Admin Area</label> 
								<select name="area" class="form-control" v-model="admin.area">
									<option value="">Chose Admin Area</option>
									<option v-for="value in cities" :key="value.id" :value="value.id">{{ value.city }}</option>
								</select>
							</div>
							<div class="form-group">
								<label>Admin Role</label> 
								<select name="status" class="form-control" v-model="admin.role_id">
									<option value="">Chose Admin Role</option>
									<option v-for="value in roleList" :key="value.id" :value="value.id">{{ value.role_name }}</option>
								</select>
							</div>
							<div class="form-group">
								<label>Avatar (264X264) *</label> <br>
								<div class="fileinput fileinput-new" data-provides="fileinput">
									<span class="btn btn-block btn-primary btn-file"><span class="fileinput-new"><i class="fa fa-camera"></i> Chose Avatar</span>
									<span class="fileinput-exists">Change Avatar</span><input type="file" name="..." @change="onImageChange"/></span>
                              </div> 
                            </div>	
							<div class="form-group">
								<label>Status *</label> 
								<select name="status" class="form-control" v-model="admin.status">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								</select>
							</div>
							<div style="margin-bottom: 20px;">
								<button  class="btn btn-lg  btn-primary float-right " type="submit"><strong>{{ button_name }}</strong></button>
							</div>
						</form>
						</div>
						<div class="col-sm-4"><h4>Avatar Preview</h4>

							<p class="text-center" v-if="admin.image">
								<img class="img-responsive img-fluid" v-lazy="admin.image">
							</p>										
							<p class="text-center" v-else>
								<img class="img-responsive img-fluid" v-lazy="url+'images/default_avatar.png'">
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
	
	import {EventBus} from  '../../../vue-assets';

	import Mixin from  '../../../mixin';
	

	export default {

		mixins : [Mixin],

		data(){

			return {
				admin : {

					'name'                   :  '',   
					'email'                  :  '',   
					'password'               :  '',  
					'password_confirmation'  :  '',  
					'area'                	 : '',
					'role_id'                : '',
					'status'                 : 1,
					'image'                  : '',
				},

				roleList : [],
				cities : [],
				button_name : "Save",
				validation_error : null,
				url : base_url,

			}

		},

		mounted(){

			this.getRoleList();
			this.getAreaList();
		},

		methods : {

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
					vm.admin.image = e.target.result;
				};
				reader.readAsDataURL(file);
			},

			save(){
             this.button_name = "Saving...";   
             axios.post(base_url+'admin/admin',this.admin)
                .then(response => {

					if(response.data.status === 'success')
					{

                    $('#modal-form').modal('hide');
                    this.resetForm();
                    this.successMessage(response.data);
                    EventBus.$emit('admin-created');

                    this.button_name = "Save";


					}
					else{
					  this.successMessage(response.data);
					  this.button_name = "Save";	
					}
                    
                })
                .catch(err => {

                 if (err.response.status == 422) {

                    this.validation_error = err.response.data.errors;

                    this.validationError();

                    this.button_name = "Save";
                } 
                else 
                {

                    this.successMessage(err);

                    this.isloading = false;

                    this.button_name = "Save";
                }
             })

         },

         resetForm(){
          
          this.admin = {

					'name'                   :  '',   
					'email'                  :  '',   
					'password'               :  '',  
					'password_confirmation'  :  '',  
					'role_id'                :  '',
					'status'                 :   1, 
					'image'                  :  '', 

				}

		  this.validation_error = null;		

         }



     }

 }

</script>