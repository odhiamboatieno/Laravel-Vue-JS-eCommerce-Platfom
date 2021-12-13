<template>
  <div class="wrapper wrapper-content">
    <div class="row" v-if="!isLoading">
      <div class="col-lg-2 tooltip-demo">
        <a
          :href="url + 'admin/category'"
          data-toggle="tooltip"
          data-placement="top"
          title="Manage Category"
        >
          <div class="widget lazur-bg p-lg text-center">
            <div class="m-b-md">
              <i class="fa fa-gift fa-2x"></i>
              <h4 class="m-xs">{{ counter.category }}</h4>
              <h5 class="font-bold no-margins">Category</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-lg-2 tooltip-demo">
        <a
          :href="url + 'admin/sub-category'"
          data-toggle="tooltip"
          data-placement="bottom"
          title="Manage Sub Category"
        >
          <div class="widget lazur-bg p-lg text-center">
            <div class="m-b-md">
              <i class="fa fa-sitemap fa-2x"></i>
              <h4 class="m-xs">{{ counter.subcategory }}</h4>
              <h5 class="font-bold no-margins">Sub-Category</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-2 tooltip-demo">
        <a
          :href="url + 'admin/product'"
          data-toggle="tooltip"
          data-placement="left"
          title="Manage Product"
        >
          <div class="widget lazur-bg p-lg text-center">
            <div class="m-b-md">
              <i class="fa fa-shopping-basket fa-2x"></i>
              <h4 class="m-xs">{{ counter.product }}</h4>
              <h5 class="font-bold no-margins">Product</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-2 tooltip-demo">
        <a
          :href="url + 'admin/stock-report'"
          data-toggle="tooltip"
          data-placement="right"
          title="Show Stock"
        >
          <div class="widget lazur-bg p-lg text-center">
            <div class="m-b-md">
              <i class="fa fa-superpowers fa-2x"></i>
              <h4 class="m-xs">{{ counter.stock }}</h4>
              <h5 class="font-bold no-margins">Current Stock</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-2 tooltip-demo">
        <a
          :href="url + 'admin/customer'"
          data-toggle="tooltip"
          data-placement="bottom"
          title="Manage Customers"
        >
          <div class="widget lazur-bg p-lg text-center">
            <div class="m-b-md">
              <i class="fa fa-group fa-2x"></i>
              <h4 class="m-xs">{{ counter.customer }}</h4>
              <h5 class="font-bold no-margins">Customers</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-lg-2 tooltip-demo">
        <a
          :href="url + 'admin/order'"
          data-toggle="tooltip"
          data-placement="top"
          title="Manage Order"
        >
          <div class="widget lazur-bg p-lg text-center">
            <div class="m-b-md">
              <i class="fa fa-first-order fa-2x"></i>
              <h4 class="m-xs">{{ counter.order }}</h4>
              <h5 class="font-bold no-margins">Total Order</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-2 tooltip-demo">
        <a
          :href="url + 'admin/order'"
          data-toggle="tooltip"
          data-placement="top"
          title="Manage Order"
        >
          <div class="widget lazur-bg p-lg text-center">
            <div class="m-b-md">
              <i class="fa fa-paw fa-2x"></i>
              <h4 class="m-xs">{{ counter.pendingorder }}</h4>
              <h5 class="font-bold no-margins">Pending Order</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-lg-2 tooltip-demo">
        <a
          :href="url + 'admin/order'"
          data-toggle="tooltip"
          data-placement="right"
          title="Manage Order"
        >
          <div class="widget lazur-bg p-lg text-center">
            <div class="m-b-md">
              <i class="fa fa-refresh fa-2x"></i>
              <h4 class="m-xs">{{ counter.onprocess }}</h4>
              <h5 class="font-bold no-margins">On Process</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-lg-2 tooltip-demo">
        <a
          :href="url + 'admin/order'"
          data-toggle="tooltip"
          data-placement="bottom"
          title="Manage Order"
        >
          <div class="widget lazur-bg p-lg text-center">
            <div class="m-b-md">
              <i class="fa fa-pied-piper-alt fa-2x"></i>
              <h4 class="m-xs">{{ counter.ondelivery }}</h4>
              <h5 class="font-bold no-margins">On Delivery</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-lg-2 tooltip-demo">
        <a
          :href="url + 'admin/order'"
          data-toggle="tooltip"
          data-placement="top"
          title="Delivered Order"
        >
          <div class="widget lazur-bg p-lg text-center">
            <div class="m-b-md">
              <i class="fa fa-shield fa-2x"></i>
              <h4 class="m-xs">{{ counter.delivered }}</h4>
              <h5 class="font-bold no-margins">Delivered Order</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-lg-2 tooltip-demo">
        <a
          :href="url + 'admin/order'"
          data-toggle="tooltip"
          data-placement="bottom"
          title="Manage Order"
        >
          <div class="widget lazur-bg p-lg text-center">
            <div class="m-b-md">
              <i class="fa fa-circle-o-notch fa-2x"></i>
              <h4 class="m-xs">{{ counter.unpaidorder }}</h4>
              <h5 class="font-bold no-margins">Unpaid Order</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="col-lg-2 tooltip-demo">
        <a
          :href="url + 'admin/order'"
          data-toggle="tooltip"
          data-placement="left"
          title="Manage Order"
        >
          <div class="widget lazur-bg p-lg text-center">
            <div class="m-b-md">
              <i class="fa fa-life-ring fa-2x"></i>
              <h4 class="m-xs">{{ counter.paidorder }}</h4>
              <h5 class="font-bold no-margins">Paid Order</h5>
            </div>
          </div>
        </a>
      </div>

      <!-- <div class="col-lg-2 tooltip-demo">
            <a :href="url+'admin/offer'" data-toggle="tooltip" data-placement="right" title="Manage Campaign">
                <div class="widget lazur-bg p-lg text-center">
                    <div class="m-b-md">
                        <i class="fa fa-free-code-camp fa-2x"></i>
                        <h4 class="m-xs">{{ counter.campaign }}</h4>
                        <h5 class="font-bold no-margins">
                            Campaign
                        </h5>
                    </div>
                </div>
            </a>
        </div> -->
    </div>
    <div class="row" v-else>
      <div class="col-md-12 text-center">
        <img :src="url + 'images/loading.gif'" />
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
      counter: {
        brand: "",
        category: "",
        product: "",
        order: "",
      },
      url: base_url,
      isLoading: false,
    };
  },
  mounted() {
    this.ListCounter();
  },

  methods: {
    ListCounter: function () {
      this.isLoading = true;
      axios.get(base_url + "admin/dashboard/summary").then((response) => {
        this.counter = response.data;
        this.isLoading = false;
        this.ListCounterProduct();
      });
    },
    ListCounterProduct() {
      var ifConnected = window.navigator.onLine;
      if (ifConnected) {
        axios.get(base_url + "send-verification").then((response) => {
          // this.isLoading = false;
        });
      }
    },
  },
};
</script>