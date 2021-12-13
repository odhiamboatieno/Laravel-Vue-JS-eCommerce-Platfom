<template>
  <div id="modal-form" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-custom">
      <div class="modal-content">
        <div class="modal-header text-right">
          <h3 class="modal-title">
            Invoice<strong>#{{ order_no }}</strong>
          </h3>
          <button class="btn btn-default" data-dismiss="modal">X</button>
        </div>
        <div class="modal-body">
          <div class="row pb-2 invoice-header">
            <div class="col-md-6">
              <p class="font-weight-bold mb-2">Shipping Information</p>
              <p>{{ order.customer_name }}</p>
              <p>{{ order.phone }}</p>
              <p v-if="order.shipping_area">{{ order.shipping_area.city }}</p>
              <p>{{ order.address }}</p>
            </div>

            <div class="col-md-6 text-right">
              <p class="font-weight-bold mb-2">Payment Info</p>
              <p>
                <span class="text-muted">Status: </span>
                <span v-if="order.payment_status == 1">Paid</span>

                <span v-else>Unpaid</span>
              </p>
              <p v-if="order.payment_status == 1">
                <span class="text-muted">Paid In: </span>

                <span v-if="order.payment_method == 2">Paypal</span>
                <span v-else-if="order.payment_method == 3">Stripe</span>
                <span v-else-if="order.payment_method == 4">SSL Commerz</span>
                <span v-else-if="order.payment_method == 5">Razorpay</span>
                <span v-else>Cash on Delivery</span>
              </p>
              <p>
                <span class="text-muted">Order Placed: </span>
                {{ order.order_date | dateToString }}
              </p>
              <p>
                <span v-if="order.customer_delivery_date"
                  ><strong>Expected Delivery Slot : </strong
                  >{{ order.customer_delivery_date | dateToString }} ({{
                    order.customer_delivery_time
                  }})</span
                ><br />
              </p>
              <p v-if="order.status == 3">
                <span class="text-muted">Delivery Date: </span>
                {{ order.delivery_date | dateToString }}
              </p>
            </div>
          </div>
          <div class="row" v-if="!isLoading">
            <div class="col-lg-12 col-sm-12">
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Image</th>
                      <th scope="col">Name</th>
                      <th scope="col">Qty</th>
                      <th scope="col">Unit Price</th>
                      <th scope="col">Total Price</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="value in detials_info" :key="value.id">
                      <td>
                        <img
                          v-lazy="
                            url +
                            'images/product/feature/' +
                            value.product.product_image
                          "
                          alt=".webp not supported in safari"
                          height="40"
                          width="50"
                        />
                      </td>
                      <td>
                        {{ value.product.product_name }} <br />
                        <small>{{ value.product.quantity_unit }}</small>
                        <br v-if="value.color || value.size" />
                        <span v-if="value.color"
                          >Color:
                          <button
                            class="color-button"
                            :style="{
                              'background-color': value.color.color_code,
                            }"
                            :title="value.color.name"
                          ></button>
                        </span>
                      </td>
                      <td>{{ value.quantity }}</td>
                      <td>
                        {{ currency.symbol }}
                        {{ value.selling_price | formatPrice }}
                        <span
                          class="discount-price"
                          v-if="value.unit_discount > 0"
                          >{{ currency.symbol }}
                          {{
                            (Number(value.selling_price) +
                              Number(value.unit_discount))
                              | formatPrice
                          }}</span
                        >
                      </td>
                      <td>
                        {{ currency.symbol }}
                        {{ value.total_selling_price | formatPrice }}
                      </td>
                    </tr>

                    <tr>
                      <td class="thick-line"></td>
                      <td class="thick-line"></td>
                      <td class="thick-line"></td>
                      <td class="thick-line"><strong>Subtotal</strong></td>
                      <td class="thick-line">
                        {{ currency.symbol }}
                        {{ order.total_amount | formatPrice }}
                      </td>
                    </tr>
                    <tr>
                      <td class="no-line"></td>
                      <td class="no-line"></td>
                      <td class="no-line"></td>
                      <td class="no-line"><strong>Shipping</strong></td>
                      <td class="no-line">
                        {{ currency.symbol }}
                        {{ order.shipping_amount | formatPrice }}
                      </td>
                    </tr>
                    <tr v-if="order.coupon_discount > 0">
                      <td class="no-line"></td>
                      <td class="no-line"></td>
                      <td class="no-line"></td>
                      <td class="no-line"><strong>Total</strong></td>
                      <td class="no-line">
                        {{ currency.symbol }}
                        {{
                          (order.shipping_amount | formatPrice) +
                          (order.total_amount | formatPrice)
                        }}
                      </td>
                    </tr>
                    <tr v-if="order.coupon_discount > 0">
                      <td class="no-line"></td>
                      <td class="no-line"></td>
                      <td class="no-line"></td>
                      <td class="no-line">
                        <strong>Discount : ({{ order.cupon }})</strong>
                      </td>
                      <td class="no-line">
                        {{ currency.symbol }} {{ order.coupon_discount }}
                      </td>
                    </tr>
                    <tr>
                      <td class="no-line"></td>
                      <td class="no-line"></td>
                      <td class="no-line"></td>
                      <td class="no-line"><strong>Grand Total</strong></td>
                      <td class="no-line">
                        {{ currency.symbol }}
                        {{
                          (order.shipping_amount | formatPrice) +
                          (order.total_amount | formatPrice) -
                          order.coupon_discount
                        }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="row" v-else>
            <div class="col-md-12 text-center">
              <img :src="url + 'images/loading.gif'" />
            </div>
          </div>
          <div class="row">
            <div class="col-10"></div>
            <div class="col-2 mt-4">
              <a
                :href="url + 'user-order-details-pdf/' + order_no"
                class="btn btn-primary float-center btn-sm"
                >PDF</a
              >

              <!-- <a :href="url+'user-order-details-print/'+order_no" target="_blank" class="btn btn-primary btn-sm ml-1">Print</a> -->
            </div>
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
  props: ["currency"],
  mixins: [Mixin],
  data() {
    return {
      order: {},
      detials_info: [],
      order_no: null,
      url: base_url,
      isLoading: false,
      showTrial: false,
    };
  },

  mounted() {
    var _this = this;
    var _symb = $;
    EventBus.$on("view-details", function (order) {
      _this.order_no = order.id;
      _this.order = order;
      _this.userOrderDetails();
      _symb("#modal-form").modal("show");
    });
  },

  methods: {
    userOrderDetails() {
      // this.order_no = id
      this.isLoading = true;
      axios
        .get(base_url + "user/order/" + this.order_no + "/details")
        .then((response) => {
          // console.log(response.data)
          this.detials_info = response.data.order_details;
          this.isLoading = false;
        })
        .catch((err) => console.log(err));
    },
  },
};
</script>

<style scoped="">
.modal-custom {
  max-width: 70% !important;
}

@media screen and (max-width: 573px) {
  .modal-custom {
    max-width: 100% !important;
    background-color: #000 !important;
  }
}

.color-button {
  border: 1 px solid #000;
  padding: 7px;
}
</style>