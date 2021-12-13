<template>	
<div id="update-payment" class="modal fade" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header text-right">
				<button class="btn btn-default" data-dismiss="modal">X</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-8 mr-auto ml-auto"><h3 class="m-t-none m-b">Edit payment</h3>
						<form @submit.prevent="save()" role="form">
							<div class="form-group">
								<label>Provider Name *</label> 
								<input v-model="payment.provider" type="text" placeholder="Provider Name" disabled="" class="form-control">
							</div>								

							<div class="form-group">
								<label v-if="payment.id == 6">Encryption Key:  </label><label v-else>Client Id / Key *</label> 
								<input v-model="payment.client_id" type="text" placeholder="Client Key" class="form-control">
							</div>									


							<div class="form-group">
								<label v-if="payment.id == 6">Secret Key:  </label><label v-else>Secret *</label> 
								<input v-model="payment.client_secret" type="text" placeholder="Client Secret" class="form-control">
							</div>


							<div class="form-group" v-if="payment.id == 6">
								<label>Public Key *</label> 
								<input v-model="payment.public_key" type="text" placeholder="Public Key" class="form-control">
							</div>									

							<div class="form-group">
								<label>Status *</label> 
								<select class="form-control" v-model="payment.status">
									<option value="0">Inactive</option>
									<option value="1">Active</option>
								</select>
							</div>
							<div class="form-group">
								<label>Platform	 *</label> 
								<select class="form-control" v-model="payment.live_status">
									<option value="0">SandBox</option>
									<option value="1">Live</option>
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

				payment : {

					'id' : '',  
					'provider' : '',  
					'client_id' : '',  
					'client_secret' : '',  
					'public_key' : '',  
					'status' : 1,
					'live_status' : null

				},

				button_name : "Update",
				validation_error : null, 

			}

		},

		mounted(){

          // this not work in event bus 

          var _this = this;

          EventBus.$on('update-payment',function(value) {
          _this.payment = value;
          $('#update-payment').modal('show');


          });



		},


		methods : {
			save(){

             this.button_name = "Updating...";

                 
             axios.post(base_url+'admin/setting/payment-gateway/update/'+this.payment.id,this.payment)
                .then(response => {

                    if(response.data.status === 'success'){


                    $('#update-payment').modal('hide');

                    this.resetForm();
                    this.successMessage(response.data);
                    EventBus.$emit('payment-created');

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
          
          this.payment = {

					'id' : '',  
					'provider' : '',  
					'client_id' : '',  
					'client_secret' : '',  
					'status' : 1, 
					'live_status' : null  

				}

		  this.validation_error = null;		

         }



     }

 }

</script>