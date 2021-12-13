<template>
  <div class="row">
    <div class="col-md-6 mr-auto ml-auto border p-4 bg-white">
      <h3 class="m-t-none m-b">Update Trial Setting</h3>
      <form @submit.prevent="save()" role="form">
        <div class="form-group">
          <label
            >Minnimum Product in Cart For Getting Trial Option By User (Suppose
            if user don't add 2 product on cart he won't get trial opption for
            any product)*</label
          >
          <input
            v-model="form.product_in_cart"
            type="text"
            class="form-control"
          />
        </div>

        <div class="form-group">
          <label>Maximum Trial Item User can Add To Thier Cart*</label>
          <input v-model="form.max_trial_item" class="form-control" />
        </div>
        <!-- <div class="form-group">
          <label>Trial Charge per Item *</label>
          <input
            v-model="form.trial_charge_per_item"
            type="text"
            class="form-control"
          />
        </div> -->

        <div class="form-group">
          <label>Trial Status (Do You Want Trial System On Your Site ?)</label>

          <select class="form-control" v-model="form.status">
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>

        <div style="margin-bottom: 20px">
          <button
            style="margin-bottom: 20px"
            class="btn btn-lg btn-primary float-right"
            type="submit"
          >
            <strong>{{ button_name }}</strong>
          </button>
        </div>
      </form>
    </div>
    <div
      class="col-md-12"
      v-if="validation_error"
      style="margin-top: 20px; margin-bottom: 20px"
    >
      <div class="form-group">
        <div>
          <ul>
            <li
              class="text-danger"
              v-for="error in validation_error"
              :key="error[0]"
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
import { VueEditor } from "vue2-editor";

export default {
  mixins: [Mixin],
  data() {
    return {
      form: {
        product_in_cart: "",
        max_trial_item: "",
        trial_charge_per_item: "",
        status: "",
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

    _this.getTrialSetting();

    EventBus.$on("trial-created", function () {
      _this.getTrialSetting();
    });
  },

  methods: {
    getTrialSetting() {
      axios
        .get(base_url + "admin/setting/trial/" + 1 + "/edit")
        .then((response) => {
          this.form.product_in_cart = response.data.product_in_cart;
          this.form.max_trial_item = response.data.max_trial_item;
          this.form.trial_charge_per_item = response.data.trial_charge_per_item;
          this.form.status = response.data.status;
        });
    },

    save() {
      this.button_name = "Updating...";

      axios
        .post(`${base_url}admin/setting/trial`, this.form)
        .then((response) => {
          if (response.data.status === "success") {
            this.successMessage(response.data);
            EventBus.$emit("trial-created");
            this.button_name = "Update";
            this.validation_error = null;
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
  },
};
</script>