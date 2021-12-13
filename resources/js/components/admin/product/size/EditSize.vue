<template>
	<div id="update-form" class="modal fade" >
		<div class="modal-dialog modal-lg modal-custom">
			<div class="modal-content">
				<div class="modal-header ">
					<h3 class="m-t-none m-b">Edit Size</h3>
					<button class="btn btn-default text-right" data-dismiss="modal">X</button>
				</div>
				<div class="modal-body">
					<form @submit.prevent="save()">
						<div class="row">
							<div class="col-md-12" v-if="validation_error" style="margin-top: 20px">
								<div class="form-group">

									<div>
										<ul>
											<li class="text-danger" v-for="error in validation_error" :key="error">{{ error[0] }}</li>
										</ul>
									</div>

								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Size Name*</label> 
									<input type="text" v-model="form.name" class="form-control" placeholder="Size Name">
								</div>
						    </div>						

						    <div class="col-md-6">
								<label>Choose Category</label>
								<select class="form-control" v-model="form.category_id">
									<option selected> Choose a Option</option>
									<option v-for="category in categories" :value="category.id" :key="category.id">{{ category.category_name }}</option>
								</select>
							</div>	
						</div>	

					 	<div class="row">								
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary">{{ button_name }}</button>
								<button type="close" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	
	import {EventBus} from  '../../../../vue-assets';
	import Mixin from  '../../../../mixin';
	import Multiselect from 'vue-multiselect';
	export default {

		mixins : [Mixin],
		props: ['categories'],
		components : { Multiselect },
		data(){

			return {

				form : {
					id : '',
					name : '',  
                    category_id : ''
				},

				button_name : "Update",
				validation_error : null, 
				isLoading: false,
			}

		},
		mounted()
		{
          
          var _this = this;

          EventBus.$on('update-size',function(value) {
                  _this.form = value;
           	    $('#update-form').modal('show');

          });

		},

		methods : {
            save(){

            	this.button_name = "Updating...";
            	axios.put(base_url+'admin/product-size/'+this.form.id,this.form)
            	.then(response => {
            		if(response.data.status === 'success'){
            			$('#update-form').modal('hide');
            			this.resetForm();
            			EventBus.$emit('size-created');
            			this.successMessage(response.data);
            			this.button_name = "Update";
					}
				   else
				   {
					  this.successMessage(response.data);	
					  this.button_name = "Update";
					}

            	})
            	.catch(err => {

            		if (err.response.status == 422) 
            		{

            			this.validation_error = err.response.data.errors;

                        this.validationError();

            			this.button_name = "Update";
            		} 
            		else 
            		{

            			this.successMessage(err);
                        
						// this.isloading = false;
						this.button_name = "Update";
					}
				})

            },

            resetForm(){

            	this.form = {

					name : '',  
					category_id : ''
				};

				this.validation_error = null;
				this.isLoading = false;

            },

        },

    }

</script>

<style scoped="">

@media screen and (max-width: 573px)
{


	.modal-custom {

		max-width: 100% !important;
		background-color: #000 !important;
	}



}
</style>