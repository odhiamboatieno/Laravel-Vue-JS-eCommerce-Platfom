<template>
  <div>
    <div class="row">
      <div class="col-lg-6">
        <div class="ibox">
          <div class="ibox-title">
            <h5>Messenger Chat</h5>
          </div>
          <div class="ibox-content">
            <form @submit.prevent="updateFbPageId()">
              <div class="switch">
                <div class="onoffswitch">
                  <input
                    type="checkbox"
                    :checked="fbForm.status == 1 ? true : false"
                    class="onoffswitch-checkbox"
                    id="example1"
                    @change="changeStatus()"
                  />
                  <label class="onoffswitch-label" for="example1">
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                  </label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="app_id">Facebook Page ID</label>
                  <input
                    type="text"
                    v-model="fbForm.app_id"
                    class="form-control"
                  /><br />
                  <button type="submit" class="btn btn-primary">
                    {{ button_name }}
                  </button>
                  <button
                    type="close"
                    class="btn btn-default"
                    data-dismiss="modal"
                  >
                    Close
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="ibox">
          <div class="ibox-title">
            <h5>Follow This Instruction for Facebook Chat Setting</h5>
          </div>
          <div class="ibox-content">
            <ul class="docs-ul">
              <li>First login into your facebook page</li>
              <li>
                Go to about section of page and grab the page id put it in place
                of facebook page id of our admin panel
              </li>
              <li>
                Now go to setting of the page and move to 'Advance Messaging'
              </li>
              <li>You will get a whitelisted domain section</li>
              <li>Put your site domain name there</li>
              <li>Click Save. and it's Done !</li>
            </ul>
          </div>
        </div>
        <!-- <div class="row mt-5">
			    <div class="col-lg-6" > -->

        <!--  </div>
			</div> -->
      </div>
      <div class="col-lg-6">
        <div class="ibox">
          <div class="ibox-title">
            <h5>Google Analytics</h5>
          </div>
          <div class="ibox-content">
            <form @submit.prevent="updateGoogleAppId()">
              <div class="switch">
                <div class="onoffswitch">
                  <input
                    type="checkbox"
                    :checked="googleForm.status == 1"
                    class="onoffswitch-checkbox"
                    id="example2"
                    @change="changeGoogleStatus()"
                  />
                  <label class="onoffswitch-label" for="example2">
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                  </label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="google_id">Tracking ID</label>
                  <input
                    type="text"
                    v-model="googleForm.app_id"
                    id="google_id"
                    class="form-control"
                  /><br />
                  <button type="submit" class="btn btn-primary">
                    {{ button }}
                  </button>
                  <button
                    type="close"
                    class="btn btn-default"
                    data-dismiss="modal"
                  >
                    Close
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="ibox">
          <div class="ibox-title">
            <h5>Google Analytics Setting Instruction</h5>
          </div>
          <div class="ibox-content">
            <ul class="docs-ul">
              <li>
                in google analytics u will get a tracking id put it up here in
                tracking id section
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { EventBus } from "../../../../vue-assets";
import Mixin from "../../../../mixin";

export default {
  name: "VueMessenger",
  mixins: [Mixin],
  data() {
    return {
      fbForm: {
        app_id: "",
        status: "",
      },

      googleForm: {
        app_id: "",
        status: "",
      },
      button_name: "Update",
      button: "Update",
      url: base_url,
    };
  },

  mounted() {
    this.getFbData();
    this.getGoogleData();
  },

  methods: {
    getFbData() {
      axios
        .get(this.url + "admin/setting/get-fb-page-data")
        .then((response) => {
          this.fbForm.app_id = response.data.app_id;
          this.fbForm.status = response.data.status;
        })
        .catch((error) => console.log(error));
    },

    getGoogleData() {
      axios
        .get(this.url + "admin/setting/get-google-app-data")
        .then((response) => {
          this.googleForm.app_id = response.data.app_id;
          this.googleForm.status = response.data.status;
        })
        .catch((error) => console.log(error));
    },

    updateFbPageId() {
      this.button_name = "Saving...";
      axios
        .post(this.url + "admin/setting/set-fb-page-data", this.fbForm)
        .then((response) => {
          this.successMessage(response.data);
          this.button_name = "Update";
        })
        .catch((error) => {
          if (error.response.status == 422) {
            this.validation_error = error.response.data.errors;
            this.validationError();
            this.button_name = "Update";
          } else {
            this.successMessage(error);
            this.button_name = "Update";
          }
        });
    },

    updateGoogleAppId() {
      this.button = "Saving...";
      axios
        .post(this.url + "admin/setting/set-google-app-data", this.googleForm)
        .then((response) => {
          this.successMessage(response.data);
          this.button = "Update";
        })
        .catch((error) => {
          if (error.response.status == 422) {
            this.validation_error = error.response.data.errors;
            this.validationError();
            this.button = "Update";
          } else {
            this.successMessage(error);
            this.button = "Update";
          }
        });
    },

    changeStatus: function () {
      axios
        .post(this.url + "admin/setting/change-facebook-id-status")
        .then((response) => {
          this.successMessage(response.data);
          this.getFbData();
        })
        .catch((error) => {
          this.successMessage(error);
        });
    },

    changeGoogleStatus: function () {
      axios
        .post(this.url + "admin/setting/change-google-app-status")
        .then((response) => {
          this.successMessage(response.data);
          this.getFbData();
        })
        .catch((error) => {
          this.successMessage(error);
        });
    },
  },
};
</script>

<style scoped="">
.docs-ul li {
  margin-top: 10px;
}
</style>