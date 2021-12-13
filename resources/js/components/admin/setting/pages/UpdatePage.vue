<template>
	<div id="modal-updatepage" class="modal fade" >
		<div class="modal-dialog modal-custom">
			<div class="modal-content">
				<div class="modal-header ">
					<h3 class="m-t-none m-b">Edit Page</h3>
					<button class="btn btn-default text-right" data-dismiss="modal">X</button>
				</div>
				<div class="col-md-8" v-if="validation_error" style="margin-top: 20px">
					<div class="form-group">
						<div >
							<ul>
								<li class="text-danger" v-for="error in validation_error">{{ error[0] }}</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<form @submit.prevent="update()">
						<div class="row">
							<div class="col-md-12">
								<label for="title">Title</label>
								<input type="text" name="title" id="title" v-model="page.title" class="form-control">
								<div class="form-group">
								<label>Page Description</label>
									<vue-editor v-model="page.description"></vue-editor>
								</div>
						
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
	import { EventBus } from  '../../../../vue-assets';

	import Mixin from  '../../../../mixin';
	import { VueEditor } from "vue2-editor";
	import Multiselect from 'vue-multiselect'

	export default {
		name : "UpdatePage",
		mixins : [Mixin],
		data(){
			return {
				page: {
					id: '',
					title: '',
					description: ''
				},
				button_name: 'Update',
				validation_error :  null, 
			}
		},

		mounted() {
			var _this = this;

			EventBus.$on('update-page',function(value){
             _this.page = value;

             $('#modal-updatepage').modal('show');
			});
		},

		methods: {
			update(){
				this.button_name = "Saving...";
				axios.post(base_url+'admin/setting/page/update/'+this.page.id,this.page)
					.then(response => {
						$('#modal-updatepage').modal('hide');
						this.resetForm();
						this.successMessage(response.data);
            			EventBus.$emit('page-created');

            			this.button_name = "Update";
					})
					.catch(error => {
						if (error.response.status == 422) {
		                    this.validation_error = error.response.data.errors;
		                    this.validationError();
		                    this.button_name = "Update";
		                } 
		                else 
		                {
		                    this.successMessage(error);
		                    this.button_name = "Update";
		                }
					});
			},
			resetForm(){
				this.page = {
					title: '',
					description: ''
				}
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
