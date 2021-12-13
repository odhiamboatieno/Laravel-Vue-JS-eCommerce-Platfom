<template>
  <div>
    <h3 class="m-t-none m-b">Time Slot List</h3>
    <div class="table-responsive" style="max-height: 600px; overflow-y: auto">
      <table class="table">
        <tr>
          <th>Slot Name</th>
          <th>Expired At Same Day</th>
          <th>Status</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
        <tr v-for="value in time_slots" :key="value.id">
          <td>{{ value.slot_name }}</td>
          <td>{{ value.expired_at }}</td>
          <td>
            <span v-if="value.status == 1" class="badge badge-success"
              >Active</span
            >
            <span v-else class="badge badge-danger">Inactive</span>
          </td>
          <td>
            <a href="" @click.prevent="editSlot(value)" class="btn btn-success"
              >Edit</a
            >
          </td>
          <td>
            <a
              href=""
              @click.prevent="deleteSlot(value.id)"
              class="btn btn-danger"
              >Delete</a
            >
          </td>
        </tr>
      </table>
    </div>
    <edit-time-slot></edit-time-slot>
  </div>
</template>


<script>
import { EventBus } from "../../../../vue-assets";

import Mixin from "../../../../mixin";
import EditTimeSlot from "./EditTimeSlot";

export default {
  mixins: [Mixin],
  components: {
    "edit-time-slot": EditTimeSlot,
  },

  data() {
    return {
      time_slots: [],
      isLoading: false,
    };
  },

  mounted() {
    this.getSlot();
    var _this = this;
    EventBus.$on("slot-created", function () {
      _this.getSlot();
    });
  },

  methods: {
    getSlot() {
      axios.get(base_url + "admin/setting/time-slot-list").then((response) => {
        this.time_slots = response.data;
      });
    },
    editSlot(value) {
      EventBus.$emit("edit-slot", value);
    },
    deleteSlot(id) {
      Swal.fire(
        {
          title: "Are you sure ?",
          text: "You won't be able to revert this!",
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
            .delete(base_url + "admin/setting/time-slot/" + id)
            .then((res) => {
              this.successMessage(res.data);
              this.getSlot();
            });
        }
      });
    },
  },
};
</script>