<template>
    <div id="modal-bulk" class="modal fade" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header ">
                    <h3 class="m-t-none m-b">Upload</h3>
                    <button class="btn btn-default text-right" data-dismiss="modal">X</button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-right">
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
                        <div class="col-md-12">
                        <form @submit.prevent="uploadExcel()" role="form">
                            <div class="form-group">
                                <p>Download format and product list by filtering in <a :href="url+'admin/stock-report'">stock list report </a> <br> you will find a button download sample product list</p> <br>
                                <span class="btn btn-primary btn-file"><span class="fileinput-new" id="excel-file"><i class="fa fa-file-excel-o"></i> Chose Excel</span>
                                <input type="file" id="file" ref="file" v-on:change="handleFileUpload()"/></span>
                            </div>
                            <button type="submit" class="btn btn-info"><i class="fa fa-upload" aria-hidden="true"></i> {{ button_name }}</button>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { EventBus } from  '../../../vue-assets';
    import Mixin from  '../../../mixin';
    export default {

        mixins : [Mixin],

        data(){

            return {
                file : '',
                attachment : '',
                upload_size : 0,
                url : base_url,
                button_name : "Upload",
                validation_error : null,
            }

        },

        methods : {

            handleFileUpload(){
                this.file = this.$refs.file.files[0];
                document.getElementById("excel-file").innerHTML = '<i class="fa fa-file-excel-o"></i> ' + this.file.name;
            },

            uploadExcel(){
                this.button_name = "Uploading...";
                let formData = new FormData();
                formData.append('file', this.file);
                axios.post(base_url+'admin/import',
                  formData,
                  {
                    headers : {
                        'Content-Type': 'multipart/form-data'
                    }
                  }
                ).then(response => {
                    if(response.data.status === 'success'){
                        this.successMessage(response.data);
                        $('#modal-bulk').modal('hide');
                        EventBus.$emit('product-created');
                        this.button_name = "Upload";
                    }
                   else
                    {
                      this.successMessage(response.data);   
                      this.button_name = "Upload";
                    }                           
                    this.data = new FormData();
                })
                .catch(err => {
                        if (err.response.status == 422) 
                        {
                            this.validation_error = err.response.data.errors;
                            this.validationError();
                            this.file = new FormData()
                            this.button_name = "Upload";
                        } 
                        else 
                        {
                            this.successMessage(err);
                            // this.isloading = false;
                            this.button_name = "Upload";
                            this.file = new FormData();
                        }
                    }
                );
            },
        }

    }

</script>