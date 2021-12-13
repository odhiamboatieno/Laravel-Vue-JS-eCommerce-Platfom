<template>
  <div class="offer">
    <!-- <a href="" class="btn theme-background color-white" title="Add To Trial">
     <i class="lni lni-tshirt"></i>
   </a> -->
    <a :href="url + 'product/' + product.id + '/' + product.product_slug">
      <img v-lazy="product.feature_image" class="img-fluid" />
    </a>
    <div class="content">
      <a
        :href="url + 'product/' + product.id + '/' + product.product_slug"
        class="name"
        >{{ product.product_name }}</a
      >
      <p class="qty_unit">
        <small>{{ product.quantity_unit }}</small>
      </p>
      <span class="regular-price" v-if="product.discount_status == 1"
        >{{ currency.symbol
        }}{{
          (product.selling_price - product.discount_amount) | formatPrice
        }}</span
      >
      <span class="regular-price" v-else
        >{{ currency.symbol }}{{ product.selling_price }}</span
      >

      <span
        class="discount-price"
        v-if="product.discount_status == 1 && product.discount_amount > 0"
        >{{ currency.symbol }}{{ product.selling_price | formatPrice }}</span
      >

      <div class="item-cart" v-if="havingProduct">
        <a
          title="Remove On"
          @click.prevent="updateCart(havingProduct.rowId, 'decrement')"
          class="float-left qty-minus"
        >
          <strong><i class="lni lni-minus"></i></strong>
        </a>

        <div class="qty-text float-left">
          <strong>{{ havingProduct.qty }} in Cart</strong>
        </div>

        <a
          title="Add One More"
          @click.prevent="updateCart(havingProduct.rowId, 'increment')"
          class="float-left qty-plus"
        >
          <strong><i class="lni lni-plus"></i></strong>
        </a>
      </div>

      <a
        v-else
        @click.prevent="
          addToCart(
            product.id,
            product.product_name,
            product.quantity_unit,
            1,
            product.current_quantity,
            product.discount_status == 1 && product.discount_amount > 0
              ? product.selling_price - product.discount_amount
              : product.selling_price,
            product.feature_image,
            product.discount_status == 1 && product.discount_amount > 0
              ? product.discount_amount
              : 0
          )
        "
        href=""
        class="button button-sm add_to_cart_button"
      >
        {{ cart_button }} <i class="lni-shopping-basket"></i
      ></a>
    </div>
  </div>
</template>

<script>
import { EventBus } from "../../../vue-assets";
import Mixin from "../../../mixin";

export default {
  props: ["currency", "product"],
  mixins: [Mixin],
  data() {
    return {
      url: base_url,
      cart_button: "Add to Cart",
    };
  },

  computed: {
    havingProduct() {
      return this.$store.getters.productWithId(this.product.id);
    },
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
      this.playCartSound();
      this.cart_button = "Adding...";
      axios
        .post(base_url + "add-to-cart", {
          id: id,
          product_name: product_name,
          qty_unit: qty_unit,
          qty: qty,
          current_qty: current_qty,
          price: price,
          product_image: product_image,
          discount: discount,
        })
        .then((response) => {
          if (response.data.status === "success") {
            // this.successMessage(response.data);
            // dispatch a store commit
            this.$store.dispatch("getCart");
            this.cart_button = "Add to Cart";
          } else {
            this.successMessage(response.data);
            this.cart_button = "Add to Cart";
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
  },
};
</script>

<style>
</style>


