<template>
	<div id="modal-form" class="modal fade" >
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header ">
					<h3 class="m-t-none m-b">Add Coupon</h3>
					<button class="btn btn-default text-right" data-dismiss="modal">X</button>
				</div>
				<div class="modal-body">
					<form @submit.prevent="save()">
						<div class="row">
							<div class="col-md-12" v-if="validation_error" style="margin-top: 20px">
								<div class="form-group">

									<div>
										<ul>
											<li class="text-danger" v-for="error in validation_error" :key="error[0]">{{ error[0] }}</li>
										</ul>
									</div>

								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Coupon Code*</label> 
									<input type="text" v-model="form.coupon_code"  class="form-control" placeholder="Coupon Code">
								</div>
							</div>						
							<div class="col-md-12">
								<div class="form-group">
									<label>Amount Type*</label> 
									<select class="form-control" v-model="form.amount_type" @change="visibility_max_amount">
										<option value="">Select Type</option>
										<option value="1">Amount</option>
										<option value="2">%</option>
									</select>
								</div>
							</div>	
							<div class="col-md-12">
								<div class="form-group">
									<label>Amount*</label> 
									<input type="text" v-model="form.amount" class="form-control" placeholder="Coupon Amount">
								</div>
							</div>	
							<div class="col-md-12" v-if="visible_max_amount == true">
								<div class="form-group">
									<label>Max Amount*</label> 
									<input type="text" v-model="form.limit_amount" class="form-control" placeholder="Maximum Amount">
								</div>
							</div>	
							<div class="col-md-12">
								<div class="form-group">
									<label>Valid Date*</label> 
									<v2-datepicker lang="en" style="padding-top:3px" format="yyyy-MM-DD" v-model="form.valid_date" :picker-options="pickerOptions2"></v2-datepicker>
								</div>
							</div>	

							<!-- <div class="col-md-6">
								<div class="form-group">
									<label>Slider Status*</label> 

									 <select class="form-control">
										<option value="1">Publish</option>
										<option value="0">Not Publish</option>
									</select>
									
								</div>
							</div> -->
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
					coupon_code : '',  
					amount_type : '',  
					amount : '',  
                    limit_amount : '', 
                    valid_date : '',
					status : 1,
				},
				visible_max_amount : false,
				pickerOptions2: {
                shortcuts: [{
                    text: 'Today',
                    onClick (picker) {
                        picker.$emit('pick', new Date());
                    }
                }, {
                    text: 'Yesterday',
                    onClick (picker) {
                        const date = new Date();
                        date.setTime(date.getTime() - 3600 * 1000 * 24);
                        picker.$emit('pick', date);
                    }
                }, {
                    text: 'A week ago',
                    onClick (picker) {
                        const date = new Date();
                        date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
                        picker.$emit('pick', date);
                    }
                }]
            },

				button_name : "Save",
				validation_error : null, 
				isLoading: false,
			}

		},


		methods : {
			visibility_max_amount(){
				if(this.form.amount_type == 2){
					this.visible_max_amount = true
				}else{
					this.visible_max_amount = false
				}
			},

            save(){

            	this.button_name = "Saving...";
            	axios.post(base_url+'admin/coupon',this.form)
            	.then(response => {
            		if(response.data.status === 'success'){
            			$('#modal-form').modal('hide');
            			this.resetForm();
            			EventBus.$emit('coupon-created');
            			this.successMessage(response.data);
            			this.button_name = "Save";
					}
				   else
				   {
					  this.successMessage(response.data);	
					  this.button_name = "Save";
					}

            	})
            	.catch(err => {

            		if (err.response.status == 422) 
            		{

            			this.validation_error = err.response.data.errors;

                        this.validationError();

            			this.button_name = "Save";
            		} 
            		else 
            		{

            			this.successMessage(err);
                        
						// this.isloading = false;
						this.button_name = "Save";
					}
				})

            },

            resetForm(){

            	this.form = {

					coupon_code : '',  
					amount_type : '',  
					amount : '',  
                    limit_amount : '', 
                    valid_date : '',
					status : 1,

				};
				this.visible_max_amount = false;
				this.validation_error = null;
				this.isLoading = false;

            },

        },

    }

</script>

<style scoped="">
.modal-custom {

	max-width: 90% !important;

}

@media screen and (max-width: 573px)
{


	.modal-custom {

		max-width: 100% !important;
		background-color: #000 !important;
	}



}
</style>