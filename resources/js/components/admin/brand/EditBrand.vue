<template>
	
	<div id="update-brand" class="modal fade" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header text-right">
					<button class="btn btn-default" data-dismiss="modal">X</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-8 b-r"><h3 class="m-t-none m-b">Edit Brand</h3>
							<form @submit.prevent="save()" role="form">
								<div class="form-group">
									<label>Brand Name *</label> 
									<input v-model="brand.name" type="text" placeholder="brand Name" class="form-control">
								</div>									

								<div class="form-group">
									<label>Native Name</label> 
									<input v-model="brand.native_name" type="text" placeholder="Native brand Name" class="form-control">
								</div>									

								<div class="form-group">
									<label>Brand Logo (120X87) *</label> <br>
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<span class="btn btn-block btn-primary btn-file"><span class="fileinput-new"><i class="fa fa-camera"></i> Chose Logo</span>
										<span class="fileinput-exists">Change Logo</span><input type="file" name="..." @change="onImageChange"/></span>
<!-- 											<span class="fileinput-filename"></span>
	<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a> -->
                                  	</div> 
                                </div>	

									<div class="form-group">
										<label>Status *</label> 
										<select name="status" class="form-control" v-model="brand.status">
											<option value="1">Active</option>
											<option value="0">Inactive</option>
										</select>
									</div>

									<div>
										<button style="margin-bottom: 20px;" class="btn btn-lg  btn-primary float-right " type="submit"><strong>{{ button_name }}</strong></button>
									</div>
									</form>
								</div>
						<div class="col-sm-4"><h4>Logo Preview</h4>
							<p class="text-center" v-if="brand.image_status === 'changed'">
								<img class="img-responsive img-fluid" v-lazy="brand.image">
							</p>
							<p class="text-center" v-else>
								<img class="img-responsive img-fluid" v-lazy="brand.view_image">
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

				brand : {

					'id' : '',  
					'name' : '',  
					'native_name' : '',  
					'image' : '',  
					'view_image' : '',  
					'status' : 1, 

                     // when new file given it will update 

					'image_status' : 'unchanged',  

				},

				button_name : "Update",
				validation_error : null, 

			}

		},

		mounted(){

          // this not work in event bus 

          var _this = this;

          EventBus.$on('update-brand',function(id) {
 
          _this.getbrand(id);

          $('#update-brand').modal('show');


          });



		},


		methods : {

			getbrand(id){
            
             axios.get(base_url+'admin/brand/'+id+'/edit')
                  .then(response => {
                     
                     this.brand.id = response.data.data.id;
                     this.brand.name = response.data.data.brand_name;
                     this.brand.native_name = response.data.data.brand_native_name;
                     this.brand.view_image = response.data.data.image;
                     this.brand.status = response.data.data.status;

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

					vm.brand.image = e.target.result;

					vm.brand.view_image = e.target.result;

					// update status during chaning of new image 

					vm.image_status = 'changed';
				};
				reader.readAsDataURL(file);
			},

			save(){

             this.button_name = "Updating...";
                 
             axios.post(base_url+'admin/brand/update/'+this.brand.id,this.brand)
                .then(response => {

                    if(response.data.status === 'success'){
                    $('#update-brand').modal('hide');
                    this.resetForm();
                    this.successMessage(response.data);
                    EventBus.$emit('brand-created');
                    this.button_name = "Update";

					}
				   else
				   {
					  this.successMessage(response.data);	
					  this.button_name = "Update";
					}					
                    
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
          
          this.brand = {

					'name' : '',  
					'native_name' : '',  
					'image' : '',  
					'view_image' : '',  
					'image_status' : 'unchanged',  
					'status' : 1,   

				}

		  this.validation_error = null;		

         }



     }

 }

</script>