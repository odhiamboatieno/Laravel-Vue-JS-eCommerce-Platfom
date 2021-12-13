<template>
	
	<div id="modal-form" class="modal fade" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header text-right">
					<button class="btn btn-default" data-dismiss="modal">X</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-8 mr-auto ml-auto"><h3 class="m-t-none m-b">Add Role</h3>
							<form @submit.prevent="save()" role="form">
								<div class="form-group">
									<label>Role Name *</label> 
									<input v-model="role.role_name" type="text" placeholder="Role Name" class="form-control">
								</div>									<div style="margin-bottom: 20px;">
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
	
	import {EventBus} from  '../../../vue-assets';

	import Mixin from  '../../../mixin';
	

	export default {

		mixins : [Mixin],

		data(){

			return {

				role : {

					'role_name' : '',     

				},

				button_name      : "Save",
				validation_error :  null, 

			}

		},


		methods : {

			save(){

             this.button_name = "Saving...";

                 
             axios.post(base_url+'admin/role',this.role)
                .then(response => {

                    if(response.data.status === 'success'){


                    $('#modal-form').modal('hide');

                    this.resetForm();
                    this.successMessage(response.data);
                    EventBus.$emit('role-created');

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
          
          this.role = {

					'role_name'      : '',  

				}

		  this.validation_error = null;		

         }



     }

 }

</script>