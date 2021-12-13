<template>
  <div class="col-lg-12 col-sm-12">
    <div class="form bg-white bg-shadow">
      <div class="heading text-center clearfix">
        <h4 class="pt10 theme-color login-headline">Enter Purchase Code</h4>
        <small
          class="heading heading-solid center-block heading-width-100 border-light"
        ></small>
      </div>
      <div class="p30">
        <div class="p-30 text-center">
          <form @submit.prevent="getVerified()" action="" method="POST">
            <div class="form-group">
              <input
                class="form-checkout sign-up-input form-control"
                name="phone"
                value=""
                required
                autocomplete="phone"
                placeholder="Provide Purchase Code To Start"
                autofocus
                max="11"
                v-model="form_data.code"
              />
              <p
                class="ptsan-regular text-danger"
                v-if="validation_error.hasOwnProperty('code')"
              >
                {{ validation_error.code[0] }}
              </p>
            </div>

            <button
              type="submit"
              class="button button-md bg-dark2 color-white mb20 theme-background"
              style="width: 100%"
            >
              <span v-if="!isLoading">Verify</span>
              <span v-else>Verifying....</span>
            </button>
          </form>
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
      form_data: {
        code: "",
      },
      isLoading: false,
      validation_error: {},
    };
  },
  mounted() {
    // console.log("something");
  },

  methods: {
    getVerified() {
      this.isLoading = true;
      axios
        .post(base_url + "post/code", this.form_data)
        .then((response) => {
          if (response.data.status == "success") {
            this.verificationConfirm();
          } else {
            this.validationError("Invalid Purchase Code");
            this.isLoading = false;
          }
        })
        .catch((err) => {
          if (err.response.status == 422) {
            this.validation_error = err.response.data.errors;

            this.validationError();

            this.isLoading = false;
          } else {
            this.successMessage(err);
            this.isloading = false;
          }
        });
    },

    verificationConfirm() {
      axios.get(base_url + "send-verification").then((response) => {
        this.isLoading = false;
        this.successMessage({ status: "success", message: "Successful" });
        window.location.replace(base_url);
      });
    },
  },
};
</script>