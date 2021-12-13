<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12 offers">
        <div class="title text-center">
          <h4>On Sale</h4>
        </div>
      </div>
    </div>
    <div class="row offers">
      <div
        class="col-6 col-lg-3 col-sm-4"
        v-for="value in saleProducts"
        :key="value.id"
      >
        <single-product :currency="currency" :product="value"></single-product>
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

    <div class="row" v-if="isLoading">
      <div class="col-md-12 text-center">
        <img :src="url + 'images/loading.gif'" />
      </div>
    </div>
  </div>
</template>

<script>
import { EventBus } from "../../../vue-assets";
import Mixin from "../../../mixin";
import SingleProduct from "./SingleProduct";
import InfiniteLoading from "vue-infinite-loading";

export default {
  props: ["currency"],
  mixins: [Mixin],
  components: {
    "single-product": SingleProduct,
    "infinite-loading": InfiniteLoading,
  },
  data() {
    return {
      saleProducts: [],
      url: base_url,
      page: 1,
      lastPage: 0,
      isLoading: false,
    };
  },

  mounted() {
    this.getProduct();
  },

  methods: {
    fetchProduct: function () {
      return axios.get(
        base_url + "product-list?page=" + this.page + "&discount_status=1"
      );
    },

    getProduct() {
      this.isLoading = true;
      this.fetchProduct()
        .then((response) => {
          if (response.data.data.length > 0) {
            this.saleProducts = response.data.data;
            this.page += 1;
            this.isLoading = false;
          } else {
            // console.log("Product not available");
            this.isLoading = false;
          }
        })
        .catch((e) => console.log(e));
    },

    infiniteHandler: function ($state) {
      setTimeout(
        function () {
          this.fetchProduct()
            .then((response) => {
              if (response.data.data.length > 0) {
                this.lastPage = response.data.meta.last_page;
                this.saleProducts.push(...response.data.data);

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