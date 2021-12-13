<template>
	
	<div id="update-currency" class="modal fade" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header text-right">
					<button class="btn btn-default" data-dismiss="modal">X</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-8 mr-auto ml-auto"><h3 class="m-t-none m-b">Edit Currency</h3>
							<form @submit.prevent="save()" role="form">
								<div class="form-group">
									<label>Country Name *</label> 
									<input v-model="currency.country" type="text" placeholder="Country Name" class="form-control">
								</div>								

								<div class="form-group">
									<label>Currency Name *</label> 
									<input v-model="currency.currency" type="text" placeholder="Currency Name" class="form-control">
								</div>									


								<div class="form-group">
									<label>Code</label> 
									<input v-model="currency.code" type="text" placeholder="ex : USD" class="form-control">
								</div>								

								<div class="form-group">
									<label>Symbol</label> 
									<input v-model="currency.symbol" type="text" placeholder="ex : $" class="form-control">
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
	
	import {EventBus} from  '../../../../vue-assets';

	import Mixin from  '../../../../mixin';
	

	export default {

		mixins : [Mixin],

		data(){

			return {

				currency     : {

					'id'       : '',  
					'currency' : '',  
					'country'  : '',  
					'code'     : '',  
					'symbol'   : '',   

				},

				button_name      : "Update",
				validation_error :  null, 

			}

		},

		mounted()
		{

			var _this = this;

			EventBus.$on('update-currency',function(value){
             _this.currency = value;

             $('#update-currency').modal('show');
			});

		},


		methods : {

			save(){

             this.button_name = "updating...";

                 
             axios.post(base_url+'admin/setting/currency/update/'+this.currency.id,this.currency)
                .then(response => {

                    if(response.data.status === 'success'){


                    $('#update-currency').modal('hide');

                    this.resetForm();
                    this.successMessage(response.data);
                    EventBus.$emit('currency-created');

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

                    this.button_name = "Save";
                }
             })

         },

         resetForm(){
          
          this.currency = {

					'id'            : '',  
					'currency'      : '',  
					'country'       : '',  
					'code'          : '',  
					'symbol'        : '',  

				}

		  this.validation_error = null;		

         }



     }

 }

</script>