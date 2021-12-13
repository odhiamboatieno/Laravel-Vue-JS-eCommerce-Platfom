<template>
	
	<div id="update-category" class="modal fade" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header text-right">
					<button class="btn btn-default" data-dismiss="modal">X</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-8 b-r"><h3 class="m-t-none m-b">Add Category</h3>



							<form @submit.prevent="save()" role="form">
								<div class="form-group">
									<label>Category Name *</label> 
									<input v-model="category.name" type="text" placeholder="Category Name" class="form-control">
								</div>									


								<div class="form-group">
									<label>Native Name</label> 
									<input v-model="category.native_name" type="text" placeholder="Native Category Name" class="form-control">
								</div>									


								<div class="form-group">
									<label>Category Icon (128X128) *</label> <br>
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<span class="btn btn-block btn-primary btn-file"><span class="fileinput-new"><i class="fa fa-camera"></i> Chose Icon</span>
										<span class="fileinput-exists">Change Icon</span><input type="file" name="..." @change="onImageChange"/></span>
<!-- 											<span class="fileinput-filename"></span>
	<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a> -->
                                  </div> 
                                  </div>	


									<div class="form-group">
										<label>Status *</label> 
										<select name="status" class="form-control" v-model="category.status">
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

										<p class="text-center" v-if="category.image_status === 'changed'">
											<img class="img-responsive img-fluid" v-lazy="category.image">
										</p>
										<p class="text-center" v-else>
											<img class="img-responsive img-fluid" v-lazy="category.view_image">
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

				category : {

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

          EventBus.$on('update-category',function(id) {
 
          _this.getCategory(id);

          $('#update-category').modal('show');


          });



		},


		methods : {

			getCategory(id){
            
             axios.get(base_url+'admin/category/'+id+'/edit')
                  .then(response => {
                     
                     this.category.id = response.data.data.id;
                     this.category.name = response.data.data.category_name;
                     this.category.native_name = response.data.data.category_native_name;
                     this.category.view_image = response.data.data.image;
                     this.category.status = response.data.data.status;

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

					vm.category.image = e.target.result;

					vm.category.view_image = e.target.result;

					// update status during chaning of new image 

					vm.image_status = 'changed';
				};
				reader.readAsDataURL(file);
			},

			save(){

             this.button_name = "Updating...";

                 
             axios.post(base_url+'admin/category/update/'+this.category.id,this.category)
                .then(response => {

                    if(response.data.status === 'success'){


                    $('#update-category').modal('hide');

                    this.resetForm();
                    this.successMessage(response.data);
                    EventBus.$emit('category-created');

                    this.button_name = "Update";


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
          
          this.category = {

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