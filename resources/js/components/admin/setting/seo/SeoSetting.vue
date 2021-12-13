<template>
    <div class="row">
    <div class="col-sm-8 b-r"><h3 class="m-t-none m-b">Update Setting</h3>
        <form @submit.prevent="save()" role="form">
            <div class="form-group">
                <label>Og Title*</label> 
                <input v-model="form.title" type="text" placeholder="og : Title" class="form-control">
            </div>                                  

            <div class="form-group">
                <label>Sitemap Link</label> 
                <input v-model="form.sitemap_link" type="text" placeholder="Sitemap Link" class="form-control">
            </div>                                
            <div class="form-group">
                <label>Author</label> 
                <input v-model="form.author" type="text" placeholder="Author" class="form-control">
            </div>
            <div class="form-group">
                <label>Keyword</label> 
                <multiselect v-model="form.seo_keyword" tag-placeholder="Add this as new tag" placeholder="Search or add a tag" label="keyword" track-by="id" :options="tags" :multiple="true" :taggable="true" @tag="addTag"></multiselect>
            </div>                                   
            <div class="form-group">
                <label>Meta Image</label> <br>
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <span class="btn btn-block btn-primary btn-file"><span class="fileinput-new"><i class="fa fa-camera"></i> Change Image</span>
                    <span class="fileinput-exists">Change Image</span><input type="file" name="..." @change="onImageChange"/></span>
              </div> 
              </div>

            <div class="form-group">
                <label>Description</label> 
                <textarea class="form-control" v-model="form.description"></textarea>
            </div>        


                <div style="margin-bottom: 20px;">
                    <button  class="btn btn-lg  btn-primary float-right " type="submit"><strong>{{ button_name }}</strong></button>
                </div>
                </form>
                </div>
                <div class="col-sm-4"><h4>Seo Image Preview</h4>

                    <p class="text-center" v-if="form.meta_image">
                        <img class="img-responsive img-fluid" :src="form.meta_image">
                    </p>                                        

                    <p class="text-center" v-else>
                        <img class="img-responsive img-fluid" :src="url+'images/setting/seo/'+form.view_image">
                    </p>

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
    import { VueEditor } from "vue2-editor";
    import Multiselect from 'vue-multiselect'
	
	export default {

        mixins : [Mixin],
        components : {
          Multiselect
        },

       data(){
         
         return {
            
            form : {

                title         :   '',
                meta_image    :   '',
                view_image    :   '',
                sitemap_link  :   '',
                author        :   '',
                description   :   '',
                seo_keyword   :   [],

            },

            isLoading   : false,
            button_name : 'update',
            url : base_url,
            validation_error : null,
            tags : [],

         }

       },

       mounted(){


        // this  will not work in eventBus that why 
        // we are initializing with _this

        var _this = this;

        _this.getSetting();

        EventBus.$on('seo-created',function(){

        _this.getSetting();


        });



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
                    vm.form.meta_image = e.target.result;
                };
                reader.readAsDataURL(file);
            },


        getSetting(){

  
         
         axios.get(base_url+'admin/setting/seo/'+5+'/edit')
              .then(response => {
               
               this.form.title        = response.data.title;
               this.form.view_image   = response.data.meta_image;
               this.form.sitemap_link = response.data.sitemap_link;
               this.form.author       = response.data.author;
               this.form.description  = response.data.description;
               this.form.seo_keyword  = response.data.seo_keyword;
               this.tags              = response.data.seo_keyword;
              });

        },

            addTag (newTag) {
                const tag = {
                    keyword: newTag,
                    id: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
                }
                this.tags.push(tag)
                this.form.seo_keyword.push(tag)
            },
         
            save(){

             this.button_name = "Updating...";

                 
             axios.post(base_url+'admin/setting/seo',this.form)
                .then(response => {

                    if(response.data.status === 'success'){
                    this.successMessage(response.data);
                    EventBus.$emit('seo-created');
                    this.button_name = "Update";
                    this.validation_error = null;
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

                    this.button_name = "Update";
                }
             })

         },
       }

	}

</script>