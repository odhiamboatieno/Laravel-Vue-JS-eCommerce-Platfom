<template>
  <div>
    <section class="product-details mt30">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 col-sm-12">
            <div class="products-slider" style="overflow: hidden">
              <div class="big-img">
                <div class="zoom-left">
                  <img
                    class="img-fluid"
                    style
                    id="product_zoom"
                    v-lazy="product.feature_image"
                    :data-zoom-image="product.feature_image"
                  />

                  <div
                    id="product_small"
                    class="mt10"
                    v-if="product.multiple_image.length > 0"
                  >
                    <a
                      href="#"
                      class="elevatezoom-gallery"
                      :data-image="product.feature_image"
                      :data-zoom-image="product.feature_image"
                    >
                      <img
                        v-lazy="product.feature_image"
                        width="100"
                        class="img-fluid"
                      />
                    </a>
                    <a
                      v-for="(value, index) in product.multiple_image"
                      :key="index"
                      href="#"
                      class="elevatezoom-gallery"
                      :data-image="value.image"
                      :data-zoom-image="value.image"
                    >
                      <img v-lazy="value.image" width="100" class="img-fluid" />
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- left product end-->

          <div class="col-lg-6 col-sm-12">
            <div class="pro-det-right">
              <h3>{{ product.product_name }}</h3>
              <div class="pro-items qty_unit_details">
                <span class="item-number">{{ product.quantity_unit }}</span>
              </div>

              <div class="pro-avai">
                <span class="item-name">AVAILABILITY:</span>
                <span class="item-val" v-if="product.current_quantity > 0"
                  >YES</span
                >
                <span class="item-val" v-else>NO</span>
              </div>

              <div class="pro-avai">
                <span class="item-name">Brand:</span>
                <span class="item-val">{{ product.brand.brand_name }}</span>
              </div>

              <div class="pro-items">
                <span class="item-name">ITEM NO.:</span>
                <span class="item-number">ITM-#{{ product.id }}</span>
              </div>
              <div class="price">
                <span class="price-number" v-if="product.discount_status == 1"
                  >{{ currency.symbol
                  }}{{
                    (product.selling_price - product.discount_amount)
                      | formatPrice
                  }}</span
                >

                <span class="price-number" v-else
                  >{{ currency.symbol
                  }}{{ product.selling_price | formatPrice }}</span
                >

                <span
                  class="discount-price"
                  v-if="
                    product.discount_status == 1 && product.discount_amount > 0
                  "
                  >{{ currency.symbol
                  }}{{ product.selling_price | formatPrice }}</span
                >
              </div>
              <div class="cart-product-quantity" v-if="havingProduct">
                <div class="quantity">
                  <input
                    title="Remove one"
                    @click="updateCart(havingProduct.rowId, 'decrement')"
                    type="button"
                    value="-"
                    class="minus theme-background"
                  />
                  <strong class="qty_text"
                    >{{ havingProduct.qty }} in Cart</strong
                  >
                  <input
                    title="Add one more"
                    @click="updateCart(havingProduct.rowId, 'increment')"
                    type="button"
                    value="+"
                    class="plus theme-background"
                  />
                </div>
              </div>

              <span class="button-wrapper" v-else style="margin-top: -15px">
                <a
                  @click.prevent="
                    addToCart(
                      product.id,
                      product.product_name,
                      product.quantity_unit,
                      cart.qty,
                      product.current_quantity,
                      product.discount_status == 1 &&
                        product.discount_amount > 0
                        ? product.selling_price - product.discount_amount
                        : product.selling_price,
                      product.feature_image,
                      product.discount_status == 1 &&
                        product.discount_amount > 0
                        ? product.discount_amount
                        : 0
                    )
                  "
                  class="button btn-cart"
                  href
                >
                  Add to Cart
                  <i class="lni lni-shopping-basket"></i>
                </a>
              </span>

              <div
                style="margin-bottom: 20px"
                class="short-des"
                v-if="product.product_description"
              >
                <div
                  v-if="
                    !readMoreActive && product.product_description.length > 199
                  "
                >
                  <div v-html="product.product_description.slice(0, 200)"></div>
                  <a
                    class="more-less theme-color"
                    v-if="!readMoreActive"
                    @click.prevent="activateReadMore"
                    href
                    >...Read more</a
                  >
                </div>
                <div v-else>
                  <div v-html="product.product_description"></div>
                  <a
                    class="more-less theme-color"
                    v-if="readMoreActive"
                    @click.prevent="activateLessText"
                    href
                    >---Less text</a
                  >
                </div>
              </div>

              <div class="follow mt10">
                <!-- <span>share:</span> -->
                <a
                  class="entry bg-primary text-white"
                  :href="
                    'https://www.facebook.com/sharer/sharer.php?u=' +
                    url +
                    'product/' +
                    product.id +
                    '/' +
                    product.product_slug
                  "
                >
                  <i class="lni lni-facebook-filled"></i>
                </a>
                <a
                  class="entry bg-info text-white"
                  :href="
                    'https://twitter.com/intent/tweet?text=' +
                    url +
                    'product/' +
                    product.id +
                    '/' +
                    product.product_slug
                  "
                  data-size="large"
                >
                  <i class="lni lni-twitter-filled"></i>
                </a>
                <!-- <a class="entry" href="#"><i class='lni lni-linkedin-original'></i></a>
                <a class="entry" href="#"><i class='lni lni-pinterest'></i></a>-->
              </div>
            </div>
          </div>
          <!-- product details end-->
        </div>
      </div>
    </section>
    <!--Raleted product-->
    <section class="product-details mt30">
      <div class="container">
        <div class="row">
          <div class="col-md-12 offers">
            <div class="title text-center">
              <h4>Related Product</h4>
            </div>
          </div>
        </div>
        <div class="row offers">
          <div
            class="col-6 col-lg-3 col-sm-4"
            v-for="value in hotProducts"
            :key="value.id"
          >
            <single-product
              :currency="currency"
              :product="value"
            ></single-product>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { EventBus } from "../../../vue-assets";
import Mixin from "../../../mixin";
import SingleProduct from "./SingleProduct";

export default {
  props: ["currency", "product"],
  mixins: [Mixin],
  components: {
    "single-product": SingleProduct,
  },
  data() {
    return {
      hotProducts: [],
      url: base_url,

      cart: {
        qty: 1,
      },

      readMoreActive: false,
    };
  },

  computed: {
    havingProduct() {
      return this.$store.getters.productWithId(this.product.id);
    },
  },

  mounted() {
    this.getDeals();
  },

  methods: {
    addToCart(
      id,
      product_name,
      qty_unit,
      qty,
      current_qty,
      price,
      product_image,
      discount
    ) {
      //  ceck color and size checked if color is there
      this.playCartSound();
      //  ceck color and size checked if color is there
      axios
        .post(base_url + "add-to-cart", {
          id: id,
          product_name: product_name,
          qty_unit: qty_unit,
          qty: this.cart.qty,
          current_qty: current_qty,
          price: price,
          product_image: product_image,
          discount: discount,
          size_id: "",
          size_name: "",
          color_id: "",
          color_name: "",
          color_code: "",
        })
        .then((response) => {
          if (response.data.status === "success") {
            if (
              this.product.product_size.length > 0 &&
              this.product.product_color.length > 0
            ) {
              this.successMessage(response.data);
            }

            // dispatch a store commit
            this.$store.dispatch("getCart");
          } else {
            this.successMessage(response.data);
          }
        });
    },
    updateCart(id, status) {
      this.playCartSound();
      axios
        .get(base_url + "cart/update/" + id + "/" + status)
        .then((response) => {
          if (response.data.status === "success") {
            this.$store.dispatch("getCart");
          } else {
            this.successMessage(response.data);
          }
        });
    },

    activateReadMore() {
      this.readMoreActive = true;
    },

    activateLessText() {
      this.readMoreActive = false;
    },

    fetchProduct: function () {
      return axios.get(
        base_url +
          "product-list?no_paginate=yes&take_only=8&sub_category=" +
          this.product.sub_category_id +
          "&without_id=" +
          this.product.id
      );
    },

    getDeals() {
      this.fetchProduct()
        .then((response) => {
          if (response.data.data.length > 0) {
            this.hotProducts = response.data.data;
          } else {
          }
        })
        .catch((e) => console.log(e));
    },
  },
};
</script>


<style scoped="">
/* .discount-price{
	text-decoration: line-through;
} */

.num {
  display: inline;
}
.qty_text {
  float: left;
  /*width: 34px;*/
  height: 40px;
  line-height: 40px;
  border: none;
  background-color: transparent;
  text-align: center;
  padding: 0px 10px 0px 10px;
  margin: 0px;
  font-size: 1.5em;
}
</style>