<template>
  <div id="pwaModal" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header text-right">
          <button class="btn btn-default" data-dismiss="modal">X</button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-8 b-r">
              <h3 class="m-t-none m-b">Prograssive web app settings</h3>

              <form @submit.prevent="save()" role="form">
                <div class="form-group">
                  <label>App Name *</label>
                  <input
                    v-model="pwa.app_name"
                    type="text"
                    placeholder="App Name"
                    class="form-control"
                  />
                </div>

                <div class="form-group">
                  <label>App short Name Name</label>
                  <input
                    v-model="pwa.app_short_name"
                    type="text"
                    placeholder="App Short Name"
                    class="form-control"
                  />
                </div>

                <div class="form-group">
                  <label
                    >PWA Icon (512X512) * (will show as desktop and phone apps
                    icon)</label
                  >
                  <br />
                  <div
                    class="fileinput fileinput-new"
                    data-provides="fileinput"
                  >
                    <span class="btn btn-block btn-primary btn-file"
                      ><span class="fileinput-new"
                        ><i class="fa fa-camera"></i> Chose a png Icon</span
                      >
                      <span class="fileinput-exists">Change Icon</span
                      ><input type="file" name="..." @change="onImageChange"
                    /></span>
                  </div>
                </div>

                <div style="margin-bottom: 20px">
                  <button
                    class="btn btn-lg btn-primary float-right"
                    type="submit"
                  >
                    <strong>{{ button_name }}</strong>
                  </button>
                  <h4 style="color: green" v-if="button_name != 'Save'">
                    Please Wait.... it will take a while to generate app
                  </h4>
                </div>
              </form>
            </div>
            <div class="col-sm-4">
              <h4>Photo Preview</h4>

              <p class="text-center">
                <img
                  class="img-responsive img-fluid"
                  v-if="pwa.image"
                  :src="pwa.image"
                />
                <img
                  class="img-responsive img-fluid"
                  v-else
                  :src="url + 'images/icons/icon-384x384.png'"
                />
              </p>
            </div>

            <div
              class="col-md-12"
              v-if="validation_error"
              style="margin-top: 20px"
            >
              <div class="form-group">
                <div>
                  <ul>
                    <li class="text-danger" v-for="error in validation_error">
                      {{ error[0] }}
                    </li>
                  </ul>
                </div>
              </div>
            </div>
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
  mixins: [Mixin],

  data() {
    return {
      pwa: {
        id: 0,
        app_name: "",
        app_short_name: "",
        image: "",
      },
      url: base_url,

      button_name: "Save",
      validation_error: null,
    };
  },
  mounted() {
    this.getSetting();
  },

  methods: {
    getSetting() {
      axios.get(base_url + "admin/setting/pwa-setting").then((response) => {
        if (response.data) {
          this.pwa.app_name = response.data.app_name;
          this.pwa.app_short_name = response.data.app_short_name;
          this.pwa.id = response.data.id;
        }
      });
    },
    onImageChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.createImage(files[0]);
    },
    createImage(file) {
      let reader = new FileReader();
      let vm = this;
      reader.onload = (e) => {
        vm.pwa.image = e.target.result;
      };
      reader.readAsDataURL(file);
    },

    save() {
      this.button_name = "Generating App Data...";

      axios
        .post(base_url + "admin/setting/pwa-setting", this.pwa)
        .then((response) => {
          if (response.data.status === "success") {
            $("#modal-form").modal("hide");

            this.resetForm();
            this.successMessage(response.data);
            EventBus.$emit("pwaModal");

            this.button_name = "Save";
          } else {
            this.successMessage(response.data);
            this.button_name = "Save";
          }
        })
        .catch((err) => {
          if (err.response.status == 422) {
            this.validation_error = err.response.data.errors;

            this.validationError();

            this.button_name = "Save";
          } else {
            this.successMessage(err);

            this.isloading = false;

            this.button_name = "Save";
          }
        });
    },

    resetForm() {
      // this.pwa = {
      //   app_name: "",
      //   app_short_name: "",
      //   image: "",
      // };

      this.validation_error = null;
    },
  },
};
</script>