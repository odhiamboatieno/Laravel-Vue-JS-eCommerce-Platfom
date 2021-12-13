<template>
    <div class="container">
        <div class="row">
        	<div class="col-md-12" v-if="validation_error" style="margin-top: 20px">
				<div class="form-group text-center">

					<div>
						<ul>
							<li class="text-danger" v-for="(error,index) in validation_error" :key="index">{{ error[0] }}</li>
						</ul>
					</div>

				</div>
			</div>
            <div class="col-lg-6 col-sm-12">
                <div class="content">
                    <h3 class="mt15">Get our notification at email</h3>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12">
                <div class="search">
                    <form @submit.prevent="subscribe()">
                        <div class="subs-box">
                            <input name="subscribe" v-model="form.email" class="form-control" placeholder="Your Email Here" value="" type="text">
                            <button type="submit" class="button src-btn"> {{ button_name }}</button>
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
		data(){
			return {
				form : {
					email : ''
				},
				url : base_url,
				button_name : 'Subscribe',
				validation_error : null,
			}
		},

		methods: {
			subscribe(){
				this.button_name = 'Sending...'
				axios.post(this.url+'user/subscribe',this.form)
				.then(response => {
        			this.successMessage(response.data);
        			this.button_name = 'Subscribe';
					this.resetForm();
				})
				.catch(error => {
					if (error.response.status == 422) {
						this.validation_error = error.response.data.errors;
						this.validationError();
                        this.button_name = 'Subscribe';
                        this.resetForm();
					}
				})
			},

			resetForm(){
				this.form.email = ''	
			}
		}
	}
</script>