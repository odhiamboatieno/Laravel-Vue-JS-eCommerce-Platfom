<template>
  <div class="container" id="searchItem">
    <div class="row">
      <div class="col-md-12 offers">
        <div class="title text-center" v-if="searchProducts.length > 0">
          <h4>Search Result</h4>
        </div>
        <div
          class="title text-center mt50"
          v-if="keyword != '' && searchProducts.length <= 0 && !isLoading"
        >
          <h3>No product available 🥺 for keyword {{ keyword }}</h3>
        </div>
      </div>
    </div>
    <div class="row offers">
      <div
        class="col-6 col-lg-3 col-sm-4"
        v-for="value in searchProducts"
        :key="value.id"
      >
        <single-product :currency="currency" :product="value"> </single-product>
      </div>
      <infinite-loading
        spinner="bubbles"
        :identifier="infiniteId"
        @infinite="infiniteHandler"
      >
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

    <!-- <div class="row row_margin" v-if="keyword != ''"></div> -->
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
  },
  data() {
    return {
      url: base_url,
      searchProducts: [],
      page: 1,
      keyword: "",
      infiniteId: +new Date(),
      isLoading: false,
    };
  },

  mounted() {
    var _this = this;
    EventBus.$on("scrol-to-result", function (keyword) {
      _this.searchProducts = [];
      _this.keyword = keyword;
      _this.chnageType();
      var elmnt = document.getElementById("searchItem");
      if (!_this.isInViewport(elmnt)) {
        elmnt.scrollIntoView(true);
      }
    });
  },

  methods: {
    isInViewport(elem) {
      var bounding = elem.getBoundingClientRect();
      return (
        bounding.top >= 20 &&
        bounding.left >= 0 &&
        bounding.bottom <=
          (window.innerHeight || document.documentElement.clientHeight) &&
        bounding.right <=
          (window.innerWidth || document.documentElement.clientWidth)
      );
    },

    fetchProduct: function () {
      return axios.get(
        base_url + "product-list?page=" + this.page + "&keyword=" + this.keyword
      );
    },

    infiniteHandler: function ($state) {
      setTimeout(
        function () {
          this.fetchProduct()
            .then((response) => {
              if (response.data.data.length > 0) {
                this.lastPage = response.data.meta.last_page;
                this.searchProducts.push(...response.data.data);

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
      if (this.keyword != "" && this.keyword != " ") {
        this.fetchProduct().then((response) => {
          if (response.data.data.length > 0) {
            this.searchProducts = response.data.data;
            this.isLoading = false;
            this.page += 1;
          }
          this.isLoading = false;
        });
        // .catch(
        //   (e) => {
        //     this.isLoading = false;
        //   }
        //   // console.log(e)
        // );
      } else {
        this.searchProducts = [];
        this.isLoading = false;
      }
    },
    chnageType() {
      this.page = 1;
      this.searchProducts = [];
      this.infiniteId += 1;
      this.isLoading = false;
      this.initialData();
    },
  },
};
</script>

<style scoped>
.row_margin {
  min-height: 100vh;
}
</style>
