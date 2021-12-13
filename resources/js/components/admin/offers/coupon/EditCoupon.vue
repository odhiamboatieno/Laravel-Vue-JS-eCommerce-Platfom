<template>
	<div id="update-coupon" class="modal fade" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header ">
					<h3 class="m-t-none m-b">Update Coupon</h3>
					<button class="btn btn-default text-right" data-dismiss="modal">X</button>
				</div>
				<div class="modal-body">
					<form @submit.prevent="update()">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Coupon Code*</label> 
									<input type="text" v-model="form.coupon_code"  class="form-control" placeholder="Coupon Code">
								</div>
							</div>						
							<div class="col-md-6">
								<div class="form-group">
									<label>Amount Type*</label> 
									<select class="form-control" v-model="form.amount_type">
										<option value="">Select Type</option>
										<option value="1">Amount</option>
										<option value="2">%</option>
									</select>
								</div>
							</div>	
							<div class="col-md-6">
								<div class="form-group">
									<label>Amount*</label> 
									<input type="text" v-model="form.amount" class="form-control" placeholder="Coupon Amount">
								</div>
							</div>	
							<div class="col-md-6">
								<div class="form-group">
									<label>Max Amount*</label> 
									<input type="text" v-model="form.max_amount_limit" class="form-control" placeholder="Maximum Amount">
								</div>
							</div>	
							<div class="col-md-6">
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
					id : '',  
					coupon_code : '',  
					amount_type : '',  
					amount : '',  
                    max_amount_limit : '', 
                    valid_date : '',
					status : 1,
                },
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
				url : base_url,
				button_name : "Update",
				validation_error : null, 
				isLoading: false,

			}

		},

		mounted()
		{
          
          var _this = this;

          EventBus.$on('update-coupon',function(value) {
          	 _this.form = value;
           	$('#update-coupon').modal('show');

          });

		},


		methods : {

            update(){

            	this.button_name = "Updating...";
            	axios.put(base_url+'admin/coupon/'+this.form.id,this.form)
            	.then(response => {
            		if(response.data.status === 'success'){
            			$('#update-coupon').modal('hide');
            			this.resetForm();
            			this.successMessage(response.data);
            			EventBus.$emit('coupon-created');

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
					id : '',  
					coupon_code : '',  
					amount_type : '',  
					amount : '',  
                    limit_amount : '', 
                    valid_date : '',
					status : 1,
				};

				this.validation_error = null;
				this.isLoading = false;

            },
         
        },

        watch : {

        }

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