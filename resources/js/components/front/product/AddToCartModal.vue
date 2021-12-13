<template>
  <div>
    <div
      class="modal fade"
      id="myModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content" v-if="product">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Please Chose Color and Size
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="product-info">
              <a
                :href="
                  url + 'product/' + product.id + '/' + product.product_slug
                "
                class="name"
                >{{ product.product_name }}</a
              >
              <hr />
            </div>
            <!-- color  -->
            <div class="pro-item" v-if="product.product_color.length > 0">
              <span class="item-name">Color</span> <br />
              <div class="btn-group" data-toggle="buttons">
                <label
                  v-for="clr in product.product_color"
                  data-toggle="tooltip"
                  data-placement="top"
                  :key="clr.id"
                  :title="clr.name + ' color'"
                  class="btn btn-success"
                  :style="{
                    'background-color': clr.color_code,
                    'margin-right': '5px',
                    color: clr.name == 'white' ? 'black' : 'white',
                  }"
                  @click="changeColor(clr)"
                >
                  <input
                    type="radio"
                    name="options"
                    class="my-radio"
                    :value="clr.id"
                    autocomplete="off"
                  />
                  <i class="fas fa-check"></i>
                </label>
              </div>
              <br />
            </div>

            <!-- color        -->

            <!--size-->
            <div class="pro-item" v-if="product.product_size.length > 0">
              <span class="item-name">Size</span> <br />
              <div class="btn-group" data-toggle="buttons">
                <label
                  v-for="sz in product.product_size"
                  :key="sz.id"
                  class="btn btn-outline-danger"
                  style="margin-right: 5px"
                  @click="changeSize(sz)"
                >
                  <input
                    type="radio"
                    name="options"
                    class="my-radio"
                    :value="sz.id"
                    autocomplete="off"
                  />
                  <i class="fas fa-check" v-if="cart.size_id == sz.id"></i>
                  {{ sz.name }}
                </label>
              </div>
              <br />
            </div>
            <!--size-->
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              @click.prevent="
                addToCart(
                  product.id,
                  product.product_name,
                  product.quantity_unit,
                  cart.qty,
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
              class="btn text-white theme-background"
            >
              Add To Cart
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { EventBus } from "../../../vue-assets";
import Mixin from "../../../mixin";

export default {
  mixins: [Mixin],
  data() {
    return {
      product: {
        product_color: [],
        product_size: [],
      },

      url: base_url,

      cart: {
        qty: 1,
        size_id: "",
        size_name: "",
        color_id: "",
        color_name: "",
        color_code: "",
      },
      jq: "",
    };
  },
  mounted() {
    this.jq = $;
    var _this = this;
    EventBus.$on("open-size-modal", function (product) {
      _this.jq("#myModal").modal("show");
      _this.product = product;
    });
  },

  methods: {
    changeColor(clr) {
      this.cart.color_id = clr.id;
      this.cart.color_name = clr.name;
      this.cart.color_code = clr.color_code;
    },

    changeSize(size) {
      this.cart.size_id = size.id;
      this.cart.size_name = size.name;
    },

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
      //  ceck color and size checked if color is there

      if (this.product.product_color.length > 0 && this.cart.color_id == "") {
        this.validationError("Please Choose a color");
        return false;
      }

      if (this.product.product_size.length > 0 && this.cart.size_id == "") {
        this.validationError("Please choose a size");
        return false;
      }

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
          size_id: this.cart.size_id,
          size_name: this.cart.size_name,
          color_id: this.cart.color_id,
          color_name: this.cart.color_name,
          color_code: this.cart.color_code,
        })
        .then((response) => {
          if (response.data.status === "success") {
            this.successMessage(response.data);
            this.jq("#myModal").modal("hide");
            this.cartReset();
            // dispatch a store commit
            this.$store.dispatch("getCart");
          } else {
            this.successMessage(response.data);
          }
        });
    },

    cartReset() {
      this.product = {
        product_color: [],
        product_size: [],
      };

      this.cart = {
        qty: 1,
        size_id: "",
        size_name: "",
        color_id: "",
        color_name: "",
        color_code: "",
      };
    },
  },
};
</script>