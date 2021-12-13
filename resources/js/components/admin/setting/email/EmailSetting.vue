<template>
<div class="row">
    <div class="col-md-5 border p-4 mx-auto"><h3 class="m-t-none m-b">Update Setting</h3>
        <form @submit.prevent="save()" role="form">
            <div class="form-group">
                <label>Mail Driver</label>
                <select class="form-control m-b" name="account" v-model="form.driver">
                    <option value="smtp">SMTP</option>
                    <option value="mail">Mail</option>
                    <option value="sendmail">SendMail</option>
                </select>
                <!-- <input v-model="form.driver" type="text" class="form-control"> -->
            </div>                                  

            <div class="form-group">
                <label>Mail Host</label> 
                <input v-model="form.host" type="text" class="form-control">
            </div>                                
            <div class="form-group">
                <label>Mail Port</label> 
                <input v-model="form.port" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label>Username</label> 
                <input v-model="form.username" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label>Password</label> 
                <input v-model="form.password" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label>From Email</label> 
                <input v-model="form.from_address" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label>From Name</label> 
                <input v-model="form.from_name" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label>Encryption</label> 
                <select class="form-control m-b" v-model="form.encryption">
                    <option value="null">NULL</option>
                    <option value="ssl">SSL</option>
                    <option value="tls">TLS</option>
                </select>
                <!-- <input v-model="form.encription" type="text" class="form-control"> -->
            </div>
            <div class="form-group">
                <label>Mailing</label> 
                <div class="switch">
                    <div class="onoffswitch">
                        <input type="checkbox" :checked="form.status == 1 ? true : false" class="onoffswitch-checkbox" id="example2" @change="mailStatus()">
                        <label class="onoffswitch-label" for="example2">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <button  class="btn btn-lg  btn-primary float-right " type="submit"><strong>{{ button_name }}</strong></button>
            </div>
        </form>
    </div>
    <div class="col-md-5 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><h3>Instuction</h3></div>
            </div>
            <div class="card-body">
                <ul>
                    <li>Make sure you select IMAP server to send email. The mailing driver you'd like to use. By default, this is set to SMTP, but you can also change it to use PHPs mail feature or Sendmail instead.</li>
                    
                    <li>Enter your SMTP server's host address.</li>

                    <li>Under user information, enter a name and the current email address in which you would like to set up.</li>
                    
                    <li>Enter your email address into the username field and your password that you have been sent.</li>
                
                    <li>Turning on 'less secure apps' settings as mailbox user
                        <ul>
                            <li>Go to your (Google Account).</li>
                            <li>On the left navigation panel, click Security.</li>
                            <li>On the bottom of the page, in the Less secure app access panel, click Turn on access.</li>
                            <li>If you don't see this setting, your administrator might have turned off less secure app account access (check the instruction above).</li>
                            <li>Click the Save button.</li>
                        </ul> 
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-12" v-if="validation_error" style="margin-top: 20px;margin-bottom: 10px;">
        <div class="form-group">
            <div >
                <ul>
                    <li class="text-danger" v-for="error in validation_error">{{ error[0] }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
</template>

<script>

    import { EventBus } from  '../../../../vue-assets';
    import Mixin from  '../../../../mixin';
    
    export default {
        name: 'EmailSetting',
        mixins : [Mixin],

       data(){
         
         return {
            
            form : {
                driver :   '',
                host    :   '',
                port :   '',
                username :   '',
                password :   '',
                encryption :  '',
                from_address :  '',
                from_name :  '',
                status :   '',
            },

            isLoading   : false,
            button_name : 'update',
            url : base_url,
            validation_error : null

         }

       },

       mounted(){

        var _this = this;

        _this.getMailData();

       },

       methods : {

            getMailData(){  
             axios.get(this.url+'admin/setting/mail/data')
                  .then(response => {
                        this.form = response.data;
                  });
            },

            save(){

             this.button_name = "Updating...";
                
             axios.post(base_url+'admin/setting/update/mail',this.form)
                .then(response => {

                        this.successMessage(response.data);
                        this.button_name = "Update";
                        this.validation_error = null;

                    
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
                    this.button_name = "Update";
                }
             })

            },

            mailStatus: function(){
                 axios.post(base_url+'admin/setting/mail/status')
                  .then(response => {

                        this.successMessage(response.data);
                        this.form.status = response.data.status;

                  });
            },
        }

    }

</script>