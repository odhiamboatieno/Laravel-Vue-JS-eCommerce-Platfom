<template>
	<div id="modal-discount" class="modal fade" >
		<div class="modal-dialog modal-custom">
			<div class="modal-content">
				<div class="modal-header ">
					<h3 class="m-t-none m-b">Add Discount</h3>
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
					<div class="row" style="margin-top: 20px;">	
						<div class="col-md-12">
                        	<div class="table-responsive">
                        	<table class="table table-border table-condensed table-strip">
                        		<thead>
                        			<tr>
                        		    <th>Image</th>
                        			<th>Product</th>
                        			<th>Base Price</th>
                        			<th>Discount</th>
                        			<th>Discount Type</th>
                        			<th>Total Discount</th>
                        			<th>Discount Price</th>
                        			<th>Discount Status</th>
                        			</tr>
                        		</thead>

                        		<tbody>
                        		   <tr>
                        		    <td><img style="height: 60px;" v-lazy="feature_image"></td>
                        			<td>{{ product_name }}</td>
                        			<td>{{ selling_price }}</td>
                        			<td>
                        				<input v-model="discform.discount" class="form-control" type="number" name="">
                        			</td>
                        			<td>
                        				<select class="form-control" v-model="discform.discount_type">
                        					<option value="1">Amount</option>
                        					<option value="2">%</option>
                        				</select>
                        			</td>
                        			<td>
                                      <input type="hidden"   :value="discform.discount_amount = discount(discform.discount_type,discform.discount,selling_price)">
                                      {{ discform.discount_amount }}

                        			</td>   

                        			<td>
                                      {{ selling_price - discform.discount_amount }}
                        			</td>
                        			<td>
                                        <select class="form-control" v-model="discform.discount_status">
                                            <option value="1">ON</option>
                                            <option value="0">OFF</option>
                                        </select>
                        			</td>
                        			</tr>
                        		</tbody>
                        	</table>
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
	
	import {EventBus} from  '../../../vue-assets';

	import Mixin from  '../../../mixin';

	export default {

		mixins : [Mixin],

		mounted()
		{
          
          var _this = this;

          EventBus.$on('discount-product',function(id) {
 			_this.getProduct(id)
           $('#modal-discount').modal('show');

          });

		},

		data(){

			return {

				discform : {
					id : '',  
					discount : 0, 
					discount_type : 1, 
					discount_amount : 0,  
					discount_status : null,
				},
				feature_image : '',  
				product_name : '',  
				selling_price : '', 
				url : base_url,
				button_name : "Save",
				validation_error : null, 
				isLoading: false,

			}

		},


		methods : {
			getProduct(id)
			{
				axios.get(base_url+'admin/product/'+id+'/discount')
				.then(response => {
                   this.feature_image = response.data.data.feature_image;
                   this.product_name = response.data.data.product_name;
                   this.selling_price = response.data.data.selling_price;
                   this.discform.id = response.data.data.id;
                   this.discform.discount = response.data.data.discount;
                   this.discform.discount_type = response.data.data.discount_type;
                   this.discform.discount_amount = response.data.data.discount_amount;
                   this.discform.discount_status = response.data.data.discount_status;
				});

			},

            save(){

            	this.button_name = "Saving...";
            	axios.post(base_url+'admin/set-discount',this.discform)
            	.then(response => {
                    $('#modal-discount').modal('hide');
                    this.successMessage(response.data);
                    EventBus.$emit('product-created');
                    this.button_name = "Save";
                    this.resetForm();
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
						this.button_name = "Save";
					}
				})
            },

            discount(type, discount, main_amount) {
            	if (type === "2") {
            		return parseFloat(((discount / 100) * main_amount)).toFixed(2);
            	} else {
            		return parseFloat(discount).toFixed(2);
            	}
            }
        },

        resetForm(){
        	this.discform = {
					id : '',  
					discount : 0, 
					discount_type : 1, 
					discount_amount : 0,  
					discount_status : '',
				};
            this.validation_error = null;
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