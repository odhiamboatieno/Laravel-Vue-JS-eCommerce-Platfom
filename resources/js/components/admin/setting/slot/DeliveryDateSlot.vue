<template>
  <div>
    <h3 class="m-t-none m-b">Update Date Slot Setting</h3>
    <form @submit.prevent="save()" role="form">
      <div class="form-group">
        <label
          >Date Start Interval (date will start in slot by this interval if you
          provide 0 then it will start from user current date which he
          visiting)*</label
        >
        <input v-model="form.date_interval" type="text" class="form-control" />
        <span>date will show by {{ form.date_interval }} days interval</span>
      </div>

      <div class="form-group">
        <label
          >Date End (how many next days will be shown in date slot
          option)*</label
        >
        <input v-model="form.date_end" class="form-control" />
        <span
          >date list will stop by adding next {{ form.date_end }} days to the
          list</span
        >
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
        <label>Slot Status (Do You Want Date Time Slot on your System ?)</label>

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

export default {
  mixins: [Mixin],
  data() {
    return {
      form: {
        date_interval: 0,
        date_end: 7,
        status: 0,
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
      axios.get(base_url + "admin/setting/get-date-slot").then((response) => {
        this.form = response.data;
      });
    },

    save() {
      this.button_name = "Updating...";

      axios
        .post(`${base_url}admin/setting/date-slot`, this.form)
        .then((response) => {
          if (response.data.status === "success") {
            this.successMessage(response.data);
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