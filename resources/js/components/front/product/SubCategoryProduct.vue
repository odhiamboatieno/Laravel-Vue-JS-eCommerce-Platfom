<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12 offers">
        <div class="title text-center">
          <h4>
            <span v-if="sub_category.sub_sub_category.length > 0">All</span>
            {{ sub_category.sub_category_name }}
          </h4>
        </div>
      </div>
    </div>

    <div class="row offers">
      <div
        class="col-6 col-lg-3 col-sm-4"
        v-for="(value, index) in subCategoryProducts"
        :key="index"
      >
        <single-product
          :currency="currency"
          :identifier="infiniteId"
          :product="value"
        >
        </single-product>
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

    <div
      class="row"
      v-if="
        !isLoading &&
        subCategoryProducts.length <= 0 &&
        sub_category.sub_sub_category.length <= 0
      "
    >
      <div class="col-md-12 text-center">
        <img
          :src="url + 'images/static/product_not_found.png'"
          class="img-fluid"
        />
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
  props: ["currency", "sub_category", "brands"],
  mixins: [Mixin],
  components: {
    "single-product": SingleProduct,
    "infinite-loading": InfiniteLoading,
  },
  data() {
    return {
      brand_id: "",
      subCategoryProducts: [],
      page: 1,
      lastPage: 0,
      infiniteId: +new Date(),
      url: base_url,
      isLoading: false,
    };
  },

  mounted() {
    this.initialData();
  },
  methods: {
    fetchProduct: function () {
      return axios.get(
        base_url +
          "product-list?page=" +
          this.page +
          "&sub_category=" +
          this.sub_category.id +
          "&brand_id=" +
          this.brand_id
      );
    },

    infiniteHandler: function ($state) {
      setTimeout(
        function () {
          this.fetchProduct()
            .then((response) => {
              if (response.data.data.length > 0) {
                this.lastPage = response.data.meta.last_page;
                this.subCategoryProducts.push(...response.data.data);

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

    initialData() {
      this.isLoading = true;
      this.fetchProduct()
        .then((response) => {
          if (response.data.data.length > 0) {
            this.subCategoryProducts = response.data.data;
            this.page += 1;
            this.isLoading = false;
          }
          this.isLoading = false;
        })
        .catch((e) => console.log(e));
    },
  },
};
</script>
<style scoped="">
.brand_active {
  border: 1px solid #e3106e !important;
}
</style>
