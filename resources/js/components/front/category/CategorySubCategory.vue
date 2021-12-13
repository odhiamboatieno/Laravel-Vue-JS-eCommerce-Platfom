<template>
  <div class="container">
    <div class="row category">
      <div class="col-md-12">
        <div class="title text-center">
          <h4>{{ category.category_name }}</h4>
        </div>
      </div>
    </div>
    <div class="row category" v-if="category.sub_category.length > 0">
      <div
        class="col-lg-3 col-sm-4 col-6"
        v-for="(value, index) in category.sub_category"
        :key="index"
      >
        <a
          :href="
            url +
            'product/sub-category/' +
            value.id +
            '/' +
            value.sub_category_slug
          "
        >
          <div class="box">
            <div class="content">
              <img v-lazy="value.image" alt="icon" class="img-fluid" />
              <h3>{{ value.sub_category_name }}</h3>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="row" v-else>
      <div class="col-md-12 text-center">
        <h3>No Sub Category</h3>
      </div>
    </div>
  </div>
</template>

<script>
import { EventBus } from "../../../vue-assets";
import Mixin from "../../../mixin";

export default {
  props: ["category"],
  data() {
    return {
      url: base_url,
    };
  },

  mounted() {
    // console.log(this.category.sub_category);
  },
};
</script>