<template>
	<div id="modal-update" class="modal fade" >
		<div class="modal-dialog modal-lg modal-custom">
			<div class="modal-content">
				<div class="modal-header ">
					<h3 class="m-t-none m-b">Edit Color</h3>
					<button class="btn btn-default text-right" data-dismiss="modal">X</button>
				</div>
				<div class="modal-body">
					<form @submit.prevent="save()">
						<div class="row">
							<div class="col-md-12" v-if="validation_error" style="margin-top: 20px">
								<div class="form-group">

									<div>
										<ul>
											<li class="text-danger" v-for="error in validation_error">{{ error[0] }}</li>
										</ul>
									</div>

								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Color Name*</label> 
									<input type="text" v-model="form.name"   class="form-control" placeholder="Color Name">
								</div>
						    </div>						

						    <div class="col-md-4">
								<div class="form-group">
									<label>Color*</label> 
									<input type="color" v-model="form.color_code" class="form-control" placeholder="Select Color">
								</div>
							</div>		
							<div class="col-md-4">
								<div class="form-group">
									<label>Color Code*</label> 
									<input type="text" v-model="form.color_code" class="form-control" placeholder="Color Code">
								</div>
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

	export default {

		mixins : [Mixin],

		data(){

			return {

				form : {
                    id : '',
					name : '',  
                    color_code : ''
				},

				button_name : "Update",
				validation_error : null, 
				isLoading: false,
			}

		},

        mounted()
		{
          
          var _this = this;

          EventBus.$on('update-color',function(value) {
                  _this.form = value;
           	    $('#modal-update').modal('show');

          });

		},

		methods : {
            save(){
				var code = this.form.color_code.trim();
				if((code.indexOf('#') > 0) || (code.indexOf('#') != 0) || (code.length !=7)){
					this.successMessage({'status' : 'error', 'message' : 'Enter Invalid Color Code!'});
					return ;
				}
            	this.button_name = "Updating...";
            	axios.put(base_url+'admin/product-color/'+this.form.id,this.form)
            	.then(response => {
            		if(response.data.status === 'success'){
            			$('#modal-update').modal('hide');
            			this.resetForm();
            			EventBus.$emit('color-created');
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

					color_name : '',  
					color_code : ''
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