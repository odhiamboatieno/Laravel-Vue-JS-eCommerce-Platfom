<template>
	
<div id="update-social" class="modal fade" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header text-right">
				<button class="btn btn-default" data-dismiss="modal">X</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-8 mr-auto ml-auto"><h3 class="m-t-none m-b">Update {{ social.provider }} Login Credential</h3>
						<form @submit.prevent="save()" role="form">
							<div class="form-group">
								<label>Provider Name *</label> 
								<input v-model="social.provider" type="text" placeholder="Provider Name" disabled="" class="form-control">
							</div>								

							<div class="form-group">
								<label>Client Id / app_id *</label> 
								<input v-model="social.app_id" type="text" placeholder="App Key" class="form-control">
							</div>									

							<div class="form-group">
								<label>Secret *</label> 
								<input v-model="social.app_secret" type="text" placeholder="App Secret" class="form-control">
							</div>								

							<div class="form-group">
								<label>Status *</label> 
								<select class="form-control" v-model="social.status">
									<option value="0">Inactive</option>
									<option value="1">Active</option>
								</select>
							</div>								

								
							<div style="margin-bottom: 20px;">
								<button  class="btn btn-lg  btn-primary float-right " type="submit"><strong>{{ button_name }}</strong></button>
							</div>
						</form>
					</div>
					<div class="col-md-8 mr-auto ml-auto" v-if="validation_error" style="margin-top: 20px">
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
	
	import { EventBus } from  '../../../../vue-assets';

	import Mixin from  '../../../../mixin';

	export default {

		mixins : [Mixin],

		data(){

			return {

				social : {

					'id' : '',  
					'provider' : '',  
					'app_id' : '',  
					'app_secret' : '',  
					'status' : 1,

				},

				button_name : "Update",
				validation_error : null, 

			}

		},

		mounted(){

          // this not work in event bus 

          var _this = this;

          EventBus.$on('update-social',function(value) {
          _this.social = value;
          $('#update-social').modal('show');


          });



		},


		methods : {
			save(){

             this.button_name = "Updating...";

                 
             axios.post(base_url+'admin/setting/social-creadential/update/'+this.social.id,this.social)
                .then(response => {

                    if(response.data.status === 'success'){


                    $('#update-social').modal('hide');

                    this.resetForm();
                    this.successMessage(response.data);
                    EventBus.$emit('social-created');

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
          
          this.social = {

					'id' : '',  
					'provider' : '',  
					'app_id' : '',  
					'app_secret' : '',  
					'status' : 1,   

				}

		  this.validation_error = null;		

         }



     }

 }

</script>