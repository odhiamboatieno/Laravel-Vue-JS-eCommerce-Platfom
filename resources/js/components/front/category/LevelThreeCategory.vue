<template>
  <div class="container">
    <div class="row category">
      <div class="col-md-12">
        <div class="title text-center">
          <h4>{{ sub_category.sub_category_name }}</h4>
        </div>
      </div>
    </div>
    <div class="row category">
      <div
        class="col-lg-3 col-sm-4 col-6"
        v-for="(value, index) in sub_category.sub_sub_category"
        :key="index"
      >
        <a
          :href="
            url +
            'product/sub-sub-category/' +
            value.id +
            '/' +
            value.sub_sub_category_slug
          "
        >
          <div class="box">
            <div class="content">
              <img v-lazy="value.image" alt="icon" class="img-fluid" />
              <h3>{{ value.sub_sub_category_name }}</h3>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import { EventBus } from "../../../vue-assets";
import Mixin from "../../../mixin";

export default {
  props: ["sub_category"],
  data() {
    return {
      url: base_url,
    };
  },

  mounted() {
    // console.log(this.sub_category.sub_sub_category);
  },
};
</script>