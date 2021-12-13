<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12 offers">
        <div class="title text-center">
          <h4>Product Of {{ category_name }}</h4>
        </div>
      </div>
    </div>
    <div class="row offers">
      <div
        class="col-6 col-lg-3 col-sm-6"
        v-for="(value, index) in categoryProducts"
        :key="index"
      >
        <single-product :currency="currency" :product="value"> </single-product>
      </div>

      <infinite-loading spinner="bubbles" @infinite="infiniteHandler">
        <div slot="spinner">
          <div class="col-md-12 text-center">
            <img :src="url + 'images/loading.gif'" />
          </div>
        </div>
        <div slot="no-more"></div>
        <div slot="no-results"></div>
      </infinite-loading>
    </div>
  </div>
</template>

<script>
import { EventBus } from "../../../vue-assets";
import Mixin from "../../../mixin";
import SingleProduct from "../product/SingleProduct";
import InfiniteLoading from "vue-infinite-loading";

export default {
  props: ["currency", "category_id", "category_name"],
  mixins: [Mixin],
  components: {
    "single-product": SingleProduct,
    "infinite-loading": InfiniteLoading,
  },
  data() {
    return {
      categoryProducts: [],
      page: 1,
      lastPage: 0,
      url: base_url,
    };
  },

  mounted: function () {
    this.fetchProduct()
      .then((response) => {
        if (response.data.data.length > 0) {
          this.categoryProducts = response.data.data;
          this.page += 1;
        } else {
          // console.log('No users found.');
        }
      })
      .catch((e) => console.log(e));
  },
  methods: {
    fetchProduct: function () {
      return axios.get(
        base_url +
          "category-product-list/" +
          this.category_id +
          "?page=" +
          this.page
      );
    },

    infiniteHandler: function ($state) {
      setTimeout(
        function () {
          this.fetchProduct()
            .then((response) => {
              if (response.data.data.length > 0) {
                this.lastPage = response.data.meta.last_page;
                // response.data.data.forEach(message => {
                // this.categoryProducts.push(message);
                // });
                this.categoryProducts.push(...response.data.data);

                if (this.page === this.lastPage) {
                  this.page = 1;
                  $state.complete();
                } else {
                  this.page += 1;
                }
                $state.loaded();
              } else {
                this.page = 1;
                $state.complete();
              }
            })
            .catch((e) => console.log(e));
        }.bind(this),
        1000
      );
    },
  },
};
</script>