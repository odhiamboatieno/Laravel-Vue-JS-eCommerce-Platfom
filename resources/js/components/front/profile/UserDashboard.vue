<template>
	
	<div class="row">
		<div class="col-lg-4 col-sm-4 xs-mb15">
			<div class="list red">
				<span class="red"><i class='lni lni-list'></i></span>
				<h4>{{ total_order }} Orders</h4>
			</div>
		</div>
		<!-- end list -->
		<div class="col-lg-4 col-sm-4 xs-mb15">
			<div class="list green">
				<span class="green"><i class='lni lni-gift'></i></span>
				<h4>{{ total_product }} Products</h4>
			</div>
		</div>
		<!-- end list -->
		<div class="col-lg-4 col-sm-4 xs-mb15">
			<div class="list blue">
				<span class="blue"><i class='lni lni-offer'></i></span>
				<h4>{{ currency.symbol }} {{ total_discount | formatPrice  }} discount</h4>
			</div>
		</div>
		<!-- end list -->
	</div>
	<!-- end list-->
</template>

<script>
  import {EventBus} from  '../../../vue-assets';
  import Mixin from  '../../../mixin';
	export default {
        props : ['currency'],
		mixins : [Mixin],

		data(){

			return {

				 total_order   : 0,
				 total_product : 0,
				 total_discount : 0,
				 isLoading     : false,
			}
		},

		mounted(){

         this.getDashboardData();
		},

		methods : {

			getDashboardData(){
               
               this.isLoading = true;

               axios.get(base_url+'user-dashboard-data')
                    .then(response => {
                      
                      this.total_order = response.data.total_order;
                      this.total_product = response.data.total_product;
                      this.total_discount = response.data.total_discount;
                      this.isLoading = false;

                    })

			}
		}
	}
</script>