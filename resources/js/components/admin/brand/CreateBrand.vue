<template>
	<div id="modal-form" class="modal fade" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header text-right">
					<button class="btn btn-default" data-dismiss="modal">X</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-8 b-r"><h3 class="m-t-none m-b">Add Brand</h3>
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
									<label>Brand Logo (120X87) </label> <br>
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


									<div style="margin-bottom: 20px;">
										<button  class="btn btn-lg  btn-primary float-right " type="submit"><strong>{{ button_name }}</strong></button>


									</div>
									</form>
									</div>
									<div class="col-sm-4"><h4>Logo Preview</h4>

										<p class="text-center" v-if="brand.image">
											<img class="img-responsive img-fluid" :src="brand.image">
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

				brand : {

					'name' : '',  
					'native_name' : '',  
					'image' : '',  
					'status' : 1,   

				},

				button_name : "Save",
				validation_error : null, 

			}

		},


		methods : {

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
				};
				reader.readAsDataURL(file);
			},

			save(){

             this.button_name = "Saving...";

                 
             axios.post(base_url+'admin/brand',this.brand)
                .then(response => {

                    if(response.data.status === 'success'){


                    $('#modal-form').modal('hide');

                    this.resetForm();
                    this.successMessage(response.data);
                    EventBus.$emit('brand-created');

                    this.button_name = "Save";


					}
				   else
				   {
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
          
          this.brand = {

					'name' : '',  
					'native_name' : '',  
					'image' : '',  
					'status' : 1,   

				}

		  this.validation_error = null;		

         }



     }

 }

</script>