<template>
<div class="row">
    <div class="col-lg-12" >
        <div class="ibox animated fadeInRightBig">
            <div class="ibox-content">
                <div class="table-responsive" style="margin-top: 15px;">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Page Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="value in pagesData.data" :key="value.id">
                            <td>{{ value.id }}</td>
                            <td>{{ value.title }}</td>
                            <td>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" :checked="value.publish == 1 ? true : false" class="onoffswitch-checkbox" :id="value.id" @change="changeStatus(value.id)">
                                        <label class="onoffswitch-label" :for="value.id">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a @click.prevent="showData(value)" class="btn btn-success" href="#"><i class="fa fa-book" title="View"></i></a> 
                                <a @click.prevent="edit(value)" class="btn btn-primary" href="#"><i class="fa fa-edit" title="Edit"></i></a>
                                <a @click.prevent="deletePage(value.id)" class="btn btn-danger" href="#"><i class="fa fa-trash" title="Delete"></i></a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="ibox animated fadeInRightBig">
           <pagination v-if="pagesData" :pageData="pagesData"></pagination> 
        </div>

        <div class="ibox">
            <update-page></update-page>
        </div>
        
        <div class="ibox">
            <show-page></show-page>
        </div>
    </div>
</div>

</template>
<script>
	import { EventBus } from  '../../../../vue-assets';
    import Mixin from  '../../../../mixin';

    import Pagination from  '../../pagination/Pagination';
    import Multiselect from 'vue-multiselect'

    import UpdatePage from './UpdatePage';
    import showpages from './ShowPage.vue'

	export default {
		name: "ViewPage",
        mixins : [Mixin],
        data(){
            return {
                pagesData: [],
            }
        },

        components: {
            'show-page' : showpages,
            'pagination' : Pagination,
            'update-page' : UpdatePage,
        },

        mounted(){
           
        // this  will not work in eventBus that why 
        // we are initializing with _this
        var _this = this;
        _this.getPage();
        EventBus.$on('page-created',function(){
        // getting updated data when insert update delete happend 
            _this.getPage();
        });
       },

       methods: {
            getPage(page = 1){
                axios.get(base_url+'admin/setting/get-pages?page='+page)
                .then(response => {
                    // console.log(response.data)
                    this.pagesData = response.data;
                })
                .catch(error => console.log(error))
            },

            pageClicked(pageNo){
                var vm = this;
                vm.getPage(pageNo);
            },

            edit(value){
                EventBus.$emit('update-page',value);
            },

            changeStatus(id){
                axios.get(base_url+'admin/setting/change/publishing/status/'+id)
                  .then(response => {

                        this.successMessage(response.data);
                        // this.form.status = response.data.status;

                });
            },

            showData(value){
                EventBus.$emit('show-page',value);
            },

            deletePage(id){
                Swal.fire({
                    title: 'Are you sure ?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                },() => {

                }).then((result) => {
                    if (result.value) {
                        axios.get(base_url+'admin/setting/page/'+id+'/delete')
                        .then(res => {
                            // console.log(response.data)
                            this.successMessage(res.data);
                            this.getPage();
                        })
                    }
                }) 
            }
       },

	}
</script>