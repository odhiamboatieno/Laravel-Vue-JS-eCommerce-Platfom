<template>
  <div>
    <div data-hide="" class="sidebar_right" id="hidden_right">
      <div class="">
        <div class="sidebar_right_inner">
          <a class="sidebar_right_close theme-hover-bg"
            ><i class="lni lni-close"></i
          ></a>
        </div>

        <div class="slider_right_content" style="opacity: 1">
          <h2>
            <strong v-if="section_active == 'cart'"
              >My Cart(<span class="cart-pro-count">{{ cartCount }}</span
              >)</strong
            >
          </h2>
          <div class="cart-body">
            <ul class="slider_right_product">
              <div v-if="shipping.shipping_status == 1">
                <span
                  class="shipping-discount theme-background"
                  v-if="
                    shipping.discount_status == 1 &&
                    cartTotal < shipping.order_amount
                  "
                  >Shop {{ currency.symbol
                  }}{{ (shipping.order_amount - cartTotal) | formatPrice }} more
                  and save {{ currency.symbol
                  }}{{ shipping.discount_amount }}</span
                >

                <span
                  class="shipping-discount bg-success"
                  v-else-if="
                    shipping.discount_status == 1 &&
                    cartTotal > shipping.order_amount
                  "
                  >You have saved {{ currency.symbol
                  }}{{ shipping.discount_amount }} delivery cost</span
                >

                <span class="shipping-discount theme-background" v-else
                  >Shipping Cost {{ currency.symbol
                  }}{{ shipping.shipping_amount }}</span
                >
              </div>

              <div v-else>
                <span class="shipping-discount bg-success">Free Shipping</span>
              </div>

              <!--cart empty icon-->

              <!--cart product-->
              <div class="cart-section">
                <div class="cart-item" v-if="section_active == 'cart'">
                  <table class="table-responsive mt-2" v-if="cartCount > 0">
                    <tbody>
                      <tr v-for="(value, index) in cartItems" :key="index">
                        <td>
                          <i
                            title="Add one more"
                            @click="updateCart(value.rowId, 'increment')"
                            class="lni lni-plus"
                          ></i>
                          <strong> {{ value.qty }} </strong>
                          <i
                            title="Remove one"
                            @click="updateCart(value.rowId, 'decrement')"
                            class="lni lni-minus"
                          ></i>
                        </td>
                        <td>
                          <img
                            width="150"
                            v-lazy="value.options.image"
                            alt="product-image"
                            class="img-fluid"
                          />
                        </td>
                        <td>
                          <span class="title"
                            >{{ value.name }}
                            <small v-if="value.options.size_name"
                              >Size:{{ value.options.size_name }}</small
                            >
                            <small v-if="value.options.color_name"
                              >Color:{{ value.options.color_name }}</small
                            >
                          </span>
                          <span class="price"
                            >{{ currency.symbol
                            }}{{ value.price | formatPrice }}</span
                          >

                          <span
                            class="price discount-price"
                            v-if="value.options.discount > 0"
                            >{{ currency.symbol
                            }}{{
                              (value.price + value.options.discount)
                                | formatPrice
                            }}</span
                          >
                        </td>
                        <td>
                          {{ currency.symbol
                          }}{{ (value.qty * value.price) | formatPrice }}
                        </td>
                        <td>
                          <a @click.prevent="removeItem(value.rowId)" href="">
                            <i class="lni lni-close theme-hover-color"></i>
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <div class="cart-empty" v-else>
                    <div class="icon">
                      <i class="lni lni-shopping-basket theme-color"></i>
                    </div>
                    <span>Your shopping bag is empty. Start shopping now.</span>
                  </div>
                </div>
              </div>
              <!-- end cart product  -->
            </ul>
          </div>
          <div class="slider-right__money p10" v-if="cartCount > 0">
            <span>Subtotal: </span>
            <strong class="float-right">
              <span class="woocommerce-Price-amount amount">
                <span class="woocommerce-Price-currencySymbol">{{
                  currency.symbol
                }}</span
                >{{ cartTotal | formatPrice }}</span
              >
            </strong>
            <a
              v-if="cartCount > 0"
              @click="
                checkMinimumAmount(
                  $event,
                  shipping.minimum_order_amount,
                  cartTotal
                )
              "
              class="slider_right_order theme-background"
              :href="url + 'checkout'"
              >Checkout</a
            >
          </div>
        </div>
      </div>
    </div>

    <div class="cart-right d-none d-sm-block d-md-block d-lg-block d-xl-block">
      <a class="sidebar_right_cart theme-background" style="">
        <span
          ><i class="lni lni-shopping-basket"></i> {{ cartCount }} Items</span
        >
        <div class="cart-count custom-cart-total">
          <span class="cart-text"></span>
          <span class="cart_count theme-color" style="opacity: 1"
            >{{ currency.symbol
            }}<animated-number
              :value="cartTotal"
              :formatValue="formatToPrice"
              :duration="1000"
            />
          </span>
        </div>
      </a>
    </div>

    <div
      class="container-fluid cart-bottom d-sm-none d-md-none d-lg-none d-xl-none"
    >
      <div class="row">
        <div class="col-3 no-padding text-center">
          <a
            href=""
            class="price-bottom theme-color cart-open sidebar_right_cart"
            >{{ currency.symbol
            }}<animated-number
              :value="cartTotal"
              :formatValue="formatToPrice"
              :duration="1000"
          /></a>
        </div>
        <div class="col-6 no-padding">
          <!-- <a v-if="cartCount > 0" :href="url+'checkout'"  class="button button-md bg-custom color-white btn-block ">Place Order</a> -->
          <a
            href=""
            class="button button-md theme-background color-white btn-block sidebar_right_cart"
            >View Cart</a
          >
        </div>
        <div class="col-3 no-padding text-center">
          <a
            title="cart open"
            class="button button-md btn-block bg-white cart-open sidebar_right_cart"
            href=""
          >
            <i class="lni lni-shopping-basket"></i>
            <span class="badge theme-background color-white">{{
              cartCount
            }}</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { EventBus } from "../../../vue-assets";
import Mixin from "../../../mixin";
import AnimatedNumber from "animated-number-vue";

export default {
  mixins: [Mixin],
  props: ["currency", "trial_setting"],
  components: {
    AnimatedNumber,
  },
  data() {
    return {
      // search_keyword : {}
      url: base_url,
      shipping: {},
      section_active: "cart",
    };
  },

  mounted() {
    this.$store.dispatch("getCart");
    this.getShipping();
  },

  methods: {
    checkMinimumAmount(event, minimum_order_amount, cart_total) {
      if (minimum_order_amount > cart_total) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Minimum order amount is " + minimum_order_amount,
        });

        event.preventDefault();
      }
    },
    removeItem(id) {
      axios.get(base_url + "cart/remove/" + id).then((response) => {
        this.$store.dispatch("getCart");
      });
    },

    updateCart(id, status) {
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
    getShipping() {
      axios.get(base_url + "get-shipping").then((response) => {
        this.shipping = response.data;
      });
    },
    formatToPrice(value) {
      return value.toFixed(2);
    },

    changeSection(value) {
      this.section_active = value;
    },
  },

  computed: {
    cartCount() {
      return this.$store.getters.cart_count;
    },

    cartTotal() {
      return this.$store.getters.cart_total;
    },
    isLoading() {
      return this.$store.getters.cart_loading;
    },

    cartItems() {
      return this.$store.getters.cart_items;
    },
  },
};
</script>

<style  scoped>
.actived {
  border-bottom: none !important;
}
</style>



