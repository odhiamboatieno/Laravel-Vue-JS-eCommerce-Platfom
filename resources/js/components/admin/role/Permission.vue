<template>
	<div id="assign-role" class="modal fade" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header">
		         <h3 class="m-t-none m-b">Assinging Permission To <strong class="text-primary">{{ role.role_name }}</strong></h3>
				</div>
		<div class="modal-body">
					<div class="col-md-12">

							<form @submit.prevent="AssignRole()" role="form">

								<div class="row" v-for="(value,index) in role.menus" :key="index">
									<div v-if="index !== 0" class="col-md-12">
										<hr>
									</div>									
									<div class="col-md-12">
									<h3>{{ value.name }}</h3>
				                    <div class="switch" v-if="value.sub_menu.length === 0">
				                        <div class="onoffswitch">
				                            <input :value="value.id" :id="value.id"  v-model="value.check" type="checkbox"  class="onoffswitch-checkbox"">
				                            <label class="onoffswitch-label" :for="value.id">
				                                <span class="onoffswitch-inner"></span>
				                                <span class="onoffswitch-switch"></span>
				                            </label>
				                        </div>
				                    </div>
								    </div>
									<div class="col-md-3" v-for="sub in value.sub_menu">
										<h5 style="margin-top: 20px;">{{ sub.name }}</h5>

				                    <div class="switch">
				                        <div class="onoffswitch">
				                            <input :value="sub.id" :id="sub.id"  v-model="sub.check" type="checkbox"  class="onoffswitch-checkbox"">
				                            <label class="onoffswitch-label" :for="sub.id">
				                                <span class="onoffswitch-inner"></span>
				                                <span class="onoffswitch-switch"></span>
				                            </label>
				                        </div>
				                    </div>										

									</div>									

								</div>
							
                        
									
								<div class="row">
									<div class="col-md-12">
									<button style="margin-top: 20px;"  class="btn btn-lg  btn-primary float-right " type="submit"><strong>{{ button_name }}</strong></button>										
									</div>

								</div>
							</form>

							</div>
							</div>
							</div>
							</div>
						</div>
</template>

<script>

	
	import {EventBus} from  '../../../vue-assets';

	import Mixin from  '../../../mixin';

	
	export default{

		mixins:[Mixin],

		data(){

			return{

				role : {

					id : 0,
					role_name : '',
					menus : [],
				},

				button_name : 'Update',

				

				errors : null

			}

		},

		created(){

			let vm = this;

			EventBus.$on('assign-permission',function(id){

				vm.role.id = id;

				vm.RoleInfo(id);
				vm.getMenus(id);

				$('#assign-role').modal('show');

			});

			$('#assign-role').on('hidden.bs.modal', function(){
				vm.closeModal();
			});



		},

		methods : {

			RoleInfo(id){
				axios.get(base_url+'admin/role/'+id+'/edit')
				.then(response => {
					this.role.id = response.data.id;
					this.role.role_name = response.data.role_name;
				})
			},

			getMenus(id){

				axios.get(base_url+'admin/role/'+id)

				.then(response => {
                   
                   this.role.menus = response.data;
				})

			},
			AssignRole(){
                this.button_name = 'Updating.....';
				axios.post(base_url+'admin/permission',this.role)
				.then(res => {

					console.log(res);

					if(res.data.status == 'success'){
						this.successMessage(res.data);
						this.button_name = 'Update';
						EventBus.$emit('role-created',1);
						this.closeModal();
						$('#assign-role').modal('hide');
					}
					else{
					 this.successMessage(res.data);
					 this.button_name = 'Update';
					}

					
				})
				.catch(err => {

					if(err.response){

						this.errors = err.response.data.errors;
					}
					this.button_name = 'Update';
				})
								

			},



			closeModal(){
				EventBus.$emit('role-created',1);
			}			




		}

	}	



</script>