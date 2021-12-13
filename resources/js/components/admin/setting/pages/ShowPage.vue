<template>
	<div id="modal-showpage" class="modal fade" >
		<div class="modal-dialog modal-custom">
			<div class="modal-content">
				<div class="modal-header ">
					<h3 class="m-t-none m-b">Show Page</h3>
					<button class="btn btn-default text-right" data-dismiss="modal">X</button>
				</div>
		<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight article">
        <div class="row justify-content-md-center">
            <div class="col-lg-10">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="text-center" style="margin: 10px 0 40px 0px;">
                            <span class="text-muted"><i class="fa fa-clock-o"></i>{{ page.created_at | dateToString }}</span>
                            <h1>
                                {{ page.title }}
                            </h1>
                        </div>
                        <p v-html="page.description" style="text-align: justify;">
                            {{ page.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
		</div>
	</div>
</div>
</template>
<script>
	import { EventBus } from  '../../../../vue-assets';
	import Mixin from  '../../../../mixin';

	export default{
		name: 'ShowPage',
		mixins : [Mixin],
		data(){
			return {
				page: {
					title: '',
					description: '',
					created_at: ''
				},
			}
		},

		mounted() {
			var _this = this;

			EventBus.$on('show-page',function(value){
             	_this.page = value;
             	$('#modal-showpage').modal('show');
			});
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