<script>
import { Line } from "vue-chartjs";
export default {
  extends: Line,
  data() {
    return {
      // gradient: null,
      order_date : [],
      order_data : [],
      gradient2: null,
    }
  },
  
  mounted() {
    this.getOrderData();
    this.setupStyle();
    
  },

  methods : {

  	setupStyle(){

    this.gradient2 = this.$refs.canvas
      .getContext("2d")
      .createLinearGradient(0, 0, 0, 450);

    this.gradient2.addColorStop(0, "rgba(255,20,147, 0.9)");
    this.gradient2.addColorStop(0.5, "rgba(255,20,147, 0.25)");
    this.gradient2.addColorStop(1, "rgba(255,20,147, 0)");

  	},
  	generateChart(){
  	 this.renderChart(
      {
        labels: this.order_date,
        datasets: [
          {
            label: "Order",
            borderColor: "#05CBE1",
            pointBackgroundColor: "white",
            pointBorderColor: "black",
            borderWidth: 1,
            backgroundColor: this.gradient2,
            data: this.order_data,
          }
        ]
      },
      { responsive: true, maintainAspectRatio: false }
    );
  	},

  	getOrderData(){
  		axios.get(base_url+'admin/last-month/order/chart')
            .then(response => {
                response.data.map((value,index) => {
                    this.order_data.push(Number(value.total));
                    this.order_date.push(value.date);
            });
            this.generateChart();
        });
  	}
  },
}
</script>