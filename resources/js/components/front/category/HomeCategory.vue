<template>
	<div class="container">
	          <div class="row category">
	          	<div class="col-md-12">
                    <div class="title text-center">
                        <h4>Product Categories</h4>
                    </div>	          		
	          	</div>

                </div>
               <div class="row category home-category" v-if="!isLoading">
                    <!-- end service 1-->
                    <div class="col-lg-3 col-sm-4 col-6" v-for="(value,index) in categories" :key="index">
                    	<a :href="url+'product/category/'+value.id+'/'+value.category_slug">
                        <div class="box">
                            <div class="content">
                                
                                    <img v-lazy="value.image" alt="icon" class="img-fluid">
                                    <h3>{{ value.category_name }}</h3>
                                
                            </div>
                        </div>
                        </a>
                    </div>
            </div>

            <div class="row" v-else>
                <div class="col-md-12 text-center">
                    <img :src="url+'images/loading.gif'">
                </div>            	
            </div>

            </div>
</template>

<script>
	import {EventBus} from  '../../../vue-assets';
	import Mixin from  '../../../mixin';

	export default {
      mixins: [Mixin],
      data()
      {
      	return {
            
            categories : [],
            isLoading : false,
            url : base_url,

      	}
      },

      mounted()
      {

      	this.getCategoryList();

      },

      methods : {
       
       getCategoryList(){
       this.isLoading = true;
       axios.get(base_url+'category-list')
            .then(response => {

              this.categories = response.data.data;
              this.isLoading = false;

            })

       },

      }

	}
</script>