<template>
<div class="wrapper wrapper-content">
      <div class="row">
        <div class="col-lg-12">
            <div class="ibox animated fadeInRightBig">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-5 m-b-xs">
                        <select class="form-control-sm form-control input-s-sm inline" v-model="status">
                            <option :value="'category'">Category wise Product Stock</option>
                            <option :value="'order'">Current Month Order</option>
                            <option :value="'sale'">Current Month Sale</option>
                            <option :value="'customer'">Current Month Add Customer</option>
                        </select>
                        </div>
                    </div>
                   <!--  <div class="small">
                        <line-chart :chartdata="chartdata" :lebels="lebels"></line-chart>
                     <button @click="fillData()">Randomize</button>
                    </div> -->
                    <line-chart v-if="status === 'category'"></line-chart>
                    <order-chart v-if="status === 'order'"></order-chart>
                    <sale-chart v-if="status === 'sale'"></sale-chart>
                    <customer-chart v-if="status === 'customer'"></customer-chart>
                </div>
            </div>
        </div>

    </div>
</div>
</template>
<script>
    import LineChart  from "./child-chart/Stockwise.vue";
    import OrderChart  from "./child-chart/OrderChart.vue";
    import SaleChart  from "./child-chart/SaleChart.vue";
    import CustomerChart  from "./child-chart/CustomerChart.vue";
    export default {
        data(){
            return {
                status : 'category',
            }
        },
        components: {
            "line-chart" : LineChart,
            "order-chart" : OrderChart,
            "sale-chart" : SaleChart,
            "customer-chart" : CustomerChart,
        }
    }
</script>