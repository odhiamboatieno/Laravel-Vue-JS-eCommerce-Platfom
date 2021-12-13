<template>
<div class="animated fadeInRight">
    <div class="mail-box-header">
        <h2>
            Compse mail
        </h2>
    </div>
    <div class="col-md-12" v-if="validation_error" style="margin-top: 20px">
		<div class="form-group">

			<div>
				<ul>
					<li class="text-danger" v-for="error in validation_error">{{ error[0] }}</li>
				</ul>
			</div>

		</div>
	</div>
    <form @submit.prevent="send()">
    <div class="mail-box">
    <div class="mail-body">
        <div class="form-group row"><label class="col-sm-2 col-form-label">To:</label>
            <div class="col-sm-8">
            	<multiselect v-model="users.selected_email" tag-placeholder="Add this as new email" placeholder="Chose or write email" label="email" track-by="id" :options="emails" :multiple="true" :taggable="true" @tag="addTag"></multiselect>
            </div>
            <div class="col-sm-2 m-auto">
    			<label> <input type="checkbox" v-model="users.all_user" class="icheckbox_square-green" @change="loadUser()"> All User </label>
    		</div>
        </div>
        <div class="form-group row"><label class="col-sm-2 col-form-label">To:</label>
            <div class="col-sm-8">
            	<multiselect v-model="users.selected_subscriber" tag-placeholder="Add this as new email" placeholder="Chose or write email" label="email" track-by="id" :options="subscriber_email" :multiple="true" :taggable="true" @tag="addTag"></multiselect>
            </div>
            <div class="col-sm-2 m-auto">
    		<label> <input type="checkbox" v-model="users.all_subscriber" class="icheckbox_square-green" @change="loadSubscriber()"> All Subscriber </label>
    	</div>
        </div>
        <div class="form-group row"><label class="col-sm-2 col-form-label">Subject:</label>
            <div class="col-sm-10"><input type="text" class="form-control" v-model="users.subject"></div>
        </div>
        
    </div>
    <div class="col-sm-12">
    	<vue-editor v-model="users.text_body"></vue-editor>
    </div>
        <div class="mail-body text-right tooltip-demo">
        	<button class="btn btn-sm btn-primary" title="Send">{{ button_name }}</button>
        </div>
        <div class="clearfix"></div>
    </div>
     </form>
</div>
</template>
<script>
import { EventBus } from  '../../../vue-assets';
import Mixin from  '../../../mixin';
import { VueEditor } from "vue2-editor";
import Multiselect from 'vue-multiselect'

export default {
	mixins : [Mixin],
	data(){
		return {
			users : {
				selected_email : [],
				selected_subscriber : [],
				all_user : false,
				all_subscriber : false,
				subject : '',
				text_body : ''
			},
			// url : base_url,
			isLoading : false,
			emails : [],
			subscriber_email : [],
			button_name: 'Send',
			validation_error : null,
		}
	},
	mounted(){
		this.emailList();
	},
	components: {
		Multiselect,
		VueEditor
	},
	methods : {
		emailList : function() {
			this.isLoading = true;
			axios.get(base_url+'admin/setting/get-email')
        		.then(response => {
        			if(this.users.all_user != true){
        				this.emails = response.data.user;
        			}
        			if(this.users.all_subscriber != true){
        				this.subscriber_email = response.data.subscriber;
        			}
        			console.log(response.data);
        			this.isLoading = false;
        	});
		},

		loadSubscriber(){
			axios.get(base_url+'admin/setting/get-subscriber-email')
        		.then(response => {
        		if(this.users.all_subscriber != false){
        			this.users.selected_subscriber = response.data;
        		}
        		else{
		        	this.users.selected_subscriber = '';
		        }
        	});
		},

		loadUser(){
			axios.get(base_url+'admin/setting/get-user-email')
        		.then(response => {
        		if(this.users.all_user != false){
        			this.users.selected_email = response.data;
        		}
        		else{
		        	this.users.selected_email = '';
		        }
        	});
	        
		},

		addTag (newTag) {
		  const tag = {
		    email: newTag,
		    id: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
		  }
		  this.emails.push(tag)
		  this.user.selected_email.push(tag)
		},

		send(){
			this.button_name = 'Sending...'
			axios.post(base_url+'admin/setting/send/email',this.users)
			.then(response => {
				console.log(response.data)
				this.resetForm();
				this.successMessage(response.data);
            	this.button_name = "Send";
			})
			.catch(error => {
				if (error.response.status == 422) {
	                this.validation_error = error.response.data.errors;
	                this.validationError();
	                this.button_name = "Send";
	            } 
	            else 
	            {
	                this.successMessage(error);
	                this.button_name = "Send";
	            }
			});
		},
		resetForm(){
			this.users = {
				selected_email : [],
				subject : '',
				text_body : ''
			}
		},
	},
}
</script>