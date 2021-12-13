<template>
	
	<div id="update-sub-category" class="modal fade" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header text-right">
					<button class="btn btn-default" data-dismiss="modal">X</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-8 b-r"><h3 class="m-t-none m-b">Update Sub category</h3>



							<form @submit.prevent="save()" role="form">


								<div class="form-group">
									<label>Chose Category*</label> 
								    <select v-model="subcategory.category" class="form-control">
								    	<option value="">Please Chose a Category</option>
								    	<option v-for="value in categories" :key="value.id" :value="value.id">{{ value.category_name }}</option>
								    </select>
								</div>								


								<div class="form-group">
									<label>Sub category Name *</label> 
									<input v-model="subcategory.name" type="text" placeholder="subcategory Name" class="form-control">
								</div>									


								<div class="form-group">
									<label>Native Name</label> 
									<input v-model="subcategory.native_name" type="text" placeholder="Native Sub category Name" class="form-control">
								</div>																	


								<div class="form-group">
									<label>Sub Category Icon (128X128) *</label> <br>
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<span class="btn btn-block btn-primary btn-file"><span class="fileinput-new">
											<i class="fa fa-camera"></i> Change Icon</span>
										<span class="fileinput-exists">Change Icon</span><input type="file" name="..." @change="onImageChange"/></span>
                                  </div> 
                                  </div>	


									<div class="form-group">
										<label>Status *</label> 
										<select name="status" class="form-control" v-model="subcategory.status">
											<option value="1">Active</option>
											<option value="0">Inactive</option>
										</select>
									</div>


									<div style="margin-bottom: 20px;">
										<button  class="btn btn-lg  btn-primary float-right " type="submit"><strong>{{ button_name }}</strong></button>


									</div>
									</form>
									</div>
									<div class="col-sm-4"><h4>Photo Preview</h4>

										<p class="text-center" v-if="subcategory.image_status === 'unchanged'">
											<img class="img-responsive img-fluid" v-lazy="subcategory.view_image" >
										</p>										

										<p class="text-center" v-else>
											<img class="img-responsive img-fluid" v-lazy="subcategory.image" >
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
	import Multiselect from 'vue-multiselect'

	import Mixin from  '../../../mixin';

	export default {

		components: {
			Multiselect
		},

		mixins : [Mixin],

		props : ['categories'],

		data(){

			return {

				subcategory : {

					'id' : '',  
					'category' : '',  
					'name' : '',  
					'native_name' : '',  
					'image' : '', 
					'view_image' : '', 
					'status' : 1, 

					'image_status' : 'unchanged',

				},

				button_name : "Update",
				validation_error : null, 

			}

		},

		mounted(){
        
        var _this = this;
        
        EventBus.$on('update-sub-category',function(id){
            _this.subcategory.id = id;
        	_this.getSubCategory(id);

            $('#update-sub-category').modal('show');
        })

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
					vm.subcategory.image = e.target.result;
					vm.subcategory.image_status = "changed";
				};
				reader.readAsDataURL(file);
			},

			getSubCategory(id){
            
             axios.get(base_url+'admin/sub-category/'+id+'/edit')
             .then(response => {
              
             // console.log(response.data.sub_category);

              this.subcategory.name = response.data.data.sub_category_name;
              this.subcategory.native_name = response.data.data.sub_category_native_name;
              this.subcategory.view_image = response.data.data.image;
              this.subcategory.status = response.data.data.status;
			  this.subcategory.category = response.data.data.category_id;


             });

			},

			save(){

             this.button_name = "Updating....";

                 
             axios.post(base_url+'admin/sub-category/update/'+this.subcategory.id,this.subcategory)
                .then(response => {

                    if(response.data.status === 'success'){


                    $('#update-sub-category').modal('hide');

                    this.resetForm();
                    this.successMessage(response.data);
                    EventBus.$emit('sub-category-created');

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
          
          this.subcategory = {

					'id' : '',  
					'category' : '',  
					'name' : '',  
					'native_name' : '',  
					'image' : '',  
					'view_image' : '',  
					'status' : 1,
					'image_status' : 'unchanged',   

				}

		  this.validation_error = null;		

         }



     }

 }

</script>