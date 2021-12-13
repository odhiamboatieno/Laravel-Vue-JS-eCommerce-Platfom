<template>
	<div class="col-lg-12 col-sm-12">
		<div class="user-info bg-white bg-shadow ">
			<div class="p20">
				<form @submit.prevent="submit" id="user-form">
					<div class="form-group col-md-3 mr-auto ml-auto">
				      <img class="img-fluid rounded-circle" v-if="user.image" v-lazy="user.image" >				      

				      <img class="img-fluid rounded-circle" v-else v-lazy="user.avatar" >
					</div>					

					<div class="form-group col-lg-12 col-sm-12">
						<label for="user-name" class="">Name  &nbsp;<span class="text-danger" v-if="errors.hasOwnProperty('name')"> {{ errors.name[0] }} </span></label>
						<input v-model="user.name" id="user-name" class="form-control" name="user-name" placeholder="User Name" type="text">
					</div>
					<div class="form-group col-lg-12 col-sm-12">
						<label for="phone-number" class="">Phone Number &nbsp;<span class="text-danger" v-if="errors.hasOwnProperty('phone')"> {{ errors.phone[0] }} </span></label>
						<input v-model="user.phone" id="phone-number" class="form-control" name="phone-number" placeholder="Phone Number" type="text">
					</div>
					<div class="form-group col-lg-12 col-sm-12">
						<label for="email-address" class="">Email Address&nbsp;<span class="text-danger" v-if="errors.hasOwnProperty('email')"> {{ errors.email[0] }} </span></label>
						<input v-model="user.email" id="email-address" class="form-control" name="Email address" placeholder="Email Address" type="email">
					</div>					

					<div class="form-group col-lg-12 col-sm-12">
						<label for="location" class="">Location&nbsp;<span class="text-danger" v-if="errors.hasOwnProperty('location')"> {{ errors.location[0] }} </span></label>
						<select class="form-control" v-model="user.location_id" required>
                            <option v-for="area in location" :selected="user.location_id == area.id" :value="area.id">{{ area.city }}</option>      
                            
                        </select>
					</div>

					<div class="form-group col-lg-12 col-sm-12">
						<label for="address" class="">Address&nbsp;<span class="text-danger" v-if="errors.hasOwnProperty('address')"> {{ errors.address[0] }} </span></label>
						<textarea v-model="user.address" class="form-control" placeholder="Your Address" id="address"></textarea>
					</div>					

					<div class="form-group col-lg-12 col-sm-12">
						<label for="Picture" class="">Profile Picture &nbsp;<span class="text-danger" v-if="errors.hasOwnProperty('image')"> {{ errors.image[0] }} </span></label> <br>
		            <label class="btn-bs-file btn btn-lg theme-background">
		                <i class='lni lni-image im-icon'></i>
		                <input type="file" @change="onImageChange" id="Picture" />
		            </label>
					</div>

					<div class="col-lg-12 col-sm-12">
						<button type="submit" class="button button-md bg-dark2 color-white">{{ button }}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</template>

<script>
  import {EventBus} from  '../../../vue-assets';
  import Mixin from  '../../../mixin';
	export default {
        mixins : [Mixin],
        props: ['getLocation'],
		data()
		{

			return {

				location : [],
				user : {
					id       : '',
					name     : '',
					email    : '',
					phone    : '',
					address  : '',
					avatar   : '',
					image    : '',
					location_id : null,
				},

				errors : {},
				button : 'Update'

			}

		},

		mounted()
		{

			this.getAuthenticatedUser();
		},

		methods : {

			onImageChange(e) {

				let files = e.target.files || e.dataTransfer.files;
				if (!files.length)
					return;
				this.createImage(files[0]);

			},
			createImage(file) {
				let reader = new FileReader();
				let vm = this;
				reader.onload = (e) => {
					vm.user.image = e.target.result;
				};
				reader.readAsDataURL(file);
			},			

			getAuthenticatedUser(){

				axios.get(base_url+'authenticate-user')
				.then(response => {
					this.user.id      = response.data.user.id;
					this.user.name    = response.data.user.name;
					this.user.location_id   = response.data.user.location_id;
					this.user.email   = response.data.user.email;
					this.user.phone   = response.data.user.phone;
					this.user.address = response.data.user.address;
					this.user.avatar  = response.data.user.avatar ? base_url+'images/avatar/'+response.data.user.avatar : base_url+'images/avatar/default_avatar.png';
					this.location = response.data.location;
				});

			},

			submit()
			{
                this.button = 'updating...'
				axios.post(base_url+'update-profile',this.user)
				.then(response => {

					if(response.data.status == 'success')
					{

						this.successMessage(response.data);
						this.errors = {};
						this.button = 'update'

					}
					else
					{
                      this.successMessage(response.data);
                      this.button = 'update'
					}
				})
				.catch(err => {

					if (err.response.status == 422) {

						this.errors = err.response.data.errors;

						this.validationError();
						this.button = 'update'
					} 
					else 
					{

						this.successMessage(err);

						this.isloading = false;
						this.button = 'update'
					}
				})
			}

		}

	}	


</script>

<style>
	.btn-bs-file{
      position:relative;

}
.btn-bs-file input[type="file"]{
    position: absolute;
    top: -9999999;
    filter: alpha(opacity=0);
    opacity: 0;
    width:0;
    height:0;
    outline: none;
    cursor: inherit;

}
.im-icon {
	   color: #fff;
}
</style>