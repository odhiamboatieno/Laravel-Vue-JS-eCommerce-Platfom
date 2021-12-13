<template>
  <div class="row">
    <div class="col-sm-8 b-r">
      <h3 class="m-t-none m-b">Update Setting</h3>
      <form @submit.prevent="save()" role="form">
        <div class="form-group">
          <label>Shop Name *</label>
          <input
            v-model="form.shop_name"
            type="text"
            placeholder="Shop Name"
            class="form-control"
          />
        </div>

        <div class="form-group">
          <label>Shop Address *</label>
          <textarea
            v-model="form.address"
            placeholder="Address"
            class="form-control"
          ></textarea>
        </div>
        <div class="form-group">
          <label>Phone *</label>
          <input
            v-model="form.phone"
            type="text"
            placeholder="Phone No"
            class="form-control"
          />
        </div>
        <div class="form-group">
          <label>Email *</label>
          <input
            v-model="form.email"
            type="text"
            placeholder="Email"
            class="form-control"
          />
        </div>

        <div class="form-group">
          <label
            >Theme Color (<span class="text-danger"
              >Remeber Your Color Will Combination with white text</span
            >)</label
          >
          <input
            type="text"
            v-model="form.theme_color"
            class="form-control"
            placeholder="Theme Color Code EX : #e3106e"
          />
        </div>

        <div class="form-group">
          <div class="fileinput fileinput-new" data-provides="fileinput">
            <span class="btn btn-block btn-primary btn-file"
              ><span class="fileinput-new"
                ><i class="fa fa-camera"></i> header logo</span
              >
              <span class="fileinput-exists">header logo</span
              ><input type="file" name="..." @change="onHeaderImageChange"
            /></span>
          </div>

          <div class="fileinput fileinput-new" data-provides="fileinput">
            <span class="btn btn-block btn-primary btn-file"
              ><span class="fileinput-new"
                ><i class="fa fa-camera"></i> footer logo</span
              >
              <span class="fileinput-exists">footer logo</span
              ><input type="file" name="..." @change="onFooterImageChange"
            /></span>
          </div>

          <div class="fileinput fileinput-new" data-provides="fileinput">
            <span class="btn btn-block btn-primary btn-file"
              ><span class="fileinput-new"
                ><i class="fa fa-camera"></i> Favicon</span
              >
              <span class="fileinput-exists">Favicon</span
              ><input type="file" name="..." @change="onFaviconChange"
            /></span>
          </div>
        </div>

        <div class="form-group">
          <label>Facebook Link</label>
          <input
            v-model="form.facebook_link"
            type="text"
            placeholder="Facebook Link"
            class="form-control"
          />
        </div>
        <div class="form-group">
          <label>Twitter Link</label>
          <input
            v-model="form.twitter_link"
            type="text"
            placeholder="Twitter Link"
            class="form-control"
          />
        </div>
        <div class="form-group">
          <label>Youtube Link</label>
          <input
            v-model="form.youtube_link"
            type="text"
            placeholder="Youtube Link"
            class="form-control"
          />
        </div>
        <div class="form-group">
          <label>Footer Text</label>
          <textarea
            v-model="form.footer_text"
            class="form-control"
            placeholder="Footer Text"
          ></textarea>
        </div>

        <div class="form-group">
          <label
            >Slider Status (<small
              >you can on or off showing slider on home page by this
              option</small
            >)</label
          >

          <select class="form-control" v-model="form.slider_status">
            <option value="1">Show Slider</option>
            <option value="0">Don't Show Slider</option>
          </select>
        </div>

        <div class="form-group">
          <label
            >Hot Deal Status (<small
              >you can on or off showing hot deal on home page by this
              option</small
            >)</label
          >

          <select class="form-control" v-model="form.hot_deal_status">
            <option value="1">Show Hot Deal</option>
            <option value="0">Don't Show Hot Deal</option>
          </select>
        </div>

        <div class="form-group">
          <label
            >On Sale Status (<small
              >you can on or off showing on sale section on home page by this
              option</small
            >)</label
          >

          <select class="form-control" v-model="form.onsale_status">
            <option value="1">Show on Sale Section</option>
            <option value="0">Don't Show on Sale Section</option>
          </select>
        </div>

        <div class="form-group">
          <label>Show Side Menubar (<small>you can on or off showing side menu by this
              option</small
            >)</label
          >

          <select class="form-control" v-model="form.sidemenu_status">
            <option value="1">Show Side Menubar</option>
            <option value="0">Don't Show Side Menubar</option>
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
    <div class="col-sm-4">
      <h4>Logo Header Preview</h4>

      <p class="text-center" :style="themBg" v-if="form.header_logo">
        <img class="img-responsive img-fluid" :src="form.header_logo" />
      </p>

      <p class="text-center" v-else :style="themBg">
        <img
          class="img-responsive img-fluid"
          :src="url + 'images/logo/' + form.header_logo_view"
        />
      </p>

      <h4>Logo Footer Preview</h4>

      <p class="text-center" v-if="form.footer_logo">
        <img class="img-responsive img-fluid" :src="form.footer_logo" />
      </p>

      <p class="text-center" v-else>
        <img
          class="img-responsive img-fluid"
          :src="url + 'images/logo/' + form.footer_logo_view"
        />
      </p>

      <h4>Favicon Preview</h4>

      <p class="text-center" v-if="form.favicon">
        <img class="img-responsive img-fluid" :src="form.favicon" />
      </p>

      <p class="text-center" v-else>
        <img
          class="img-responsive img-fluid"
          :src="url + 'images/logo/' + form.favicon_view"
        />
      </p>
    </div>

    <div
      class="col-md-12"
      v-if="validation_error"
      style="margin-top: 20px; margin-bottom: 20px"
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
        shop_name: "",
        shop_short_name: "",
        address: "",
        phone: "",
        email: "",
        footer_text: "",
        header_logo: "",
        footer_logo: "",
        header_logo_view: "",
        footer_logo_view: "",
        favicon: "",
        favicon_view: "",
        facebook_link: "",
        twitter_link: "",
        youtube_link: "",
        theme_color: "",
        hot_deal_status: "",
        slider_status: "",
        onsale_status: "",
        sidemenu_status: "",
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

    EventBus.$on("shop-created", function () {
      _this.getSetting();
    });
  },

  methods: {
    onHeaderImageChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.createheaderImage(files[0]);
    },
    createheaderImage(file) {
      let reader = new FileReader();
      let vm = this;
      reader.onload = (e) => {
        vm.form.header_logo = e.target.result;
      };
      reader.readAsDataURL(file);
    },

    onFooterImageChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.createFooterImage(files[0]);
    },
    createFooterImage(file) {
      let reader = new FileReader();
      let vm = this;
      reader.onload = (e) => {
        vm.form.footer_logo = e.target.result;
      };
      reader.readAsDataURL(file);
    },

    onFaviconChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.createFavicon(files[0]);
    },
    createFavicon(file) {
      let reader = new FileReader();
      let vm = this;
      reader.onload = (e) => {
        vm.form.favicon = e.target.result;
      };
      reader.readAsDataURL(file);
    },

    getSetting() {
      axios
        .get(base_url + "admin/setting/shop/" + 1 + "/edit")
        .then((response) => {
          this.form.shop_name = response.data.shop_name;
          this.form.shop_short_name = response.data.shop_short_name;
          this.form.address = response.data.address;
          this.form.phone = response.data.phone;
          this.form.email = response.data.email;
          this.form.footer_text = response.data.footer_text;
          this.form.header_logo_view = response.data.logo_header;
          this.form.footer_logo_view = response.data.logo_footer;
          this.form.favicon_view = response.data.favicon;
          this.form.facebook_link = response.data.facebook;
          this.form.twitter_link = response.data.twitter;
          this.form.youtube_link = response.data.youtube;
          this.form.theme_color = response.data.theme_color;
          this.form.hot_deal_status = response.data.hot_deal_status;
          this.form.slider_status = response.data.slider_status;
          this.form.onsale_status = response.data.onsale_status;
          this.form.sidemenu_status = response.data.sidemenu_status;
        });
    },

    save() {
      this.button_name = "Updating...";

      axios
        .post(base_url + "admin/setting/shop", this.form)
        .then((response) => {
          if (response.data.status === "success") {
            this.successMessage(response.data);
            EventBus.$emit("shop-created");
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

  computed: {
    themBg() {
      return {
        background: this.form.theme_color,
      };
    },
  },
};
</script>