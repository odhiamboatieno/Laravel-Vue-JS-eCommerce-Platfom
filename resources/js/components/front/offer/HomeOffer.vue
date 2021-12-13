<template>
	<div v-if="offers.length >= 1">
	<div class="banner mt50 mb50 row">
			<div  class="col-lg-6" v-for="(value,index) in offers" :key="value.id" v-if="index < 2">
				<div class="bn">
					<a :href="url+'offer/'+value.id+'/'+value.slug">
					<img v-lazy="value.banner" alt="banner" class="img-fluid">
				    </a>
				</div>
			</div>
	</div>

	<div class="row category" v-if="offers.length >= 3">
		<div class="col-md-12 read-more text-center">
			<a :href="url+'offers'" class="button"> View All Offer <i class="lni-arrow-right"></i></a>
		</div>
	</div>
		</div>
</template>

<script>
	import {EventBus} from  '../../../vue-assets';
	import Mixin from  '../../../mixin';

	export default {
        data(){
           
           return {
            
            offers : [],
            isLoading : false,
            url : base_url

           }

        },

        mounted()
        {
        	this.getOffers();
        },

        methods : {
       
         getOffers()
         {
           this.isLoading = true;
           axios.get(base_url+'home-offers')
           .then(response => {
           	this.offers    = response.data.data;
           	this.isLoading = false;
           });
         }

        }

	}	
</script>