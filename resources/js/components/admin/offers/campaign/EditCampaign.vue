<template>
	<div id="update-offer" class="modal fade" >
		<div class="modal-dialog modal-custom">
			<div class="modal-content">
				<div class="modal-header ">
					<h3 class="m-t-none m-b">Update Campaign</h3>
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
									<label>Offer Title*</label> 
									<input type="text" v-model="form.campaign_title"   class="form-control" placeholder="Campaign Title">
								</div>
							</div>						

							<div class="col-md-3">
								<div class="form-group">
								<label>Offer Image (something X somthing)</label> <br>
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<span class="btn btn-block btn-primary btn-file"><span class="fileinput-new"><i class="fa fa-camera"></i> Chose Image</span>
										<span class="fileinput-exists">Change Image</span><input type="file" name="..." @change="onImageChange"/></span>

										<img style="height: 80px;" v-if="form.banner" :src="form.banner">
										<img style="height: 80px;" v-else :src="form.view_banner">
									</div>
								</div>
							</div>							


							<div class="col-md-3">
								<div class="form-group">
								<label>Meta Image (something X somthing)</label> <br>
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<span class="btn btn-block btn-primary btn-file"><span class="fileinput-new"><i class="fa fa-camera"></i> Chose Image</span>
										<span class="fileinput-exists">Change Image</span><input type="file" name="..." @change="onMetaImageChange"/></span>

										<img style="height: 80px;" v-if="form.meta_image" :src="form.meta_image">	

										<img style="height: 80px;" v-else :src="form.view_meta_image">
									</div>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Offer Status*</label> 

									 <select class="form-control" v-model="form.status">
										<option value="1">Active</option>
										<option value="0">Inactive</option>
									</select>
									
								</div>
							</div>									

							</div>	

							<div class="row">
								<div class="col-md-12">
									<multiselect v-model="form.product" id="ajax" label="product_name" track-by="id" placeholder="Search for Product" open-direction="bottom" :options="products" :multiple="true" :searchable="true" :loading="isLoading" :internal-search="false" :clear-on-select="false" :close-on-select="false" :options-limit="300" :limit="15" :limit-text="limitText" :max-height="600" :show-no-results="false" :hide-selected="true" @search-change="asyncFind">
									</multiselect>
								</div>
							</div>

						<div class="row" style="margin-top: 20px;">	
						<div class="col-md-12">
                        <div class="table-responsive" v-if="form.product.length">
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
                        			</tr>
                        		</thead>

                        		<tbody>
                        		   <tr v-for="(value,index) in form.product" :key="index">
                        		    <td><img style="height: 60px;" v-lazy="value.feature_image"></td>
                        			<td>{{ value.product_name }}</td>
                        			<td>{{ value.selling_price }}</td>
                        			<td>
                        				<input v-model="value.discount" class="form-control" type="number" name="">
                        			</td>
                        			<td>
                        				<select class="form-control" v-model="value.discount_type">
                        					<option value="1">Amount</option>
                        					<option value="2">%</option>
                        				</select>
                        			</td>
                        			<td>
                                      <input type="hidden"   :value="value.discount_amount = discount(value.discount_type,value.discount,value.selling_price)">

                                      {{ value.discount_amount }}

                        			</td>   

                                    
                        			<td>
                                      {{ value.selling_price - value.discount_amount }}
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
	
	import {EventBus} from  '../../../../vue-assets';

	import Mixin from  '../../../../mixin';
	import { VueEditor } from "vue2-editor";
	import Multiselect from 'vue-multiselect'

	export default {

		mixins : [Mixin],

		components : {

			VueEditor,
			Multiselect

		},

		data(){

			return {

				form : {

					id : '',  
					campaign_title : '',  
					banner : '',  
					meta_image : '', 
					view_banner : '', 
					view_meta_image : '', 
					start_date : '',  
					end_date : '',  
					status : 1,

					product : [], 

				},

				products : [],

				button_name : "Update",
				validation_error : null, 
				isLoading: false,

			}

		},

		mounted()
		{
          
          var _this = this;

          EventBus.$on('update-campaign',function(id) {
          
           _this.getCampaign(id);
           $('#update-offer').modal('show');

          });

		},


		methods : {

			getCampaign(id)
			{

				axios.get(base_url+'admin/offer/'+id+'/edit')
				.then(response => {
                   
                   this.form.id = response.data.data.id;
                   this.form.campaign_title = response.data.data.campaign_title;
                   this.form.view_banner = response.data.data.banner;
                   this.form.view_meta_image = response.data.data.meta_image;
                   this.form.status = response.data.data.status;
                   this.form.product = response.data.data.product;
                   
				});

			},

            // main feature image 
            onImageChange(e) {

            	let files = e.target.files || e.dataTransfer.files;
            	if (!files.length)
            		return;
            	this.createImage(files[0]);

            },
			// creating main feature image 
			createImage(file) {
				let reader = new FileReader();
				let vm = this;
				reader.onload = (e) => {
					vm.form.banner = e.target.result;
				};
				reader.readAsDataURL(file);
			},            
			// main feature image 
            onMetaImageChange(e) {

            	let files = e.target.files || e.dataTransfer.files;
            	if (!files.length)
            		return;
            	this.createMetaImage(files[0]);

            },
			// creating main feature image 
			createMetaImage(file) {
				let reader = new FileReader();
				let vm = this;
				reader.onload = (e) => {
					vm.form.meta_image = e.target.result;
				};
				reader.readAsDataURL(file);
			},



            save(){

            	this.button_name = "Updating...";


            	axios.post(base_url+'admin/offer/'+this.form.id+'/update',this.form)
            	.then(response => {

            		if(response.data.status === 'success'){


            			$('#update-offer').modal('hide');

            			this.resetForm();
            			this.successMessage(response.data);
            			EventBus.$emit('campaign-created');

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

            limitText (count) {
            	return `and ${count} other products`
            },
            asyncFind (query) {
            	this.isLoading = true
            	axios.get(base_url+'admin/offer/product-list/search?keyword='+query)
            	.then(response => {
            		if(response.data.status === 'not-found')
            		{
                     this.products = []; 
            		}
            		else
            		{
                     this.products = response.data.data
            		}

            		this.isLoading = false
            	})
            },
            clearAll () {
            	this.form.product = [];
            },

            resetForm(){

            	this.form = {

					campaign_title : '',  
					banner : '',  
					meta_image : '', 
					view_banner : '', 
					view_meta_image : '', 
					start_date : '',  
					end_date : '',  
					status : 1,

					product : [], 

				};

				this.products = [];
				this.validation_error = null;
				this.isLoading = false;

            },
            discount(discount_type, discount, main_amount) {
            	if (parseInt(discount_type) === 2) {
            		return parseFloat(((discount / 100) * main_amount)).toFixed(2);
            	} else {
            		return parseFloat(discount).toFixed(2);
            	}
            }




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