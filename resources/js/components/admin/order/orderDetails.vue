<template>
  <div id="order_details" class="modal fade">
    <div class="modal-dialog modal-custom">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="m-t-none m-b">Order #{{ order.id }}</h3>
          <button class="btn btn-default text-right" data-dismiss="modal">
            X
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-3 offset-6">
              <div class="form-group">
                <select
                  class="form-control"
                  v-model="order.payment_status"
                  @change="changePaymentStatus(order.id)"
                >
                  <option value="1">Paid</option>
                  <option value="0">Unpaid</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <select
                  class="form-control"
                  v-model="order.status"
                  @change="changeProcessStatus(order.id, $event)"
                >
                  <option value="0">Pending</option>
                  <option value="1">On Process</option>
                  <option value="2">On Delivery</option>
                  <option value="3">Delivered</option>
                </select>
              </div>
            </div>
          </div>
          <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
              <div class="col-sm-6">
                <h5>From:</h5>
                <address>
                  <strong>{{ address.shop_name }}</strong
                  ><br />
                  {{ address.address }}<br />
                  <!-- Chicago, VT 32456<br> -->
                  <abbr title="Phone">P:</abbr> {{ address.phone }}
                </address>
              </div>

              <div class="col-sm-6">
                <div class="float-right">
                  <h4>
                    Order No. <span class="text-navy">{{ order.id }}</span>
                  </h4>
                  <!-- <h4 class="text-navy"></h4> -->
                  <span>To: </span>
                  <address>
                    <strong>{{ order.customer_name }}</strong
                    ><br />
                    <span>{{ order.address }}</span
                    ><br />

                    <abbr title="Phone">Phone: </abbr> {{ order.phone }}
                  </address>
                  <p>
                    <span
                      ><strong>Order Date: </strong
                      >{{ order.order_date | dateToString }}</span
                    ><br />
                    <span v-if="order.customer_delivery_date"
                      ><strong>Expected Delivery Slot : </strong
                      >{{ order.customer_delivery_date | dateToString }} ({{
                        order.customer_delivery_time
                      }})</span
                    >
                    <br />
                    <span v-if="order.status == 3"
                      ><strong>Delivered at: </strong
                      >{{ order.delivery_date | dateToString }}</span
                    ><br />

                    <span v-if="order.payment_status == 1"
                      ><strong>Paid By:</strong>
                      <span v-if="order.payment_method == 1">Cash</span>
                      <span v-else-if="order.payment_method == 2">Paypal</span>
                      <span v-else-if="order.payment_method == 3">Stripe</span>
                      <span v-else-if="order.payment_method == 4">{{
                        order.card_type
                      }}</span>
                      <span v-else-if="order.payment_method == 5"
                        >Razorpay</span
                      >
                      <span v-else>Cash On Delivery</span>
                    </span>
                  </p>
                </div>
              </div>
            </div>

            <div class="table-responsive mt-5">
              <table class="table invoice-table table-bordered">
                <thead>
                  <tr>
                    <th
                      v-if="order.status != 3 && order.order_details.length > 1"
                      style="width: 10%"
                    >
                      Delete
                    </th>
                    <th>Item Id</th>
                    <th>Image</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="value in order.order_details" :key="value.id">
                    <td
                      v-if="order.status != 3 && order.order_details.length > 1"
                    >
                      <a
                        class="btn btn-sm btn-danger"
                        @click.prevent="deleteSingleItem(value.id)"
                        href=""
                      >
                        <i class="fa fa-trash"></i>
                      </a>
                    </td>
                    <td>{{ value.product_id }}</td>
                    <td>
                      <img
                        :src="
                          url +
                          'images/product/feature/' +
                          value.product.product_image
                        "
                        alt="Product Image"
                        height="50"
                        width="50"
                      />
                    </td>
                    <td>
                      {{ value.product.product_name }}
                      {{ value.product.quantity_unit }}
                      <br v-if="value.color || value.size" />
                      <span v-if="value.color"
                        >,Color:
                        <button
                          class="color-button"
                          :style="{
                            'background-color': value.color.color_code,
                          }"
                          :title="value.color.name"
                        ></button>
                      </span>
                      <span v-if="value.size"
                        >,Size: {{ value.size.name }}</span
                      >
                    </td>
                    <td>
                      <button
                        v-if="order.status != 3 && value.quantity > 1"
                        class="btn btn-sm btn-danger mr-2"
                        @click.prevent="updateQty('decrement', value.id)"
                      >
                        -
                      </button>

                      {{ value.quantity }}

                      <button
                        v-if="order.status != 3"
                        class="btn btn-sm btn-success ml-2"
                        @click.prevent="updateQty('increment', value.id)"
                      >
                        +
                      </button>
                    </td>
                    <td>
                      {{ currency.symbol
                      }}{{ value.selling_price | formatPrice }}
                      <span
                        class="discount-price"
                        v-if="value.unit_discount > 0"
                        >{{ currency.symbol
                        }}{{
                          (Number(value.selling_price) +
                            Number(value.unit_discount))
                            | formatPrice
                        }}</span
                      >
                    </td>
                    <td>
                      {{ currency.symbol
                      }}{{
                        (value.quantity * value.selling_price) | formatPrice
                      }}
                    </td>
                  </tr>

                  <!-- trial item  -->
                  <tr
                    v-if="order.trial_product.length > 0"
                    class="bg-dark text-white"
                  >
                    <td colspan="7" style="text-align: center; font-size: 20px">
                      Trial Items
                    </td>
                  </tr>

                  <tr v-for="value in order.trial_product" :key="value.id">
                    <td
                      v-if="order.status != 3 && order.order_details.length > 1"
                    >
                      <a
                        class="btn btn-sm btn-danger"
                        @click.prevent="deleteTrialItem(value.id)"
                        href=""
                      >
                        <i class="fa fa-trash"></i>
                      </a>
                    </td>
                    <td>{{ value.product_id }}</td>
                    <td>
                      <img
                        :src="
                          url +
                          'images/product/feature/' +
                          value.product.product_image
                        "
                        alt="Product Image"
                        height="50"
                        width="50"
                      />
                    </td>
                    <td>
                      {{ value.product.product_name }}
                      {{ value.product.quantity_unit }}
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
                      <span v-if="value.size"
                        >,Size: {{ value.size.name }}</span
                      >
                      <br />
                    </td>
                    <td>
                      <!-- <button v-if="order.status != 3 && value.quantity > 1"
                                         class="btn btn-sm btn-danger mr-2" @click.prevent="updateQty('decrement',value.id)">-</button> -->

                      {{ value.quantity }}

                      <!-- <button v-if="order.status != 3" class="btn btn-sm btn-success ml-2" @click.prevent="updateQty('increment',value.id)">+</button> -->
                    </td>
                    <td>
                      {{ currency.symbol
                      }}{{ value.selling_price | formatPrice }}
                      <span
                        class="discount-price"
                        v-if="value.unit_discount > 0"
                        >{{ currency.symbol
                        }}{{
                          (Number(value.selling_price) +
                            Number(value.unit_discount))
                            | formatPrice
                        }}</span
                      >
                    </td>
                    <td>
                      <button
                        v-if="order.status != 3 && value.invoiced == 0"
                        class="btn btn-success"
                        @click="addToInvoice(value.id)"
                      >
                        Add To Invoice
                      </button>
                      <span class="badge badge-info" v-if="value.invoiced == 1"
                        >Added To Invoice</span
                      >
                    </td>
                  </tr>

                  <!-- end trila item part  -->
                </tbody>
              </table>
              <h5 v-if="order.note" class="text-bold text-black">
                Customer Note: {{ order.note }}
              </h5>
            </div>
            <!-- /table-responsive -->
            <div class="row mt-5">
              <div class="col-md-6">
                <form @submit.prevent="sendmail()" role="form">
                  <div class="form-group">
                    <label
                      >Additional Text
                      <small
                        >(if u don't provide text just invoice will be
                        sended)</small
                      ></label
                    >
                    <textarea
                      v-model="form.message"
                      class="form-control"
                      rows="4"
                      placeholder="Write some text..."
                    ></textarea>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary btn-block">
                      {{ button_name }}
                    </button>
                  </div>
                </form>
              </div>
              <div class="col-md-6">
                <table class="table invoice-total">
                  <tbody>
                    <tr>
                      <td><strong>Sub Total :</strong></td>
                      <td class="text-right">
                        {{ currency.symbol }}{{ order.total_amount }}
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Shipping :</strong></td>
                      <td class="text-right">
                        {{ currency.symbol
                        }}{{ order.shipping_amount | formatPrice }}
                      </td>
                    </tr>
                    <tr v-if="order.coupon_discount > 0">
                      <td><strong>Total :</strong></td>
                      <td class="text-right">
                        {{ currency.symbol
                        }}{{
                          (order.shipping_amount | formatPrice) +
                          (order.total_amount | formatPrice)
                        }}
                      </td>
                    </tr>
                    <tr v-if="order.coupon_discount > 0">
                      <td>
                        <strong
                          >(-) Coupon Discount ({{ order.cupon }}) :</strong
                        >
                      </td>
                      <td class="text-right">
                        {{ currency.symbol }}{{ order.coupon_discount }}
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Grand Total :</strong></td>
                      <td class="text-right">
                        {{ currency.symbol
                        }}{{
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
          <div class="text-right">
            <a
              :href="url + 'admin/order/pdf/' + order.id"
              class="btn btn-primary"
              ><i class="fa fa-file-pdf-o"></i> PDF</a
            >
            <a
              :href="url + 'admin/order/print/' + order.id"
              target="_blank"
              class="btn btn-primary"
              ><i class="fa fa-print"></i> Print</a
            >
            <button
              class="btn btn-danger"
              @click.prevent="deleteOrder(order.id)"
            >
              <i class="fa fa-trash"></i> Delete
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
  props: ["currency"],
  data() {
    return {
      form: {
        message: "",
      },
      order: { order_details: [], trial_product: [] },
      address: {},
      isLoading: false,
      output: null,
      button_name: "Send Email with Invoice",
      url: base_url,
    };
  },

  mounted() {
    var _this = this;
    EventBus.$on("order-details", function (id) {
      _this.orderDetails(id);
      _this.shopAddress();
      $("#order_details").modal("show");
    });
  },
  methods: {
    orderDetails(id) {
      this.isLoading = true;

      axios.get(base_url + "admin/order/" + id).then((response) => {
        this.order = response.data;
        this.isLoading = false;
      });
    },

    shopAddress() {
      axios.get(base_url + "admin/shop/address").then((response) => {
        this.address = response.data;
      });
    },

    changePaymentStatus(id) {
      Swal.fire(
        {
          title: "Are you sure ?",
          text: "Change the Payment Status!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, Change it!",
        },
        () => {}
      ).then((result) => {
        if (result.value) {
          axios
            .get(base_url + "admin/order/payment-status/" + id)
            .then((response) => {
              this.successMessage(response.data);
              this.orderDetails(id);
              EventBus.$emit("order-created");
            });
        }
      });
    },

    changeProcessStatus(id, event) {
      Swal.fire(
        {
          title: "Are you sure ?",
          text: "Process Status will change!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, Change it!",
        },
        () => {}
      ).then((result) => {
        if (result.value) {
          var datavalue = event.target.value;
          axios
            .get(
              base_url + "admin/order/" + datavalue + "/process-status/" + id
            )
            .then((response) => {
              this.successMessage(response.data);
              this.orderDetails(id);
              EventBus.$emit("order-created");
            });
        }
      });
    },

    deleteSingleItem(id) {
      Swal.fire(
        {
          title: "Are you sure ?",
          text: "This item will be deleted from invoice!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        },
        () => {}
      ).then((result) => {
        if (result.value) {
          axios
            .get(
              base_url +
                "admin/order/delete/single-item/" +
                this.order.id +
                "/" +
                id
            )
            .then((res) => {
              if (res.data.status === "success") {
                this.successMessage(res.data);
                this.orderDetails(this.order.id);
              } else {
                this.successMessage(res.data);
              }
            });
        }
      });
    },

    deleteTrialItem(id) {
      Swal.fire(
        {
          title: "Are you sure ?",
          text: "This item will be deleted from trial!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        },
        () => {}
      ).then((result) => {
        if (result.value) {
          axios
            .get(base_url + "admin/order/delete/trial-item/" + id)
            .then((res) => {
              if (res.data.status === "success") {
                this.successMessage(res.data);
                this.orderDetails(this.order.id);
              } else {
                this.successMessage(res.data);
              }
            });
        }
      });
    },

    updateQty(action_type, id) {
      Swal.fire(
        {
          title: "Are you sure ?",
          text:
            action_type == "increment"
              ? "The Item Will Incremented by 1!"
              : "The Item Will decremented by 1!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText:
            action_type == "increment"
              ? "Yes, increment "
              : "Yes decrement " + "it!",
        },
        () => {}
      ).then((result) => {
        if (result.value) {
          axios
            .post(base_url + "admin/order/item-increment/" + id, {
              type: action_type,
            })
            .then((res) => {
              if (res.data.status === "success") {
                this.successMessage(res.data);
                this.orderDetails(this.order.id);
              } else {
                this.successMessage(res.data);
              }
            });
        }
      });
    },

    sendmail() {
      this.button_name = "Sending...";
      axios
        .post(base_url + "admin/send-sms-invoice-to-customer", {
          order_id: this.order.id,
          message: this.form.message,
        })
        .then((response) => {
          console.log(response.data);
          if (response.data.status === "success") {
            this.resetForm();
            this.successMessage(response.data);
            this.button_name = "Send Email with Invoice";
          } else {
            this.button_name = "Send Email with Invoice";
            this.successMessage(response.data);
          }
        })
        .catch((err) => {
          this.isloading = false;
          this.button_name = "Send Email with Invoice";
          this.successMessage(err);
        });
    },

    resetForm() {
      this.form = {
        order_id: "",
        message: "",
      };
    },
    deleteOrder(id) {
      Swal.fire(
        {
          title: "Are you sure ?",
          text: "The Whole Order Will Be Deleted!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        },
        () => {}
      ).then((result) => {
        if (result.value) {
          axios.get(base_url + "admin/order/delete/" + id).then((res) => {
            this.successMessage(res.data);
            $("#order_details").modal("hide");
            EventBus.$emit("order-created");
          });
        }
      });
    },

    addToInvoice(id) {
      Swal.fire(
        {
          title: "Are you sure ?",
          text: "Add To Invoice!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, Add!",
        },
        () => {}
      ).then((result) => {
        if (result.value) {
          axios
            .post(base_url + "admin/trial-to-invoice/" + id)
            .then((response) => {
              this.successMessage(response.data);
              this.orderDetails(this.order.id);
            });
        }
      });
    },
  },
};
</script>

<style scoped="">
.modal-custom {
  max-width: 90% !important;
}
.discount-price {
  text-decoration: line-through;
  color: red;
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