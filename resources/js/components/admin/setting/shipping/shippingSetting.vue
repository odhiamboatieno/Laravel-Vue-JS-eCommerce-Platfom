<template>
  <div class="row">
    <div class="col-sm-10">
      <h3 class="m-t-none m-b">Update Setting</h3>
      <form @submit.prevent="save()" role="form">
        <div class="form-group">
          <label
            >Minimum Order Amount (Customer order amount must upto this
            amount)</label
          >
          <input
            v-model="form.minimum_order_amount"
            type="text"
            placeholder="Minimum Order Amount"
            class="form-control"
          />
        </div>

        <div class="form-group">
          <label>Shipping Amount* (This is Flat Rate)</label>
          <input
            v-model="form.shipping_amount"
            type="text"
            placeholder="Shipping Amount"
            class="form-control"
          />
        </div>

        <div class="form-group">
          <label
            >Order Amount (customer shipping cost will decrease by discount
            amount if cusotomer buy <i class="fa fa-arrow-down"></i> bellow
            mentioned amount item)</label
          >
          <input
            v-model="form.order_amount"
            type="text"
            placeholder="Order Amount"
            class="form-control"
          />
        </div>
        <div class="form-group">
          <label
            >Discount Amount (enter how much discount will have in shipping cost
            for <i class="fa fa-arrow-up"></i> avobe mentioned order
            amount)</label
          >
          <input
            v-model="form.discount_amount"
            type="text"
            placeholder="Discount Amount"
            class="form-control"
          />
        </div>
        <div class="form-group">
          <label
            >Wanna Take Shipping Cost Or Free? (if u off this switch shipping
            cost will be free)</label
          >
          <div class="switch">
            <div class="onoffswitch">
              <input
                type="checkbox"
                :checked="form.shipping_status == 1 ? true : false"
                class="onoffswitch-checkbox"
                id="example1"
                @change="shippingStatus()"
              />
              <label class="onoffswitch-label" for="example1">
                <span class="onoffswitch-inner"></span>
                <span class="onoffswitch-switch"></span>
              </label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label
            >Wanna Apply Discount on Shipping Cost by Shipping Amount ? (if u on
            this switch shipping cost will discount will decrease followed by
            order amount and discount amount)</label
          >
          <div class="switch">
            <div class="onoffswitch">
              <input
                type="checkbox"
                :checked="form.discount_status == 1"
                class="onoffswitch-checkbox"
                id="example2"
                @change="discountStatus()"
              />
              <label class="onoffswitch-label" for="example2">
                <span class="onoffswitch-inner"></span>
                <span class="onoffswitch-switch"></span>
              </label>
            </div>
          </div>
        </div>

        <div style="margin-bottom: 20px">
          <button class="btn btn-lg btn-primary float-right" type="submit">
            <strong>{{ button_name }}</strong>
          </button>
        </div>
      </form>
    </div>

    <div
      class="col-md-12"
      v-if="validation_error"
      style="margin-top: 20px; margin-bottom: 10px"
    >
      <div class="form-group">
        <div>
          <ul>
            <li
              class="text-danger"
              v-for="(error, index) in validation_error"
              :key="index"
            >
              {{ error[0] }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { EventBus } from "../../../../vue-assets";
import Mixin from "../../../../mixin";

export default {
  name: "shippingSetting",
  mixins: [Mixin],

  data() {
    return {
      form: {
        minimum_order_amount: "",
        shipping_amount: "",
        order_amount: "",
        discount_amount: "",
        shipping_status: "",
        discount_status: "",
      },

      isLoading: false,
      button_name: "update",
      url: base_url,
      validation_error: null,
    };
  },

  mounted() {
    // this  will not work in eventBus that why
    // we are initializing with _this

    var _this = this;

    _this.getSetting();

    EventBus.$on("shipping-created", function () {
      _this.getSetting();
    });
  },

  methods: {
    getSetting() {
      axios
        .get(base_url + "admin/setting/shipping/discount")
        .then((response) => {
          this.form.shipping_amount = response.data.shipping_amount;
          this.form.minimum_order_amount = response.data.minimum_order_amount;
          this.form.order_amount = response.data.order_amount;
          this.form.discount_amount = response.data.discount_amount;
          this.form.shipping_status = response.data.shipping_status;
          this.form.discount_status = response.data.discount_status;
        });
    },

    save() {
      this.button_name = "Updating...";

      axios
        .post(base_url + "admin/setting/shipping/discount", this.form)
        .then((response) => {
          if (response.data.status === "success") {
            this.successMessage(response.data);
            this.button_name = "Update";
            this.validation_error = null;
            EventBus.$emit("shipping-created");
          } else {
            this.successMessage(response.data);
            this.button_name = "Update";
          }
        })
        .catch((err) => {
          if (err.response.status == 422) {
            this.validation_error = err.response.data.errors;
            this.validationError();
            this.button_name = "Update";
          } else {
            this.successMessage(err);
            this.button_name = "Update";
          }
        });
    },

    shippingStatus: function () {
      axios
        .post(base_url + "admin/setting/shipping/status")
        .then((response) => {
          if (response.data.status === "success") {
            this.successMessage(response.data);
            this.form.shipping_status = response.data.shipping_status;
            // EventBus.$emit('shipping-created');
          }
        });
    },

    discountStatus: function () {
      axios
        .post(base_url + "admin/setting/discount/status")
        .then((response) => {
          if (response.data.status === "success") {
            this.successMessage(response.data);
            this.form.discount_status = response.data.discount_status;
            // EventBus.$emit('shipping-created');
          }
        });
    },
  },
};
</script>