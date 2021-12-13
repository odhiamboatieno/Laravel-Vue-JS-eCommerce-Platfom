<template>
  <div>
    <div id="modal-update" class="modal fade">
      <div class="modal-dialog modal-custom">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="m-t-none m-b">Update Product</h3>
            <button class="btn btn-default text-right" data-dismiss="modal">
              X
            </button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="save()">
              <div class="row">
                <div
                  class="col-md-12"
                  v-if="validation_error"
                  style="margin-top: 20px"
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
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Product Name*</label>
                    <input
                      type="text"
                      v-model="product.product_name"
                      class="form-control"
                      placeholder="Product Name"
                    />
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Product Native Name</label>
                    <input
                      type="text"
                      v-model="product.product_native_name"
                      class="form-control"
                      placeholder="Product Native Name"
                    />
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Category*</label>
                    <multiselect
                      v-model="product.category"
                      deselect-label
                      track-by="id"
                      label="category_name"
                      :searchable="true"
                      open-direction="bottom"
                      placeholder="Chose Category"
                      :options="categories"
                      @input="getSubCategory()"
                      :disabled="false"
                    ></multiselect>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Sub Category*</label>
                    <multiselect
                      v-model="product.sub_category"
                      deselect-label
                      track-by="id"
                      label="sub_category_name"
                      :loading="isCategoryLoading"
                      :searchable="true"
                      open-direction="bottom"
                      placeholder="Chose Sub Category"
                      :options="sub_categories"
                      @input="getSubSubCategories()"
                      :disabled="false"
                    ></multiselect>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Sub Sub Category</label>
                    <multiselect
                      v-model="product.sub_sub_category"
                      deselect-label
                      track-by="id"
                      label="sub_sub_category_name"
                      :loading="isSubCategoryLoading"
                      :searchable="true"
                      open-direction="bottom"
                      placeholder="Chose Sub Sub Category"
                      :options="sub_sub_categories"
                      @input="getBrand()"
                      :disabled="false"
                    ></multiselect>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Brand</label>
                    <multiselect
                      v-model="product.brand"
                      deselect-label
                      track-by="id"
                      label="brand_name"
                      :loading="isBrandLoading"
                      :searchable="true"
                      open-direction="bottom"
                      placeholder="Chose a Brand"
                      :options="brands"
                      :disabled="false"
                    ></multiselect>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Current Quantity*</label>
                    <input
                      type="number"
                      v-model="product.quantity"
                      class="form-control"
                      placeholder="Product Quantity"
                    />
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Quantity Unit</label>
                    <input
                      type="text"
                      v-model="product.quantity_unit"
                      class="form-control"
                      placeholder="Ex: 1 pieces,1/2 Kg,250gram,1kg per bag"
                    />
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Cost Price/Quantity*</label>
                    <input
                      type="text"
                      v-model="product.buying_price"
                      class="form-control"
                      placeholder="Cost Price or Buying Price"
                    />
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Sale Price/Quantity*</label>
                    <input
                      type="text"
                      v-model="product.selling_price"
                      class="form-control"
                      placeholder="Sale Price or Saling Price"
                    />
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Product Tags*</label>
                    <multiselect
                      v-model="product.product_tag"
                      tag-placeholder="Add this as new tag"
                      placeholder="Search or add a tag"
                      label="keyword_name"
                      track-by="id"
                      :options="tags"
                      :multiple="true"
                      :taggable="true"
                      @tag="addTag"
                    ></multiselect>
                  </div>
                </div>
                <!-- <div class="col-md-4">
                  <div class="form-group">
                    <label>Chose Size</label>
                    <multiselect
                      v-model="product.size"
                      id="ajax"
                      label="name"
                      track-by="id"
                      placeholder="Type to search"
                      open-direction="bottom"
                      :options="sizes"
                      :multiple="true"
                      :searchable="true"
                      :internal-search="true"
                      :clear-on-select="false"
                      :close-on-select="false"
                      :options-limit="300"
                      :limit="6"
                      :max-height="600"
                      :show-no-results="false"
                      :hide-selected="true"
                    >
                      <template slot="tag" slot-scope="{ option, remove }"
                        ><span class="custom__tag"
                          ><span>{{ option.name }}</span
                          ><span class="custom__remove" @click="remove(option)">
                            ❌</span
                          ></span
                        ></template
                      >
                      <template slot="clear" slot-scope="props">
                        <div
                          class="multiselect__clear"
                          v-if="product.size.length"
                          @mousedown.prevent.stop="clearAll(props.search)"
                        ></div> </template
                      ><span slot="noResult"
                        >Oops! No elements found. Consider changing the search
                        query.</span
                      >
                    </multiselect>
                  </div>
                </div> -->
                <!-- <div class="col-md-4">
                  <div class="form-group">
                    <label>Chose Color</label> <span @click="colorModalUpdate()" class="float-right">Add Color</span>
                    <label>Chose Color</label>
                    <multiselect
                      v-model="product.color"
                      id="ajax"
                      label="name"
                      track-by="id"
                      placeholder="Type to search"
                      open-direction="bottom"
                      :options="colors"
                      :multiple="true"
                      :searchable="true"
                      :internal-search="true"
                      :clear-on-select="false"
                      :close-on-select="false"
                      :options-limit="300"
                      :limit="6"
                      :max-height="600"
                      :show-no-results="false"
                      :hide-selected="true"
                    >
                      <template slot="tag" slot-scope="{ option, remove }"
                        ><span
                          class="custom__tag"
                          :style="{
                            color: '#fff',
                            'background-color': option.color_code,
                          }"
                          ><span>{{ option.name }}</span
                          ><span class="custom__remove" @click="remove(option)">
                            ❌</span
                          ></span
                        ></template
                      >
                      <template slot="clear" slot-scope="props">
                        <div
                          class="multiselect__clear"
                          v-if="product.color.length"
                          @mousedown.prevent.stop="clearAll(props.search)"
                        ></div> </template
                      ><span slot="noResult"
                        >Oops! No elements found. Consider changing the search
                        query.</span
                      >
                    </multiselect>
                  </div>
                </div> -->

                <div class="col-md-3" v-if="trial_setting.status == 1">
                  <div class="form-group">
                    <label>Trialable</label>
                    <select class="form-control" v-model="product.trialable">
                      <option value="1">ON</option>
                      <option value="0">OFF</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Main/Feature Image (600X600) *</label> <br />
                    <div
                      class="fileinput fileinput-new"
                      data-provides="fileinput"
                    >
                      <span class="btn btn-block btn-primary btn-file"
                        ><span class="fileinput-new"
                          ><i class="fa fa-camera"></i> Chose Image</span
                        >
                        <span class="fileinput-exists">Change Iimage</span
                        ><input type="file" name="..." @change="onImageChange"
                      /></span>

                      <img
                        style="height: 100px"
                        v-if="product.image"
                        v-lazy="product.image"
                      />
                      <img
                        style="height: 100px"
                        v-else
                        v-lazy="product.view_image"
                      />
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Multiple Image (600X600) *</label> <br />
                    <div
                      class="fileinput fileinput-new"
                      data-provides="fileinput"
                    >
                      <span class="btn btn-block btn-primary btn-file"
                        ><span class="fileinput-new"
                          ><i class="fa fa-camera"></i> Chose Image</span
                        >
                        <span class="fileinput-exists">Change Iimage</span
                        ><input
                          id="attachments"
                          type="file"
                          multiple="multiple"
                          name="attachments"
                          @change="uploadFieldChange"
                      /></span>
                    </div>

                    <div
                      class="img-frame"
                      v-if="multiple_image.length > 0"
                      v-for="images in multiple_image"
                      :key="images.id"
                    >
                      <img
                        v-lazy="images.image"
                        style="max-height: 100px; position: relative"
                      />
                      <a
                        @click.prevent="deleteImage(images.id)"
                        href=""
                        class="text-danger"
                        ><i class="fa fa-trash"></i
                      ></a>
                    </div>

                    <div
                      class="attachment-holder animated fadeIn"
                      v-cloak
                      v-for="(attachment, index) in product.attachments"
                      :key="index"
                    >
                      <span class="label label-primary">{{
                        attachment.name +
                        " (" +
                        Number((attachment.size / 1024 / 1024).toFixed(1)) +
                        "MB)"
                      }}</span>
                      <span
                        class=""
                        style="background: red; cursor: pointer"
                        @click.prevent="removeAttachment(attachment)"
                        ><button class="btn btn-xs btn-danger">
                          Remove
                        </button></span
                      >
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label>Product Description</label>
                    <vue-editor v-model="product.description"></vue-editor>
                  </div>
                </div>

                <div class="col-md-12">
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
      </div>
    </div>
    <!-- <div id="modal-color-update" class="modal fade" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header ">
					<h3 class="m-t-none m-b">Add Color</h3>
					<button class="btn btn-default text-right" onclick="document.getElementById('modal-color').style.display='none'">X</button>
				</div>
				<div class="modal-body">
					<form>
						<div class="row">
							<div class="col-md-12" v-if="validation_error" style="margin-top: 20px">
								<div class="form-group">

									<div>
										<ul>
											<li class="text-danger" v-for="error in validation_error" :key="error[0]">{{ error[0] }}</li>
										</ul>
									</div>

								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Color Name*</label> 
									<input type="text" v-model="color_form.name"   class="form-control" placeholder="Color Name">
								</div>
						    </div>						

						    <div class="col-md-4">
								<div class="form-group">
									<label>Color*</label> 
									<input type="color" v-model="color_form.color_code"   class="form-control" placeholder="Select Color">
								</div>
							</div>	
						    <div class="col-md-4">
								<div class="form-group">
									<label>Color Code*</label> 
									<input type="text" v-model="color_form.color_code" class="form-control" placeholder="Color Code">
								</div>
							</div>	
						</div>	
					</form>
                    <div class="row">								
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary"  @click.prevent="colorAddInUpdate()">Add Color</button>
                            <button type="close" class="btn btn-default" onclick="document.getElementById('modal-color').style.display='none'">Close</button>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div> -->
  </div>
</template>

<script>
import { EventBus } from "../../../vue-assets";

import Mixin from "../../../mixin";
import { VueEditor } from "vue2-editor";
import Multiselect from "vue-multiselect";

export default {
  mixins: [Mixin],
  props: ["trial_setting"],
  components: {
    VueEditor,
    Multiselect,
  },
  data() {
    return {
      product: {
        id: "",
        product_name: "",
        product_native_name: "",
        category: "",
        sub_category: "",
        sub_sub_category: "",
        brand: "",
        size: [],
        color: [],
        trialable: "",
        quantity: "",
        quantity_unit: "",
        buying_price: "",
        selling_price: "",
        product_tag: [],
        image: "",
        view_image: "",
        attachments: [],
        description: "",
        status: 1,
      },

      color_form: {
        from: "createproduct",
        name: "",
        color_code: "",
      },

      data: new FormData(),

      categories: [],

      sub_categories: [],
      sub_sub_categories: [],

      brands: [],
      tags: [],

      sizes: [],
      colors: [],

      recieve_color_id: [],
      send_color_id: [],

      multiple_image: [],

      isCategoryLoading: false,
      isSubCategoryLoading: false,
      isBrandLoading: false,

      button_name: "Update",
      validation_error: null,
    };
  },

  mounted() {
    this.getColors();
    var _this = this;
    EventBus.$on("update-product", function (id) {
      $("#modal-update").modal("show");
      _this.product.id = id;
      _this.getProduct(id);
    });

    $("#modal-update").on("hide", function () {
      _this.resetForm();
    });
  },

  methods: {
    // main feature image
    onImageChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.createImage(files[0]);
    },
    // creating main feature image
    createImage(file) {
      let reader = new FileReader();
      let vm = this;
      reader.onload = (e) => {
        vm.product.image = e.target.result;
      };
      reader.readAsDataURL(file);
    },

    // multiple image if any

    getAttachmentSize() {
      this.upload_size = 0; // Reset to beginningƒ
      this.product.attachments.map((item) => {
        this.upload_size += parseInt(item.size);
      });

      this.upload_size = Number(this.upload_size.toFixed(1));
      this.$forceUpdate();
    },
    prepareFields() {
      if (this.product.attachments.length > 0) {
        for (var i = 0; i < this.product.attachments.length; i++) {
          let attachment = this.product.attachments[i];
          this.data.append("attachments[]", attachment);
        }
      }
      this.data.append("id", this.product.id);
      this.data.append("product_name", this.product.product_name);
      this.data.append("product_native_name", this.product.product_native_name);
      this.data.append(
        "category",
        this.product.category.id ? this.product.category.id : ""
      );
      this.data.append(
        "sub_category",
        this.product.sub_category.id ? this.product.sub_category.id : ""
      );
      this.data.append(
        "sub_sub_category",
        this.product.sub_sub_category.id ? this.product.sub_sub_category.id : ""
      );
      this.data.append(
        "brand",
        this.product.brand.id ? this.product.brand.id : ""
      );
      this.data.append("quantity", this.product.quantity);
      this.data.append("trialable", this.product.trialable);
      this.data.append("quantity_unit", this.product.quantity_unit);
      this.data.append("buying_price", this.product.buying_price);
      this.data.append("selling_price", this.product.selling_price);
      this.data.append("description", this.product.description);
      this.data.append("image", this.product.image);

      if (this.product.product_tag.length > 0) {
        this.product.product_tag.forEach((tag, i) =>
          this.data.append(`product_tag[${i}]`, tag.keyword_name)
        );
      }
      // if (this.product.size.length > 0) {

      this.product.size.forEach((siz, i) =>
        this.data.append(`product_size[${i}]`, siz.id)
      );

      // }

      // if (this.product.color.length > 0){

      this.product.color.forEach((col, i) =>
        this.data.append(`product_color[${i}]`, col.id)
      );

      // }
      // this.data.append('product_tag[]',this.product.product_tag);
      this.data.append("status", this.product.status);
    },
    removeAttachment(attachment) {
      this.product.attachments.splice(
        this.product.attachments.indexOf(attachment),
        1
      );

      this.getAttachmentSize();
    },
    // This function will be called every time you add a file
    uploadFieldChange(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      for (var i = files.length - 1; i >= 0; i--) {
        this.product.attachments.push(files[i]);
      }
      // Reset the form to avoid copying these files multiple times into this.attachments
      document.getElementById("attachments").value = [];
    },

    getColors() {
      axios.get(base_url + "admin/get-color").then((response) => {
        this.colors = response.data;
      });
    },
    getProduct(id) {
      axios.get(base_url + "admin/product/" + id + "/edit").then((response) => {
        //   console.log(response.data)
        this.categories = response.data.categories;
        this.sub_categories = response.data.sub_categories;
        this.sub_sub_categories = response.data.sub_sub_categories;
        this.brands = response.data.brands;
        this.tags = response.data.product.product_keyword;
        this.sizes = response.data.sizes;
        this.product.size = response.data.product.product_size;
        this.product.color = response.data.product.product_color;
        this.product.trialable = response.data.product.trialable;
        // returned from product resource
        this.product.product_name = response.data.product.product_name;
        this.product.product_native_name =
          response.data.product.product_native_name;
        this.product.category = response.data.product.category;
        this.product.sub_category = response.data.product.sub_category;
        this.product.sub_sub_category = response.data.product.sub_sub_category;
        this.product.brand = response.data.product.brand;
        this.product.description = response.data.product.product_description;
        this.product.product_tag = response.data.product.product_keyword;
        this.product.buying_price = response.data.product.buying_price;
        this.product.selling_price = response.data.product.selling_price;
        this.product.quantity = response.data.product.current_quantity;
        this.product.quantity_unit = response.data.product.quantity_unit;
        this.product.view_image = response.data.product.feature_image;
        this.multiple_image = response.data.product.multiple_image;
      });
    },

    save() {
      this.button_name = "Updating...";

      this.prepareFields();
      var config = {
        headers: { "Content-Type": "multipart/form-data" },
        onUploadProgress: function (progressEvent) {
          this.percentCompleted = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
          );
          this.$forceUpdate();
        }.bind(this),
      };

      axios
        .post(
          base_url + "admin/product/update/" + this.product.id,
          this.data,
          config
        )
        .then((response) => {
          if (response.data.status === "success") {
            $("#modal-update").modal("hide");

            this.successMessage(response.data);
            EventBus.$emit("product-created");

            this.resetForm();
            this.button_name = "Update";
          } else {
            this.successMessage(response.data);
            this.button_name = "Update";
          }

          this.data = new FormData();
        })
        .catch((err) => {
          if (err.response.status == 422) {
            this.validation_error = err.response.data.errors;

            this.validationError();

            this.data = new FormData();

            this.button_name = "Update";
          } else {
            this.successMessage(err);

            // this.isloading = false;
            this.button_name = "Update";
            this.data = new FormData();
          }
        });
    },

    getSubCategory() {
      this.product.sub_category = "";
      this.product.brand = "";
      this.product.size = "";
      this.sub_categories = [];
      this.sub_sub_categories = [];
      this.brands = [];
      this.sizes = [];
      if (this.product.category === "") {
        this.sub_categories = [];
      } else {
        this.isCategoryLoading = true;
        axios
          .get(base_url + "admin/get-subcategory/" + this.product.category.id)
          .then((response) => {
            this.sub_categories = response.data;
            this.getSizeByCategory();
            this.isCategoryLoading = false;
          });
      }
    },

    getSizeByCategory() {
      this.product.size = "";
      this.sizes = [];
      if (this.product.category === "") {
        this.sizes = [];
      } else {
        this.isCategoryLoading = true;
        axios
          .get(base_url + "admin/get-sizes/" + this.product.category.id)
          .then((response) => {
            this.sizes = response.data;
            this.isCategoryLoading = false;
          });
      }
    },

    getSubSubCategories() {
      this.product.sub_sub_category = "";
      this.product.brand = "";
      this.sub_sub_categories = [];
      this.brands = [];
      if (this.product.sub_category === "") {
        this.sub_sub_categories = [];
      } else {
        this.isSubCategoryLoading = true;
        axios
          .get(
            base_url +
              "admin/get-sub-subcategory/" +
              this.product.sub_category.id
          )
          .then((response) => {
            this.sub_sub_categories = response.data;
            this.isSubCategoryLoading = false;
          });
      }
    },
    getBrand() {
      this.product.brand = "";
      this.brands = [];
      if (this.product.sub_sub_category === "") {
        this.brands = [];
      } else {
        this.isBrandLoading = true;
        axios
          .get(base_url + "admin/get-brand/" + this.product.sub_sub_category.id)
          .then((response) => {
            this.brands = response.data;
            this.isBrandLoading = false;
          });
      }
    },

    addTag(newTag) {
      const tag = {
        keyword_name: newTag,
        id: newTag.substring(0, 2) + Math.floor(Math.random() * 10000000),
      };
      this.tags.push(tag);
      this.product.product_tag.push(tag);
    },

    colorModalUpdate() {
      $("#modal-color-update").modal("show");
    },

    colorAddInUpdate() {
      // this.colorCodeValidation(this.form.color_code)
      var code = this.color_form.color_code.trim();
      if (code.indexOf("#") > 0 || code.indexOf("#") != 0 || code.length != 7) {
        this.successMessage({
          status: "error",
          message: "Enter Invalid Color Code!",
        });
        return;
      }

      axios
        .post(base_url + "admin/product-color", this.color_form)
        .then((response) => {
          $("#modal-color-update").modal("hide");
          this.resetColorForm();
          console.log(response.data);
          this.colors.push(response.data);
          this.product.color.push(response.data);
        })
        .catch((err) => {
          if (err.response.status == 422) {
            this.validation_error = err.response.data.errors;

            this.validationError();
          } else {
            this.successMessage(err);
          }
        });
    },
    resetColorForm() {
      this.color_form = {
        name: "",
        color_code: "",
      };
    },

    resetForm() {
      this.data = new FormData();
      this.product = {
        id: "",
        product_name: "",
        product_native_name: "",
        category: "",
        sub_category: "",
        sub_sub_category: "",
        brand: "",
        quantity: "",
        quantity_unit: "",
        buying_price: "",
        selling_price: "",
        product_tag: [],
        image: "",
        view_image: "",
        attachments: [],
        description: "",
        status: 1,
      };

      this.validation_error = null;
      this.categories = [];
      this.sub_categories = [];
      this.sub_sub_categories = [];
      this.brands = [];
      this.tags = [];
    },

    deleteImage(image_id) {
      // 'product/image/{id}/delete'

      Swal.fire(
        {
          title: "Are you sure ?",
          text: "The picture will be removed!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        },
        () => {}
      ).then((result) => {
        if (result.value) {
          axios
            .get(base_url + "admin/product/image/" + image_id + "/delete")
            .then((res) => {
              this.successMessage(res.data);
              this.getProduct(this.product.id);
            });
        }
      });
    },
  },
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