<template>
  <div id="modal-form" class="modal fade">
    <div class="modal-dialog modal-custom">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="m-t-none m-b">Add Coupon for Customer</h3>
          <button class="btn btn-default text-right" data-dismiss="modal">
            X
          </button>
        </div>
        <div class="modal-body">
          <div class="ibox animated fadeInRightBig">
            <div class="ibox-content">
              <div class="row">
                <div class="col-sm-2 m-b-xs">
                  <multiselect
                    v-model="form.coupon_code"
                    deselect-label
                    track-by="id"
                    label="coupon_code"
                    :searchable="true"
                    open-direction="bottom"
                    placeholder="Select Coupon"
                    :options="coupons"
                    :disabled="false"
                    @input="setDate()"
                  ></multiselect>
                </div>

                <div class="col-sm-2 m-b-xs">
                  <multiselect
                    v-model="getdata.location"
                    deselect-label
                    track-by="id"
                    label="city"
                    :searchable="true"
                    open-direction="bottom"
                    placeholder="Filter By Location"
                    :options="locations"
                    :disabled="false"
                    :multiple="true"
                  ></multiselect>
                </div>
                <div class="col-sm-2 m-b-xs">
                  <select class="form-control" v-model="form.order">
                    <option value="">Select By Most Order</option>
                    <option value="most_order">Last Month Most Order</option>
                  </select>
                </div>
                <div class="col-sm-2 m-b-xs">
                  <v2-datepicker
                    lang="en"
                    style="padding-top: 3px"
                    format="yyyy-MM-DD"
                    v-model="form.valid_date"
                    :picker-options="pickerOptions2"
                  ></v2-datepicker>
                </div>

                <div class="col-sm-2 m-b-xs">
                  <button class="btn btn-primary" @click="setCustomer()">
                    Filter
                  </button>
                  <button class="btn btn-primary" @click="clearFilter()">
                    Clear Filter
                  </button>
                </div>
              </div>
              <div class="ibox-content">
                <div class="row" style="margin-top: 15px" v-if="!isLoading">
                  <div
                    class="table-responsive text-center"
                    v-if="customers.length > 0"
                  >
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>
                            <input
                              type="checkbox"
                              v-model="isCheckAll"
                              @click="selectAll()"
                            />
                            Check All
                          </th>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Location</th>
                          <th>Phone</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="value in customers" :key="value.id">
                          <td>
                            <input
                              type="checkbox"
                              :value="value.id"
                              v-model="selectedCustomer"
                              @change="updateCheckall()"
                            />
                          </td>
                          <td>{{ value.id }}</td>
                          <td>{{ value.name }}</td>
                          <td>{{ value.email }}</td>
                          <td>{{ value.location.city }}</td>
                          <td>{{ value.phone }}</td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="float-right">
                      <div class="form-check form-check-inline">
                        <input
                          class="form-check-input"
                          type="checkbox"
                          v-model="send_email"
                          id="inlineCheckbox1"
                        />
                        <label class="form-check-label" for="inlineCheckbox1"
                          >Send Email</label
                        >
                      </div>
                      <!-- <div class="form-check form-check-inline">
										<input class="form-check-input" v-model="send_sms" type="checkbox" id="inlineCheckbox2">
										<label class="form-check-label" for="inlineCheckbox2">Send SMS</label>
									</div> -->

                      <button class="btn btn-primary" @click="save()">
                        Send
                      </button>
                    </div>
                  </div>
                </div>

                <div class="col-md-12 text-center" v-else>
                  <img :src="url + 'images/loading.gif'" />
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
import Multiselect from "vue-multiselect";

export default {
  props: ["coupons", "locations"],
  mixins: [Mixin],
  components: {
    Multiselect,
  },

  data() {
    return {
      form: {
        location: [],
        coupon_code: "",
        valid_date: "",
        order: "",
        status: 1,
      },
      customers: [],
      getdata: {
        location: [],
      },
      pickerOptions2: {
        shortcuts: [
          {
            text: "Today",
            onClick(picker) {
              picker.$emit("pick", new Date());
            },
          },
          {
            text: "Yesterday",
            onClick(picker) {
              const date = new Date();
              date.setTime(date.getTime() - 3600 * 1000 * 24);
              picker.$emit("pick", date);
            },
          },
          {
            text: "A week ago",
            onClick(picker) {
              const date = new Date();
              date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit("pick", date);
            },
          },
        ],
      },
      isCheckAll: false,
      selectedCustomer: [],
      send_sms: false,
      send_email: false,
      button_name: "Save",
      validation_error: null,
      isLoading: false,
      url: base_url,
    };
  },

  methods: {
    save() {
      this.button_name = "Saving...";
      axios
        .post(base_url + "admin/send-coupon", {
          select_id: this.selectedCustomer,
          coupon: this.form.coupon_code,
          valid_date: this.form.valid_date,
          send_to_sms: this.send_sms,
          send_to_email: this.send_email,
        })
        .then((response) => {
          // console.log(response.data)
          if (response.data.status === "success") {
            $("#modal-form").modal("hide");
            this.clearFilter();
            EventBus.$emit("customer-coupon-created");
            this.successMessage(response.data);
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

            // this.isloading = false;
            this.button_name = "Save";
          }
        });
    },

    setCustomer() {
      const locations = this.getdata.location.map((data) => data.id);

      axios
        .post(base_url + "admin/get-customer-for-coupon", {
          location: locations,
          order_by: this.form.order,
        })
        .then((response) => {
          if (response.data.location[0] && response.data.user_by_order[0]) {
            var concatdata = Array.prototype.concat.apply(
              [],
              [response.data.location[0], response.data.user_by_order[0]]
            );
          } else if (response.data.location[0]) {
            var concatdata = response.data.location[0];
          } else {
            var concatdata = response.data.user_by_order[0];
          }

          var noDupeObj = {};
          for (var i = 0, n = concatdata.length; i < n; i++) {
            var item = concatdata[i];
            noDupeObj[item.id] = item;
          }

          var i = 0;
          var nonDuplicatedArray = [];
          for (var item in noDupeObj) {
            nonDuplicatedArray[i++] = noDupeObj[item];
          }
          this.customers = [...nonDuplicatedArray];
        })
        .catch((error) => {
          console.log(error);
        });
    },

    setDate() {
      this.form.valid_date = this.form.coupon_code.valid_date;
    },
    selectAll() {
      this.isCheckAll = !this.isCheckAll;
      this.selectedCustomer = [];
      var filterData = [];
      if (this.isCheckAll) {
        // Check all
        for (var key in this.customers) {
          filterData.push(this.customers[key]);
        }
      }
      this.selectedCustomer = filterData.map((data) => data.id);
      // console.log(this.selectedCustomer)
    },

    updateCheckall() {
      if (this.selectedCustomer.length == this.customers.length) {
        this.isCheckAll = true;
      } else {
        this.isCheckAll = false;
      }
    },

    clearFilter() {
      this.form = {
        location: [],
        coupon_code: "",
        valid_date: "",
        status: 1,
      };
      this.getdata = {
        location: [],
      };
      this.customers = [];
      (this.isCheckAll = false),
        (this.send_sms = false),
        (this.send_email = false),
        (this.selectedCustomer = []),
        (this.validation_error = null);
      this.isLoading = false;
    },
  },

  watch: {},
};
</script>

<style scoped="">
.modal-custom {
  max-width: 90% !important;
}

@media screen and (max-width: 573px) {
  .modal-custom {
    max-width: 100% !important;
    background-color: #000 !important;
  }
}
</style>