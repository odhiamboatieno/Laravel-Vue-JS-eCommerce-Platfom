<template>
  <div class="container">
	<div v-if="offers.length >= 1">
	<div class="banner mt30 mb50 row">
			<div  class="col-lg-6 mt20" v-for="(value,index) in offers" :key="value.id">
				<div class="bn">
					<a :href="url+'offer/'+value.id+'/'+value.slug" :title="value.campaign_title">
					<img v-lazy="value.banner" alt="banner" class="img-fluid">
				    </a>
				</div>
			</div>
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